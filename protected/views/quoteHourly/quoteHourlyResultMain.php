<br />
<div>
    <?php
    //render right hourly
    $this->renderPartial('/quoteHourly/_quoteHourlyResult', array('quote' => $quote));
    ?>
</div>
<div class="rc-container-button">
    <span class="buttons">
        <?php
        if (Yii::app()->user->isGuest || Yii::app()->user->wizard_completed != Wizard2::CLIENT_LAST_STEP_INDEX) {
            $action = 'clientRegistration/quote';
        } else {
            $action = 'clientNewBooking/quote';
        }
        echo CHtml::link(Yii::t('texts', 'BUTTON_SEE_AVAILABLE_CARERS'), array($action, 'navigation' => 'next', 'quoteType' => $quote->type), array('class' => 'rc-linkbutton ajaxLoaderButton'));
        ?>
    </span>
    <?php $this->renderPartial('/common/_ajaxLoader', array('html' => true, 'javascript' => false)); ?>
</div>