<?php if (Yii::app()->user->hasFlash('error')):
    ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error') ?>
    </div>
<?php endif ?>

<?php if (Yii::app()->user->hasFlash('success')):
    ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success') ?>
    </div>
    <?php
endif
?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'service_payment-form',
    'enableAjaxValidation' => false,
    'stateful' => true,
        ));

echo CHtml::label('Environment ', 'environment');
echo CHtml::radioButtonList('environment', $environment, array(IPaymentHandler::ENVIRONMENT_TEST => 'Test', IPaymentHandler::ENVIRONMENT_LIVE => 'Live'), array('class' => '', 'separator' => Yii::app()->params['radioButtonSeparator'], 'template' => "{input} {label} "));
echo '<br>';
echo '<br>';
echo CHtml::label('Amount ', 'amount');
echo CHtml::textField('amount', $amount);
echo 'GBP';
echo '<BR>';
echo '<br>';
$i = 0;
$data = array();

foreach ($creditCards as $cc) {
    if ($cc == null) {
        $data[$i] = 'None';
    } else {
        $data[$i] = 'Card ' . $i . ' number ' . $cc->card_number;
    }
    $i++;
}

echo CHtml::label('Pre-defined card ', 'creditCards');
echo CHtml::dropDownList('creditCards', null, $data, array('class' => 'rc-drop'));
echo '<br>';
echo '<br>';
echo '<hr>';

$test = $creditCard->expiry_date;

$this->renderPartial('webroot.protected.views.creditCard._creditCardDetails', array('form' => $form, 'creditCard' => $creditCard, 'index' => 'new'));

echo CHtml::submitButton();

$this->endWidget();
?>

