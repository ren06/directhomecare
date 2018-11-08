<?php
for ($i = 0; $i < count($creditCards); $i++):
    $j = $i + 1;
    $card = $creditCards[$i];
    $radioButtonName = "radio_button_credit_card";
    $radioValue = $card->id;
    $radioId = 'radio' . $card->id;

    echo '<p>';
    echo CHtml::radioButton($radioButtonName, ($selectedCreditCardRadioButton == $radioValue), array('id' => $radioId, 'value' => $radioValue, 'class' => 'radio-button radio-button-card'));
    echo CHtml::label($card->displayShort(), $radioId);
    echo '</p>';

endfor;

$radioOtherValue = UIConstants::RADIO_BUTTON_CREDIT_CARD_OTHER;
echo '<p>';
echo CHtml::radioButton($radioButtonName, ($selectedCreditCardRadioButton == $radioOtherValue), array('value' => $radioOtherValue, 'class' => 'radio-button radio-button-card', 'id' => $radioOtherValue));
echo CHtml::label(Yii::t('texts', 'LABEL_ENTER_A_NEW_CARD'), $radioOtherValue);
echo '</p>';
?>
<!-- Display new card form -->
<?php
if ($selectedCreditCardRadioButton == $radioOtherValue) {
    $style = 'display:block';
} else {
    $style = 'display:none';
}
?>
<div id="new_credit_card" style="<?php echo $style ?>">
    <?php
    $this->renderPartial('/creditCard/_newCreditCard', array('form' => $form, 'creditCard' => $creditCard, 'client' => $client, 'index' => 'new'));
    ?>
</div>

<script type="text/javascript">
    $(".radio-button-card").click(function() {

        //uncheck all existing cards
        $('.radio-button-card').attr('checked', false);

        //check selected card
        $(this).attr('checked', true);

        //if selected card is create new one
        if ($(this).attr('id') == '<?php echo UIConstants::RADIO_BUTTON_CREDIT_CARD_OTHER ?>') {
            if ($('#new_credit_card').is(":hidden")) {
                $('#new_credit_card').show('blind');
            }
        }
        else {

            //user selected an existing card

            //hide new credit card section
            if ($('#new_credit_card').is(":visible")) {
                $('#new_credit_card').hide('blind');
                $('#new_location').hide('blind'); //defined in _newCreditCard
            }
            //select the first address, in case the user presses back the other credit card
            $('.radio-button-address').attr('checked', false);
            $('.radio-button-address').first().attr('checked', true);
        }

    });

</script>