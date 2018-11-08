<?php

$this->widget('ext.jcrop.EJcrop', array(
    //
    // Image URL
    'url' => $url,//$this->createAbsoluteUrl('/path/to/full/image.jpg'),
    //
    // ALT text for the image
    'alt' => 'Crop This Image',
    //
    // options for the IMG element
    'htmlOptions' => array('id' => 'imageId', 'width' => 400),
    //
    // Jcrop options (see Jcrop documentation)
    'options' => array(
        'minSize' => array(80, 100),
        'aspectRatio' => 0.8,
        'onRelease' => "js:function() {ejcrop_cancelCrop(this);}",
    ),
    // if this array is empty, buttons will not be added
    'buttons' => array(
        'start' => array(
            'label' => Yii::t('promoter', 'Adjust portrait'),
            'htmlOptions' => array(
                'class' => 'myClass',
                'style' => 'color:red;' // make sure style ends with « ; »
            )
        ),
        'crop' => array(
            'label' => Yii::t('promoter', 'Select'),
        ),
        'cancel' => array(
            'label' => Yii::t('promoter', 'Cancel')
        )
    ),
    // URL to send request to (unused if no buttons)
    'ajaxUrl' => Yii::app()->createAbsoluteUrl('test/handleCrop'),
    'ajaxSuccess' => 'js:function(html){$("#crop").html(html);}',

    // Additional parameters to send to the AJAX call (unused if no buttons)
    'ajaxParams' => array('fileName' => $fileName, 'id' => $id),
));
?>

<div id="crop"></div>
    
