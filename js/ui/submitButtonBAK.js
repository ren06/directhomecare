
$(document).ready(function() {
//    $("form").submit(function(event) {
//
//        var form = event.currentTarget;
//        // Disable the button, turn the background gray, and set the text to "Working..."
//        $("#" + form.id).find("input[type=submit]").closest('div').hide();
//        $("#" + form.id).find("input[type=submit]").closest(".container_button").find("#loading").show();
//
//
//        // Submit
//        form.submit();
//
//        // Re-enable the button
//        // $("#" + form.id).find("input[type=submit]").attr("disabled", false);
//
//        return true;
//    });

    $("input[type=submit]").click(function() {

        $(this).closest('.rc-container-button').find('.buttons').hide();
        $(this).closest('.rc-container-button').find('.loading').show();

        //special case for payment button
        if (this.id === 'payButton') {
            $("#loadingText").show();
        }
    });
})
