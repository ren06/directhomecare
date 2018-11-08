
<h2>Carers withdrawals</h2>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'withdrawals-grid',
    'dataProvider' => $dataProvider,
    //'filter' => $filter,
    'columns' => array(
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->id;
            },
            'headerHtmlOptions' => array('style' => 'width:40px'),
            'header' => 'Id',
            'name' => 'id',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->carerTransaction->id_carer;
            },
            'headerHtmlOptions' => array('style' => 'width:50px'),
            'header' => 'Carer Id',
            'name' => 'id_carer',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $carer = Carer::loadModel($data->carerTransaction->id_carer);
                return $carer->fullName;
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Name',
            'name' => 'name',
        ),
        'id_carer_transaction',
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateText($data->created);
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Request date',
            'name' => 'date',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $price = new Price($data->carerTransaction->credit_balance, 'GBP');
                return '(' . $price->text . ')';
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Balance',
            'name' => 'balance',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $price = new Price($data->carerTransaction->withdraw, 'GBP');
                return '<b>' . $price->text . '</b>';
            },
            'headerHtmlOptions' => array('style' => 'width:50px'),
            'header' => 'Amount',
            'name' => 'amount',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $carer = Carer::loadModel($data->carerTransaction->id_carer);
                return '<b>' . $carer->account_number . ' ' . $carer->sort_code . '</b>';
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Bank Account',
            'name' => 'bank_account',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                //if ($data->status == CarerWithdrawal::STATUS_COMPLETED {

                if ($data->payment_date == '0000-00-00 00:00:00') {
                    return 'No payment date';
                } else {
                    return Calendar::convert_DBDateTime_DisplayDateTimeText($data->payment_date);
                }
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Payment date',
            'name' => 'date',
            'type' => 'raw',
        ),
        'bank_reference',
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->getStatusLabel();
            },
            'headerHtmlOptions' => array('style' => 'width:100px'),
            'header' => 'Status',
            'name' => 'status_label',
            'type' => 'raw',
        ),
        array(
            'class' => 'ButtonColumn',
            'visible' => true,
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:144px'),
            'template' => '{setCompleted}{setFailed}',
            'evaluateID' => true,
            'buttons' => array(
                'setFailed' => array(
                    'label' => 'Set to failed',
                    'url' => 'Yii::app()->createUrl("/admin/carerAdmin/setBankTransferFailed", array("withdrawalId" => $data->id))',
                    'options' => array('class' => 'applyButton rc-linkbutton-white-small'),
                    'visible' => '$data->status == CarerWithdrawal::STATUS_COMPLETED',
                ),
                'setCompleted' => array(
                    'label' => 'Set to completed',
                    'url' => 'Yii::app()->createUrl("/admin/carerAdmin/showUpdateWithdrawalDetailsDialog", array("withdrawalId" => $data->id))',
                    'options' => array('id' => '"updateDetails" . $row', 'class' => 'cancelAppliedButton rc-linkbutton-white-small',
                        'ajax' => array('type' => 'POST',
                            'url' => "js:$(this).attr('href')",
                            'update' => '#dialog',
                        ),),
                    'visible' => '$data->status == CarerWithdrawal::STATUS_NEW',
                ),
            ),
        ),
    ),
));
?>

<div id="hook" style="display:none">
    <div id="dialog">
    </div>
</div>