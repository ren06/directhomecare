<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CARERS') ?>

<?php
$formId = 'choose-carers-form';

$form = $this->beginWidget('CActiveForm', array('id' => $formId,
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
<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_WHICH_CARERS_DO_YOU_LIKE'); ?></h2>
<p class="rc-note">
    <?php echo Yii::t('texts', 'NOTES_SELECTION_OF_CARERS_YOU_CAN_ADJUST'); ?>
</p>
<div id="carerProfiles">
    <?php
    $this->renderPartial('application.views.carer.profile._carerProfilesMain', array('carers' => $carers, 'clientId' => $clientId, 'showMale' => $showMale, 'showFemale' => $showFemale,
        'nationality' => $nationality, 'nationalities' => $nationalities, 'carersNotWanted' => $carersNotWanted, 'formId' => 'choose-carers-form', 'view' => $view,
        'maxDisplayCarers' => $maxDisplayCarers, 'showGenderSelection' => true));
    ?>
</div>
<?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
<div class="rc-container-button">
    <div class="buttons">
        <?php
        echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'rc-button-white', 'submit' => array(Wizard::getStepActive(), 'navigation' => 'back')));
        echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('class' => 'rc-button', 'submit' => array(Wizard::getStepActive(), 'navigation' => 'next')));
        ?>
    </div>  
    <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
</div>
<?php $this->endWidget(); ?>