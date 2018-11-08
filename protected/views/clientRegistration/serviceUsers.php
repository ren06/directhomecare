<?php $this->pageTitle = Yii::t('texts', 'HEADER_USERS') ?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'service_users-form',
    'enableAjaxValidation' => false,
    //'focus' => array($client, 'firstName'),
    'stateful' => true,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
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
<h2 class="rc-h2red"><?php echo Yii::t('texts', 'CLIENT_REGISTRATION_SERVICE_USERS_HEADER1'); ?></h2>
<p class="rc-note">
    <?php echo Yii::t('texts', 'LABEL_YOUR_DETAILS_WILL_ALWAYS_BE_KEPT_PRIVATE'); ?><br />
    <?php echo Yii::t('texts', 'FIELDS_REQUIRED'); ?>
</p>
<?php
if (Yii::app()->params['test']['showPopulateData'] == true) {
    echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populateServiceUser'));
}
?>
<div style="float:left;">
    <div class="rc-container-40">
        <div id="service_users">
            <?php
            for ($i = 0; $i < count($serviceUsers); $i++):
                $this->renderPartial('_serviceUser', array(
                    'serviceUser' => $serviceUsers[$i],
                    'conditionErrors' => $conditionErrors,
                    'index' => $i,
                ));
                Yii::app()->session['serviceUserIndex'] = $i;
            endfor
            ?>
        </div>
        <?php echo CHtml::button(Yii::t('texts', 'BUTTON_ADD_ANOTHER_USER'), array('class' => 'rc-button-white service_users-add')) ?>
        <?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
        <div class="rc-container-button">
            <div class="buttons">
                <?php
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'rc-button-white', 'submit' => array('serviceUser', 'navigation' => 'back')));
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('class' => 'rc-button', 'submit' => array('serviceUser', 'navigation' => 'next')));
                ?>
            </div>  
            <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
        </div>
    </div>
</div>
<?php
//Carers samples
// $numberProfiles = 8;
// $this->renderPartial('application.views.carer.profile._carerProfileSample3-vertical', array('numberProfiles' => $numberProfiles));
?>
<div style="clear:both"></div>

<script type="text/javascript">
    $(".service_users-add").click(function() {
        $.ajax({
            success: function(html) {
                $("#service_users").append(html);
            },
            type: 'get',
            url: '<?php echo $this->createUrl('addServiceUser') ?>',
            data: {
                index: <?php echo -1 //echo count($carerDocuments);   ?>
            },
            cache: false,
            dataType: 'html'
        });
    });
</script>
<?php $this->endWidget(); ?>

<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/submitButton.js');
?>