<?php ?>

<h1>Assign Carers Shifts</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'carer-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id_mission',
        'id_applying_carer',
        'status',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>

