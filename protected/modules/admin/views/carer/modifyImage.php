<h3>Modify Image</h3>
<br />

<?php
$url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/approveCarerDocuments/carerId/' . $imageDocument->id_carer);
echo CHtml::link('<< Back to Carer documents', $url);
?>
<br />

<?php
//$html = $this->renderPartial('application.views.carer._showImage', array('fileContent' => $fileContent, 'width' => 450, 'class' => '', 'doc' => $imageDocument, 'visible' => true), true);
//echo $html;
//$this->renderPartial('/carer/_showDocumentAdmin', array('document' => $document, 'carer' => $carer), true, false);
//$url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/getImageAdmin', array('documentId' => $imageDocument->id, 'crop' => false));
//$width = 450;
//echo '<img src="' . $url . '" alt="Photo" width="' . $width . '" />';
?>

<br />
<br />
<?php
$url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/rotateImage/documentId/' . $imageDocument->id);
echo 'Rotate image: ' ;
echo CHtml::link('Left', $url . '/angle/90');
echo ' | ';
echo CHtml::link('Right', $url . '/angle/-90');
?>
<br />
<br />
<?php
$maxPictureWidth = 400;

$result = CarerDocument::publishTempImage($imageDocument->id);

$urlCrop = $result['url'];
$fileName = $result['fileName'];
$id = $imageDocument->id;

$photoWidth = $result['width'];
$photoHeight = $result['height'];

$dialogId = 'toto';

$ratio = $photoHeight / $photoWidth;

//if ($photoWidth > $maxPictureWidth) {

$photoWidth = $maxPictureWidth;
$photoHeight = $photoWidth * $ratio;
//}

$dialogHeight = $photoHeight + 220;
$dialogWidth = $photoWidth + 32;

$this->widget('ext.jcrop.EJcrop', array(
    //
    // Image URL
    'url' => $urlCrop, //$this->createAbsoluteUrl('/path/to/full/image.jpg'),
    //
    // ALT text for the image
    'alt' => 'Crop This Image',
    //
    // options for the IMG element
    'htmlOptions' => array('id' => 'imageId', 'width' => $maxPictureWidth, 'height' => $photoHeight),
    //
    // Jcrop options (see Jcrop documentation)
    'options' => array(
        'minSize' => array(40, 50),
        'allowSelect' => false,
        'aspectRatio' => 0.8,
        'onRelease' => "js:function() {ejcrop_cancelCrop(this);}",
    ),
    // if this array is empty, buttons will not be added
    'buttons' => array(
        'start' => array(
            'label' => Yii::t('texts', 'BUTTON_ADJUST_PHOTO'),
            'htmlOptions' => array(
                'style' => 'visibility:visible;', //by RC, if take off the button it does not work, so hide it.
                'class' => 'rc-button',
            )
        ),
        'crop' => array(
            'label' => Yii::t('texts', 'BUTTON_SAVE'),
            'htmlOptions' => array(
                'class' => 'rc-button',
            )
        ),
        'cancel' => array(
            'label' => Yii::t('texts', 'BUTTON_RESET_SELECTION'),
            'htmlOptions' => array(
                'class' => 'rc-button',
            )
        )
    ),
    // URL to send request to (unused if no buttons)
    'ajaxUrl' => Yii::app()->createAbsoluteUrl('admin/carerAdmin/handleCrop'),
    'ajaxSuccess' => 'js:function(html){ $("#crop").html(html);}',
    //'success' => 'js:function(response){ alert("success"); }',
    //
    // Additional parameters to send to the AJAX call (unused if no buttons)
    'ajaxParams' => array('id' => $id, 'fileName' => $fileName),
));
?>
<?php
echo CHtml::button('Re-crop', array('onclick'=> "js:location.reload();"));
?>
 
<br />
Crop result
<br />
<div id="crop">
    <?php
    $url = Yii::app()->createAbsoluteUrl('admin/carerAdmin/getImageAdmin', array('documentId' => $imageDocument->id, 'crop' => true));
    echo '<img id="photo" src="' . $url . '" alt="Photo" width="80" />';
    ?>
</div>
