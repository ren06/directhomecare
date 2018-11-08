<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_REGISTER') ?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'signup-login-form',
    'enableAjaxValidation' => false,
    'focus' => array($client, 'email_address'),
    'stateful' => true,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_REGISTER'); ?><span style='font-size:0.4em;display: compact;'> <?php
if (Yii::app()->params['test']['showPopulateData'] == true) {
    echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populateSignUp'));
}
?></span></h2>
<div class="rc-container-60">
    
    <div class="rc-container-40-float-left">
        <?php
        if (Yii::app()->user->hasFlash('success')):
            echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
        endif;
        if (Yii::app()->user->hasFlash('error')):
            echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
        endif;
        ?>
        <div class="rc-module-bar">
            <div class="rc-module-name">
                <?php echo Yii::t('texts', 'LABEL_YOUR_DETAILS_WILL_ALWAYS_BE_KEPT_PRIVATE'); ?>
            </div>
        </div>
        <?php
        $passwordHtmlOptions = array('id' => 'passwordField', 'maxlength' => 40, 'class' => 'rc-field', 'autocomplete' => 'off');
        ?>

        <div class="rc-module-inside">
            <h3><?php echo 'New customer ? Please Register Below.'; //RCRC   ?></h3><br>
            <table>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($client, 'email_address') . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_WE_USE_YOUR_EMAIL_ADDRESS_TO_SEND_YOU_CARERS')); ?></td>
                    <td class="rc-cell2"><?php echo $form->textField($client, 'email_address', array('maxlength' => 60, 'class' => 'rc-field')); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($client, 'email_address', array('class' => 'rc-error')); ?></td>
                </tr>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($client, 'first_name'); ?></td>
                    <td class="rc-cell2"><?php echo $form->textField($client, 'first_name', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($client, 'first_name', array('class' => 'rc-error')); ?></td>
                </tr>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($client, 'last_name'); ?></td>
                    <td class="rc-cell2"><?php echo $form->textField($client, 'last_name', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($client, 'last_name', array('class' => 'rc-error')); ?></td>
                </tr>
            </table>
            <h3><?php echo Yii::t('texts', 'Protect your account'); // RCRC   ?></h3><br>
            <table>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($client, 'password'); ?></td>
                    <td class="rc-cell2"><?php echo $form->passwordField($client, 'password', $passwordHtmlOptions); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($client, 'password', array('class' => 'rc-error')); ?></td>
                </tr>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($client, 'repeat_password'); ?></td>
                    <?php $htmlOptions['autocomplete'] = 'off' ?>
                    <td class="rc-cell2"><?php echo $form->passwordField($client, 'repeat_password', $passwordHtmlOptions); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($client, 'repeat_password', array('class' => 'rc-error')); ?></td>
                </tr>  
            </table>
        </div>
        <div class="rc-container-button">
            <div class="buttons">
                <?php                
                echo CHtml::button(Yii::t('texts', 'BUTTON_BACK'), array('submit' => array('signUp', 'nav' => 'back'), 'class' => 'rc-button-white'));
                //triggered by enter key:
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_REGISTER'), array('submit' => array('signUp', 'nav' => 'next'), 'class' => 'rc-button'));
                ?>
            </div>  
            <?php $this->renderPartial('/common/_ajaxLoader'); ?>
        </div>
    </div>
    <?php
//Side Cart
    $client = Session::getSigninClient();

    //$this->renderPartial('/client/_sideCart', array('client' => $client));
    ?>
    <div style="clear:both"></div>
</div>
<?php
//Carers samples
// $numberProfiles = 3;
// $this->renderPartial('application.views.carer.profile._carerProfileSample3-vertical', array('numberProfiles' => $numberProfiles));
?>
<div style="clear:both"></div>

<?php $this->endWidget(); ?>
