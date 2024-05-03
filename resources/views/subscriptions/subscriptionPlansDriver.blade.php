@extends('layouts.vertical', ['title' => 'Subscriptions'])

@section('css')
<!-- Plugins css -->
<link href="{{ asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" />
<style>
    .percentage_value_wrapper{
        display: none;
    }
    .user_subscription .card-body label small {
        font-size: 12px;
        line-height: 18px;
        letter-spacing: 0.1px;
        color: #000000bd;
    }
    .user_info_icon{position: relative;cursor: pointer;}
    
    .user_info_icon p {
        position: absolute;
        background-color: #000;
        border-radius: 5px;
        box-shadow: 0 0 10px rgb(0 0 0 / 20%);
        padding: 0 0;
        right: 20px;
        top: 0;
        opacity: 0;
        transition: 0.5s;
        color: #fff !important;
        font-size: 12px;
        text-align: center;
        z-index: 9;
    }
    .user_info_icon p:before{
        position: absolute;
        content:"";
        border-width: 5px;
        border-style: solid;
        border-color: #000 transparent transparent transparent;
        top: 5px;
        right: -10px;
        -webkit-transform: rotate(-90deg);
        transform: rotate(-90deg);
    }
    .user_info_icon:hover p{opacity: 1;}
    .al_user{padding-right:45px}
    .alFromUsers span.switchery.switchery-default {
    position: absolute;
    right: 0;
    top:0;
}
    @media screen and (max-width:576px){
        .user_subscription .card-body label small {
                font-size: 13px;
                line-height: 15px;
                letter-spacing: 0.1px;
                color: #000000bd;
    }   
    }
</style>
@endsection

