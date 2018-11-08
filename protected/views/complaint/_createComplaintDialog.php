<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'confirmationDialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'title' => 'Complaint',
        'autoOpen' => true,
        'resizable' => false,
        'draggable' => false,
        'modal' => true,
        'width' => 460,
    ),
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'complain-form',
    'enableAjaxValidation' => true,
        ));

$model = new ComplaintPost();

$uniqueId = uniqid();
?>

<p class="rc-note">
    <?php echo Yii::t('texts', 'NOTE_PLEASE_ENTER_A_MESSAGE'); ?>
</p>
<span id="errorMessage_<?php echo $uniqueId ?>" class="rc-error" style="display:none">
    <?php echo '<br />' . Yii::t('texts', 'ERROR_PLEASE_TYPE_A_MESSAGE'); ?>
</span>
<?php
echo $form->textArea($model, 'text', array('id' => "complaint_text_$uniqueId", 'maxlength' => 250, 'class' => 'rc-textarea-chat'));
echo CHtml::hiddenField('id_mission', $missionId);
echo CHtml::hiddenField('user', $user);
?>
<div class="rc-container-button">
    <div class="buttons">
        <?php
        //lazy way
        if (Yii::app()->user->roles == Constants::USER_CLIENT) {
            $url = $this->createUrl('clientManageBookings/addComplaint');
        } else {
            $url = $this->createUrl('carer/addComplaint');
        }
        if ($user == Constants::USER_CLIENT) {
            $div = "#discussion_client";
        } else {
            $div = "#discussion_carer";
        }

        echo CHtml::ajaxButton(Yii::t('texts', Yii::t('texts', 'BUTTON_SEND')), $url, array(
            'beforeSend' => "function(xhr, opts) {
            
            var text = $.trim($('#complaint_text_$uniqueId').val());
            
            if( text == '' ) {
                document.getElementById('errorMessage_$uniqueId').style.display = 'block';
                xhr.abort();
            }
            else{
                $('.buttons').hide();
                $('.loading').show();
            }
        }",
            'success' => "function(html) {
                        $('.loading').hide();
                        $('$div').html(html);
                        $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');                         
                    }",
            'type' => 'POST',
            'error' => 'function(data) {
                        }',
                ), array('class' => 'rc-button', 'id' => 'send' . $uniqueId, 'name' => 'sendName' . $uniqueId)
        );
        echo Yii::t('texts', 'SEPARATOR_BUTTON');
        echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button', 'id' => 'closeDialog' . $uniqueId, 'name' => 'closeDialogName' . $uniqueId
            , 'onclick' => "$('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');"));
        ?>
    </div>
    <?php $this->renderPartial('/common/_ajaxLoader'); ?>
</div>

<?php $this->endWidget(); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>