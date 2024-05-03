<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Agent;
use App\Model\Team;
use App\Model\Geo;
use App\Model\DriverGeo;
use Auth;
use Illuminate\Support\Facades\Cookie;

class GeoFenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::with(['agents'])->where('client_id', auth()->user()->code)->orderBy('name');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }

        $teams = $teams->get();
       
        $agents = Agent::with('team');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $agents = $agents->where('is_approved', 1)->get();

        $geos = Geo::where('client_id', auth()->user()->code)->orderBy('created_at', 'DESC')->first();

        $all_coordinates = [];
        $geo = Geo::where('client_id', auth()->user()->code)->orderBy('created_at', 'DESC')->get();

        foreach ($geo as $k => $v) {
            $all_coordinates[] = [
                'name' => 'abc',
                'coordinates' => $v->geo_coordinates
            ];
        }

        $center = [
            'lat' => 30.0612323,
            'lng' => 76.1239239
        ];

        if (!empty($all_coordinates)) {
            $center['lat'] = $all_coordinates[0]['coordinates'][0]['lat'];
            $center['lng'] = $all_coordinates[0]['coordinates'][0]['lng'];
        }

         
        if (isset($geos)) {
            $codinates = $geos->geo_coordinates[0];
        } else {
            $codinates[] = [
                'lat' => 33.5362475,
                'lng' => -111.9267386
            ];
        }

        return view('geo-fence')->with([
            'teams' =>  $teams,
            'agents' =>  $agents,
            'coninates' => $codinates,
            'all_coordinates' => $all_coordinates,
        ]);
    }

    public function allList()
    {
        $all_coordinates = [];
        $geos = Geo::where('client_id', auth()->user()->code)->orderBy('created_at', 'DESC')->get();
        foreach ($geos as $k => $v) {
            $all_coordinates[] = [
                'name' => 'abc',
                'coordinates' => $v->geo_coordinates
            ];
        }

        $center = [
            'lat' => 30.0612323,
            'lng' => 76.1239239
        ];

        if (!empty($all_coordinates)) {
            $center['lat'] = $all_coordinates[0]['coordinates'][0]['lat'];
            $center['lng'] = $all_coordinates[0]['coordinates'][0]['lng'];
        }

        return view('geo-fence-list')->with(['geos' => $geos, 'all_coordinates' => $all_coordinates, 'center' => $center]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Validation method for geo-fence data
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'zoom_level' => ['required'],
            'latlongs'   => ['required']
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $domain = '')
    {
        $validator = $this->validator($request->all())->validate();
        

        $latlng = str_replace('),(', ';', $request->latlongs);
        $latlng = str_replace(')', '', $latlng);
        $latlng = str_replace('(', '', $latlng);
        $latlng = str_replace(', ', ' ', $latlng);
        $codsArray = explode(';', $latlng);
        $latlng = implode(', ', $codsArray);
        $latlng = $latlng. ', ' . $codsArray[0];


        $data = [
            'name'          => $request->name,
            'description'   => $request->description,
            'zoom_level'    => $request->zoom_level,
            'geo_array'     => $request->latlongs,
            'polygon'       => \DB::raw("ST_GEOMFROMTEXT('POLYGON((".$latlng."))')"),
            'client_id'     => auth()->user()->code
        ];

        $geo = Geo::create($data);
        $geo->agents()->sync($request->agents);

        //update team_id if any provided //
        if ($request->team_id) {
            DriverGeo::where('geo_id', $geo->id)->update([
                'team_id' => $request->team_id
            ]);
        }

        return redirect()->route('geo.fence.list')->with('success', 'Added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($domain = '', $id)
    {
        $geo = Geo::with(['agents'])->where('id', $id)->first();
        $teams = Team::with(['agents'])->where('client_id', auth()->user()->code)->orderBy('name');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $teams = $teams->get();



        $agents = Agent::with('team');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $agents = $agents->where('is_approved', 1)->get();

        return view('update-geo-fence')->with([
            'geo' => $geo,
            'agents' => $agents,
            'teams' => $teams
        ]);
    }


    protected function updateValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $domain = '', $id)
    { 
        $validator = $this->updateValidator($request->all())->validate();
        $geo = Geo::find($id);
        if(Auth::user()->is_superadmin == 0)
        {
            $request->name = $geo->name;
            $request->description = $geo->description;
            $request->latlongs = $geo->geo_array;
        }
        

        if (isset($request->latlongs)) {

            $latlng = str_replace('),(', ';', $request->latlongs);
            $latlng = str_replace(')', '', $latlng);
            $latlng = str_replace('(', '', $latlng);
            $latlng = str_replace(', ', ' ', $latlng);
            $codsArray = explode(';', $latlng);
            $latlng = implode(', ', $codsArray);
            $latlng = $latlng. ', ' . $codsArray[0];

            $data = [
                'name'          => $request->name,
                'description'   => $request->description,
                'geo_array'     => $request->latlongs,
                'polygon'       => \DB::raw("ST_GEOMFROMTEXT('POLYGON((".$latlng."))')"),
            ];
        } else {
            $data = [
                'name'          => $request->name,
                'description'   => $request->description,
            ];
        }

        $updated = Geo::where('id', $id)->update($data);
       
        if(Auth::user()->is_superadmin == 0){
           $agents = Agent::with('team');
            if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
                $agents = $agents->whereHas('team.permissionToManager', function ($query) {
                    $query->where('sub_admin_id', Auth::user()->id);
                });
            }
    
            $agents = $agents->where('is_approved', 1)->pluck('id');
            
            DriverGeo::whereIn('driver_id',$agents)->where('geo_id',$id)->delete();
            if(isset($request->agents) && !empty($request->agents)){
                foreach($request->agents as $key => $driver_id)
                DriverGeo::updateOrCreate(['geo_id' => $id,'driver_id' => $driver_id],['team_id' => $request->team_id]);
            }
                
            
        }else{
            $geo->agents()->sync($request->agents);
        }
       

        

        return redirect()->back()->with('success', 'Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        Geo::where('id', $id)->where('client_id', auth()->user()->code)->delete();
        return redirect()->back()->with('success', 'Deleted successfully!');
    }
    public function dummy(Request $request, $domain = '')
    {
        return response()->json([
            'status'=>'success',
            'message' => 'Successfully!',
            'newchange' => $request->value
        ]);
    }




    public function newDemo()
    {
        $all_coordinates = [];
        $geos = Geo::where('client_id', auth()->user()->code)->orderBy('created_at', 'DESC')->get();
        foreach ($geos as $k => $v) {
            $all_coordinates[] = [
                'name' => 'abc',
                'coordinates' => $v->geo_coordinates
            ];
        }

        $center = [
            'lat' => 30.0612323,
            'lng' => 76.1239239
        ];

        if (!empty($all_coordinates)) {
            $center['lat'] = $all_coordinates[0]['coordinates'][0]['lat'];
            $center['lng'] = $all_coordinates[0]['coordinates'][0]['lng'];
        }

        return view('new-demo-page')->with(['geos' => $geos, 'all_coordinates' => $all_coordinates, 'center' => $center]);
    }

    
}
