/**
 * Submit Client INFO
 */

$("#submitTeam").submit(function (e) {
    e.preventDefault();
    let formData = $(this).serializeArray();
    $.ajax({
        method: "POST",
        headers: {
            Accept: "application/json",
        },
        url: "/team",
        data: formData,
        success: function (response) {
            if (response.status == "success") {
                $("#add-team-modal .close").click();
                     location.reload(); 
            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text(response.message);
            }
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
        },
    });
});
