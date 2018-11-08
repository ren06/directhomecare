<style type="text/css">
    #mybookings, #money{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
if ($user == Constants::USER_CLIENT) {
    $pageTitle = 'HEADER_PAYMENT_INVOICE';
    $invoiceRef = BusinessLogic::getReference($missions[0]->missionPayment);
    $headerRef = 'HEADER_PAYMENT_REFERENCE';
    $paidBy = 'HEADER_INVOICE_PAID_BY_CARD';
} else {
    $pageTitle = 'HEADER_MISSION_INVOICE';
    $invoiceRef = BusinessLogic::getReference($missions[0]);
    $headerRef = 'HEADER_MISSION_REFERENCE';
    $paidBy = '';//'HEADER_INVOICE_AVAILABLE_TO_WITHDRAW';
}
?>

<?php
$this->pageTitle = Yii::t('texts', $pageTitle);
$this->pageSubTitle = 'You can print your invoice here';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        //include back button
        $this->renderPartial('/common/_backTo', array('scenario' => $scenario));
        echo Yii::t('texts', 'SEPARATOR_BUTTON');
        echo CHtml::button(Yii::t('texts', 'BUTTON_PRINT'), array('onClick' => 'window.print()', 'class' => 'button tiny'));
        ?>
        <h3 style="text-align:center"><?php echo Yii::t('texts', 'HEADER_INVOICE_BLOCK'); ?></h3>
        <hr />
        <table width="100%">
            <tr>
                <td width="50%">
                    <h3><?php echo Yii::t('texts', $headerRef) ?></h3>
                    <p>
                        <?php echo $invoiceRef; ?>
                    </p>
                </td>
                <td width="25%">
                    <h3><?php echo Yii::t('texts', 'HEADER_DATE'); ?></h3>
                    <p>
                        <?php echo Calendar::convert_DBDateTime_DisplayDateText($missions[0]->missionPayment->created, true); ?>
                    </p>
                </td>
                <td width="25%">
                    <h3><?php echo Yii::t('texts', 'HEADER_CLIENT') ?></h3>
                    <p>
                        <?php echo Client::loadModel($missions[0]->booking->id_client)->fullName; ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <h3><?php echo Yii::t('texts', 'HEADER_AGENT'); ?></h3>
                    <p>
                        <?php echo Yii::t('texts', 'NOTE_DIRECT_HOMECARE_ADDRESS'); ?>
                    </p>            
                </td>
                <td>
                    <h3><?php echo Yii::t('texts', 'HEADER_LOCATION'); ?></h3>
                    <p>
                        <?php echo $missions[0]->missionPayment->getLocationText() ?>
                    </p>
                </td>
                <td>
                    <h3><?php echo Yii::t('texts', 'HEADER_USERS'); ?></h3>
                    <?php echo $missions[0]->missionPayment->getServiceUsersText(); ?>
                </td>
            </tr>
        </table>
        <br>
        <?php
        $rawData = array();
        foreach ($missions as $mission) {
            $rawData = CMap::mergeArray($rawData, $mission->getInvoiceRawData($user));
        }
        $dataProvider = new CArrayDataProvider($rawData, array('keyField' => false,
            'pagination' => array('pageSize' => 100),
        ));

        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'myGrid',
            'dataProvider' => $dataProvider,
            'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
            'htmlOptions' => array('style' => 'padding:0'),
            'template' => '{items}',
            'rowCssClassExpression' => '"rc-row-stronggreen"',
            'columns' => array(
                array(
                    'name' => 'date',
                    'header' => Yii::t('texts', 'TABLES_HEADER_DATE'),
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array(),
                    'htmlOptions' => array('style' => 'width:220px;'),
                    'type' => 'raw'
                ),
                array(
                    'name' => 'service',
                    'header' => Yii::t('texts', 'TABLES_HEADER_SERVICE'),
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array(),
                    'htmlOptions' => array('style' => 'width:230px'),
                    'type' => 'raw'
                ),
                array(
                    'name' => 'provider',
                    'header' => Yii::t('texts', 'TABLES_HEADER_PROVIDER'),
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => ''),
                    'htmlOptions' => array('style' => 'width:180px'),
                    'type' => 'raw'
                ),
                array(
                    'name' => 'quantity',
                    'header' => Yii::t('texts', 'TABLES_HEADER_QUANTITY'),
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => ''),
                    'htmlOptions' => array('style' => 'width:180px'),
                    'type' => 'raw'
                ),
                array(
                    'name' => 'unitPrice',
                    'header' => Yii::t('texts', 'TABLES_HEADER_UNIT_PRICE'),
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => ''),
                    'htmlOptions' => array('style' => 'width:180px'),
                    'type' => 'raw'
                ),
                array(
                    'name' => 'total',
                    'header' => Yii::t('texts', 'TABLES_HEADER_PRICE'),
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => ''),
                    'htmlOptions' => array('style' => 'width:180px'),
                    'type' => 'raw'
                )
            )
                )
        );
        ?>
        <table>
            <tr>
                <td class="rc-invoice-smalltable-cell1">
                    <?php
                    if ($user == Constants::USER_CLIENT) {
                        echo Yii::t('texts', 'LABEL_TOTAL_PRICE') . '&#58;';
                        ?>
                    </td>
                    <td class="rc-invoice-smalltable-cell2">
                        <?php echo $missions[0]->missionPayment->getTotalPriceWithCancel(Constants::USER_CLIENT)->text; ?>
                    </td>
                <tr>
                    <td class="rc-invoice-smalltable-cell1">
                        <?php echo Yii::t('texts', 'TABLES_HEADER_PAID_VOUCHER') . '&#58;'; ?>   
                    </td>
                    <td class="rc-invoice-smalltable-cell2">
                        <?php
                        $transaction = $missions[0]->missionPayment->getPaymentTransaction();
                        echo $transaction->getPaidCredit()->text;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="rc-invoice-smalltable-cell1">
                        <b><?php echo Yii::t('texts', 'TABLES_HEADER_PAID_CASH') . '&#58;'; ?></b>
                    </td>
                    <td class="rc-invoice-smalltable-cell2">
                        <b><?php
                            $paidByCredit = $missions[0]->missionPayment->getPaymentTransaction()->getPaidCredit();

                            $pricePaid = $missions[0]->missionPayment->getTotalPriceWithCancel(Constants::USER_CLIENT)->substract($paidByCredit);
                            echo $pricePaid->text;
                        } else {
                            echo '<b>' . Yii::t('texts', 'LABEL_WAGE') . '&#58;' . '</b>';
                            ?>
                    </td>
                    <td class="rc-invoice-smalltable-cell2">
                        <b>
                            <?php
                            if ($mission->isCancelledByClient()) {
                                echo $missions[0]->calculateCancelMoneyAmountsCarer(true)->text;
                            } else {
                                echo $missions[0]->getTotalPrice(Constants::USER_CARER)->text;
                            }
                        }
                        ?>
                    </b>
                </td>
            </tr>
        </table>
        <p>
            <?php echo Yii::t('texts', 'NOTE_VAT_EXEMPTED'); ?>
        </p>
        <hr />
        <h3 style="text-align:center"><?php echo Yii::t('texts', $paidBy); ?></h3>
    </div>
</div>