<div>
    <p>
        <a href="javascript:;" id='cropTrigger'>crop image</a>
        <a href="javascript:;" onclick="cropZoom2.restore();">restore image</a>
    </p>
    <div id="crop_container">
    </div>
    <?php
    $this->widget('ext.cropzoom.JCropZoom', array(
        'id' => 'cropZoom',
        'options' => array(
            'width' => '400',
            'height' => '500',
            'enableRotation' => false,
            'enableZoom' => true,
            'zoomSteps' => 10,
        ),
        'callbackUrl' => $this->createUrl('handleCropZoom'),
        'containerId' => 'crop_container',
        'cropTriggerId' => 'cropTrigger',
        'onServerHandled' => 'js:function(response){ $("#result").html(response); }',
        'selector' => array(
            'centered' => true,
            'borderColor' => 'blue',
            'borderColorHover' => 'yellow',
            'w' => 100,
            'h' => 125,
            'animated' => false,
            'showPositionsOnDrag' => false,
            'showDimetionsOnDrag' => false,
            'aspectRatio' => true,
            'startWithOverlay' => true,
        ),
        'image' => array(
            'source' => $url,
            'useStartZoomAsMinZoom' => true,
            'width' => 400,
            'height' => 500,
            'minZoom' => 50,
            'maxZoom' => 200,
            'startZoom' => 100,
            'snapToContainer' => true,
        )
    ));
    ?>
    <?php //echo $this->renderPartial('/carer/_showImage', array('id' => 45, 'width' => 200)); ?>
</div>
<div id="result" >
</div>