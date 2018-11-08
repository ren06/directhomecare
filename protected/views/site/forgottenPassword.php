<?php
$this->pageTitle = Yii::t('texts', 'HEADER_RESET_PASSWORD');
$this->pageSubTitle = 'No worries, it only takes 20 seconds !';
$this->keyWords = 'forgotten password';
$this->description = 'Forgotten your password.';
?>

<div class="row">
    <div class="large-12 columns">
        <?php
        if (Yii::app()->user->hasFlash('success')) {
            ?>
            <div data-alert class="alert-box success radius">
                <?php
                echo Yii::app()->user->getFlash('success');
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
                    <h3><?php echo Yii::t('texts', 'NOTE_YOU_CANT_REMEMBER_YOUR_PASSWORD'); ?></h3>
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
                    echo $form->textField($model, 'email', array('maxlength' => 50, 'autocomplete' => 'off', 'placeholder' => 'Type your email'));
                    echo $form->error($model, 'email', array('class' => 'errorMessage'));
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="large-6 medium-6 small-12 columns">
                    <div class="row">
                        <div class="large-6 medium-6 small-12 columns centered-text">
                            <?php
                            $this->widget('CCaptcha', array('buttonLabel' => Yii::t('texts', 'NEW_CODE')));
                            ?>
                        </div>
                        <div class="large-6 medium-6 small-12 columns">
                            <?php
                            echo $form->textField($model, 'verifyCode', array('maxlength' => 50, 'placeholder' => 'Type the verification code'));
                            echo $form->error($model, 'verifyCode');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="large-6 medium-6 small-12 columns">
                    <?php
                    echo CHtml::submitButton(Yii::t('texts', 'BUTTON_RESET_PASSWORD'), array('class' => 'button expand'));
                    ?>
                </div>
            </div>
            <?php
            $this->endWidget();
        }
        ?>
    </div>
</div>