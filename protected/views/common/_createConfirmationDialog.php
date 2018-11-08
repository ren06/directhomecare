<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'confirmationDialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'title' => $dialogConfig->title,
        'autoOpen' => true,
        'resizable' => false,
        'draggable' => false,
        'modal' => true,
        'width' => $dialogConfig->width,
    //'min-height' => '92',
    //'height' => '92'
    //'height' => $dialogConfig->height,
    ),
));

$uniqueId = uniqid();
?>
<p>
    <?php
    echo $dialogConfig->text;
    ?>
</p>
<div class="rc-container-button">
    <div class="buttons">
        <?php
        foreach ($dialogConfig->buttonsHTML as $button) {
            ?>
            <?php echo $button; ?>
            <?php
        }
        ?>
    </div>  
    <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
</div>
<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
