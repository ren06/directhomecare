<?php
//used in:
// _creditCardDetailsAndBillingAddress
// _maintainCreditCard
// _newCreditCard

$extraOptions = array();
if ($creditCard->getScenario() == CreditCard::VALIDATION_SCENARIO_EDIT) {

    $extraOptions['disabled'] = 'disabeld';
    $class = 'rc-field-disabled';
    $classSmall = 'rc-field-small-disabled';
    $classDrop = 'rc-drop-long-disabled';
} else {
    $class = 'rc-field';
    $classSmall = 'rc-field-small';
    $classDrop = 'rc-drop-long';
}
echo $form->error($creditCard, 'error_message', array('class' => 'rc-error'));
?>

<!--
<?php // echo $form->dropDownList($creditCard, 'card_type', $creditCard->getCardTypeOptions(), CMap::mergeArray(array('class' => $classDrop), $extraOptions)); ?>
<?php // echo $form->error($creditCard, 'card_type', array('class' => 'rc-error', 'id' => 'error_card_type_' . $index)); ?>
-->
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php echo $form->textField($creditCard, 'card_number', CMap::mergeArray(CMap::mergeArray(array('class' => $class), $extraOptions), array('maxlength' => 16, 'placeholder' => 'Card long number (16 digits)'))); ?>
        <?php echo $form->error($creditCard, 'card_number', array('class' => 'rc-error', 'id' => 'error_card_number_' . $index)); ?>
    </div>
    <div class="large-12 medium-12 small-12 columns">
        <div class="row">
            <div class="large-6 medium-6 small-6 columns">
                <?php echo $form->textField($creditCard, 'last_three_digits', CMap::mergeArray(CMap::mergeArray(array('class' => $classSmall), $extraOptions), array('maxlength' => 3, 'placeholder' => 'Last 3 digits on back of the card'))); ?>
                <?php echo $form->error($creditCard, 'last_three_digits', array('class' => 'rc-error', 'id' => 'error_last_three_digits_' . $index)); ?>
            </div>
            <div class="large-6 medium-6 small-6 columns">
                <img alt="CV2" src="<?php echo Yii::app()->request->baseUrl . '/images/card-cv2.gif'; ?>" heigt="44" width="65" />
            </div>
        </div>
    </div>
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->widget('DropDownDatePickerWidget', array('hideDay' => true, 'scenario' => 'creditCard', 'myLocale' => 'en_gb', 'date' => $creditCard->expiry_date)); ?>
        <?php echo $form->error($creditCard, 'expiry_date', array('class' => 'rc-error', 'id' => 'error_expiry_date_' . $index)); ?>
    </div>
</div>
<!--
<?php // echo $form->textField($creditCard, 'name_on_card', CMap::mergeArray(array('class' => $class), $extraOptions));  ?>
<?php // echo $form->error($creditCard, 'name_on_card', array('class' => 'rc-error', 'id' => 'error_name_on_card_' . $index));  ?>
-->