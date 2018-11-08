<?php
$this->pageTitle = Yii::t('texts', 'SITE_CONTACT_TITLE');
$this->pageSubTitle = 'Get in touch, we are always happy to help.';
$this->keyWords = 'contact direct homecare, contact details carer';
$this->description = 'Contact Direct Homecare for any question. We are always glad to hear from visitors.';
?>

<div class="row">
    <div class="large-3 columns ">
        <div class="panel">
            <div class="section-container vertical-nav" data-section data-options="deep_linking: false; one_up: true">
                <section class="section">
                    <h5 class="title"><a href="#form">Contact form</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#email">Email</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#phone">Phone line</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#address">Registered address</a></h5>
                </section>
            </div>
        </div>
    </div>
    <div class="large-9 columns">
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-info.svg"></div>
            <div id="form" class="large-10 columns">
                <h4>Contact form</h4>
                <?php
                if (Yii::app()->user->hasFlash('contact')) {
                    ?>
                    <div data-alert class="alert-box success radius">
                        <?php echo Yii::app()->user->getFlash('contact'); ?>
                        <a href="#" class="close">&times;</a>
                    </div>
                    <?php
                }

                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'contact-form',
                    'enableClientValidation' => false,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));

                echo $form->textField($model, 'name', array('maxlength' => 50, 'placeholder' => 'Enter your name'));
                echo $form->error($model, 'name');

                echo $form->textField($model, 'email', array('maxlength' => 50, 'placeholder' => 'Email'));
                echo $form->error($model, 'email');

                echo $form->textField($model, 'subject', array('maxlength' => 150, 'placeholder' => 'Subject'));
                echo $form->error($model, 'subject');

                echo $form->textArea($model, 'body', array('maxlength' => 1000, 'placeholder' => 'Message'));
                echo $form->error($model, 'body');
                ?>
                <?php
                if (CCaptcha::checkRequirements()) {
                    ?>
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
                    <?php
                }
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SEND_MESSAGE'), array('class' => 'large button expand'));
                $this->endWidget();
                ?>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-mail.svg"></div>
            <div id="email" class="large-10 columns">
                <h4>Client support email</h4>
                <p><?php echo Yii::t('texts', 'NOTE_CLIENTS_CAN_CONTACT_US_BY_EMAIL'); ?></p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-telephone.svg"></div>
            <div id="phone" class="large-10 columns">
                <h4>Phone line</h4>
                <p>Our UK landline number is: <?php echo Yii::t('texts', 'NOTE_PHONE_LINE'); ?></p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-map.svg"></div>
            <div id="address" class="large-10 columns">
                <h4>Registered address</h4>
                <p><?php echo Yii::t('texts', 'NOTE_CONTACT_DETAILS'); ?></p>
            </div>
        </div>
    </div>
</div>