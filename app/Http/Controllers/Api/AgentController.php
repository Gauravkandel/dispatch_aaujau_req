<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\{Agent, AgentLog, AllocationRule, Client, ClientPreference, Cms, Order, Task, TaskProof, Timezone, User, DriverGeo, Geo, TagsForAgent};
use Validator;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Model\Roster;
use Config;
use Illuminate\Support\Facades\URL;
use GuzzleHttp\Client as GClient;

class AgentController extends BaseController
{
    /**   get agent according to lat long  */
    function getAgents(Request $request){
        try {

            $validator = Validator::make(request()->all(), [
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }

            $geoid = $this->findLocalityByLatLng($request->latitude, $request->longitude);
            $geoagents_ids = DriverGeo::where('geo_id', $geoid)->pluck('driver_id');

            $tagId = '';
            if(!empty($request->tag)){
                $tag = TagsForAgent::where('name', $request->tag)->get()->first();
                if(!empty($tag)){
                    $tagId = $tag->id;
                }else{
                    return response()->json([
                        'data' => [],
                        'status' => 200,
                        'message' => __('Agents not found.')
                    ], 200);
                }

            }

            $geoagents = Agent::with(['agentlog','vehicle_type']);

            if(!empty($tagId)){
                $geoagents->whereHas('tags', function($q) use($tagId){
                    $q->where('tag_id', $tagId);
                });
            }

            $geoagents = $geoagents->whereIn('id', $geoagents_ids)->where(["is_available" => 1, "is_approved" => 1])->orderBy('id', 'DESC')->get();
            // $preferences = ClientPreference::with('currency')->first();
            // $clientPreference = json_decode($preferences->customer_notification_per_distance);
            
            $finalAgents = [];
            foreach($geoagents as $geoagent){
                if(empty($geoagent->agentlog)){
                    continue;
                }
                $agentLat = $geoagent->agentlog->lat;
                $agentLong = $geoagent->agentlog->long;

                $getDistance = $this->getLatLongDistance($agentLat, $agentLong, $request->latitude, $request->longitude, 'km');
                
                //get agent under 5 km
                if($getDistance < 6){
                    $geoagent->distance = $getDistance;
                    $geoagent->distance_type = 'km';
                    $finalAgents[] = $geoagent;
                }

                $getArrivalTime = $this->getLatLongDistance($agentLat, $agentLong, $request->latitude, $request->longitude, 'minutes');
                $geoagent->arrival_time = $getArrivalTime;
                
            }

            $distance = array_column($finalAgents, 'distance');
            array_multisort($distance, SORT_ASC, $finalAgents);

            return response()->json([
                'data' => $finalAgents,
                'status' => 200,
                'message' => __('success')
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

    }

    function getLatLongDistance($lat1, $lon1, $lat2, $lon2, $unit) {

        $earthRadius = 6371;  // earth radius in km

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        $final = round($angle * $earthRadius);
        if ($unit == "km") {
            return $final;
        } else if ($unit == "minutes") {
            $kmsPerMin = 0.5; // asume per km time estimate 0.5 minute
            $minutesTaken = $final / $kmsPerMin;
            if($minutesTaken < 60){
                return $minutesTaken.' min';
            }else{
                return intdiv($minutesTaken, 60).'hours '. ($minutesTaken % 60).'min';
            }
        } else {
            return round($final * 0.6214); //miles
        }
    }

    public function findLocalityByLatLng($lat, $lng)
    {
        // get the locality_id by the coordinate //
        $latitude_y = $lat;
        $longitude_x = $lng;
        $localities = Geo::all();

        if (empty($localities)) {
            return false;
        }

        foreach ($localities as $k => $locality) {

            if(!empty($locality->polygon)){
                $geoLocalitie = Geo::where('id', $locality->id)->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $lat . " " . $lng . ")'))")->first();
                if(!empty($geoLocalitie)){
                    return $locality->id;
                }
            }else{
                $all_points = $locality->geo_array;
                $temp = $all_points;
                $temp = str_replace('(', '[', $temp);
                $temp = str_replace(')', ']', $temp);
                $temp = '[' . $temp . ']';
                $temp_array =  json_decode($temp, true);

                foreach ($temp_array as $k => $v) {
                    $data[] = [
                        'lat' => $v[0],
                        'lng' => $v[1]
                    ];
                }

                // $all_points[]= $all_points[0]; // push the first point in end to complete
                $vertices_x = $vertices_y = array();

                foreach ($data as $key => $value) {
                    $vertices_y[] = $value['lat'];
                    $vertices_x[] = $value['lng'];
                }

                $points_polygon = count($vertices_x) - 1;  // number vertices - zero-based array
                $points_polygon;

                if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)) {
                    return $locality->id;
                }
            }
            
        }

        return false;
    }

    public function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon; $i < $points_polygon; $j = $i++) {
            if ((($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]))) {
                $c = !$c;
            }
        }
        return $c;
    }
}
