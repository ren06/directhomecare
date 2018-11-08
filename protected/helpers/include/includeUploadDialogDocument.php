<?php

include Yii::getPathOfAlias('webroot.protected.helpers.include') . '/includeUploadCommon.php';

$fileId = $fileContent->id;

Yii::app()->session['file'] = $fileId;

?>