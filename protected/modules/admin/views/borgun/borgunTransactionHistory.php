<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'myGrid',
    'dataProvider' => $dataProvider,
    //'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
    'template' => '{items}{pager}',
    'columns' => array(
        array(
            'class' => 'CDataColumn',
            'value' => '$data["TransactionType"]',
            'header' => 'Trn type',
            'name' => 'TransactionType',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["BatchNumber"]',
            'header' => 'Batch',
            'name' => 'BatchNumber',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["TransactionNumber"]',
            'header' => 'Batch trn num',
            'name' => 'TransactionNumber',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["FormattedTransactionDate"]',
            'header' => 'Date',
            'name' => 'Date',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["TransactionDate"]',
            'header' => 'Date Borgun format',
            'name' => 'TransactionDate',
            'type' => 'raw',
        ),
        'PAN',
        'RRN',
        array(
            'class' => 'CDataColumn',
            'value' => '$data["ActionCode"]',
            'header' => 'Action code',
            'name' => 'ActionCode',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["AuthorizationCode"]',
            'header' => 'Auth code',
            'name' => 'AuthorizationCode',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["Amount"]',
            'header' => 'Amount',
            'name' => 'Amount',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["Voided"]',
            'header' => 'Voided',
            'name' => 'Voided',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data["Status"]',
            'header' => 'Status',
            'name' => 'Status',
            'type' => 'raw',
        ),
    )
));
?>
