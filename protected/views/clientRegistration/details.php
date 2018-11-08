<script type="text/javascript">

    $(document).ready(function()
    {
        $("#customer_type_0").click(function() { //new customer 

            if ($(this).is(":checked")) {

                $(".returning_customer_only_field").hide();
                $(".new_customer_only_field").show();
                $(".new_customer_update_fields").show();

                $("#LoginErrorMessage").hide();//if any

            }
        });

        $("#customer_type_1").click(function() { //returning customer

            if ($(this).is(":checked")) {


                $(".new_customer_only_field").hide();
                $(".new_customer_update_fields").hide();

                $(".returning_customer_only_field").show();

            }
        });
    });
</script>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_SIGN_IN') ?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'client_details-form',
    'enableAjaxValidation' => false,
    'focus' => array($client, 'emailAddress'),
    'action' => 'signIn',
    'stateful' => true,
        ));
?>

<?php echo Wizard::generateWizard(); ?>
<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_WHAT_IS_YOUR_EMAIL'); ?></h2>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success') ?>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error') ?>
    </div>
<?php endif ?>
<p class="rc-note">
    <?php echo Yii::t('texts', 'FIELDS_REQUIRED'); ?>
</p>
<?php
$htmlOptions = array('maxlength' => 60, 'class' => 'rc-field');

if (Yii::app()->user->isGuest == false) {
    $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field-disabled', 'disabled' => 'disabled');
}
?>
<?php
$modelScenario = $client->getScenario();

if ($modelScenario == Client::SCENARIO_UPDATE_CLIENT) {

    $displayRadioButton = false;

    $cssReturningCustomerOnly = 'style="display:none"';
    $cssNewCustomerOnly = 'style="display:none"';
    $cssNewCustomerUpdate = 'style="display:block"';
} else {
    $displayRadioButton = true;
    if (isset($_POST['customer_type'])) {

        $customerType = $_POST['customer_type'];

        if ($customerType == Client::SCENARIO_CREATE_CLIENT) {
            $cssReturningCustomerOnly = 'style="display:none"';
            $cssNewCustomerOnly = 'style="display:block"';
            $cssNewCustomerUpdate = 'style="display:block"';
        } else {//LOGIN
            $cssReturningCustomerOnly = 'style="display:block"';
            $cssNewCustomerOnly = 'style="display:none"';
            $cssNewCustomerUpdate = 'style="display:none"';
        }
    } else {
        //default value
        $customerType = Client::SCENARIO_CREATE_CLIENT;
        $cssReturningCustomerOnly = 'style="display:none"';
        $cssNewCustomerOnly = 'style="display:block"';
        $cssNewCustomerUpdate = 'style="display:block"';
    }
}
?>
<?php
if (Yii::app()->params['test']['showPopulateData'] == true) {
    echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populate')) . '<br /><br />';
}
?>
<div style="float:left;">
    <div class="rc-container-40">
        <div class="rc-module-bar">
            <div class="rc-module-name">
                <?php echo Yii::t('texts', 'LABEL_YOUR_DETAILS_WILL_ALWAYS_BE_KEPT_PRIVATE'); ?>
            </div>
        </div>
        <div class="rc-module-inside">
            <div class="always_visible_fields">
                <table>
                    <?php
                    if ($displayRadioButton) {
                        echo '<td class="rc-cell1"></td><td colspan="2" class="rc-cell2">';
                        echo CHtml::radioButtonList('customer_type', $customerType, array(Client::SCENARIO_CREATE_CLIENT => Yii::t('texts', 'NEW_CUSTOMER'), Client::SCENARIO_LOGIN_CLIENT => Yii::t('texts', 'RETURNING_CUSTOMER')), array('separator' => Yii::app()->params['radioButtonSeparator']));
                        echo '</td>';
                    }
                    ?>
                    </tr>
                    <tr>
                        <td class="rc-cell1"><?php echo $form->labelEx($client, 'email_address'); ?></td>
                        <td class="rc-cell2"><?php echo $form->textField($client, 'email_address', $htmlOptions); ?></td>
                        <td class="rc-cell3"><?php echo $form->error($client, 'email_address', array('class' => 'rc-error')); ?></td>
                    </tr>
                    <tr>
                        <td class="rc-cell1"><?php echo $form->labelEx($client, 'password'); ?></td>
                        <?php $htmlOptions['autocomplete'] = 'off' ?>
                        <td class="rc-cell2"><?php echo $form->passwordField($client, 'password', $htmlOptions); ?></td>
                        <td class="rc-cell3"><?php echo $form->error($client, 'password', array('class' => 'rc-error')); ?></td>
                    </tr>
                </table>
            </div>
            <div class="returning_customer_only_field" <?php echo $cssReturningCustomerOnly ?>>
                <table>
                    <tr id="passwordResetLink">
                        <td class="rc-cell1"></td>
                        <td class="rc-cell2"><?php echo CHtml::Link(Yii::t('texts', 'FORGOTTEN_PASSWORD'), array('site/forgottenPassword'), array('class' => 'rc-link')); ?></td>
                        <td class="rc-cell3"></td>
                    </tr> 
                </table>
            </div>
            <div class="new_customer_only_field" <?php echo $cssNewCustomerOnly ?>>
                <table>
                    <tr>
                        <td class="rc-cell1"><?php echo $form->labelEx($client, 'repeat_password'); ?></td>
                        <?php $htmlOptions['autocomplete'] = 'off' ?>
                        <td class="rc-cell2"><?php echo $form->passwordField($client, 'repeat_password', $htmlOptions); ?></td>
                        <td class="rc-cell3"><?php echo $form->error($client, 'repeat_password', array('class' => 'rc-error')); ?></td>
                    </tr>  
                </table>
            </div>
<!--            <div class="new_customer_update_fields" <?php echo $cssNewCustomerUpdate    ?> >
                <table>
                    <tr>
                        <td class="rc-cell1"><?php //echo $form->labelEx($client, 'first_name');    ?></td>
                        <td class="rc-cell2"><?php //echo $form->textField($client, 'first_name', array('maxlength' => 50, 'class' => 'rc-field'));    ?></td>
                        <td class="rc-cell3"><?php //echo $form->error($client, 'first_name', array('class' => 'rc-error'));    ?></td>
                    </tr>
                    <tr>
                        <td class="rc-cell1"><?php //echo $form->labelEx($client, 'last_name');    ?></td>
                        <td class="rc-cell2"><?php //echo $form->textField($client, 'last_name', array('maxlength' => 50, 'class' => 'rc-field'));    ?></td>
                        <td class="rc-cell3"><?php //echo $form->error($client, 'last_name', array('class' => 'rc-error'));    ?></td>
                    </tr>
                </table>
            </div>-->
        </div>
        <div class="rc-container-button">
            <div class="buttons">
                <?php
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'rc-button-white', 'submit' => array('signIn', 'navigation' => 'back')));
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SEE_AVAILABLE_CARERS'), array('class' => 'rc-button', 'submit' => array('signIn', 'navigation' => 'next')));
                ?>
            </div>  
            <?php $this->renderPartial('/common/_ajaxLoader'); ?>
        </div>
    </div>
</div>
<?php
//Carers samples
// $numberProfiles = 3;
// $this->renderPartial('application.views.carer.profile._carerProfileSample3-vertical', array('numberProfiles' => $numberProfiles));
?>
<div style="clear:both"></div>

<?php $this->endWidget(); ?>
