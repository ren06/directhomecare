<?php

echo CHtml::beginForm();
echo CHtml::hiddenField('documentId', $document->id);
echo CHtml::hiddenField('carerId', $carer->id);

$years = Calendar::getDropDownDiplomaYears();

echo '<b>Type: </b>';
if ($document->type == Document::TYPE_TEXT) {
    echo $document->getName();
} else {
    echo $document->getTypeLabel();
}
echo '<br />';
if ($document->type == Document::TYPE_DIPLOMA || $document->type == Document::TYPE_CRIMINAL) {
    echo CHtml::activeDropDownList($document, "year_obtained", $years, array('class' => 'rc-drop'));
    if ($document->documentType->type == Document::TYPE_DIPLOMA) {
        $documents = Document::getDocumentList(Document::TYPE_DIPLOMA);
    } else {
        $documents = Document::getDocumentList(Document::TYPE_CRIMINAL);
    }
    echo CHtml::activeDropDownList($document, "id_document", $documents, array('class' => 'rc-drop'));
    echo '<br />';
}
?>
<?php echo $document->displayDocumentStatusWithStyle(); ?>
<?php

$reason = $document->reject_reason;
if (!isset($reason) || $reason == '') {
    $reason = 'None';
}
echo '<b>Rejected reason: </b><br />' . $reason . '<br />';
if ($document->type != Document::TYPE_TEXT) {

    $carerDocument = CarerDocument::loadModelAdmin($document->id);
    $file = $carerDocument->getFile();

    if ($file->extension == 'pdf') {

        $url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/getPdfAdmin', array('documentId' => $document->id));

        echo "<embed src='$url' width='450'>";
    } else {

        if ($document->type != Document::TYPE_PHOTO) {
            $crop = false;
            $width = "450";
        } else {
            $crop = true;
            $width = "80";
            $url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/getImageAdmin', array('documentId' => $document->id, 'crop' => false));
            echo '<img id="photo" src="' . $url . '" alt="Photo" width="450" />';

            $url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/modifyImage/documentId/' . $document->id);
            echo '<br /><br />';
            echo CHtml::link('Modify Image', $url);
            echo '<br /><br />';
        }
        $url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/getImageAdmin', array('documentId' => $document->id, 'crop' => $crop));
        echo '<img src="' . $url . '" alt="Photo" width="' . $width . '" />';
    }
} else {
    echo CHtml::textArea('text', $document->text, array('style' => ''));
}
echo '<br />';
if ($document->active == CarerDocument::UNACTIVE) {
    $requirements = $document->getRequirements();
    echo CHtml::checkBoxList('reject_reasons', '', $requirements);
    echo '<br />';
    echo CHtml::submitButton('Verify', array('submit' => Yii::app()->createUrl("admin/carerAdmin/approveDocument")));
    echo CHtml::submitButton('Waiting', array('submit' => Yii::app()->createUrl("admin/carerAdmin/waitingDocument")));
    echo CHtml::submitButton('Reject', array('submit' => Yii::app()->createUrl("admin/carerAdmin/rejectDocument")));
} else {
    echo CHtml::submitButton('Delete', array('submit' => Yii::app()->createUrl("admin/carerAdmin/deleteActiveDocument")));
}
echo CHtml::endForm();
?>