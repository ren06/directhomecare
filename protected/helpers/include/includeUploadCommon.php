<?php

Yii::import("application.extensions.EAjaxUpload.qqFileUploader");

$carerId = Yii::app()->user->id;

switch ($documentType) {

    case Document::TYPE_CRIMINAL:

        $fileContent = new FileContentCriminal();
        $allowedExtensions = Yii::app()->params['filesPolicy']['extensionTypePdf'];
        break;

    case Document::TYPE_DIPLOMA:

        $fileContent = new FileContentDiploma();
        $allowedExtensions = Yii::app()->params['filesPolicy']['extensionTypePdf'];
        break;

    case Document::TYPE_DRIVING_LICENCE:

        $fileContent = new FileContentDrivingLicence();
        $allowedExtensions = Yii::app()->params['filesPolicy']['extensionTypePdf'];
        break;

    case Document::TYPE_IDENTIFICATION:

        $fileContent = new FileContentIdentification();
        $allowedExtensions = Yii::app()->params['filesPolicy']['extensionTypePdf'];
        break;

    case Document::TYPE_PHOTO:

        $fileContent = new FileContentPhoto();
        $allowedExtensions = Yii::app()->params['filesPolicy']['extensionType'];
        break;
}

$rootFolder = Yii::getPathOfAlias('application'); //...protected

$relativeFolder = '/upload/carers/'; // . $carerId . '/' . FileContent::getDocumentTypeFolder($documentType) . '/';

$fullFolder = $rootFolder . $relativeFolder;

//if (!is_dir($fullFolder)) {
//
//    mkdir($fullFolder, null, true);
//}

$sizeLimit = Yii::app()->params['filesPolicy']['maxSize']; // maximum file size in bytes

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload($fullFolder);

$fullFileName = $fullFolder . $result['filename'];
$ext = $result['extension'];
$fileSize = filesize($fullFileName); //GETTING FILE SIZE
$fileName = $result['filename']; //GETTING FILE NAME

$content = file_get_contents($fullFileName);

if($ext == 'pdf'){
    
    //convert file to jpeg first
    //$im = new imagick($fullFileName);
    //$content = $im->setImageFormat('jpg');
}



$baseUrl = Yii::app()->baseUrl;

//$fullFileNameRelative = $baseUrl . '/protected' . $relativeFolder . $fileName;

//$fullFileName = $fullFolder . $fileName;

//$content = file_get_contents($fullFileName);

list($width, $height, $type, $attr) = getimagesize($fullFileName);

//$fileContent->name = $fileName;
$fileContent->type = 'application/octet-stream';
$fileContent->size = $fileSize;
//$fileContent->path = '';
$fileContent->width = $width;
$fileContent->height = $height;
$fileContent->content = $content;
$fileContent->extension = $ext;

$errors = $fileContent->validate();

if ($fileContent->save()) {

    //delete temp file
    unlink($fullFileName);
}
?>
