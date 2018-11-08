
    $(document).on("click", ".radio-button-address", function() {
     
        $(this).closest('div').find("input:radio:checked").removeAttr("checked");

        //select clicked one
        $(this).attr('checked', true);

        var length = 'radio_button_billing_address_other_'.length;
        var index = this.id.substr(length + 1);

        //if clicked one is other show new form, otherwise hide it        
        if (this.id.indexOf('radio_button_billing_address_other_') == 0) { //found

            //e.g.. radio_button_billing_address_301-0
            
            if ($('#new_location_' + index).is(":hidden")) {
                $('#new_location_' + index).show('blind');
            }
        }
        else {
            //e.g. radio_button_billing_address_other_-0
            var ind = this.id.indexOf('-')
            var index = this.id.substr(ind + 1);
            
            if ($('#new_location_' + index).is(":visible")) {
                $('#new_location_' + index).hide('blind');
            }
        }
    });
