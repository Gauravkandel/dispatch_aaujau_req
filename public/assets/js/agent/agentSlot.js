$(function(){
    
    dispatcherStorage.removeStorageAll();
    dispatcherStorage.setStorageSingle('eventType','working_hours');
    var product_id = vendor_id = title = block = appoin=agent_id = calendar='' ;
    var calendarEl = document.getElementById('calendar');
    $(document).on('click', '.agent_slot_button', function() {
        agent_id = $(this).data('agent_id');
        spinnerJS.showSpinner();
        $('#agentTablePopup').modal('show'); 
        $('#agentTablePopup').find('.nav button:first').tab('show');
        fullCalendarInt(agent_id);
    });

    $(document).on('change','.slotTypeEdit',function(){
        var val = $(this).val();
        dispatcherStorage.setStorageSingle('SlotType',val);
        if(val == 'date') {
            if($('.forDate').length >0){
                $(".forDate").fadeIn(1000);
            }else{
                $(".forDateEdit").fadeIn(1000);
            }
           
        } else{
            if($('.forDate').length >0){
                $(".forDate").fadeOut(500);
            }else{
                $(".forDateEdit").fadeOut(500);
            }
            
        }
    })
    
    $(document).on('change', '#edit_slot_date', function() {
        var edit_slot_date = $(this).val();
        dispatcherStorage.setStorageSingle('EditSlotDate',edit_slot_date);
    });

    $(document).on('change', '#recurring', function(e) {
        var edit = $("#recurring").hasClass("edit_recurring") ? 1 : 0;
        if(e.target.checked){
            dispatcherStorage.setStorageSingle('recurring_val',1)
            $(".weekDays").fadeIn(1000);
            if(edit==1){
                document.getElementById("blocktime").disabled = false; 
                $(".forDate").fadeOut(1000);
            }
            
        }else{
            dispatcherStorage.setStorageSingle('recurring_val',0)
            $(".weekDays").fadeOut(1000);
            if(edit==1){
                $(".forDate").fadeIn(1000);
                document.getElementById("blocktime").disabled = true; 
            }
        }
            
        dispatcherStorage.setStorageSingle('recurring',e.target.checked);
    });


    $(document).on('click', '#deleteSlotBtn', function() {
        var slot_date = $('#edit_slot_date').val();
        var slot_id = $('#edit_slot_id').val();
        var recurring = dispatcherStorage.getStorage('recurring_val')
        var blocktime = $('#blocktime').val();
        var week_day = [];

        //const slotType = dispatcherStorage.getStorage('SlotType')
       
        $.each($("input:checkbox[name='week_day[]']:checked"), function () {
            week_day.push($(this).val());
        });
        var formData = {
            slot_date:slot_date,
            slot_id:slot_id,
            week_day:week_day,
            blocktime:blocktime,
            recurring:recurring,
            agent_id:agent_id,
          }
        //  console.log(formData);
          //return false;
       // $('#edit-slot-modal #deleteSlotDate').val(date);
       
        Swal.fire({
            title: 'Are you sure? You want to delete slot.',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete all!',
            cancelButtonText: 'Yes, delete single day!',
            reverseButtons: true,
            preConfirm: () => {
            
            },onOpen: function() {
            }
          }).then(async (result) => {
          
            
            if (result.isConfirmed) {
                formData.delete_type = 'all'
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                formData.delete_type = 'single'
            }
            console.log(formData);
               await deleteSlot(formData)
          })
       
        return false;
    })

    $(document).on('click', '.get_event', function() {
        var event = $(this).attr('data-eventType');
        dispatcherStorage.setStorageSingle('eventType',event);
        spinnerJS.showSpinner();
        fullCalendarInt(agent_id,event);
    });

    async function fullCalendarInt(agent_id,eventType='working_hours'){
        
        var eventEnabled = true;
        if(eventType == 'working_hours'){
            eventEnabled = true;
        } else if(eventType == 'new_booking'){
            eventEnabled = true;
        } else {
            eventEnabled = true;
        }
        if($('#calendar').length > 0){
            if(calendar){
                 calendar.destroy();
            }
            
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                disableDragging: true,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                slotLabelFormat: [
                    {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: ""
                    }
                ],
                eventTimeFormat: { // like '14:30:00'
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: ""
                },
                navLinks: true,
                selectable: eventEnabled,
                selectMirror: true,
                selectOverlap:false,
                height: 'auto',
                editable: false,
                nowIndicator: true,
                //eventMaxStack: 1,
                select: function(arg) {
                    
                    dispatcherStorage.removeStorageSingle('recurring');
                    console.log(arg);
                    initDatetimeRangePicker(arg.startStr,arg.endStr);
                    Swal.fire({
                        title: 'Add working hours',
                        html: AddSlotHtml,
                        confirmButtonText: 'Submit',
                        focusConfirm: false,
                        customClass: "edit-slot-agent",
                        preConfirm: () => {
                            const start_time = Swal.getPopup().querySelector('#start_time').value
                            const end_time = Swal.getPopup().querySelector('#end_time').value
                            const blocktime = Swal.getPopup().querySelector('#blocktime').value
                            const recurring =  Swal.getPopup().querySelector('#recurring').value //dispatcherStorage.getStorage('recurring_val');
                            const memo = Swal.getPopup().querySelector('#memo').value;
                            const booking_type = Swal.getPopup().querySelector('#booking_type').value


                            var week_day = [];

                            //const slotType = dispatcherStorage.getStorage('SlotType')
                           
                            $.each($("input:checkbox[name='week_day[]']:checked"), function () {
                                week_day.push($(this).val());
                            });
                           
                            if (start_time=='' && end_time=='' && blocktime=='' && memo=='' && booking_type=='') {
                               Swal.showValidationMessage(`All feilds are required!!`)
                               return false;
                            }
                            if(recurring == 'true'){
                                console.log(week_day);
                                if (!week_day.length>0) {
                                    Swal.showValidationMessage(`Please select days to recurring!!`)
                                    return false;
                                }
                                
                            }
                            return { start_time: start_time, end_time: end_time,week_day:week_day,blocktime:blocktime,recurring:recurring,booking_type:booking_type,memo:memo}
                        },onOpen: function() {
                            
                        
                        }
                      }).then(async (result) => {
                     
                        if(result.dismiss== undefined){
                            var formData = {
                                start_time:result.value.start_time,
                                end_time:result.value.end_time,
                                week_day:result.value.week_day,
                                blocktime:result.value.blocktime,
                                recurring:result.value.recurring,
                                agent_id:agent_id,
                                booking_type:result.value.booking_type,
                                memo:result.value.memo
                              }
                              console.log(formData);
                              await add_slot_time(formData)
                        } else{
                            //alert()
                            //arg.remove()
                        }
                       
                      
                      })
                  
              
                    // calendar.addEvent({
                    //     title: '',
                    //     start: arg.start,
                    //     end: arg.end,
                    //     allDay: arg.allDay
                    // })
                    $('#standard-modal').modal({
                        //backdrop: 'static',
                        keyboard: false
                    });
                    var day = arg.start.getDay() + 1;
                    $('#day_' + day).prop('checked', true);
                     console.log(arg.start.getHours());
             
                    initDatetimeRangePicker(  new Date( arg.start), new Date( arg.end));
                    if (arg.allDay == true) {
                        document.getElementById('start_time').value = "00:00";
                        document.getElementById('end_time').value = "23:59";
                    } else {
                        var startTime = ("0" + arg.start.getHours()).slice(-2) + ":" + ("0" + arg.start.getMinutes()).slice(-2);
                        var EndTime = ("0" + arg.end.getHours()).slice(-2) + ":" + ("0" + arg.end.getMinutes()).slice(-2);
    
                        document.getElementById('start_time').value = startTime;
                        document.getElementById('end_time').value = EndTime;
                    }
                 },
               
    
                events: function(info, successCallback, failureCallback) {
                    var calender_data_url  = Agent_calender_url.replace(":id", agent_id) 
                    $.ajax({
                        url: calender_data_url,
                        type: "GET",
                        data: `start=${info.startStr}&end=${info.endStr}&eventType=${eventType}`,
                        dataType:'json',
                        success: function (response) {
                            var startDate = moment(info.start).format('MMM DD');
                            var endDate = moment(info.end - 1).format('DD, YYYY');
                            $("#calendar_slot_alldays_table thead th").html(startDate+" - "+endDate);
                            $("#calendar_slot_alldays_table tbody").html("");
                            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            var slotDayList = [];
                            var events = [];
                            console.log(response);
                            $.each(response, function(index, data){
                                var slotDay = parseInt(moment(data.start).format('d')) + 1;
                                    var slotStartTime = moment(data.start).format('h:mm A');
                                    var slotEndTime = moment(data.end).format('h:mm A');
                                
    
                                $.each(days, function(key, value){
                                    if(slotDay == key + 1){
                                        if(slotDayList.includes(slotDay)){
                                            $("#calendar_slot_alldays_table tbody tr[data-slotDay='"+slotDay+"'] td:nth-child(2)").append("<br>"+slotStartTime+" - "+slotEndTime);
                                        }
                                        else{
                                            $("#calendar_slot_alldays_table tbody").append("<tr data-slotDay="+slotDay+"><td>"+value+"</td><td>"+slotStartTime+" - "+slotEndTime+"</td></tr>");
                                        }
                                    }
                                });
                                slotDayList.push(slotDay);
    
                                events.push({
                                    title: data.title,
                                    start: data.start,
                                    end: data.end,
                                    type: data.type,
                                    color: data.color,
                                    type_id: data.type_id,
                                    slot_id: data.slot_id,
                                    schedule_date: data.memo,
                                    memo:data.memo,
                                    booking_type:data.booking_type,
                                    schedule_date: data.memo,
                                    start_time:data.start_time,
                                    end_time:data.end_time,
                                    start_date:data.start_date,
                                    end_date:data.end_date,
                                    agent_id:agent_id,
                                    recurring: data.recurring,
                                    order_url: data.order_url,
                                    days: data.days
                                    // slot_delivery: data.slot_delivery,
                                    // service_area: data.service_area,
                                });
                            });
                            successCallback(events);
                        }
                    });
                },
                eventResize: function(arg) {
                },
                eventClick: function(ev) {
                    if(!eventEnabled){
                        return;
                    }
                    if(ev.event.extendedProps.slot_id =='' && ev.event.extendedProps.slot_id ==undefined ){
                        return;
                    }
                    let title = ev.event.extendedProps.booking_type == 'new_booking' ? 'View Booking' : 'Edit working hours';
                    Swal.fire({
                        title: title,
                        html: EditSlotHtml,
                        confirmButtonText: 'Submit',
                        focusConfirm: false,
                        customClass: "edit-slot-agent",
                        preConfirm: () => {
                            const start_time = Swal.getPopup().querySelector('#edit_start_time').value
                            const end_time = Swal.getPopup().querySelector('#edit_end_time').value
                            const edit_slot_date = Swal.getPopup().querySelector('#edit_slot_date').value
                            const edit_slot_id = Swal.getPopup().querySelector('#edit_slot_id').value
                            const blocktime = Swal.getPopup().querySelector('#blocktime').value
                            const recurring =   dispatcherStorage.getStorage('recurring_val')
                            const memo = Swal.getPopup().querySelector('#edit_memo').value;
                            const booking_type =  Swal.getPopup().querySelector('#edit_booking_type').value
                            var week_day = [];

                            //const slotType = dispatcherStorage.getStorage('SlotType')
                           
                            $.each($("input:checkbox[name='week_day[]']:checked"), function () {
                                week_day.push($(this).val());
                            });
                          
                            if (start_time=='' && end_time=='' && blocktime=='' && memo=='' && booking_type=='') {
                                Swal.showValidationMessage(`All feilds are required!!`)
                                return false;
                             }
                             if(recurring == 'true'){
                                 console.log(week_day);
                                 if (!week_day.length>0) {
                                     Swal.showValidationMessage(`Please select days to recurring!!`)
                                     return false;
                                 }
                                 
                             }
                             
                             return  { start_time : start_time, end_time: end_time,week_day:week_day,blocktime:blocktime,recurring:recurring,booking_type:booking_type,memo:memo,edit_slot_id:edit_slot_id,edit_slot_date:edit_slot_date}
                        },onOpen: function() {
                            
                        }
                      }).then(async (result) => {
                        if(result.dismiss== undefined){
                            var formData = {
                                week_day:result.value.week_day,
                                blocktime:result.value.blocktime,
                                recurring:result.value.recurring,
                                booking_type:result.value.booking_type,
                                memo:result.value.memo,
                                start_time:result.value.start_time,
                                end_time:result.value.end_time,
                                slot_id:result.value.edit_slot_id,
                                edit_slot_date:result.value.edit_slot_date,
                                agent_id:agent_id
                            
                                
                            }
                         
                            await add_slot_time(formData,'edit')
                        }
                       
                      })
                  
                    if(ev.event.extendedProps.booking_type == 'new_booking'){
                        $('.view_booking').hide();
                        $('.view_orderDetails').show();
                        document.getElementById("viewOrder").href =ev.event.extendedProps.order_url ;
                        $('.swal2-actions').hide();
                    } 
                        // Delete Slot Form
                        /**storage */
                        // console.log(ev.event);
                        console.log(ev.event.extendedProps);
                        dispatcherStorage.setStorageSingle('slot_id',ev.event.extendedProps.type_id)
                        dispatcherStorage.setStorageSingle('edit_slot_id',ev.event.extendedProps.slot_id);
                        dispatcherStorage.setStorageSingle('edit_booking_type',ev.event.extendedProps.booking_type);
                        //dispatcherStorage.setStorageSingle('edit_slot_date',ev.event.extendedProps.type);
                        dispatcherStorage.setStorageSingle('edit_blocktime',ev.event.extendedProps.blocktime);
                        dispatcherStorage.setStorageSingle('edit_recurring',ev.event.extendedProps.recurring);
                        dispatcherStorage.setStorageSingle('edit_type_id',ev.event.extendedProps.type_id);
                        dispatcherStorage.setStorageSingle('edit_slot_type_old',ev.event.extendedProps.type);
                        if(ev.event.extendedProps.recurring == 1){
                            dispatcherStorage.setStorageSingle('recurring_val',1)
                            document.getElementById("recurring").checked = true;
                            $(".weekDays").fadeIn(1000);
                            $.each(ev.event.extendedProps.days, function(key,val) {
                                document.getElementById("day_"+val).checked = true;
                            });
                            document.getElementById("blocktime").disabled = false; 
                        }else
                        {
                            dispatcherStorage.setStorageSingle('recurring_val',0)
                            document.getElementById("blocktime").disabled = true; 
                            $(".forDate").fadeIn(1000);
                        }
                        initDatetimeRangePicker(ev.event.extendedProps.start_date,ev.event.extendedProps.end_date);
                        $('#edit_slot_date').flatpickr({
                            minDate: new Date(ev.event.startStr),
                            defaultDate:new Date(ev.event.startStr)
                        });
                      
                    /**storage */
                   // document.getElementById('slot_day_id').value = ev.event.extendedProps.type_id;
                     document.getElementById('edit_slot_id').value = ev.event.extendedProps.slot_id;
                   // document.getElementById('SlotType').value = ev.event.extendedProps.type;
                   // document.getElementById('SlotTypeOld').value = ev.event.extendedProps.type;
                     
                    document.getElementById('edit_start_time').value = ev.event.extendedProps.start_time;
                    document.getElementById('edit_end_time').value = ev.event.extendedProps.end_time;
                    document.getElementById('edit_memo').value = ev.event.extendedProps.memo;
                    
    
                }
            });
            setTimeout(async ()=>{
                await calendar.render();
                spinnerJS.hideSpinner()
            },200)
           
          
        }

    }
    async function add_slot_time(formData,action='add'){
        var evttype= dispatcherStorage.getStorage('eventType');
        var actionUrl = (action == 'add') ? 'add_slot' : 'update_slot';
        axios.post(`agent/${actionUrl}`, formData)
        .then(async response => {
         console.log(response);
            if(response.data.status == "Success"){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.data.message,
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
                fullCalendarInt(agent_id,evttype)
             
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: response.data.message,
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
            }
        })
        .catch(e => {
            console.log(e);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later!',
            })
        })    
    } 

    async function deleteSlot(formData){
       
        axios.post(`agent/slot/delete`, formData)
        .then(async response => {
         console.log(response.data.status);
            if(response.data.status == "Success"){
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.data.message,
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
                fullCalendarInt(agent_id)
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: response.data.message,
                    //footer: '<a href="">Why do I have this issue?</a>'
                })
            }
        })
        .catch(e => {
            console.log(e);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later!',
            })
        })    
    } 
  

   function  initDatetimeRangePicker(start_date="",end_date=""){
     //$('#blocktime').val(start_date+'-'+end_date)
        $(function() {
            $('#blocktime').daterangepicker({
              //timePicker: true,
              startDate: (start_date!='') ?  moment(start_date).startOf('hour') : moment().startOf('hour'),
              endDate: (end_date!='') ? moment(end_date).startOf('hour') : moment().startOf('hour'),
              minDate:(start_date!='') ? moment(start_date).startOf('hour') :new Date() ,
              locale: {
                format: 'M/DD/YY'
              }
            });
        });
    }
  
})
