<?php

//Yii::app()->clientScript->registerCoreScript('jquery.yiiactiveform');
//jquery.yiiactiveform.js

$allowedExtensions = Yii::app()->params['filesPolicy']['extensionTypePdf'];

if ($documentType == Document::TYPE_DIPLOMA) {

    $divId = 'diplomas';
    $title = Yii::t('texts', 'HEADER_ADD_A_DIPLOMA');
    $action = 'uploadDiploma';
    $documents = Document::getDocumentList(Document::TYPE_DIPLOMA);
} else {

    $divId = 'criminalRecords';
    $title = Yii::t('texts', 'HEADER_ADD_A_CRB');
    $action = 'uploadCriminalRecord';
    $documents = Document::getDocumentList(Document::TYPE_CRIMINAL);
}

$documents = Util::array_put_to_position($documents, Yii::t('texts', 'DROPDOWN_SELECT_DOCUMENT'), 0);

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => $divId . 'Dialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'title' => $title,
        'autoOpen' => true,
        'resizable' => false,
        'draggable' => false,
        'modal' => true,
        'width' => '500',
    //'height' => '320',
    ),
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'new' . $divId . '-form',
    //'enableAjaxValidation' => true,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>
<?php
//ksort($documents);
$years = Calendar::getDropDownDiplomaYears();
$years = Util::array_put_to_position($years, Yii::t('texts', 'DROPDOWN_YEAR_EARNED'), 0);
?>
<table class="rc-container-upload-table">
    <tr>
        <td class="rc-cell1-uploadpopup">
            <?php echo $form->dropDownList($model, 'id_document', $documents, array('class' => 'rc-drop')); ?>
        </td>
    </tr>
    <tr>
        <td class="rc-cell1-uploadpopup">
            <?php echo $form->dropDownList($model, 'year_obtained', $years, array('class' => 'rc-drop')); ?>
        </td>
    </tr>
    <tr>
        <td class="rc-cellforbutton-uploadpopup">
            <?php
            $this->widget('application.extensions.EAjaxUpload.EAjaxUpload', array(
                'id' => 'uploadDocumentDialog' . $divId,
                'config' => array(
                    'action' => Yii::app()->createUrl('carer/' . $action),
                    'buttonText' => Yii::t('texts', 'BUTTON_UPLOAD_DOCUMENT'),
                    'allowedExtensions' => $allowedExtensions, //code duplicated in includeUploadCommon.php
                    'sizeLimit' => Yii::app()->params['filesPolicy']['maxSize'], // maximum file size in bytes. code duplicated in includeUploadCommon.php
                    'minSizeLimit' => 0.001 * 1024 * 1024, // minimum file size in bytes
                    'onComplete' => "js:function(id, fileName, responseJSON){
                    var divId = '#documentStatus';
                    var html = responseJSON['html'];
                    $(divId).replaceWith(html);
                    $('#createDialogErrorMessage$divId').empty();
                    
                    }",
                )
            ));
            ?>
        </td>
    </tr>
    <tr>
        <td class="rc-upload-tablecell-errors rc-borderbottom">
            <span id="documentStatus"></span>
            <?php echo $form->error($model, 'id_document', array('class' => 'rc-error', 'id' => 'id_document' . $divId)); ?>
            <?php echo $form->error($model, 'year_obtained', array('class' => 'rc-error', 'id' => 'year_obtained' . $divId)); ?>
            <span id="createDialogErrorMessage<?php echo $divId ?>" class="rc-error"></span>
        </td>
    </tr>
</table>
<div class="rc-container-button">
    <div class="buttons">
        <?php
        $dialogId = $divId . "Dialog";
        echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'rc-button', 'id' => 'addDocument', 'name' => 'addDocumentName'
            , 'onclick' => "submitDocument('" . $dialogId . "', '" . $divId . "');"));
        echo Yii::t('texts', 'SEPARATOR_BUTTON');
        echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button', 'id' => 'closeDocument', 'name' => 'closeDocumentName'
            , 'onclick' => "$('#" . $divId . "Dialog').dialog('close');"));
        ?>
    </div>
    <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
</div>

<?php $this->endWidget(); ?>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>