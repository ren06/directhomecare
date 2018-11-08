<?php
//used in changeBookingCard (wzd3)
?>
<div id="cardsMain">
    <?php
    $this->renderPartial('/creditCard/_creditCards', array('creditCards' => $creditCards, 'client' => $client,
        'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton))
    ?>
</div>

<br />
<?php
$currentAction = Yii::app()->controller->action->id;

if ($currentAction != 'changeBookingCard') {
    echo CHtml::button(Yii::t('texts', 'BUTTON_ADD_ANOTHER_CARD'), array('class' => 'rc-button-white', 'id' => 'add_new_card', 'onClick' => 'js:newCardMode()'));
    echo '<span id="add_new_card_disabled" class="rc-linkbutton-white-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_ADD_ANOTHER_CARD') . '</span>';
}
?>
<?php include Yii::app()->basePath . '/views/creditCard/_cardLogo.php'; ?>
<script type="text/javascript">

    $(".save-button").click(function() {

        var index = this.id.substr(this.id.indexOf('_') + 1);

        $.ajax({
            success: function(html) {

                if (html.indexOf("{") == 0) {

                    var json = $.parseJSON(html);

                    $.each(json, function(key, value) {

                        $("#" + key).addClass("clsError");
                        $("#" + key + "_" + index).show().html(value.toString());


                        $("label[for=CreditCard_" + key + "]").addClass("clsError");
                    });
                }
                else {

//                    if (index == 'new') {
//                        $('.rc-container-main').html(html); //use cardsMain ??
//                    }
//                    else {
//
//                        $('#cardDetails_' + index).html(html);
//
//                        displayMode(index);
//                    }

                    window.location.href = '<?php $this->createUrl('clientManageBookings/changeBookingCard', array('bookingId' => $bookingId)); ?>';
                }
            },
            type: 'post',
            url: '<?php echo CHtml::normalizeUrl(array('clientMaintain/saveCard')) ?>',
            data: {
                CreditCard: decodeURIComponent($('#card-form-' + index).serialize()),
                BillingAddress: decodeURIComponent($('#billing-address-form-' + index).serialize()),
                index: index
            },
            cache: false,
            dataType: 'html'
        });

    });


    $(".edit-button").click(function() {

        var index = this.id.substr(this.id.indexOf('_') + 1);

        editMode(index);

    });

    $(".cancel-button").click(function() {

        var index = this.id.substr(this.id.indexOf('_') + 1);

        $.ajax({
            success: function(html) {

                if (index == 'new') {

                    // $('#creditCard_new').hide();

                    //$('.rc-container-main').html(html);
                }
                else {
                    //$('#cardDetails_' + index).html(html);

                    //displayMode(index);
                }

                window.location.href = '<?php $this->createUrl('clientManageBookings/changeBookingCard', array('bookingId' => $bookingId)); ?>';

            },
            type: 'get',
            url: '<?php echo CHtml::normalizeUrl(array('clientMaintain/cancelEditCard')) ?>',
            data: {
                index: index
            },
            cache: false,
            dataType: 'html'
        });
    });

    $(".remove-button").click(function() {

        var index = this.id.substr(this.id.indexOf('_') + 1);

        if (confirm('<?php echo Yii::t('texts', 'NOTE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_CARD'); ?>')) {
            $.ajax({
                success: function(html) {

                    if (html != "") {

                        $('#errorMessage').html(html);
                    }
                    else {
                        $("#creditCard_" + index).remove();

                    }
                },
                type: 'post',
                url: '<?php echo CHtml::normalizeUrl(array('clientMaintain/deleteCard')); ?>',
                data: {
                    index: index
                },
                cache: false,
                dataType: 'html'
            });

        }
    });


    function newCardMode() {

        //hide all buttons of other cards and show disabled span
        $('#creditCards :button').hide();
        $('.rc-linkbutton-small-disabled').show();

        //disable "add new card" button
        $("#add_new_card").hide();
        $("#add_new_card_disabled").show();

        //show content
        //show new card module
        $("#creditCard_newContainer").show();
        //edit mode: show form
        $("#cardDetails_new").show();

        //handle buttons of module
        $('#save_new').show();
        $('#cancel_new').show();

        $('#remove_new').hide();
        $('#removeDisabled_new').hide();
        $('#edit_new').hide();
        $('#editDisabled_new').hide();

    }

    //used when cancel, cancelNew and Save are used
    function displayMode(index) {

        //show all buttons and hide disabled span
        $('#creditCards :button').show();
        $('.rc-linkbutton-small-disabled').hide();

        $('.cancel-button').hide();
        $('.save-button').hide();

        //show add new card button
        $("#add_new_card").show();
        $("#add_new_card_disabled").hide();

        //hide card details
        $("#cardDetails_" + index).hide();

        $("#creditCard_newContainer").hide();

    }

    //used when save and saveEdit are used
    function editMode(index) {
        //hide all buttons and show disabled span
        $('#creditCards :button').hide();
        $('.rc-linkbutton-small-disabled').show();

        //except for current edit: hidden
        $('#editDisabled_' + index).hide();
        $('#removeDisabled_' + index).hide();

        $('#save_' + index).show();
        $('#cancel_' + index).show();

        //hide add new card button
        $("#add_new_card").hide();
        $("#add_new_card_disabled").show();


        //show card details
        $("#cardDetails_" + index).show();
    }

    $(document).on("click", ".radio-button-address", function() {

        $(this).closest('div').find("input:radio:checked").removeAttr("checked");


        //select clicked one
        $(this).attr('checked', true);


        var length = 'radio_button_billing_address_other_'.length;
        var index = this.id.substr(length + 1);

        //if clicked one is other show new form, otherwise hide it        
        if (this.id.indexOf('radio_button_billing_address_other_') == 0) {

            $('#new_location_' + index).show('blind');
        }
        else {
            //var index = this.id.substr(this.id.indexOf('-') + 1);
            $('#new_location_' + index).hide('blind');
        }

    });

</script>