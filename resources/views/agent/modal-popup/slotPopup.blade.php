<div class="modal fade standard_modal" id="add-slot-modal" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3 px-3 border-bottom-0 d-block">
                <button type="button" class="close" data-dismiss="modal"
                    aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="modal-title">{{ __('Book Slot') }}</h5>
            </div>
            <div class="modal-body px-3 pb-3 pt-0" id="add_slot_form">
                <form class="needs-validation" name="slot-form" id="slot-event" action="" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ __("Start Time(24 hours format)") }}</label>
                                <input class="form-control" placeholder="Start Time" type="text" name="start_time" id="start_time" required />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ __("End Time(24 hours format)") }}</label>
                                <input class="form-control" placeholder="End Time" type="text" name="end_time" id="end_time" required />
                            </div>
                        </div>
                
                        <div class="col-md-6 slotForDiv">
                            {!! Form::label('title', 'Slot For',['class' => 'control-label']) !!}
                            <div class="form-group">
                                <ul class="list-inline">
                                    <li class="d-inline-block ml-3 mb-1 custom-radio-design">
                                        <input type="radio" class="custom-control-input check slotTypeRadio" id="slotDay" name="stot_type" value="day" checked="">
                                        <label class="custom-control-label" for="slotDay">{{ __('Days') }}&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    </li>
                                    <li class="d-inline-block ml-3 mb-1 custom-radio-design">
                                        <input type="radio" class="custom-control-input check slotTypeRadio" id="slotDate" name="stot_type" value="date">
                                        <label class="custom-control-label" for="slotDate">{{ __('Date') }}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 weekDays">
                        <div class="col-md-12">
                            <div class="">
                            {!! Form::label('title', __('Select days of week'),['class' => 'control-label']) !!}
                            </div>
                            <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                <input name="week_day[]" type="checkbox" id="day_1" value="1">
                                <label for="day_1"> {{ __("Sunday") }} </label>
                            </div>
                            <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                <input name="week_day[]" type="checkbox" id="day_2" value="2">
                                <label for="day_2"> {{ __('Monday') }} </label>
                            </div>
                            <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                <input name="week_day[]" type="checkbox" id="day_3" value="3">
                                <label for="day_3"> {{ __("Tuesday") }} </label>
                            </div>
                            <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                <input name="week_day[]" type="checkbox" id="day_4" value="4">
                                <label for="day_4"> {{ __("Wednesday") }} </label>
                            </div>
                            <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                <input name="week_day[]" type="checkbox" id="day_5" value="5">
                                <label for="day_5"> {{ __('Thursday') }} </label>
                            </div>
                            <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                <input name="week_day[]" type="checkbox" id="day_6" value="6">
                                <label for="day_6"> {{ __('Friday') }} </label>
                            </div>
                            <div class="checkbox checkbox-success form-check pl-0 mb-1">
                                <input name="week_day[]" type="checkbox" id="day_7" value="7">
                                <label for="day_7"> {{ __('Saturday') }} </label>
                            </div>
                        </div>
                    </div>
                
                    <div class="row forDate" style="display: none;">
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label class="control-label">{{ __("Slot Date") }}</label>
                                <input class="form-control date-datepicker" placeholder={{ __("Select Date") }} type="text" name="slot_date" id="slot_date" required />
                            </div>
                        </div>
                
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 d-sm-flex justify-content-between">
                            <button type="button" class="btn btn-light mr-1" data-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-info" id="btn-save-slot">{{ __('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal standard_modal fade" id="edit-slot-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3 px-3 border-bottom-0 d-block">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title" id="modal-title">Edit Slot</h5>
                
            </div>
            <div class="modal-body px-3 pb-3 pt-0" id="edit_slot_form" >
                
            </div>
        </div>
    </div>
</div>


