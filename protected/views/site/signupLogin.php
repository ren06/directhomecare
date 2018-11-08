<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>
<?php $this->pageTitle = Yii::t('texts', 'HEADER_SIGN_UP') ?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'signup-login-form',
    'enableAjaxValidation' => false,
    'focus' => array($client, 'email_address'),
    'stateful' => true,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>

<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_SIGN_UP'); ?><span style='font-size:0.4em;display: compact;'> <?php
if (Yii::app()->params['test']['showPopulateData'] == true) {
    echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populateSignUpLogin'));
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
        <?php
        $customerType = $client->getScenario();


        if ($customerType == Client::SCENARIO_CREATE_CLIENT_EMAIL) {

            $passwordHtmlOptionsClass = 'rc-field rc-disabled';
            $disabled = 'disabled';
            $passwordRequired = 'display:none';


            //TODO focus on first field
        } else {
            $passwordHtmlOptionsClass = 'rc-field';
            $disabled = '';
            $passwordRequired = 'display:visible';
        }

        $passwordHtmlOptions = array('id' => 'passwordField', 'maxlength' => 40, 'disabled' => $disabled, 'class' => $passwordHtmlOptionsClass, 'autocomplete' => 'off');
        ?>
        <div class="rc-module-bar">
            <div class="rc-module-name">
                <?php echo Yii::t('texts', 'LABEL_YOUR_DETAILS_WILL_ALWAYS_BE_KEPT_PRIVATE'); ?>
            </div>
        </div>
        <div class="rc-module-inside">
            <div class="always_visible_fields">
                <table>
                    <tr>
                        <td class="rc-cell1"><?php echo $form->labelEx($client, 'email_address'); ?></td>
                        <td class="rc-cell2"><?php echo $form->textField($client, 'email_address', array('maxlength' => 60, 'class' => 'rc-field')); ?></td>
                        <td class="rc-cell3"><?php echo $form->error($client, 'email_address', array('class' => 'rc-error')); ?></td>
                    </tr>
                    <tr>
                        <td class="rc-cell1"></td>
                        <td class="rc-cell3">
                            <?php
                            //TODO change in two radio button to have Tab index working
                            echo CHtml::radioButtonList('customer_type', $customerType, array(Client::SCENARIO_CREATE_CLIENT_EMAIL => Yii::t('texts', 'NEW_CUSTOMER'), Client::SCENARIO_LOGIN_CLIENT => Yii::t('texts', 'RETURNING_CUSTOMER')), array('separator' => '<br /><br />'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="rc-cell1"><span class="required" style="<?php echo $passwordRequired ?>" id="mandatory">*</span></td>
                        <td class="rc-cell2"><?php echo $form->passwordField($client, 'password', $passwordHtmlOptions); ?></td>
                        <td class="rc-cell3"><?php echo $form->error($client, 'password', array('class' => 'rc-error')); ?></td>
                    </tr>
                    <tr id="passwordResetLink">
                        <td class="rc-cell1"></td>
                        <td class="rc-cell2"><?php echo CHtml::Link(Yii::t('texts', 'FORGOTTEN_PASSWORD'), array('site/forgottenPassword'), array('class' => 'rc-link')); ?></td>
                        <td class="rc-cell3"></td>
                    </tr> 
                </table>
            </div>
        </div>
        <div class="rc-container-button">
            <div class="buttons">
                <?php
                //Enter key triggers a submitButton, that's why we want the back to be a normal button and the Next a submit button
                echo CHtml::button(Yii::t('texts', 'BUTTON_BACK'), array('submit' => array('signupLogin', 'nav' => 'back'), 'class' => 'rc-button-white'));
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SIGN_UP'), array('submit' => array('signupLogin', 'nav' => 'next'), 'style' => 'width:6.5em', 'class' => 'rc-button', 'id' => 'signUpButton'));
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

<script type="text/javascript">

    $(document).ready(function()
    {
        $("#customer_type_0").click(function() { //new customer 

            if ($(this).is(":checked")) {

                $("#passwordField").toggleClass('rc-disabled');
                $('#passwordField').attr('disabled', 'disabled');
                $("#Client_email_address").focus();
                $("#mandatory").hide();
                $("#passwordField").removeClass("error");
                $(".rc-error").hide();
                $("#signUpButton").prop('value', 'SIGN IN >');

            }
        });

        $("#customer_type_1").click(function() { //returning customer

            if ($(this).is(":checked")) {

                $("#passwordField").toggleClass('rc-disabled');
                $('#passwordField').removeAttr('disabled');
                $("#passwordField").focus();
                $("#mandatory").show();

                $("#signUpButton").prop('value', 'SIGN UP >');
            }
        });
    });
</script>