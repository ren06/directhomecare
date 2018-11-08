<?php $this->pageTitle = Yii::t('texts', 'HEADER_LOCATION') ?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'service_location-form',
    'enableAjaxValidation' => false,
    'focus' => array($client, 'emailAddress'),
    'action' => 'registration', //by default current action in URL
    'stateful' => true,
        ));
?>

<?php echo Wizard::generateWizard(); ?>
<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success') ?>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error') ?>
    </div>
<?php endif ?>
<h2 class="rc-h2red"><?php echo Yii::t('texts', 'CLIENT_REGISTRATION_SERVICE_LOCATIONS_HEADER1'); ?></h2>
<p class="rc-note">
    <?php echo Yii::t('texts', 'LABEL_YOUR_DETAILS_WILL_ALWAYS_BE_KEPT_PRIVATE'); ?><br />
    <?php echo Yii::t('texts', 'FIELDS_REQUIRED'); ?>
</p>
<?php
if (Yii::app()->params['test']['showPopulateData'] == true) {
    echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populateServiceLocation'));
}
?>
<div style="float:left;">
    <div class="rc-container-40">
        <?php for ($i = 0; $i < count($serviceLocations); $i++): ?>
            <?php
            $this->renderPartial('_serviceLocation', array(
                'client' => $client,
                'serviceLocation' => $serviceLocations[$i],
                'index' => $i,
                'form' => $form,
            ));
            Yii::app()->session['serviceLocationIndex'] = $i;
        endfor
        ?>
        <?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
        <script type="text/javascript">
            $(".service_location-add").click(function() {
                $.ajax({
                    success: function(html) {
                        $(".service_locations").append(html);
                    },
                    type: 'get',
                    url: '<?php echo $this->createUrl('addServiceLocation') ?>',
                    data: {
                        index: <?php echo -1 //echo count($carerDocuments)      ?>
                    },
                    cache: false,
                    dataType: 'html'
                });
            });
        </script>
        <div class="rc-container-button">
            <div class="buttons">
                <?php
                echo CHtml::button(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'rc-button-white', 'submit' => array('serviceLocation', 'navigation' => 'back')));
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('class' => 'rc-button', 'submit' => array('serviceLocation', 'navigation' => 'next')));
                ?>
            </div>  
            <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
        </div>
    </div>
</div>

<?php
//Carers samples
// $numberProfiles = 3;
// $this->renderPartial('application.views.carer.profile._carerProfileSample3-vertical', array('numberProfiles' => $numberProfiles));
?>
<div style="clear:both"></div>

<?php $this->endWidget(); ?>

<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/submitButton.js');
?>