<div id="radioButtons_<?php echo $index?>">
    <b><?php echo Yii::t('texts', 'LABEL_SELECT_THE_ASSOCIATED_ADDRESS'); ?></b>
    <br />
    <?php
    $serviceLocations = $client->clientLocations;

    if ($selectedBillingAddressRadioButton == '') {
        $firstLocationId = $serviceLocations[0]->id;
        
        $selectedBillingAddressRadioButton = UIConstants::RADIO_BUTTON_BILLING_ADDRESS .  $firstLocationId . '-' . $index;
    }
    else{
        //$selectedBillingAddressRadioButton .= '-' . $index;
        
    }

    for ($i = 0; $i < count($serviceLocations); $i++) {

        $j = $i + 1;

        $serviceLocation = $serviceLocations[$i];
        $radioButtonName = UIConstants::RADIO_BUTTON_BILLING_ADDRESS . $serviceLocation->id  . '-' . $index;

        echo '<p>';
        echo CHtml::radioButton($radioButtonName, ($radioButtonName == $selectedBillingAddressRadioButton), array('class' => 'radio-button radio-button-address'));
        echo '&#160;&#160;';
        echo $serviceLocation->display('&#32;&#160;&#45;&#160;&#32;');
        echo '</p>';
    }

    $radioButtonName = UIConstants::RADIO_BUTTON_BILLING_ADDRESS_OTHER . '-' . $index;
    echo '<p>';
    echo CHtml::radioButton($radioButtonName, ($radioButtonName == $selectedBillingAddressRadioButton), array('class' => 'radio-button radio-button-address', 'id' => $radioButtonName));
    echo '&#160;&#160;';
    echo Yii::t('texts', 'LABEL_ENTER_A_NEW_ADDRESS');
    echo '</p>';
    ?>
</div>
<div id="new_location_<?php echo $index?>" style="<?php echo ($selectedBillingAddressRadioButton == UIConstants::RADIO_BUTTON_BILLING_ADDRESS_OTHER . '-' . $index ? 'display:visible' : 'display:none'); ?>">

    <table>
        <?php $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field'); ?>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "address_line_1"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($billingAddress, "address_line_1", $htmlOptions); ?></td>
            <td class="rc-cell3"><?php echo $form->error($billingAddress, "address_line_1", array('class' => 'rc-error', 'id' => 'error_address_line_1_' . $index)); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "address_line_2"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($billingAddress, "address_line_2", $htmlOptions); ?></td>
            <td class="rc-cell3"><?php echo $form->error($billingAddress, "address_line_2", array('class' => 'rc-error', 'id' => 'error_address_line_2_' . $index)); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "city"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($billingAddress, "city", array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
            <td class="rc-cell3"><?php echo $form->error($billingAddress, "city", array('class' => 'rc-error', 'id' => 'error_city_' . $index)); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "post_code"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($billingAddress, "post_code", array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
            <td class="rc-cell3"><?php echo $form->error($billingAddress, "post_code", array('class' => 'rc-error', 'id' => 'error_post_code_' . $index)); ?></td>
        </tr>
    </table>
</div>
