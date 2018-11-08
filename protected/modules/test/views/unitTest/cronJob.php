<?php

echo '<br>Taken: <br>';
echo 'Today: ' . Calendar::convert_DBDateTime_DisplayDateText(Calendar::today(Calendar::FORMAT_DBDATE));
echo '<p>';

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'id',
            'class' => 'CDataColumn',
            'header' => 'Id',
            'type' => 'raw',
        ),
        array(
            'name' => 'dates',
            'class' => 'CDataColumn',
            'header' => 'Booking dates',
            'type' => 'raw',
        ),
        array(
            'name' => 'lastMissionDates',
            'class' => 'CDataColumn',
            'header' => 'Last Mission Dates',
            'type' => 'raw',
        ),
        array(
            'name' => 'nextSlot',
            'class' => 'CDataColumn',
            'header' => 'Next Slot',
            'type' => 'raw',
        ),
    ),
));


?>