@section('content')
<!-- Start Content-->


    <div class="content dashboard-boxes">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __(getAgentNomenclature().' Subscription Plans') }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-right">
                    <button class="btn btn-info waves-effect waves-light text-sm-right" data-toggle="modal" data-target="#add-subscription-plan">
                        <i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add Plan') }}
                    </button>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="text-sm-left">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <span>{!! \Session::get('success') !!}</span>
                            </div>
                        @endif
                        @if (\Session::has('error_delete'))
                            <div class="alert alert-danger">
                                <span>{!! \Session::get('error') !!}</span>
                            </div>
                        @endif
                        @if ( ($errors) && (count($errors) > 0) )
                            <div class="alert alert-danger">
                                <ul class="m-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card widget-inline">
                        <div class="card-body p-2">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <h3>
                                            <i class="mdi mdi-account-multiple-plus text-primary mdi-24px"></i>
                                            <span data-plugin="counterup" id="total_subscribed_users_count">{{ $subscribed_users_count }}</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">{{ __('Total Subscribed '.getAgentNomenclature().'s') }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <h3>
                                            <i class="mdi mdi-account-multiple-plus text-primary mdi-24px"></i>
                                            <span data-plugin="counterup" id="total_subscribed_users_percentage">{{ $subscribed_users_percentage }}</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">{{ __("Total Subscribed ".getAgentNomenclature()."s") }} (%)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row user_subscription">
                {{-- <div class="col-md-3 col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="user_info_icon text-right pb-1">
                                 <i class="fa fa-info-circle" aria-hidden="true"></i>
                                 <p>{{ __("This will be by pass if the customer already have plan.") }}</p>
                            </div>
                            <div class="form-group alFromUsers position-relative mb-2">
                                <label for="isolate_single_vendor_order" class="al_user">
                                    <small class="d-block">{{ __("Show Subscription plan to customer.") }}</small>
                                </label>
                                <input class="show-subscription-plan" type="checkbox" data-plugin="switchery" name="show_plan_customer" class="chk_box status_check" data-color="#43bee1" {{(@$showSubscriptionPlan->show_plan_customer == 1) ? 'checked' : ''}}>
                            </div>
                            <div class="form-group alFromUsers position-relative mb-2">
                                <label for="isolate_single_vendor_order" class="al_user">
                                    <small class="d-block">{{ __("On every sign-up(Web).") }}</small>
                                </label>
                                <input class="show-subscription-plan" type="checkbox" data-plugin="switchery" name="every_sign_up" class="chk_box status_check" data-color="#43bee1" {{(@$showSubscriptionPlan->every_sign_up == 1) ? 'checked' : ''}}>
                            </div>
                            <div class="form-group alFromUsers position-relative mb-2">
                                <label for="isolate_single_vendor_order" class="al_user">
                                    <small class="d-block">{{ __("On every app open.") }}</small>
                                </label>
                                <input class="show-subscription-plan" type="checkbox" data-plugin="switchery" name="every_app_open" class="chk_box status_check" data-color="#43bee1" {{(@$showSubscriptionPlan->every_app_open == 1) ? 'checked' : ''}}>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <form name="saveOrder" id="saveOrder"> @csrf </form>
                                    <table class="table table-centered table-nowrap table-striped" id="sub-plans-datatable">
                                        <thead>
                                            <tr>
                                                <th>{{ __("Image") }}</th>
                                                <th>{{ __("Title") }}</th>
                                                <th>{{ __("Description") }}</th>
                                                <th>{{ __("Price") }}</th>
                                                <th>{{ __("Frequency") }}</th>
                                                <th>{{ __(getAgentNomenclature()." Type") }}</th>
                                                <th>{{ __("Fixed Commission") }}</th>
                                                <th>{{ __("Percentage Commission") }}</th>
                                                <th>{{ __("Status") }}</th>
                                                <th>{{ __("Action") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="subscriptions_list">
                                            @foreach($subscription_plans as $i => $plan)
                                            <?php
                                            ?>
                                            <tr data-row-id="{{$plan->slug}}">
                                                <td>
                                                    <img src="{{ !empty($plan->image) ? $plan->image : '' }}" class="rounded-circle" alt="{{$plan->slug}}" width="40px" height="40px">
                                                </td>
                                                <td><a href="javascript:void(0)" class="editSubscriptionPlanBtn" data-id="{{$plan->slug}}">{{$plan->title}}</a></td>
                                                <td>{{$plan->description}}</td>
                                                <td>${{number_format($plan->price, 2, '.', '')}}</td>
                                                <td>{{__(ucfirst($plan->frequency))}}</td>
                                                <td>{{__($plan->driver_type)}}</td>
                                                <td>{{__($plan->driver_commission_fixed)}}</td>
                                                <td>{{__($plan->driver_commission_percentage)}}</td>
                                                <td>
                                                    <div class="custom-switch redio-all">
                                                        <input type="checkbox" name="userSubscriptionStatus" class="custom-control-input status_check" data-id="{{$plan->slug}}" value="{{ $plan->status }}" id="userSubscriptionStatus_{{ $i }}" {{($plan->status == 1) ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="userSubscriptionStatus_{{ $i }}"></label>
                                                    </div>
                                                    {{-- <input type="checkbox" data-id="{{$plan->slug}}" data-plugin="switchery" name="userSubscriptionStatus" class="chk_box status_check" data-color="#43bee1" {{($plan->status == 1) ? 'checked' : ''}} > --}}
                                                </td>
                                                <td>
                                                    <div class="form-ul" style="width: 60px;">
                                                        <div class="inner-div" >
                                                            @if(Auth::user()->is_superadmin == 1)
                                                                <a href="javascript:void(0)" class="action-icon editSubscriptionPlanBtn" data-id="{{$plan->slug}}"><i class="mdi mdi-square-edit-outline"></i></a>
                                                                <a href="{{route('subscription.plan.delete.driver', $plan->slug)}}" onclick="return confirm('Are you sure? You want to delete the subscription plan.')" class="action-icon deleteSubscriptionPlanBtn"> <i class="mdi mdi-delete" title="Delete subscription plan"></i></a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->

    </div>

 <!-- container -->

<div id="add-subscription-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addSubscriptionPlan_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Add Plan') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="user_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscription.plan.save.driver') }}">
                @csrf
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label>{{ __('Upload Image') }}</label>
                                    <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                                    <label class="logo-size text-right w-100">{{ __('Image Size') }} 120x120</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group position-relative mb-2">
                                        <div class="custom-switch redio-all">
                                            <input type="checkbox" name="status" class="custom-control-input" id="status" value="1" checked>
                                            <label class="custom-control-label" for="status">Enable</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('title', __('Title'),['class' => 'control-label']) !!}
                                        {!! Form::text('title', null, ['class'=>'form-control', 'required'=>'required']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{ __(getAgentNomenclature()." Type") }}</label>
                                        <select class="selectpicker" data-style="btn-light" name="driver_type" id="driver_type">
                                            <option value="Employee">{{__("Employee")}}</option>
                                            <option value="Freelancer">{{__("Freelancer")}}</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6 features_wrapper">
                                    <div class="form-group">
                                        <label for="">{{ __("Features") }}</label>
                                        <select class="form-control select2-multiple subscription_features" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required="required">
                                            @foreach($features as $feature)
                                                <option value="{{$feature->id}}"> {{$feature->title}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{ __('Price') }}</label>
                                        <input class="form-control" type="number" name="price" min="0" required="required">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{ __("Frequency") }}</label>
                                        <select class="form-control" name="frequency" required="required">
                                            <option value="weekly">{{ __("Weekly") }}</option>
                                            <option value="monthly">{{ __("Monthly") }}</option>
                                            <option value="yearly">{{ __("Yearly") }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="commission_fixed">{{ __('Fixed Commission') }}</label>
                                        <input class="form-control" type="number" id="commission_fixed" name="commission_fixed" min="0" placeholder="Commission in fixed amount" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="commission_percentage">{{ __('Percentage Commission') }}</label>
                                        <input class="form-control" type="number" id="commission_percentage" name="commission_percentage" min="0" placeholder="Commission in percentage" value="0" onKeyPress="if(this.value.length==6) return false;">
                                    </div>
                                </div>
                                <?php /* ?><div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sort Order</label>
                                        <input class="form-control" type="number" name="sort_order" min="1" required="required">
                                    </div>
                                </div><?php */ ?>
                                <div class="col-md-12">
                                    <div class="form-group" id="descInput">
                                        {!! Form::label('title', __('Description'),['class' => 'control-label']) !!}
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-subscription-plan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editUserSubscription_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/libs/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>
<script>
    var edit_sub_plan_url = "{{ route('subscription.plan.edit.driver', ':id') }}";
    var update_sub_plan_status_url = "{{route('subscription.plan.updateStatus.driver', ':id')}}";
    var show_subscription_plan = "{{route('show.subscription.plan.driver')}}";

    $(document).delegate(".editSubscriptionPlanBtn", "click", function(){
        let slug = $(this).attr("data-id");
        $.ajax({
            type: "get",
            dataType: "json",
            url: edit_sub_plan_url.replace(":id", slug),
            success: function(res) {
                $("#edit-subscription-plan .modal-content").html(res.html);
                $("#edit-subscription-plan").modal("show");
                // $('#edit-subscription-plan .select2-multiple').select2();
                $('#edit-subscription-plan .dropify').dropify();
                // var switchery = new Switchery($("#edit-subscription-plan .status")[0]);
            }
        });
    });

    $("#sub-plans-datatable .status_check").on("change", function() {
        var slug = $(this).attr('data-id');
        var status = 0;
        if($(this).is(":checked")){
            status = 1;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: update_sub_plan_status_url.replace(":id", slug),
            data: {status: status},
            success: function(response) {
                return response;
            }
        });
    });

    $(".show-subscription-plan").on("change", function() {
        var showSubscriptionType = $(this).attr('name');
        var status = 0;
        if($(this).is(":checked")){
            status = 1;
        }
        console.log('showSubscriptionType', showSubscriptionType);
        console.log('status', status);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: show_subscription_plan,
            data: {showSubscriptionType:showSubscriptionType, status:status},
            success: function(response) {
                return response;
            }
        });
    });

    // $(document).on("input", ".subscription_features", function(e){
    //     var features = $(this).val();
    //     if(features.includes('2')){
    //         $(this).parents('.features_wrapper').next().show();
    //         $(this).parents('.features_wrapper').next().find('input').attr('required', true);
    //     }else{
    //         $(this).parents('.features_wrapper').next().hide();
    //         $(this).parents('.features_wrapper').next().find('input').removeAttr('required');
    //     }
    // });

    $(document).on("click", "#edit-subscription-plan input[name='status']", function(){
        if($(this).prop('checked')){
            $(this).val(1);
        }else{
            $(this).val(0);
        }
    });

</script>

@endsection
