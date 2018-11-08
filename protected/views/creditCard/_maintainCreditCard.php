<?php
$currentController = $this->id; //lazy way to handle scenarios

if ($index !== 'new') {
    $creditCard->setScenario(CreditCard::VALIDATION_SCENARIO_EDIT);
}
?>

<div id="creditCard_<?php echo $index ?>">
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php
            $currentAction = Yii::app()->controller->action->id;
            $selectedCreditCardId = Session::getSelectedCreditCard();

            //if maintainDetails there is no radio button
            if (Yii::app()->controller->id != 'clientMaintain') {

                if (($currentAction == 'changeBookingCard' || $currentAction == 'saveCard') && $index !== 'new' && isset($selectedCreditCardId)) {
                    //select service if only one or the one just created

                    if ($selectedCreditCardId == $creditCard->id) {
                        $checked = 1;
                    } else {
                        $checked = 0;
                    }

                    echo CHtml::radioButton('radio_button_card', $checked, array('value' => $creditCard->id, 'id' => 'radio_button' . $index, 'class' => 'radio_button_card'));
                } else {
                    $tets = '';
                }
            }
            ?>
            <?php
            if ($index !== 'new') {
                echo $creditCard->displayShort();
                // echo '&#160;&#160;&#45;&#160;&#160;';
                // echo $creditCard->address->display(' ');
            } else {
                echo Yii::t('texts', 'HEADER_CARD_DETAILS');
            }
            ?>
        </div>
        <div class="rc-module-container-button">
            <?php
            //if card used by booking not possible
            echo CHtml::button(Yii::t('texts', 'BUTTON_EDIT'), array('class' => 'rc-button-small edit-button', 'id' => 'edit_' . $index));
            echo '<span id="editDisabled_' . $index . '" class="rc-linkbutton-small-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_EDIT') . '</span>';

            if (!$creditCard->isUsedBooking()) { //used for legacy users
                echo CHtml::button(Yii::t('texts', 'BUTTON_DELETE_CARD'), array('class' => 'rc-button-small rc-button-small-space-left remove-button', 'id' => 'remove_' . $index));
                echo '<span id="removeDisabled_' . $index . '" class="rc-linkbutton-small-disabled rc-button-small-space-left" style="display:none">' . Yii::t('texts', 'BUTTON_DELETE_CARD') . '</span>';
            } else {
                echo '<span id="used_' . $index . '" title="' . Yii::t('texts', 'ALT_USED_IN_ANOTHER_BOOKING_CANNOT_DELETE') . '" class="rc-linkbutton-small-disabled rc-button-small-space-left">' . Yii::t('texts', 'BUTTON_DELETE_CARD') . '</span>';
            }

            echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'rc-button-small save-button', 'id' => 'save_' . $index, 'style' => 'display:none'));
            echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small rc-button-small-space-left cancel-button', 'id' => 'cancel_' . $index, 'style' => 'display:none'));
            ?>
        </div>
    </div>
    <div id="cardDetails_<?php echo $index ?>" style="display:none" class="rc-module-inside cardDetails">
        <?php
        $this->renderPartial('/creditCard/_creditCardDetailsAndBillingAddress', array('index' => $index, 'client' => $client, 'creditCard' => $creditCard, 'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));
        ?>
    </div>
    <div class="rc-module-bar-bottom" style="display:none" id="bottom_bar_<?php echo $index ?>">
        <div class="rc-module-container-button">
            <?php
            echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'rc-button-small  rc-button-small-space-left', 'id' => 'save_bottom' . $index));
            if ($index !== 'new') {

                echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small', 'id' => 'cancelNew_bottom'));
            } else {

                echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small', 'id' => 'cancel_bottom' . $index));
            }
            ?>

        </div>
    </div>
</div>

<?php
//only display this bit when payment and manage booking
if ($currentController != 'clientMaintain' && $currentController != 'clientManageBookings') {
    ?>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'HEADER_CARD_DETAILS'); ?>
        </div>
    </div>
    <div class="rc-module-inside" id="credit_card" style="display:visible">
    </div>
    <?php
}
?>