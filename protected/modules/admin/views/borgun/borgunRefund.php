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
$form = $this->beginWidget('CActiveForm', array('id' => 'refund_payment-form',
    'enableAjaxValidation' => false,
    'stateful' => true,
        ));

echo CHtml::label('Environment ', 'environment');
echo CHtml::radioButtonList('environment', $environment, array(IPaymentHandler::ENVIRONMENT_TEST => 'Test', IPaymentHandler::ENVIRONMENT_LIVE => 'Live'), array('class' => '', 'separator' => Yii::app()->params['radioButtonSeparator'], 'template' => "{input} {label} "));
echo '<br>';
echo CHtml::label('Environment ', 'refundType');
echo CHtml::radioButtonList('refundType', $refundType, array('refund_sale' => 'Refund of Sale', 'refund_partial' => 'Partial Reversal of Sale'), array('class' => '', 'separator' => Yii::app()->params['radioButtonSeparator'], 'template' => "{input} {label} "));
echo '<br>';
echo '<br>';
echo CHtml::label('Refund Amount (130.45): *', 'amount');
echo CHtml::textField('amount', $amount);
echo 'GBP';
echo '<br>';
echo CHtml::label('Original Amount (partial only): ', 'originalAmount');
echo CHtml::textField('originalAmount', $originalAmount);
echo 'GBP';
echo '<br>';
echo CHtml::label('Transaction Ref (starts with DH): *', 'transaction');
echo CHtml::textField('transaction', $transaction);
echo '<br>';
echo CHtml::label('Credit card number: *', 'creditCardNumber');
echo CHtml::textField('creditCardNumber', $creditCardNumber);
echo '<br>';
echo CHtml::label('Transaction date (2013-12-31 19:45:20 format): *', 'transactionDate');
echo CHtml::textField('transactionDate', $transactionDate);
echo '<br>';
echo '<br>';
echo CHtml::submitButton();

$this->endWidget();
?>

