<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
//Used in wizard 3 as the main page
?>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CHANGE_CARD_DETAILS') ?>

<div class="rc-container-40">
    <h2 class="rc-h2red"><?php echo $this->pageTitle; ?></h2>
    <p class="rc-note">
        <?php echo Yii::t('texts', 'NOTE_CHANGES_WILL_AFFECT_FUTURE_PAYMENTS'); ?>
    </p>

    <?php echo CHtml::beginForm('', '', array('id' => 'cardForm')) ?>
    <?php echo CHtml::hiddenField('bookingId', $booking->id); ?>

    <?php
    $this->renderPartial('/creditCard/_changeBookingCard', array('client' => $client, 'creditCards' => $creditCards, 'newCreditCard' => $newCreditCard,
        'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton, 'bookingId' => $booking->id));
    ?>

    <?php echo CHtml::endForm() ?>
</div>
<div class="rc-container-button">
    <?php
    $target = CHtml::normalizeUrl(array('clientManageBookings/selectBookingCreditCard'));
    $return = CHtml::normalizeUrl(array('clientManageBookings/myBookings'));
    echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_SELECT'), $target, array(
        'type' => 'POST',
        'success' => "function(){                                          
                            //$('body').load('$return'); //only change the html
                            window.location.href = '$return';
                      }",
        'data' => 'js:$("#cardForm").serialize()',
            ), array(//htmlOptions
        'href' => $target,
        'class' => 'rc-linkbutton'
            )
    );
    ?>
    <?php echo CHtml::link(Yii::t('texts', 'BUTTON_CANCEL'), array('clientManageBookings/myBookings'), array('class' => 'rc-linkbutton')); ?>
</div>
<script type="text/javascript">

//    function get_radio_value()
//    {
//        var selected = $('#creditCards').find('.radio_button_card').is(":checked");
//
//
//        selectedValue = selected.val();
//        return selectedValue;
//    }

    $(".radio_button_card").click(function() {

        $('#creditCards').find('.radio_button_card').removeAttr("checked");
        //select clicked one
        $(this).attr('checked', true);

    });
</script>
