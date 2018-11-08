
<?php

$bookings = $client->bookings;

$rawData = array();

if (count($bookings) > 0) { //double check, DB should be correct
    echo '<h3>' . $client->fullName . ' (' . CHtml::link($client->id, array('clientAdmin/viewClient/id/' . $client->id)) . ')</h3>';

    foreach ($bookings as $booking) {

        if (!Calendar::dateIsBefore($booking->created, '2014-05-01 00:00:00')) {

            $reference = BusinessLogic::getReference($booking);

            $serviceUser = $booking->serviceUsers[0];
            $duration = $booking->getDurationInHours();

            $line = array(
                'column1' => Html::Link($reference),
                'column2' => $booking->getServiceLocation()->display("<br>"),
                'column3' => $booking->startDateTime . ' - ' . $duration . ' hours',
                'column4' => $booking->getTotalPrice(Constants::USER_CLIENT)->text,
                'column5' => $booking->getAssignedCarer(),
                'column7' => 'Completed',
                'column6' => $serviceUser->fullName,
                'column12' => $booking->created,
            );

            $rawData[] = $line;
        }
    }

    $dataProvider = new CArrayDataProvider($rawData, array('keyField' => 'column1'));

    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'myGrid',
        'dataProvider' => $dataProvider,
        // 'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
        //'htmlOptions' => array('style' => 'padding:0'),
        //'template' => '{items}',
        // 'rowCssClassExpression' => '"rc-row-stronggreen"',
        'columns' => array(
            array(
                'name' => 'column1',
                'class' => 'CDataColumn',
                'header' => 'Ref.',
                //'headerHtmlOptions' => array('style' => 'width:80px'),
                //'headerHtmlOptions' => array('style' => 'display:none'),
                //'htmlOptions' => array('style' => 'width:225px;text-align:right'),
                'type' => 'raw'
            ),
            array(
                'name' => 'column3',
                'class' => 'CDataColumn',
                'header' => 'Date and duration',
                'type' => 'raw'
            ),
            array(
                'name' => 'column2',
                'class' => 'CDataColumn',
                'header' => 'Location',
                'type' => 'raw'
            ),
            array(
                'name' => 'column6',
                'class' => 'CDataColumn',
                'header' => 'Service User',
                'type' => 'raw'
            ),
            array(
                'name' => 'column4',
                'class' => 'CDataColumn',
                'header' => 'Price',
                'type' => 'raw'
            ),
            array(
                'name' => 'column5',
                'class' => 'CDataColumn',
                'header' => 'Assigned Carer',
                'type' => 'raw'
            ),
            array(
                'name' => 'column7',
                'class' => 'CDataColumn',
                'header' => 'Status',
                'type' => 'raw'
            ),
            array(
                'name' => 'column12',
                'class' => 'CDataColumn',
                'header' => 'Created',
                'type' => 'raw'
            ),
            array(
                'class' => 'CButtonColumn', // can be omitted, default
                'header' => 'Details',
                'template' => '{view}',
                'headerHtmlOptions' => array('style' => 'width:100px'),
                'buttons' => array(
                    'view' => array(
                        'label' => Yii::t('texts', 'View'), //Text label of the button.
                        'url' => 'Yii::app()->createUrl("admin/Complaint/viewMessages")',
                        'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_DETAILS'), 'class' => 'rc-linkbutton-small'),
                        'visible' => 'true',
                    ),
                ),
            ),
        ))
    );
}
?>

