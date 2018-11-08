

<h2>Make a booking on behalf of <?php echo $client->fullName . " (" . $client->id . ")" ?></h2>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif ?>

<?php
$serviceUsersOptions = array();
foreach ($client->serviceUsers as $serviceUser) {
    $serviceUsersOptions[$serviceUser->id] = strip_tags($serviceUser->fullName . ' (' . $serviceUser->id . ')');
}

$addressOptions = array();
foreach ($client->clientLocations as $clientLocation) {
    $addressOptions[$clientLocation->id] = strip_tags($clientLocation->display(' '));
}

$creditCardOptions = array();
foreach ($client->creditCards as $creditCard) {
    $creditCardOptions[$creditCard->id] = strip_tags($creditCard->displayShort());
}

$carerOptions = array();

$carers = Carer::model()->findAllBySql("SELECT * FROM tbl_carer where id_address is not null");

foreach ($carers as $carer) {
    $carerOptions[$carer->id] = strip_tags($carer->fullName . ' (' . $carer->id . ') - ' . $carer->address->post_code . ' ' . $carer->address->city);
}
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'carer-test-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php
    foreach ($model->hourlyQuoteSimpleForm->dayForms as $i => $dayForm) {
        ?>

        <div class="row">
            <?php echo $form->labelEx($model, "[$i]dayForm"); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => "HourlyQuoteDayForm[$i][date]",
                'value' => $dayForm->date,
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1', //Monday
                    'minDate' => '',
                    'maxDate' => '+730',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                )
                    ,));
            ?>
            <?php echo $form->error($dayForm, "[$i]date"); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($dayForm, 'startTime'); ?>
            <?php
            echo CHtml::activeDropDownList($dayForm, "[$i]startHour", Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop', 'id' => 'start_hour_one_day'));
            //echo Yii::t('texts', 'HOUR_SEPARATOR');
            echo CHtml::activeDropDownList($dayForm, "[$i]startMinute", Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'start_minute_one_day'));
            ?>
            <?php echo $form->error($dayForm, "[$i]startTime"); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'endTime'); ?>
            <?php
            echo CHtml::activeDropDownList($dayForm, "[$i]endHour", Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop', 'id' => 'start_hour_one_day'));
            //echo Yii::t('texts', 'HOUR_SEPARATOR');
            echo CHtml::activeDropDownList($dayForm, "[$i]endMinute", Calendar::getDropDownMinutes(), array('class' => 'rc-drop', 'id' => 'start_minute_one_day'));
            ?>
            <?php echo $form->error($dayForm, "[$i]endTime"); ?>
        </div>
        <hr>
        <?php
    }


    $url = $this->createUrl('/admin/clientAdmin/bookingForClient/id/' . $client->id . '/action/Add');
    echo CHtml::submitButton('Add shift', array('submit' => $url));
    echo ' ';
    $url = $this->createUrl('/admin/clientAdmin/bookingForClient/id/' . $client->id);
    echo CHtml::link('Reset shifts', $url);
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'carerId'); ?>
        <?php echo $form->dropDownList($model, 'carerId', $carerOptions); ?>
        <?php echo $form->error($model, 'carerId'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'serviceUserId'); ?>
        <?php echo $form->dropDownList($model, 'serviceUserId', $serviceUsersOptions, array('type' => 'html')); ?>        
        <?php echo $form->error($model, 'serviceUserId'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'addressId'); ?>
        <?php echo $form->dropDownList($model, 'addressId', $addressOptions, array('type' => 'html', 'encode' => 'false')); ?>
        <?php echo $form->error($model, 'addressId'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'creditCardId'); ?>
        <?php echo $form->dropDownList($model, 'creditCardId', $creditCardOptions, array('type' => 'html')); ?>
        <?php echo $form->error($model, 'creditCardId'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'sendEMail'); ?>
        <?php echo $form->checkBox($model, 'sendEMail'); ?>
        <?php echo $form->error($model, 'sendEMail'); ?>
    </div>

    <div class="row buttons">
        <?php
        $url = $this->createUrl('/admin/clientAdmin/bookingForClient/id/' . $client->id);
        echo CHtml::submitButton('Submit', array('submit' => $url));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->