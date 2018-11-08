<?php

//FOR SOME REASON IT FAILS IF THIS CODE IS IN A SEPARATE METHOD, ONLY WORKS WITH INCLUDE

include Yii::getPathOfAlias('webroot.protected.helpers.include') . '/includeUploadCommon.php';

//update those documents
if ($documentType == Document::TYPE_PHOTO || $documentType == Document::TYPE_IDENTIFICATION
        || $documentType == Document::TYPE_DRIVING_LICENCE) {
    
    //check if exists
    $carerDocument = CarerDocument::getUniqueDocument($carerId, $documentType, CarerDocument::UNACTIVE);
    
    if(!isset($carerDocument)){
        $carerDocument = new CarerDocument();
    }
    else{
        $carerDocument->reject_reason = '';
    }
    
    
    
} else {
    
    //Diplomas and CRB, always add new record
    $carerDocument = new CarerDocument();
}

$documentIds = Document::getDocumentIds($documentType);

$carerDocument->id_carer = $carerId;
$carerDocument->id_document = $documentIds[0];


//update new file
$carerDocument->id_content = $fileContent->id;
$carerDocument->status = CarerDocument::STATUS_UNAPPROVED;

$id = $carerDocument->id;
$id2 = $fileContent->id;

if ($carerDocument->validate()) {
    $carerDocument->save();
}
?>