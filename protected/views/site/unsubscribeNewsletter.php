<?php
$this->pageTitle = Yii::t('texts', 'HEADER_UNSUBSCRIBE_NEWSLETTER');
$this->pageSubTitle = 'Don\'t want to receive our emails anymore, no probs !';
$this->keyWords = 'unsubscribe newsletters';
$this->description = 'Unsubscribe from newsletters.';
?>

<div class="row">
    <div class="large-12 columns">
        <?php
        if (Yii::app()->user->hasFlash('success')) {
            ?>
            <div data-alert class="alert-box success radius">
                <?php
                echo Yii::app()->user->getFlash('success');
                $returnUri = Yii::app()->createUrl('site/index');
                Yii::app()->clientScript->registerMetaTag("3;url={$returnUri}", null, 'refresh');
                ?>
                <a href="#" class="close">&times;</a>
            </div>
            <?php
        } else {
            if (Yii::app()->user->hasFlash('error')) {
                ?>
                <div data-alert class="alert-box alert radius">
                    <?php echo Yii::app()->user->getFlash('error'); ?>
                    <a href="#" class="close">&times;</a>
                </div>
                <?php
            }
            ?>
            <h3>
                <?php
                if ($user == Constants::USER_CLIENT) {
                    echo Yii::t('texts', 'NOTE_UNSUBSCRIBE_CARER_PROFILE');
                } else {
                    echo Yii::t('texts', 'NOTE_UNSUBSCRIBE_COMPLETE_PROFILE');
                }
                ?>
            </h3>
            <?php
            echo CHtml::beginForm('', 'post');
            ?>
            <br>
            <div class="row">
                <div class="large-4 medium-6 small-12 columns">
                    <?php
                    echo CHtml::textField('email', $email, array('maxlength' => 60, 'placeholder' => 'Enter your email'));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="large-4 medium-6 small-12 columns">
                    <?php
                    echo CHtml::submitButton('UNSUBSCRIBE', array('class' => 'button expand'));
                    ?>
                </div>
            </div>
            <?php
            echo CHtml::endForm();
        }
        ?>
    </div>
</div>