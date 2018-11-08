<style type="text/css">
    #myinformation {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CONFIRMATION') ?>

<?php
//$form = $this->beginWidget('CActiveForm', array('id' => 'carer_type_work-form', 'enableAjaxValidation' => false,
//    'stateful' => true,
//        ));
?>

<?php //echo Wizard::generateWizard(); ?>

<table class="rc-container-arrows">
    <tr>
        <td class="rc-cell-arrows" style="width:13em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-left.png'; ?>"/>
        </td>
        <td class="rc-cell-arrows" style="width:7em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-up.png'; ?>"/>
        </td>
        <td class="rc-cell-arrows" style="width:6em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-up.png'; ?>"/>
        </td>
        <td class="rc-cell-arrows" style="width:10em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-right.png'; ?>"/>
        </td>
    </tr>
    <tr>
        <td class="rc-cell-arrows">
            <?php echo Yii::t('texts', 'LABEL_APPLY_TO_JOBS_HERE'); ?>
        </td>
        <td class="rc-cell-arrows">
            <?php echo Yii::t('texts', 'LABEL_SEE_ALL_YOUR_JOBS'); ?>
        </td>
        <td class="rc-cell-arrows">
            <?php echo Yii::t('texts', 'LABEL_THATS_WHERE_YOUR_WAGES'); ?>
        </td>
        <td class="rc-cell-arrows">
            <?php echo Yii::t('texts', 'LABEL_EDIT_YOUR_INFORMATION'); ?>
        </td>
    </tr>
</table>
<div class="rc-container-30">
    <div class="rc-container-greyed" style="padding:2em!important">
        <h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_YOUR_REGISTRATION'); ?></h2>
        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_CONFIRMATION_CARER'); ?>
        </p>
        <br />
        <table width="100%">
            <tr>
                <td width="50%"><?php echo Yii::t('texts', 'LABEL_LIVE_IN_CARE_UPPER') . '<br />'; ?><span class="rc-quote-price"><?php echo Prices::getPriceDisplay(Constants::USER_CARER, Prices::LIVE_IN_DAILY_PRICE, NULL); ?></span><span class="rc-quote-price-per"><?php echo '&#160;' . Yii::t('texts', 'LABEL_PER_DAY'); ?></span></td>
                <td width="50%"><?php echo Yii::t('texts', 'LABEL_HOURLY_CARE_UPPER') . '<br />'; ?><span class="rc-quote-price"><?php echo Prices::getPriceDisplay(Constants::USER_CARER, Prices::HOURLY_PRICE, NULL); ?></span><span class="rc-quote-price-per"><?php echo '&#160;' . Yii::t('texts', 'LABEL_PER_HOUR'); ?></span></td>
            </tr>
        </table>
    </div>
</div>

<?php
// $this->endWidget();
?>