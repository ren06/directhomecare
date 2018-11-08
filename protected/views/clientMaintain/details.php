<style type="text/css">
    #myinformation{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_DETAILS');
$this->pageSubTitle = 'You can make amend your details here';
?>

<?php
$editDetailsButton = 'editDetails';
$editDetailsButtonDisabled = 'editDetailsDisabled';
$saveDetailsButton = 'saveDetails';
$cancelDetailsButton = 'cancelDetails';
$editPasswordButton = 'editPassword';
$editPasswordButtonDisabled = 'editPasswordDisabled';
$savePasswordButton = 'savePassword';
$cancelPasswordButton = 'cancelPassword';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myInformationClientMenu', array('selectedMenu' => 'Details')); ?>
    </div>
</div>
<div class="row" style="min-height:15em" id="allDetails">
    <div class="large-6 medium-8 small-12 columns">
        <div id="detailsSuccess" class="flash-success" style="display:none">
            <?php echo Yii::t('texts', 'FLASH_CHANGES_SAVED'); ?>
        </div>
        <div id="detailsError" class="flash-error" style="display:none"></div>
        <div id="passwordSuccess" class="flash-success" style="display:none">
            <?php echo Yii::t('texts', 'FLASH_PASSWORD_CHANGED'); ?>
        </div>
        <div id="passwordError" class="flash-error" style="display:none"></div>
        <?php
        $this->beginWidget('CActiveForm', array('id' => 'client_details-form',
            'enableAjaxValidation' => true,
            'clientOptions' => array('validateOnSubmit' => true),
        ));
        ?>
        <div id="editDetailsContainer">
            <h4><?php echo Yii::t('texts', 'HEADER_CONTACT_DETAILS'); ?></h4>
            <div class="rc-module-container-button">
                <?php
                echo CHtml::button(Yii::t('texts', 'BUTTON_EDIT'), array('class' => 'rc-button-small editButtonClass', 'id' => $editDetailsButton));
                echo '<span id="' . $editDetailsButtonDisabled . '" class="rc-linkbutton-small-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_EDIT') . '</span>';
                echo CHtml::ajaxSubmitButton(Yii::t('texts', 'BUTTON_SAVE'), CHtml::normalizeUrl(array('saveDetails')), array('success' => 'function(data){
                   if (data.indexOf("{")==0) {

                        var json = $.parseJSON(data);

                        $.each(json, function(key, value) {
                            $("#" + key).addClass("clsError");
                            $("#" + key + "_em_").show().html(value.toString());
                            $("label[for=" + key + "]").addClass("clsError");
                        });           
                    }
                    else {

                        $("#_details").html(data);
                        $("#detailsSuccess").show();
                        var newName = "Welcome " + $("#Client_first_name").val() + " " + $("#Client_last_name").val();

                        $("span.rc-login-welcome-text").html(newName);
                        displayMode();
                    }
                }'), array('class' => 'rc-button-small', 'id' => $saveDetailsButton)
                );

                echo CHtml::ajaxSubmitButton(
                        Yii::t('texts', 'BUTTON_CANCEL'), CHtml::normalizeUrl(array('cancelDetails')), array('success' => 'function(data){                         
                                                $("#_details").html(data);
                                                displayMode();
                        }'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => $cancelDetailsButton)
                );
                ?>

            </div>
            <div id="_details">
                <?php $this->renderPartial('_details', array('client' => $client)); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
        <div id="editPasswordContainer">
            <?php
            $this->beginWidget('CActiveForm', array(
                'id' => 'client_resetpassword-form',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
            ?>
            <h4><?php echo Yii::t('texts', 'CLIENT_DETAILS_HEADER1'); ?></h4>
            <?php
            echo CHtml::button(Yii::t('texts', 'BUTTON_EDIT'), array('class' => 'rc-button-small editButtonClass', 'id' => $editPasswordButton));
            echo '<span id="' . $editPasswordButtonDisabled . '" class="rc-linkbutton-small-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_EDIT') . '</span>';
            echo CHtml::ajaxSubmitButton(Yii::t('texts', 'BUTTON_SAVE'), CHtml::normalizeUrl(array('savePassword')), array('success' => 'function(data){
                   if (data.indexOf("{")==0) {

                        var json = $.parseJSON(data);

                            $.each(json, function(key, value) {

                                 if(key == "PasswordError"){

                                    $("#passwordError").html(value);
                                    $("#passwordError").show();
                                 }
                                 else{

                                    $("#" + key).addClass("clsError");
                                    $("#" + key + "_em_").show().html(value.toString());
                                    $("label[for=" + key + "]").addClass("clsError");
                                }
                            });           
                        }          
                    else {
                        $("#_password").html(data);
                        $("#passwordSuccess").show();
                        displayMode();
                    }
                }'), array('class' => 'rc-button-small', 'id' => $savePasswordButton)
            );

            echo CHtml::ajaxSubmitButton(
                    Yii::t('texts', 'BUTTON_CANCEL'), CHtml::normalizeUrl(array('cancelPassword')), array('success' => 'function(data){
                                                $("#_password").html(data);
                                                displayMode();
                        }'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => $cancelPasswordButton)
            );
            ?>
            <div id="_password">
                <?php
                $this->renderPartial('_password', array('resetPassword' => $resetPassword));
                ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">

    $('.editButtonClass').click(function() {

        editMode(this);
    });

    $(function() {

        displayMode();
    });

    function editMode(currentButton) {

        var buttonId = currentButton.id;

        //enable current form buttons
        $('#' + buttonId + 'Container :input').removeAttr('disabled');

        //hide all edit buttons
        $('[id^=edit] :button').hide();

        //show all disable spans
        $('[id$=Disabled]').show();

        //show required stars
        $('#' + buttonId + 'Container span.required').show();
        //$('#allDetails span').show();

        //hide current disabled span
        $('#' + buttonId + 'Container [id$=Disabled]').hide();

        //show edit and cancel button for current
        $('#' + buttonId + 'Container [id^=save]').show();
        $('#' + buttonId + 'Container [id^=cancel]').show();

        //make sure fields like email address always hidden
        $('.alwaysDisabled').attr('disabled', 'disabled');

        $('#' + buttonId + 'Container').find('.toggable').addClass('rc-field');
        $('#' + buttonId + 'Container').find('.toggable').removeClass('rc-field-disabled');

        //hide existing success message
        $('div.flash-success').hide();

    }

    function displayMode() {

        //disable all input fields of all forms
        $('#allDetails :input').attr('disabled', 'disabled');
        $('#allDetails').find('.toggable').addClass('rc-field-disabled');
        $('#allDetails').find('.toggable').removeClass('rc-field');

        //enable edit buttons
        $('[id^=edit]').show();
        $('[id^=edit] [id$=Disabled]').hide();
        $('[id^=edit]').removeAttr('disabled');

        //hide save and cancel buttons
        $('[id^=save]').hide();//hide all edit buttons
        $('[id^=cancel]').hide();//hide all edit buttons

        //hide required stars of all forms
        $('#editDetailsContainer span.required').hide();
        $('#editPasswordContainer span.required').hide();
    }
</script>