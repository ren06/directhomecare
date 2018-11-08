<?php

$path = Yii::getPathOfAlias('webroot.protected.helpers.include');
$file = $path . '/includeUploadDocument.php';
include $file;

Yii::app()->clientScript->scriptMap['jquery.js'] = false;

$entity = FileContent::getDocumentTypeFolder($documentType);

$output = $carerDocument->showImage();
$output2 = $this->renderPartial('_showDocumentStatus', array('carerDocument' => $carerDocument, 'entity' => $entity, 'type' => $documentType), true, false);

//$output = str_replace('"', "'", $output);
$html = htmlentities($html);
////var decoded = $('<div/>').html(html).text(); //JS
//IE8 does not support <script> inside the json data, so it is broke down and then the script tags are added in the AJAX success
$delimiter = '<script type="text/javascript">';

$pieces = explode($delimiter, $output2);

$result['html'] = $output;
$result['html2'] = $pieces[0];

$javascript = rtrim($pieces[1], "</script>");

$result['javascript'] = $javascript;
$jsonDecode = json_encode($result);

$return = $jsonDecode;

echo $return;
?>