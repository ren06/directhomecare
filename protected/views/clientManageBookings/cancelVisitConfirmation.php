<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CANCEL_VISIT') ?>

<h2 class="rc-h2red"><?php echo $this->pageTitle; ?></h2>

<div class="rc-container-60">
    <br />
    <?php $this->renderPartial('/common/_backTo', array('scenario' => $scenario)); ?>
    <br />
    <p>
        <?php echo Yii::t('texts', 'LABEL_YOU_HAVE_CANCELLED_THE_VISIT') ; ?>
    </p>
</div>