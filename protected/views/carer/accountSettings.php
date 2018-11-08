<style type="text/css">
    #myinformation {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_ACCOUNT_SETTINGS');
$this->pageSubTitle = 'Email or not, this is the question';

$form = $this->beginWidget('CActiveForm', array('id' => 'carer_account_settings-form',
    'enableAjaxValidation' => false,
    'stateful' => true,
        ));
?>
<div class="row">
    <div class="columns large-12 medium-12 small-12">
        <?php $this->renderPartial('_myInformationCarerMenu', array('selectedMenu' => 'AccountSettings')); ?>
    </div>
</div>
<div class="row">
    <div class="columns large-6 medium-8 small-12">
        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_MANAGE_YOUR_ACCOUNT'); ?>
        </p>
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <?php
        endif;
        if (Yii::app()->user->hasFlash('error')):
            ?>
            <div class="flash-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <?php
        endif;
        ?>
        <h3><?php echo Yii::t('texts', 'HEADER_ACCOUNT_SETTINGS'); ?></h3>
        <?php echo '<div class="rc-module-container-button">' . CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainAccountSettings'))) . '</div>'; ?>
        <?php
        echo $form->checkBox($carer, 'deactivated', array());
        echo $form->labelEx($carer, 'deactivated');
        echo $form->error($carer, 'deactivated', array('class' => 'rc-error'));
        ?>
        <br>
        <br>
        <?php
        echo $form->checkBox($carer, 'no_job_alerts', array());
        echo $form->labelEx($carer, 'no_job_alerts');
        echo $form->error($carer, 'no_job_alerts', array('class' => 'rc-error'));
        ?>
    </div>
</div>
<?php $this->endWidget(); ?>