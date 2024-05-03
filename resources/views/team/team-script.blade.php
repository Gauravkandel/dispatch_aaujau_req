<script type="text/javascript">

    $('.openModal').click(function(){
        $('#add-team-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        makeTag();
    });

   
    var tagList = "{{$showTag}}";

    tagList = tagList.split(',');

    function makeTag(){
        $('.myTag1').tagsInput({
            'autocomplete': {
                source: tagList
            } 
        });
    }

    $(document).on('click', ".team-list-1", function() {
        var data_id = $(this).attr('data-id');
        $(".team-details").hide();
        $("#team_detail_" + data_id).show();

        $(".team-agent-list").hide();
        $("#team_agents_" + data_id).show();
    });


    $(".tag1").click(function() {
        var val = $(this).text();

        var selectElement = $('#teamtag').eq(0);
        var selectize = selectElement.data('selectize');
        selectize.additem(1, 2);
    });

    $('.delete-team-form').on('submit', function() {
        team_agent_count = $(this).attr('data-team-agent-count');
        if (team_agent_count > 0) {
            alert("Please assign other team to {{getAgentNomenclature()}}s linked to this team before deleting");
            return false;
        }
        delete_team_confirmation = confirm("Do you want to delete the team?");
        if (delete_team_confirmation === true) {
            return true;
        }
        return false;
    });

    $(".editIcon").click(function (e) {  
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
       
        var uid = $(this).attr('teamId');

        $.ajax({
            type: "get",
            url: "<?php echo url('team'); ?>" + '/' + uid + '/edit',
            data: '',
            dataType: 'json',
            success: function (data) {

                //$('.page-title1').html('Hello');
                console.log('data');

                $('#edit-team-modal #editCardBox').html(data.html);
                $('#edit-team-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();

            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    /* add Team using ajax*/
    $("#add-team-modal #submitTeam").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.addTeamForm', function() { 
        var form =  document.getElementById('submitTeam');
        var formData = new FormData(form);
        var urls = "{{URL::route('team.store')}}";
        saveTeam(urls, formData, inp = '', modal = 'add-team-modal');
    });

    /* edit Team using ajax*/
    $("#edit-team-modal #UpdateTeam").submit(function(e) {
            e.preventDefault();
    });

    $(document).on('click', '.submitEditForm', function() {
        var form =  document.getElementById('UpdateTeam');
        var formData = new FormData(form);
        var urls =  document.getElementById('team_id').getAttribute('url');
        saveTeam(urls, formData, inp = 'Edit', modal = 'edit-team-modal');
    });

    function saveTeam(urls, formData, inp = '', modal = ''){

        $.ajax({
            method: 'post',
            headers: {
                Accept: "application/json"
            },
            url: urls,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                        $("#" + modal + " .close").click();
                        location.reload(); 
                } else { console.log('wa-1');
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) { console.log('err1');
                if (response.status === 422) { console.log('err2');
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        $("#" + key + "Input" + inp + " span.invalid-feedback").children(
                            "strong").text(errors[key][
                            0
                        ]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });

    }

    $('.mdi-delete').click(function() {
        var r = confirm("{{__('Are you sure?')}}");
        if (r == true) {
            var teamid = $(this).data('teamid');
            $('form#teamdelete' + teamid).submit();
        }
    });

</script>