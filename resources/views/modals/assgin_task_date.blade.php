<div id="add-assgin-date-model" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title">Change Date/Time</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="submit_assign_date" method="POST" enctype="multipart/form-data" action="{{route('assign.date')}}">
                @csrf
                <div class="modal-body px-3 py-0">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group" id="typeInput">
                                {!! Form::label('title', 'Select Date',['class' => 'control-label']) !!}
                                <div class="d-flex align-items-center position-relative">
                                    <input type="text" id='datetime-datepicker' name="schedule_time" class="form-control upside opendatepicker"
                                        placeholder="Date Time" required>
                                    <button type="button" class="cstmbtn check_btn btn btn-info"><i class="fa fa-check" aria-hidden="true"></i></button>
                                </div>

                                
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-blue waves-effect waves-light w-100">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>