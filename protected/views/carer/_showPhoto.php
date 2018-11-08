<?php
$uniqueId = uniqid();
$cropPhoto = null;
$photo = $carer->getPhoto();

if (isset($photo)) {
    $cropPhoto = $photo->getCropFile();
}

if (isset($cropPhoto)) {
    $this->renderPartial('_cropAndDelete', array('photo' => $photo));

    if ($photo->reject_reason != '') {
        echo '<p class="rc-error">' . $photo->reject_reason . '</p>';
    }

    //echo '<div id="photo_uploadButton" style="display:none">'; //careful with div
} else {

    echo '<div id="crop">';
    echo '<img class="rc-image-profile" style="margin-bottom:6px" src="' . Yii::app()->request->baseUrl . '/images/profile-blank.jpg"/>';
    echo '<div id="photo_uploadButton">';
    echo '</div>';

    $buttonText = Yii::t('texts', 'BUTTON_UPLOAD_PHOTO');
    $this->widget('application.extensions.EAjaxUpload.EAjaxUpload', array(
        'id' => 'photouploadWidget' . $uniqueId,
        'config' => array(
            'action' => Yii::app()->createUrl('carer/uploadImage/type/' . Document::TYPE_PHOTO),
            'allowedExtensions' => array("jpg", "jpeg", "png", "gif"),
            'sizeLimit' => 10 * 1024 * 1024, // maximum file size in bytes
            'minSizeLimit' => 0.001 * 1024 * 1024, // minimum file size in bytes
            'buttonText' => $buttonText,
            'onComplete' => "js:function(id, fileName, responseJSON){                       
                $.ajax({
                        url: '" . $this->createUrl('carer/showPhotoDialog') . "',
                        beforeSend :  function(){ },
                        type: 'post',      
                        data: {
                        documentId: responseJSON['documentId'],   
                        uniqueId: '" . $uniqueId . "'
                        },
                        success: function(data){
                                $('#photoDialog" . $uniqueId . "').html(data);
                            },                            
                        });                       
                    }",
        )
    ));

    echo '</div>';
}
?>
<div style="display:none">
    <div id="photoDialog<?php echo $uniqueId ?>">
    </div>
</div>