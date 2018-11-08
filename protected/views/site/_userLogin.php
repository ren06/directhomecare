<?php
if ($dialog) {

    $formOptions = array(
        'id' => 'login-form',
        'enableAjaxValidation' => true,
        'focus' => array($model, 'email_address'),
        'clientOptions' => array('validateOnSubmit' => true,
            'validateOnChange' => false));
} else {
    if (isset($model->email_address)) {
        $focusField = 'password';
    } else {
        $focusField = 'email_address';
    }
    $formOptions = array(
        'id' => 'login-form',
        'enableAjaxValidation' => false, // causes Yii bug in login page on email click
        'focus' => array($model, $focusField),);
}
$form = $this->beginWidget('CActiveForm', $formOptions);
?>
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        echo $form->textField($model, 'email_address', array('placeholder' => 'Type your email'));
        echo $form->error($model, 'email_address');
        ?>
    </div>
</div>
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        echo $form->passwordField($model, 'password', array('placeholder' => 'Password', 'autocomplete' => 'off'));
        echo $form->error($model, 'password');
        ?>
    </div>
</div>
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <p>
            <?php echo CHtml::Link(Yii::t('texts', 'FORGOTTEN_PASSWORD'), array('site/forgottenPassword')); ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="large-12 medium-12 small-12 columns rc-container-button">
        <?php
        $url = CHtml::normalizeUrl(array('site/loginDialog'));
        $uniqueId = uniqid();

        //Login button
        if ($dialog) {
            echo CHtml::ajaxSubmitButton(Yii::t('texts', 'BUTTON_LOG_IN'), $url, array(
                'success' => "function(data) {

                            if (data.indexOf('{')==0) {
                              
                                //clear any existing errors
                                $('[id*=_em_]').hide();
                               
                                //hide ajax loader
                                $('.loading').hide();
                             
                                //show again buttons
                                $('#login$uniqueId').closest('.rc-container-button').find('.button').show();
                                
                                //display errors
                                var json = $.parseJSON(data);
                                $.each(json, function(key, value) {
                                //alert($('#' + key);
                                    $('#' + key).addClass('error');
                                    $('#' + key + '_em_').show().html(value.toString());
                                    $('label[for=' + key + ']').addClass('error');
                                });
                            }
                            else {

                                //redirect
                                if(data == null){
                                    //reload page
                                    location.reload();
                                }
                                else{
                                    window.location.href=data;
                                }
                            }

                        }",
                'type' => 'POST',
                'error' => 'function(data) {
                      }',
                    ), array('class' => 'button expand', 'id' => 'login' . $uniqueId, 'name' => 'loginName' . $uniqueId)
            );
        } else {

            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_LOG_IN'), array('class' => 'button expand'));
        }

        $this->renderPartial('/common/_ajaxLoader');
        ?>
    </div>
</div>

<?php // echo $form->checkBox($model, 'rememberMe');  ?>
<?php // echo $form->label($model, 'rememberMe'); ?>

<?php
//if (!$dialog) {
?>
<!--    <div id="UserLoginForm_email_address_em_" class="rc-error" style="display:none"></div>
    <div id="UserLoginForm_password_em_" class="rc-error" style="display:none"></div>-->
<?php
//}

$this->endWidget();
?>