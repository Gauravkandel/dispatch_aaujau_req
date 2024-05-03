<?php

namespace App\Http\Controllers;

use App\Model\AgentsTag;
use App\Model\ClientPreference;
use App\Model\Geo;
use App\Model\PricePriority;
use App\Model\PricingRule;
use App\Model\TagsForAgent;
use App\Model\TagsForTeam;
use App\Model\Team;
use App\Model\TeamTag;
use App\Model\Timezone;
use App\Model\Client;
use App\Model\priceRuleTimeframe;
use App\Model\priceRuleTag;
use App\Model\ClientPreferences;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class PricingRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tz = new Timezone();
        $client = Client::where('code', Auth::user()->code)->with(['getAllocation', 'getPreference', 'getTimezone'])->first();
        $client_timezone = $client->getTimezone ? $client->getTimezone->timezone : 251;
        $timezone = $tz->timezone_name($client_timezone);
        
        $pricing = PricingRule::orderBy('created_at', 'DESC');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $pricing = $pricing->whereHas('priceRuleTags.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $pricing = $pricing->get();

        $priority = PricePriority::where('id', 1)->first();

        $geos       = Geo::all()->pluck('name', 'id');

        $teams      = Team::OrderBy('id','asc');
        
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $teams = $teams->whereHas('permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $teams      = $teams->get()->pluck('name', 'id');
        $team_tag   = TagsForTeam::OrderBy('id','asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $team_tag = $team_tag->whereHas('assignTeams.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        } 
        
        $team_tag = $team_tag->get()->pluck('name', 'id');
        $driver_tag = TagsForAgent::OrderBy('id','asc');
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $driver_tag = $driver_tag->whereHas('assignTags.agent.team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        
        $driver_tag = $driver_tag->get()->pluck('name', 'id');
        return view('pricing-rules.index')->with(['pricing' => $pricing, 'priority'=>$priority, 'geos' => $geos, 'teams' => $teams, 'team_tag' => $team_tag, 'driver_tag' => $driver_tag, 'weekdays' => $weekdays, 'timezone' => $timezone, 'client' => $client]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $geos       = Geo::all()->pluck('name', 'id');
        $teams      = Team::all()->pluck('name', 'id');
        $team_tag   = TagsForTeam::all()->pluck('name', 'id');
        $driver_tag = TagsForAgent::all()->pluck('name', 'id');
        $clientPre  = ClientPreference::where('id', 1)->with('currency')->first();
       
        return view('pricing-rules.add-pricing', compact('geos', 'teams', 'team_tag', 'driver_tag'));
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'start_date_time' => ['required'],
            'end_date_time' => ['required']
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
        $data = [
            'name'                            => $request->name,
            'start_date_time'                 => $request->start_date_time??date("Y-m-d H:i:s"),
            'end_date_time'                   => $request->end_date_time??date("Y-m-d H:i:s", strtotime('+15 years')),
            'is_default'                      => $request->is_default,
            'base_price'                      => $request->base_price,
            'base_duration'                   => $request->base_duration,
            'base_distance'                   => $request->base_distance,
            'base_waiting'                    => $request->base_waiting,
            'duration_price'                  => $request->duration_price,
            'waiting_price'                   => $request->waiting_price,
            'distance_fee'                    => $request->distance_fee,
            'cancel_fee'                      => $request->cancel_fee,
            'agent_commission_percentage'     => $request->agent_commission_percentage,
            'agent_commission_fixed'          => $request->agent_commission_fixed,
            'freelancer_commission_percentage'=> $request->freelancer_commission_percentage,
            'freelancer_commission_fixed'     => $request->freelancer_commission_fixed,
            'apply_timetable'                 => ($request->has('apply_timetable') && $request->apply_timetable == 'on') ? '1' : '2'
        ];
        
        $pricerule = PricingRule::create($data);

        //code to insert multiple selection of different type of tags
        $geo_ids                          = (!empty($request->geo_id))?$request->geo_id:array();
        $team_ids                         = (!empty($request->team_id))?$request->team_id:array();
        $team_tag_ids                     = (!empty($request->team_tag_id))?$request->team_tag_id:array();
        $driver_tag_ids                   = (!empty($request->driver_tag_id))?$request->driver_tag_id:array();

        foreach($geo_ids as $geo_id):
            priceRuleTag::insert(['pricing_rule_id' => $pricerule->id, 'tag_id' => $geo_id, 'identity' => 'Geo']);
        endforeach;

        foreach($team_ids as $team_id):
            priceRuleTag::insert(['pricing_rule_id' => $pricerule->id, 'tag_id' => $team_id, 'identity' => 'Team']);
        endforeach;

        foreach($team_tag_ids as $team_tag_id):
            priceRuleTag::insert(['pricing_rule_id' => $pricerule->id, 'tag_id' => $team_tag_id, 'identity' => 'Team_tag']);
        endforeach;

        foreach($driver_tag_ids as $driver_tag_id):
            priceRuleTag::insert(['pricing_rule_id' => $pricerule->id, 'tag_id' => $driver_tag_id, 'identity' => 'Agent']);
        endforeach;

        // code to insert day wise timeframes.
        $hddn_days_count = $request->hddn_days_count;
        for($i = 1;$i<=$hddn_days_count;$i++):

            if(!empty($request->input('no_of_time_'.$i))):

                $day_name      = $request->input('hddnWeekdays_'.$i);
                $is_applicable = (!empty($request->input('checkdays_'.$i)))?1:0;

                for($j = 1;$j<=$request->input('no_of_time_'.$i);$j++):

                    if(!empty($request->input('price_starttime_'.$i.'_'.$j)) && !empty($request->input('price_endtime_'.$i.'_'.$j))):

                        $pricing_timeframe = [
                                'pricing_rule_id'  => $pricerule->id,
                                'day_name'         => $day_name,
                                'is_applicable'    => $is_applicable,
                                'start_time'       => (!empty($request->input('price_starttime_'.$i.'_'.$j)))?$request->input('price_starttime_'.$i.'_'.$j):NULL,
                                'end_time'         => (!empty($request->input('price_endtime_'.$i.'_'.$j)))?$request->input('price_endtime_'.$i.'_'.$j):NULL
                        ];
                        priceRuleTimeframe::create($pricing_timeframe);

                    endif;

                endfor;

            endif;

        endfor;

        return redirect()->route('pricing-rules.index')->with('success', __('Pricing rule added successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($domain = '', $id)
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
        $pricing         = PricingRule::where('id', $id)->with('priceRuleTags')->first();
        
        $selectedtags = array();
        foreach($pricing->priceRuleTags as $priceRuleTag):
            $selectedtags[$priceRuleTag->identity][] = $priceRuleTag->tag_id;
        endforeach;

        $weekdays        = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $pricetimeframes = [];
        foreach($weekdays as $weekday):
            $timeframedata      = priceRuleTimeframe::where('pricing_rule_id', $id)->where('day_name', '=', $weekday)->get();
            $pricetimeframes[]  = array('days'      => $weekday, 'timeframe' => (!empty($timeframedata))?$timeframedata:array());
        endforeach;
        $geos            = Geo::all()->pluck('name', 'id');
        $teams           = Team::all()->pluck('name', 'id');
        $team_tag        = TagsForTeam::all()->pluck('name', 'id');
        $driver_tag      = TagsForAgent::all()->pluck('name', 'id');
        $clientPre       = ClientPreference::where('id', 1)->with('currency')->first();
        //pr($pricetimeframes);
        $returnHTML = view('pricing-rules.form')->with(['pricing' => $pricing, 'geos' => $geos, 'teams' => $teams, 'team_tag' => $team_tag, 'driver_tag' => $driver_tag,'client_pre'=> $clientPre, 'weekdays' => $weekdays, 'pricetimeframes' => $pricetimeframes, 'selectedtags' => $selectedtags])->render();

        return response()->json(array('success' => true, 'html'=>$returnHTML));
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
        $getAgent = PricingRule::find($id);
        $data = [
            'name'                            => $request->name,
            'start_date_time'                 => $request->start_date_time,
            'base_price'                      => $request->base_price,
            'base_duration'                   => $request->base_duration,
            'base_distance'                   => $request->base_distance,
            'duration_price'                  => $request->duration_price,
            'distance_fee'                    => $request->distance_fee,
            'cancel_fee'                      => $request->cancel_fee,
            'agent_commission_percentage'     => $request->agent_commission_percentage,
            'agent_commission_fixed'          => $request->agent_commission_fixed,
            'freelancer_commission_percentage'=> $request->freelancer_commission_percentage,
            'freelancer_commission_fixed'     => $request->freelancer_commission_fixed,
            'apply_timetable'                 => ($request->has('apply_timetable') && $request->apply_timetable == 'on') ? '1' : '2'
        ];
        
        $pricing = PricingRule::where('id', $id)->update($data);
 
        priceRuleTag::where('pricing_rule_id', $id)->delete();
        priceRuleTimeframe::where('pricing_rule_id', $id)->delete();

        //code to insert multiselection different type of tags.
        $geo_ids                          = (!empty($request->geo_id))?$request->geo_id:array();
        $team_ids                         = (!empty($request->team_id))?$request->team_id:array();
        $team_tag_ids                     = (!empty($request->team_tag_id))?$request->team_tag_id:array();
        $driver_tag_ids                   = (!empty($request->driver_tag_id))?$request->driver_tag_id:array();

        foreach($geo_ids as $geo_id):
            priceRuleTag::insert(['pricing_rule_id' =>  $id, 'tag_id' => $geo_id, 'identity' => 'Geo']);
        endforeach;

        foreach($team_ids as $team_id):
            priceRuleTag::insert(['pricing_rule_id' =>  $id, 'tag_id' => $team_id, 'identity' => 'Team']);
        endforeach;

        foreach($team_tag_ids as $team_tag_id):
            priceRuleTag::insert(['pricing_rule_id' =>  $id, 'tag_id' => $team_tag_id, 'identity' => 'Team_tag']);
        endforeach;

        foreach($driver_tag_ids as $driver_tag_id):
            priceRuleTag::insert(['pricing_rule_id' =>  $id, 'tag_id' => $driver_tag_id, 'identity' => 'Agent']);
        endforeach;

        // code to insert day wise timeframes.
        $hddn_edit_days_count = $request->hddn_edit_days_count;
        for($i = 1;$i<=$hddn_edit_days_count;$i++):
            if(!empty($request->input('edit_no_of_time_'.$i))):
                $day_name      = $request->input('hddnWeekdays_edit_'.$i);
                $is_applicable = (!empty($request->input('checkdays_edit_'.$i)))?1:0;
                for($j = 1;$j<=$request->input('edit_no_of_time_'.$i);$j++):
                    if(!empty($request->input('edit_price_starttime_'.$i.'_'.$j)) && !empty($request->input('edit_price_endtime_'.$i.'_'.$j))):
                        $pricing_timeframe = [
                                'pricing_rule_id'  => $id,
                                'day_name'         => $day_name,
                                'is_applicable'    => $is_applicable,
                                'start_time'       => (!empty($request->input('edit_price_starttime_'.$i.'_'.$j)))?$request->input('edit_price_starttime_'.$i.'_'.$j):NULL,
                                'end_time'         => (!empty($request->input('edit_price_endtime_'.$i.'_'.$j)))?$request->input('edit_price_endtime_'.$i.'_'.$j):NULL
                        ];
                        priceRuleTimeframe::create($pricing_timeframe);
                    endif;
                endfor;
            endif;
        endfor;
        
        return redirect()->route('pricing-rules.index')->with('success', __('Pricing rule updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($domain = '', $id)
    {
        $del_price_rule = PricingRule::where('id', $id);
        if (Auth::user()->is_superadmin == 0 && Auth::user()->all_team_access == 0) {
            $del_price_rule = $del_price_rule->orWhereHas('team.permissionToManager', function ($query) {
                $query->where('sub_admin_id', Auth::user()->id);
            });
        }
        $del_price_rule = $del_price_rule->delete();

        return redirect()->back()->with('success', __('Pricing rule deleted successfully'));
    }
}
