@php
$date = date('Y-m-d');
$color = ['one','two','three','four','five','six','seven','eight'];
$imgproxyurl = 'https://imgproxy.royodispatch.com/insecure/fill/90/90/sm/0/plain/';
use Carbon\Carbon;

$agentslocations = array();
if(!empty($agents)){
    foreach ($agents as $singleagent) {
        if((!empty($singleagent['agentlog'])) && ($singleagent['agentlog']['lat']!=0) && ($singleagent['agentlog']['long']!=0))
        {
            $agentslocations[] = $singleagent['agentlog'];
        }
    }
}

$defaultmaplocation['lat'] = $defaultCountryLatitude;
$defaultmaplocation['long'] = $defaultCountryLongitude;
$agentslocations[] = $defaultmaplocation;
@endphp

<div id="accordion" class="overflow-hidden">
    <div class="card no-border-radius">
        <div class="card-header" id="heading-1">
            <a role="button" data-toggle="collapse" href="#collapse-new"
                aria-expanded="false" aria-controls="collapse-new">
                <div class="newcheckit">
                    <div class="row d-flex align-items-center" class="mb-0">
                        <div class="col-md-4 col-lg-3 col-xl-2 col-2">
                            <span class="profile-circle">U</span>
                        </div>
                        <div class="col-md-8 col-lg-9 col-xl-10 col-10">
                            <h6 class="header-title">{{__('Unassigned')}}</h6>
                            <input type="hidden" id="newmarker_map_data" value="{{json_encode($newmarker)}}"/>
                            <input type="hidden" id="agents_map_data" value="{{json_encode($agents)}}"/>
                            <input type="hidden" id="agentslocations_map_data" value="{{json_encode($agentslocations)}}"/>
                            <input type="hidden" id="uniquedrivers_map_data" value="{{json_encode($routedata)}}"/>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div id="collapse-new" class="collapse" data-parent="#accordion"
            aria-labelledby="heading-1">
            <div class="card-body">
                <div id="accordion-0">
                    <div class="card no-border-radius">
                        <div class="card-header ml-2" id="by0">
                            <?php
                            if(isset($distance_matrix[0]))
                            {
                                if($unassigned_orders[0]['task_order']==0){
                                    $opti0 = "yes";
                                }else{
                                    $opti0 = "";
                                }
                                $routeperams0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','".$opti0."',0,'".$date."'";
                                $optimize0 = '<span class="optimize_btn" onclick="RouteOptimization('.$routeperams0.')">'.__("Optimize").'</span>';
                                $params0 = "'".$distance_matrix[0]['tasks']."','".json_encode($distance_matrix[0]['distance'])."','yes',0,'".$date."'";
                                $turnbyturn0 = '<span class="navigation_btn optimize_btn" onclick="NavigatePath('.$routeperams0.')">'.__("Export").'</span>';
                            }else{
                                $optimize0="";
                                $params0 = "";
                                $turnbyturn0 = "";
                            }
                            ?>
                            <a class="profile-block collapsed" role="button" data-toggle="collapse" href="#collapse0" aria-expanded="false" aria-controls="collapse0">
                                <div class="row">
                                    <div class="col-md-2 col-2">
                                        <span class="profile-circle">D</span>
                                    </div>
                                    <div class="col-md-10 col-10">
                                        <h6 class="mb-0 header-title scnd">{{__("Unassigned Tasks")}}<div  class="optimizebtn0">{!! $optimize0 !!} </div><div class="exportbtn0">{!! $turnbyturn0 !!} </div></h6>
                                        <p class="mb-0"> <span>{{ count($unassigned_orders) }} {{__("Tasks")}}</span> {!! $unassigned_distance==''?'':' <i class="fas fa-route"></i> '!!}<span class="dist_sec totdis0 ml-1">{{ $unassigned_distance }}</span></p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div id="collapse0" class="collapse" data-parent="#accordion-0" aria-labelledby="by0">
                            <div id="handle-dragula-left0" class="dragable_tasks" agentid="0"  params="{{ $params0 }}" date="{{ $date }}">
                                @foreach($unassigned_orders as $orders)
                                    @foreach($orders['task'] as $tasks)
                                        <div class="card-body" task_id ="{{ $tasks['id'] }}">
                                            <div class="p-2 assigned-block">
                                                @php
                                                    $st ="Unassigned";
                                                    $color_class = "assign_";
                                                    if($tasks['task_type_id']==1)
                                                    {
                                                        $tasktype = "Pickup";
                                                        $pickup_class = "yellow_";
                                                    }elseif($tasks['task_type_id']==2)
                                                    {
                                                        $tasktype = "Dropoff";
                                                        $pickup_class = "green_";
                                                    }else{
                                                        $tasktype = "Appointment";
                                                        $pickup_class = "assign_";
                                                        }
                                                @endphp

                                                <div>
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col-9 d-flex">
                                                            @php
                                                            if($tasks['assigned_time']=="")
                                                            {
                                                                $tasks['assigned_time'] = date('Y-m-d H:i:s');
                                                            }
                                                                $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                                                $order = Carbon::createFromFormat('Y-m-d H:i:s', $tasks['assigned_time'], 'UTC');

                                                                //$order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                                                $order->setTimezone($client_timezone);
                                                            @endphp

                                                            <h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i> <span>{{date(''.$timeformat.'', strtotime($order))}}</span></h5>
                                                            <h6 class="d-inline"><img class="vt-top"
                                                                src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span></h6>
                                                        </div>
                                                        <div class="col-3">
                                                            <button class="assigned-btn float-right mb-2 {{$pickup_class}}">{{__($tasktype)}}</button>
                                                            <button class="assigned-btn float-right {{$color_class}}">{{__($st)}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card no-border-radius">

        @foreach ($teams as $item)
            <div class="card-header" id="heading-1">
                    <a role="button" data-toggle="collapse" href="#collapse-{{ $item['id'] }}"
                        aria-expanded="false" aria-controls="collapse-{{ $item['id'] }}">
                        <div class="newcheckit">
                            <div class="row d-flex align-items-center" class="mb-0">
                                <div class="col-md-3 col-xl-2 col-2">
                                    <span class="profile-circle {{$color[rand(0,7)]}}">{{ substr(ucfirst($item['name']), 0, 2) }}</span>
                                </div>
                                <div class="col-md-9 col-xl-10 col-10">
                                    <h6 class="header-title">{{ ucfirst($item['name']) }}</h6>
                                    <p class="mb-0">
                                        <span class="team_agent_{{ $item['id'] }}" id="team_agent_{{ $item['id'] }}">{{ count($item['agents']) }}</span>
                                            {{ __(getAgentNomenclature()) }}
                                        @if($userstatus == 2)
                                            : <span>
                                                <span class="team_online_agent_{{ $item['id'] }}" id="team_online_agent_{{ $item['id'] }}">{{ $item['online_agents'] }}</span>
                                                    {{ __('Online') }} ・
                                                <span class="team_offline_agent_{{ $item['id'] }}" id="team_offline_agent_{{ $item['id'] }}">{{ $item['offline_agents'] }}</span>
                                                {{ __('Offline') }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
            </div>

            <div id="collapse-{{ $item['id'] }}" class="collapse" data-parent="#accordion"
                aria-labelledby="heading-1">
                <div class="card-body">
                    <?php //echo "<pre>";
                        // print_r($item['agents']); die;?>
                    @foreach ($item['agents'] as $agent)

                        <?php
                        if(isset($distance_matrix[$agent['id']]))
                        {
                            if($agent['order'][0]['task_order']==0){
                                $opti = "yes";
                            }else{
                                $opti = "";
                            }

                            $routeperams = "'".$distance_matrix[$agent['id']]['tasks']."','".json_encode($distance_matrix[$agent['id']]['distance'])."','".$opti."',".$agent['id'].",'".$date."'";

                            $optimize = '<span class="optimize_btn" onclick="RouteOptimization('.$routeperams.')">'.__("Optimize").'</span>';
                            $params = "'".$distance_matrix[$agent['id']]['tasks']."','".json_encode($distance_matrix[$agent['id']]['distance'])."','yes',".$agent['id'].",'".$date."'";

                            $turnbyturn = '<span class="navigation_btn optimize_btn" onclick="NavigatePath('.$routeperams.')">'.__("Export").'</span>';
                        }else{
                            $optimize="";
                            $params = "";
                            $turnbyturn = "";
                        }

                        //for exporting path
                        if(count($agent['order'])==1)
                        {
                            if($agent['is_available']==1)
                            {
                                $pdfperams = "'".$agent['order'][0]['task'][0]['id']."','','',".$agent['id'].",'".$date."'";
                                $turnbyturn = '<span class="navigation_btn optimize_btn" onclick="NavigatePath('.$pdfperams.')">'.__("Export").'</span>';
                            }else{
                                $pdfperams = "";
                                $turnbyturn = "";
                            }
                        }

                        ?>

                        <div id="accordion-{{ $agent['id'] }}">
                            <div class="card no-border-radius">
                                <div class="card-header ml-2" id="by{{ $agent['id'] }}">

                                        <a class="profile-block collapsed" role="button"
                                            data-toggle="collapse" href="#collapse{{ $agent['id'] }}"
                                            aria-expanded="false"
                                            aria-controls="collapse{{ $agent['id'] }}">
                                            <div class="row">
                                                <div class="col-md-2 col-2">
                                                    <img class="profile-circle"
                                                        src="{{isset($agent['profile_picture']) ? $imgproxyurl.Storage::disk('s3')->url($agent['profile_picture']):'https://dummyimage.com/36x36/ccc/fff'}}">
                                                </div>
                                                <div class="col-md-10 col-10">
                                                @php
                                                    $checkAgentActive = ($agent['is_available'] == 1)? ' ('.__('Online').')' : ' ('.__('Offline').')';
                                                @endphp

                                                <h6 class="mb-0 header-title scnd">
                                                    {{ ucfirst($agent['name']) }}
                                                    <span class="tram_agent_online_status_{{ $agent['id'] }}" id="tram_agent_online_status_{{ $agent['id'] }}">
                                                        {{ $checkAgentActive }}
                                                    </span>
                                                    <div class="optimizebtn{{ $agent['id'] }}">
                                                        {!! $optimize !!}
                                                    </div>
                                                    <div class="exportbtn{{ $agent['id'] }}">
                                                        {!! $turnbyturn !!}
                                                    </div></h6>
                                                <p class="mb-0">{{count($agent['order'])>0?__('Busy '):__('Free ')}}<span>{{$agent['agent_task_count']}} {{__('Tasks')}}</span> {!!$agent['total_distance']==''?'':' <i class="fas fa-route"></i>'!!}<span class="dist_sec totdis{{ $agent['id'] }}  ml-1">{{ $agent['total_distance'] }}</span></p>
                                                </div>
                                            </div>
                                        </a>
                                </div>
                                <div id="collapse{{ $agent['id'] }}" class="collapse"
                                    data-parent="#accordion-{{ $agent['id'] }}"
                                    aria-labelledby="by{{ $agent['id'] }}">
                                    <div id="handle-dragula-left{{ $agent['id'] }}" class="dragable_tasks" agentid="{{ $agent['id'] }}"  params="{{ $params }}" date="{{ $date }}">

                                    @foreach ($agent['order'] as $orders)

                                        @foreach ($orders['task'] as $tasks)

                                            <div class="card-body" task_id ="{{ $tasks['id'] }}">
                                                <div class="p-2 assigned-block">

                                                    @php
                                                            if($tasks['task_status']==1)
                                                            {
                                                            $st = "Assigned";
                                                            $color_class = "assign_";
                                                            }elseif($tasks['task_status']==2)
                                                            {
                                                            $st = "Started";
                                                            $color_class = "yellow_";
                                                            }elseif($tasks['task_status']==3)
                                                            {
                                                            $st = "Arrived";
                                                            $color_class = "light_green";
                                                            }elseif($tasks['task_status']==4)
                                                            {
                                                            $st = "Completed";
                                                            $color_class = "green_";
                                                            }else{
                                                            $st = "Failed";
                                                            $color_class = "red_";
                                                            }

                                                            if($tasks['task_type_id']==1)
                                                            {
                                                                $tasktype = "Pickup";
                                                                $pickup_class = "yellow_";
                                                            }elseif($tasks['task_type_id']==2)
                                                            {
                                                                $tasktype = "Dropoff";
                                                                $pickup_class = "green_";
                                                            }else{
                                                                $tasktype = "Appointment";
                                                                $pickup_class = "assign_";
                                                            }
                                                    @endphp
                                                    <div>

                                                        @php
                                                                if($tasks['assigned_time']=="")
                                                                {
                                                                    $tasks['assigned_time'] = date('Y-m-d H:i:s');
                                                                }
                                                                    $timeformat = $preference->time_format == '24' ? 'H:i:s':'g:i a';
                                                                    $order = Carbon::createFromFormat('Y-m-d H:i:s', $tasks['assigned_time'], 'UTC');

                                                                    //$order->setTimezone(isset(Auth::user()->timezone) ? Auth::user()->timezone : 'Asia/Kolkata');
                                                                    $order->setTimezone($client_timezone);
                                                                @endphp


                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-9 d-flex">
                                                                <h5 class="d-inline-flex align-items-center justify-content-between"><i class="fas fa-bars"></i> <span>{{date(''.$timeformat.'', strtotime($order))}}</span></h5>
                                                                <h6 class="d-inline"><img class="vt-top"
                                                                    src="{{ asset('demo/images/ic_location_blue_1.png') }}"> {{ isset($tasks['location']['address'])? $tasks['location']['address']:'' }} <span class="d-block">{{ isset($tasks['location']['short_name'])? $tasks['location']['short_name']:'' }}</span></h6>

                                                            </div>
                                                            <div class="col-3">
                                                                <button class="assigned-btn float-right mb-2 {{$pickup_class}}">{{__($tasktype)}}</button>
                                                                <button class="assigned-btn float-right {{$color_class}}">{{__($st)}}</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>


                                        @endforeach

                                    @endforeach
                                </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        
    </div>
</div>

