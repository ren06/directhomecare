<?php
$this->pageTitle = Yii::t('texts', 'HEADER_CHANGE_YOUR_PASSWORD');
$this->pageSubTitle = 'New password, new life !';
$this->keyWords = 'change password';
$this->description = 'Change your password.';
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
            <div class="row">
                <div class="large-6 medium-6 small-12 columns">
                    <h3><?php echo Yii::t('texts', 'NOTE_PLEASE_CHOOSE_A_NEW_PASSWORD'); ?></h3>
                </div>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'contact-form',
                'enableClientValidation' => false,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));

            echo CHtml::beginForm('', 'post');
            ?>
            <br>
            <div class="row">
                <div class="large-6 medium-6 small-12 columns">
                    <?php
                    $htmlOptions = array('maxlength' => 60, 'autocomplete' => 'off', 'placeholder' => 'Type your new passord');
                    echo $form->passwordField($model, 'newPassword', $htmlOptions);
                    echo $form->error($model, 'newPassword');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="large-6 medium-6 small-12 columns">
                    <?php
                    $htmlOptions = array('maxlength' => 60, 'autocomplete' => 'off', 'placeholder' => 'Verify your new passord');
                    echo $form->passwordField($model, 'newPasswordRepeat', $htmlOptions);
                    echo $form->error($model, 'newPasswordRepeat');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="large-6 medium-6 small-12 columns">
                    <?php
                    echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CHANGE_PASSWORD'), array('class' => 'button expand'));
                    ?>
                </div>
            </div>
            <?php
            $this->endWidget();
        }
        ?>
    </div>
</div>