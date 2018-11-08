
<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'client_details-form',
            'enableAjaxValidation' => false,
            'focus' => array($client, 'emailAddress'),
            'stateful' => true,
        ));
?>

<h2>Client registration</h2>
<h3>What are your contact details?</h3>


<p class="rc-note">Fields with <span class="required">*</span> are required.</p>
<table>
    <?php echo CHtml::link('Populate Data', array('test/populate')); //TO DO to remove ?>        

    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'email_address'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($client, 'email_address', array('maxlength' => 50, 'class' => 'rc-field')); //'on' => 'insert',  ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'email_address', array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'password'); ?></td>
        <td class="rc-cell2"><?php echo $form->passwordField($client, 'password', array('maxlength' => 50, 'class' => 'rc-field', 'autocomplete' => 'off')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'password', array('class' => 'rc-error')); ?></td>

    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'first_name'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($client, 'first_name', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'first_name', array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'last_name'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($client, 'last_name', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'last_name', array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <?php
        $countries = Countries::getCountries();
        $nationalities = Nationalities::getNationalities();
        ?>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'country_birth'); ?></td>
        <td class="rc-cell2"><?php echo $form->dropDownList($client, 'country_birth', $countries, array('class' => 'rc-drop-long')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'country_birth', array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'date_birth'); ?></td>
        <td class="rc-cell2"><?php $this->widget('application.components.drop_down_date_picker.DropDownDatePicker', array('myLocale' => 'en_gb', 'date' => $client->date_birth, 'cssClass' => 'rc-drop')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'date_birth', array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'nationality'); ?></td>
        <td class="rc-cell2"><?php echo $form->dropDownList($client, 'nationality', $nationalities, array('class' => 'rc-drop-long')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'nationality', array('class' => 'rc-error')); ?></td>
    </tr>    

    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($address, 'address_line_1'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($address, 'address_line_1', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($address, 'address_line_1', array('class' => 'rc-error')); ?></td>
    </tr>   
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($address, 'address_line_2'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($address, 'address_line_2', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($address, 'address_line_2', array('class' => 'rc-error')); ?></td>
    </tr>       
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($address, 'city'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($address, 'city', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($address, 'city', array('class' => 'rc-error')); ?></td>
    </tr>  
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($address, 'post_code'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($address, 'post_code', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($address, 'post_code', array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'mobile_phone'); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($client, 'mobile_phone', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'mobile_phone', array('class' => 'rc-error')); ?></td>
    </tr>
    <tr> 
        <td class="rc-cell1"><?php echo $form->labelEx($client, 'gender'); ?></td>
        <td class="rc-cell2"><?php echo $form->radioButtonList($client, 'gender', array(1 => 'Female', 2 => 'Male'), array('separator' => Yii::app()->params['radioButtonSeparator'])); ?></td>
        <td class="rc-cell3"><?php echo $form->error($client, 'gender', array('class' => 'rc-error')); ?></td>
    </tr>
</table>
<div class="rc-container-button">

    <?php
    echo CHtml::submitButton('Next >>', array('class' => 'rc-button', 'submit' => array('client/registration', 'navigation' => 'next')));
    ?>

</div>
<?php $this->endWidget(); ?>
    