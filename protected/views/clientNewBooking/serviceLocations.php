<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_LOCATION') ?>

<?php echo Wizard::generateWizard(); ?>

<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_SELECT_SERVICE_LOCATION'); ?></h2>
<div class="rc-container-40">
    <p class="rc-note">
        <?php echo Yii::t('texts', 'NOTE_USE_THE_CIRCLE_ON_THE_LEFT_OF_THE_ADDRESS'); ?>
    </p>
    <div id="errorMessage" class="flash-error" style="display:none">
        <?php
        if (isset($errorMessage)) {
            echo $errorMessage;
        }
        ?>
    </div>
    <?php
    $this->widget('ServiceLocationsWidget', array('client' => $client, 'serviceLocations' => $serviceLocations, 'scenario' => ServiceUsersWidget::MISSION_SCENARIO));
    ?>
    <div class="rc-container-button">
        <div class="buttons">
            <?php
            echo '<span class="rc-linkbutton-white-disabled buttons_span" style="display:none">' . Yii::t('texts', 'BUTTON_BACK') . '</span>';
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'rc-button-white buttons_input', 'id' => 'back', 'name' => 'back'));

            echo '<span class="rc-linkbutton-disabled buttons_span" style="display:none">' . Yii::t('texts', 'BUTTON_SEE_AVAILABLE_CARERS') . '</span>';
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SEE_AVAILABLE_CARERS'), array('class' => 'rc-button buttons_input', 'id' => 'next', 'name' => 'next'));
            ?>
        </div>
        <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
    </div>
</div>
<script type="text/javascript">
    $("#next").click(function() {
        var radioButtons = $('#serviceLocationsWidget').find('input[type=radio]:checked')

        if (radioButtons.length == 0) {

            $('#errorMessage').show();
            $('#errorMessage').html('<?php echo Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_LOCATION'); ?>');
            //$('#errorMessage').scrollTop();
            //scrollTop: $("#errorMessage").offset().top;
            window.scrollTo(0, 0);
            event.stopPropagation(); //make sure ajax loader is disabled
            return false;
        }
        else {

            jQuery.yii.submitForm(this, '<?php echo Yii::app()->request->baseUrl ?>/clientNewBooking/serviceLocation/navigation/next', {});
            return false;
        }
    }
    );

    $("#back").click(function() {

        jQuery.yii.submitForm(this, '<?php echo Yii::app()->request->baseUrl ?>/clientNewBooking/serviceLocation/navigation/back.html', {});
        return false;
    }
    );
</script>