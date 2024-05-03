<?php
namespace App\Traits;
use DB;
use Illuminate\Support\Collection;
use App\Model\{Client, ClientPreference, User, Agent, Order};
use GuzzleHttp\Client as GClient;

trait getLocationServices{

    //------------------------------Function to get lat long from address, created by surendra singh--------------------------//
    function getLatLong($address)
    {
        $preference = ClientPreference::where('id', 1)->first(['map_key_1']);
        $client = new GClient(['content-type' => 'application/json']);
        $result = $client->post("https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".$preference->map_key_1);
        $response = json_decode($result->getBody(), true);    
        $latitude = $response['results'][0]['geometry']['location']['lat'];
        $longitude = $response['results'][0]['geometry']['location']['lng'];
        return array('latitude'=>$latitude, 'longitude'=>$longitude);
    }
    //-------------------------------------------------------------------------------------------//


    function GoogleDistanceMatrix($latitude, $longitude)
    {
        $send   = [];
        $client = ClientPreference::where('id', 1)->first();
        $lengths = count($latitude)-1;
        $value = [];
        $count  = 0;
        $count1 = 1;
        for ($i = 0; $i<$lengths; $i++) {
            $ch = curl_init();
            $headers = array('Accept: application/json',
                    'Content-Type: application/json',
                    );
            $url =  'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$latitude[$count].','.$longitude[$count].'&destinations='.$latitude[$count1].','.$longitude[$count1].'&key='.$client->map_key_1.'';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            $result = json_decode($response);
            curl_close($ch); // Close the connection
            $new =   $result;
            array_push($value, $result->rows[0]->elements);
            $count++;
            $count1++;
        }

        if (isset($value)) {
            $totalDistance = 0;
            $totalDuration = 0;
            foreach ($value as $item) {
                $totalDistance = $totalDistance + (@$item[0]->distance->value );
                $totalDuration = $totalDuration +(@$item[0]->duration->value);
            }

            if ($client->distance_unit == 'metric') {
                $send['distance'] = round($totalDistance/1000, 2);      //km
            } else {
                $send['distance'] = round($totalDistance/1609.34, 2);  //mile
            }

            $newvalue = round($totalDuration/60, 2);
            $whole = floor($newvalue);
            $fraction = $newvalue - $whole;

            if ($fraction >= 0.60) {
                $send['duration'] = $whole + 1;
            } else {
                $send['duration'] = $whole;
            }
        }

        return $send;
    }
    

}
