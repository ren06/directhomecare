
<?php
//to use jQuery.yii.submitForm
//Yii::app()->getClientScript()->registerCoreScript('yii');
?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'title' => Yii::t('texts','HEADER_SIGN_IN'),
        'autoOpen' => true,
        'resizable' => false,
        'modal' => true,
        'draggable' => false,
        'width' => '500',
        // 'height' => '240'
    ),
));
?>

<?php $this->renderPartial('_userLogin', array('model' => $model, 'dialog' => true)); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>