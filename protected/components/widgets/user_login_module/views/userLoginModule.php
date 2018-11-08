<div class="rc-container-login" id="login_module">   
        <?php
        if (Yii::app()->user->isGuest){
        $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'stateful' => true,
                ));
        ?>
        <div class="rc-login-label1-container">
            <?php echo $form->label($model, 'email_address', array('class' => 'rc-login-text')); ?>
        </div>
        <div class="rc-login-label2-container">
            <?php echo $form->label($model, 'password', array('class' => 'rc-login-text')); ?>
        </div>
        <div class="rc-login-field1-container">
            <?php echo $form->textField($model, 'email_address', array('class' => 'rc-field-login')); ?>
        </div>
        <div class="rc-login-field2-container">
            <?php echo $form->passwordField($model, 'password', array('class' => 'rc-field-login', 'autocomplete'=>'off')); ?>
        </div>
        <div class="rc-login-button-container">
            <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_LOG_IN'), array('class' =>'rc-button-small')); ?>
        </div>
        <div class="rc-login-label3">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>
            <?php echo $form->label($model, 'rememberMe'); ?>
        </div>
        <div class="rc-login-label4">
            <?php echo CHtml::Link(Yii::t('texts', 'FORGOTTEN_PASSWORD'), array('site/forgottenPassword')); ?>
        </div>
        <div class="rc-login-error-container">
            <?php echo $form->error($model, 'email_address', array('class' => 'rc-error')); ?>
            <?php echo $form->error($model, 'password', array('class' => 'rc-error')); ?>
            <?php echo $form->error($model, 'rememberMe', array('class' => 'rc-error')); ?>       
        </div>
        <?php $this->endWidget(); 
        }
        else{
        ?>
        <div class="rc-login-welcome-container">
            <span class="rc-login-welcome-text"><?php echo Yii::t('texts', 'USER_LOGIN_MODULE_WELCOME') . Yii::app()->user->getState('full_name'); ?></span><span style="color:#333333">&#160;&#160;|</span>
        </div>
        <div class="rc-login-button-container">
            <?php echo CHtml::button(Yii::t('texts', 'BUTTON_LOG_OUT'), array('class' =>'rc-button-white-small-white', 'submit' => array('/site/logout'), 'name' => 'log_out_button')); ?>
        </div> 
        <?php } ?>
</div>