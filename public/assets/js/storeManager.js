/**
 * Reset form Data
 */
$(document).ready(function () {
    $("#add-manager-modal").on("hidden.bs.modal", function (e) {
        $(this).find("#submitManager")[0].reset();
    });
});

/**
 * Submit Managers Detail
 */

$("#submitManager").submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    AjaxSubmit(formData, "POST", "/manager", "#add-manager-modal");
});

/**
 * Call Ajax Method
 */

function AjaxSubmit(data, method, url, modals) {
    
    $.ajax({
        method: method,
        headers: {
            Accept: "application/json",
        },
        url: url,
        data: data,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.status == "success") {
                $("#add-manager-modal .close").click();
                     location.reload(); 
            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text(response.message);
            }
            return response;
        },
        error: function (response) {
            if (response.status === 422) {
                let errors = response.responseJSON.errors;
                Object.keys(errors).forEach(function (key) {
                    $("#" + key + "Input input").addClass("is-invalid");
                    $("#" + key + "Input span.invalid-feedback")
                        .children("strong")
                        .text(errors[key][0]);
                    $("#" + key + "Input span.invalid-feedback").show();
                });
            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text(
                    "Something went wrong, Please try Again."
                );
            }
            return response;
        },
    });
}
