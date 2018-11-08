<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = 'Payments & invoices';
$this->pageSubTitle = 'Print your invoices if you need to be refunded';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myBookingsMenu', array('selectedMenu' => 'transactionsHistory')); ?>
    </div>
</div>
<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'id' => 'transactions',
            'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
            'template' => '{items}{pager}',
            'columns' => array(
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    'id' => 'reference',
                    'headerHtmlOptions' => array('style' => 'width:60px'),
                    //'name' => 'id', //by RC to stop sorting
                    'header' => Yii::t('texts', 'TABLES_HEADER_TRANSACTION_REFERENCE'),
                    'value' => function($data, $row) {
                        return BusinessLogic::getReference($data);
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    'id' => 'created',
                    'headerHtmlOptions' => array('style' => 'width:60px'),
                    //'name' => 'created', //by RC to stop sorting
                    'header' => Yii::t('texts', 'TABLES_HEADER_TRANSACTION_DATE'),
                    'value' => function($data, $row) {
                        return Calendar::convert_DBDateTime_DisplayDateText($data->created);
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    //'headerHtmlOptions' => array('style' => 'width:200px'),
                    'htmlOptions' => array('style' => 'text-align:left'),
                    //'name' => 'created', //by RC to stop sorting
                    'header' => Yii::t('texts', 'TABLES_HEADER_SERVICE'),
                    'value' => function($data, $row) {
                        return $data->displayTransactionText();
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    'headerHtmlOptions' => array('style' => 'width:60px'),
                    //'name' => 'created', //by RC to stop sorting
                    'header' => Yii::t('texts', 'TABLES_HEADER_PAID_CASH'),
                    'value' => function($data, $row) {
                        $price = new Price($data->paid_card, $data->currency);
                        if ($price->amount == null) {
                            return '';
                        } else {
                            return $price->text;
                        }
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    'headerHtmlOptions' => array('style' => 'width:60px'),
                    //'name' => 'created', //by RC to stop sorting
                    'header' => Yii::t('texts', 'TABLES_HEADER_REFUND_CASH'),
                    'value' => function($data, $row) {
                        $price = new Price($data->refund_card, $data->currency);
                        if ($price->amount == null) {
                            return '';
                        } else {
                            return $price->text;
                        }
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    'headerHtmlOptions' => array('style' => 'width:60px'),
                    'header' => Yii::t('texts', 'TABLES_HEADER_PAID_VOUCHER'),
                    'value' => function($data, $row) {
                        $price = new Price($data->paid_voucher, $data->currency);
                        if ($price->amount == null) {
                            return '';
                        } else {
                            return $price->text;
                        }
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    'headerHtmlOptions' => array('style' => 'width:60px'),
                    //'name' => 'created', //by RC to stop sorting
                    'header' => Yii::t('texts', 'TABLES_HEADER_REFUND_VOUCHER'),
                    'value' => function($data, $row) {
                        $price = new Price($data->refund_voucher, $data->currency);
                        if ($price->amount == null) {
                            return '';
                        } else {
                            return $price->text;
                        }
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CDataColumn', // can be omitted, default
                    'headerHtmlOptions' => array('style' => 'width:60px'),
                    //'name' => 'created', //by RC to stop sorting
                    'header' => Yii::t('texts', 'TABLES_HEADER_BALANCE_VOUCHER'),
                    'value' => function($data, $row) {
                        $price = new Price($data->voucher_balance, $data->currency);

                        return $price->text;
                    },
                    'type' => 'raw',
                ),
                array(
                    'class' => 'CButtonColumn', // can be omitted, default
                    'headerHtmlOptions' => array('style' => 'width:100px'),
                    'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
                    'template' => '{invoice}',
                    'visible' => true,
                    'buttons' => array(
                        'invoice' => array(
                            'label' => Yii::t('texts', 'BUTTON_INVOICE'), //Text label of the button.
                            'url' => 'Yii::app()->createUrl("clientManageBookings/invoice", array("id"=>$data->missionPayment->id, "scenario" => ' . $scenario . '))',
                            'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_INVOICE'), 'class' => 'rc-linkbutton-white-small'),
                            'visible' => 'isset($data->missionPayment)',
                        ),
                    ),
                ),
            ),
        ));
        ?>
    </div>
</div>