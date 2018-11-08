
<?php
/* @var $this LoginHistoryCarerController */
/* @var $model LoginHistoryCarer */


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $('#login-history-carer-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Manage Login History Carers</h1>

<p>
    You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
    or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('/carer/_searchLoginHistory', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'login-history-carer-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'header' => 'Carer',
            'value' => function($data, $row) {
                return $data->carer->fullName . ' (ID:' . $data->id . ') . Created on ' . Calendar::convert_DBDateTime_DisplayDateTimeText($data->carer->created, true, '&#32;', false);
            },
            'type' => 'raw',
        ),
        array(
            'header' => 'Login date',
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->login_date_time, true, '&#32;', false);
            },
            'type' => 'raw',
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
