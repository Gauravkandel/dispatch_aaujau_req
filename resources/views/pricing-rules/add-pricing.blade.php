@extends('layouts.vertical', ['title' => 'Pricing Rules'])

@section('css')
    <!-- Plugins css -->
    <link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet"
    type="text/css" />
    <link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/selectize/selectize.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .new{
            margin-top: 38px;
        }
    </style>
@endsection

@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->  
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-box">
                    <h4 class="page-title">Pricing Rules</h4>
                </div>
            </div>
        </div>
        <form id="task_form" action="{{ route('pricing-rules.store') }}" method="POST">
            @csrf
            @include('pricing-rules.pricing-form')
         </form>  
        <!-- end page title --> 

        
        <!-- End row -->

    
        <!-- end Row -->

        <div class="row">
            
        </div>
        <!-- end Row -->
        
    </div> <!-- container -->
@endsection

@section('script')
    <!-- Plugins js-->
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>

    <!-- Page js-->

    <script>
        $('input:checkbox[name="is_default"]').change(
    function(){
        if ($(this).is(':checked')) 
            $('.temp').hide();
        else
        $('.temp').show();
    });
    </script>
@endsection