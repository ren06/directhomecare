<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'pager' => 'LinkPager',
    'ajaxUpdate' => false,
    'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
    'rowCssClassExpression' => '$data->getColor($row)',
    'id' => $id,
    'template' => '{items}{pager}',
    'columns' => $columns
));
?>