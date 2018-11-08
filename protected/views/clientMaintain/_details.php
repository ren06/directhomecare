<table>
    <tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($client, 'email_address'); ?></td>
        <td class="rc-cell2"><?php echo CHtml::activeTextField($client, 'email_address', array('maxlength' => 60, 'class' => 'rc-fiel toggable')); ?></td>
        <td class="rc-cell3"><div id="Client_email_address_em_" class="rc-error" style="display:none"></div></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($client, 'first_name'); ?></td>
        <td class="rc-cell2"><?php echo CHtml::activeTextField($client, 'first_name', array('maxlength' => 50, 'class' => 'rc-field toggable')); ?></td>
        <td class="rc-cell3"><div id="Client_first_name_em_" class="rc-error" style="display:none"></div></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($client, 'last_name'); ?></td>
        <td class="rc-cell2"><?php echo CHtml::activeTextField($client, 'last_name', array('maxlength' => 50, 'class' => 'rc-field toggable')); ?></td>
        <td class="rc-cell3"><div id="Client_last_name_em_" class="rc-error" style="display:none"></div></td>
    </tr>
    <!--<tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($client, 'date_birth'); ?></td>
        <td class="rc-cell2"><?php $this->widget('DropDownDatePickerWidget', array('myLocale' => 'en_gb', 'date' => $client->date_birth, 'hideDay' => true, 'hideMonth' => true, 'htmlOptions' => array('class' => 'rc-drop'))); ?></td>
        <td class="rc-cell3"><div id="Client_date_birth_em_" class="rc-error" style="display:none"></div></td>
    </tr>-->
    <tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($client, 'mobile_phone'); ?></td>
        <td class="rc-cell2"><?php echo CHtml::activeTextField($client, 'mobile_phone', array('maxlength' => 50, 'class' => 'rc-field toggable')); ?></td>
        <td class="rc-cell3"><div id="Client_mobile_phone_em_" class="rc-error" style="display:none"></div></td>
    </tr>
</table>