<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'confirmationDialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'title' => 'Rejection reason',
        'autoOpen' => true,
        'resizable' => false,
        'draggable' => false,
        'modal' => true,
        'width' => 460
    ),
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'complain-form',
    'enableAjaxValidation' => true,
        ));

$uniqueId = uniqid();
?>
<table class="rc-container-upload-table">
    <tr class="rc-upload-tablecell">
        <td class="rc-upload-tablecell">
            Please enter a message (max. 250 characters).
            <p id="errorMessage_<?php echo $uniqueId ?>" class="rc-error" style="display:none">
                <?php echo Yii::t('texts', 'ERROR_PLEASE_TYPE_A_MESSAGE'); ?>
            </p>            
        </td>
    </tr>
    <tr class="rc-upload-tablecell">
        <td class="rc-upload-tablecell">
            <?php
            echo Chtml::textArea('text', '', array('id' => "complaint_text_$uniqueId", 'maxlength' => 250, 'class' => 'rc-textarea-chat'));
            echo CHtml::hiddenField('documentId', $documentId);
            ?>
        </td>
    </tr>
</table>
<div class="rc-container-button">
    <?php
    $url =  $this->createUrl('/admin/carerAdmin/approveDocuments');
    echo CHtml::ajaxButton(Yii::t('texts', Yii::t('texts', 'Reject')), $this->createUrl('/admin/carerAdmin/rejectDocument'), array(
        'beforeSend' => "function(xhr, opts) {
            
            var text = $.trim($('#complaint_text_$uniqueId').val());
            
            if( text == '' ) {
                document.getElementById('errorMessage_$uniqueId').style.display = 'block';
                xhr.abort();
            }

        }",
        'success' => "function(html) {    
                            window.location.href='$url';
                            $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');                         
                        }",
        'type' => 'POST',
        'error' => 'function(data) {}',
            ), array('class' => 'rc-button', 'id' => uniqid())
    );

    echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button', 'id' => 'closeDialog', 'name' => 'closeDialogName'
        , 'onclick' => "$('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');"));
    ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>