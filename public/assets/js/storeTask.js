/**
 * Reset form Data
 */
 $(document).ready(function() {
    $('#add-agent-modal').on('hidden.bs.modal', function(e) {
        $(this).find('#submitAgent')[0].reset();
    });
    $('#add-customer-modal').on('hidden.bs.modal', function(e) {
        $(this).find('#submitCustomer')[0].reset();
    });
});




/**
 * Submit Customers Detail
 */

$("#taskFormHeader").submit(function(e) {
    e.preventDefault();
  //  alert();
    var formData = new FormData(this);
    AjaxSubmit(formData, 'POST', '/tasks', '#task-modal-header');
});



/**
 * Call Ajax Method
 */

function AjaxSubmit(data, method, url, modals) {
  //  alert(data);
    // $.ajax({
    //     method: method,
    //     headers: {
    //         Accept: "application/json"
    //     },
    //     url: url,
    //     data: data,
    //     contentType: false,
    //     processData: false,
    //     success: function(response) {
    //         if (response.status == 'success') {
    //                 $("#add-agent-modal .close").click();
    //                      location.reload(); 
                
    //             // $(".alert-success").removeClass('d-none');
    //             // $(".alert-success").text(response.message);
    //             // setTimeout(function() {
    //             //     $(".alert-success").addClass('d-none');
    //             // }, 5000);

    //         } else {
    //             $(".show_all_error.invalid-feedback").show();
    //             $(".show_all_error.invalid-feedback").text(response.message);
    //         }
    //         return response;
    //     },
    //     error: function(response) {
    //         if (response.status === 422) {
    //             let errors = response.responseJSON.errors;
    //             Object.keys(errors).forEach(function(key) {
    //                 $("#" + key + "Input input").addClass("is-invalid");
    //                 $("#" + key + "Input span.invalid-feedback").children(
    //                     "strong").text(errors[key][
    //                     0
    //                 ]);
    //                 $("#" + key + "Input span.invalid-feedback").show();
    //             });
    //         } else {
    //             $(".show_all_error.invalid-feedback").show();
    //             $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
    //         }
    //         return response;
    //     }
    // });
}

