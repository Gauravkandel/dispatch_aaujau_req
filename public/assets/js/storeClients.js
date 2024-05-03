/**
 * Submit Client INFO
 */

$("#submitForm").submit(function(e) {
    e.preventDefault();
    let formData = $(this).serializeArray();
    //startLoader('body');
    spinnerJS.showSpinner();
    $.ajax({
        method: "POST",
        headers: {
            Accept: "application/json"
        },
        url: "/submit_client",
        data: formData,
        success: function(response) {
            spinnerJS.hideSpinner();
            if (response.status == 'success') {

            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text(response.message);
            }
        },
        error: function(response) {
            spinnerJS.hideSpinner();
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                Object.keys(errors).forEach(function(key) {
                    $("#" + key + "Input input").addClass("is-invalid");
                    $("#" + key + "Input span.invalid-feedback").children(
                        "strong").text(errors[key][
                        0
                    ]);
                    $("#" + key + "Input span.invalid-feedback").show();
                });
            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
            }
        }
    });
});