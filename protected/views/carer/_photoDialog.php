<?php
$divId = 'photo';
$title = Yii::t('texts', 'HEADER_ADD_A_PHOTO');

$maxPictureWidth = 400;

$dialogId = $divId . "Dialog" . $uniqueId;

$result = CarerDocument::publishTempImage($photo->id);

$url = $result['url'];
$fileName = $result['fileName'];
$id = $photo->id;

$photoWidth = $result['width'];
$photoHeight = $result['height'];

$ratio = $photoHeight / $photoWidth;

//if ($photoWidth > $maxPictureWidth) {

$photoWidth = $maxPictureWidth;
$photoHeight = $photoWidth * $ratio;
//}

$dialogHeight = $photoHeight + 220;
$dialogWidth = $photoWidth + 32;

$closeUrl = $this->createUrl('carer/' . Yii::app()->controller->action->id);
?>

<?php
//UI start
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => $dialogId,
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'title' => $title,
        'autoOpen' => true,
        'resizable' => false,
        'modal' => true,
        'draggable' => false,
        'width' => $dialogWidth,
        // 'height' => $dialogHeight,
        'close' => $closeUrl,
    ),
));

echo '<div class="rc-container-photosample">';
echo '<p>' . Yii::t('texts', 'NOTE_CREATE_YOUR_PORTRAIT') . '</p>';
echo '<img class="rc-image-photosample" width="48" height="60" alt="Photo sample" src="' . Yii::app()->request->baseUrl . '/images/profile-blank.jpg' . '"/></div>';

$this->widget('ext.jcrop.EJcrop', array(
    //
    // Image URL
    'url' => $url, //$this->createAbsoluteUrl('/path/to/full/image.jpg'),
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
                'style' => 'visibility:hidden;', //by RC, if take off the button it does not work, so hide it.
                'class' => 'rc-button',
            )
        ),
        'crop' => array(
            'label' => Yii::t('texts', 'BUTTON_SAVE'),
            'htmlOptions' => array(              
                'class' => 'rc-button',
                'style' => 'margin-right:8px;',
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
    'ajaxUrl' => Yii::app()->createAbsoluteUrl('carer/handleCrop'),
    'ajaxSuccess' => 'js:function(html){ 
        $("#' . $dialogId . '").dialog("close");
        if(html != "error_file_does_not_exist"){
            $("#crop").html(html);
        }            
    }',
    //'success' => 'js:function(response){ alert("success"); }',
    //
    // Additional parameters to send to the AJAX call (unused if no buttons)
    'ajaxParams' => array('id' => $id),
));

Yii::app()->session['fileName'] = $fileName;

$id = $photo->id;
?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<script type="text/javascript">
    //    $('body').on("click", "start_imageId", function() {
    //
    //        alert('toto');
    //    });


    $(document).ready(function() {
        //alert('ready');
        //init();
    });

</script>