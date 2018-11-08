<?php
/* @var $this ClientProspectController */
/* @var $model ClientProspect */


$this->menu = array(
    array('label' => 'List ClientProspect', 'url' => array('index')),
    array('label' => 'Create ClientProspect', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#client-prospect-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Client Prospects</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_searchClientProspects', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'client-prospect-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'sessionID',
        'email_address_step1',
        'email_address_step2',
        array(
            'header' => 'Post code',
            'value' => function($data, $row) {
                return $data->getPostCode();
            },
            'type' => 'raw',
        ),
        array(
            'class' => 'CButtonColumn',
        ),
        'created',
    ),
));
?>