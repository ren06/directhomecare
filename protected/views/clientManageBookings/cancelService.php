<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CANCEL_VISIT') ?>

<h2 class="rc-h2red"><?php echo $this->pageTitle; ?></h2>

<div class="rc-container-60">
    <br />
    <?php $this->renderPartial('/common/_backTo', array('scenario' => $scenario)); ?>
    <h3><?php echo Yii::t('texts', 'HEADER_VISIT_REFERENCE') . '&#58;&#160;' . BusinessLogic::getReference($mission); ?></h3>
    <div class="rc-container-40">
        <p>
            <?php
            echo Yii::t('texts', 'NOTE_YOU_CAN_CANCEL_VISITS_BEFORE', array(
                '{hours}' => BusinessRules::getClientCancelServiceBeforeStartInHours(),
                '{clientCancelServiceShortNoticeMoneyLost}' => BusinessRules::getClientCancelServiceShortNoticeMoneyLostPercentage(),
                '{clientCancelServiceMediumNoticeMoneyLost}' => BusinessRules::getClientCancelServiceMediumNoticeMoneyLostPercentage(),
                //'{clientCancelServiceLongNoticeMoneyLost}' => BusinessRules::getClientCancelServiceLongNoticeMoneyLostPercentage(),
                '{clientCancelServiceShortNoticeMoneyRecovered}' => 100 - BusinessRules::getClientCancelServiceShortNoticeMoneyLostPercentage(),
                '{clientCancelServiceMediumNoticeMoneyRecovered}' => 100 - BusinessRules::getClientCancelServiceMediumNoticeMoneyLostPercentage(),
                '{clientCancelServiceLongNoticeMoneyRecovered}' => 100 - BusinessRules::getClientCancelServiceLongNoticeMoneyLostPercentage(),
            ));
            ?>
        </p>
    </div>
    <?php
    //$resultDays = $mission->calculateCancelMoneyDays(); //, $testStartDate, $testEndDate);
    //$cancelMoneyDays = $mission->calculateCancelMoneyDays();
    $cancelledType = '';
    $voucherCancelAmount = $mission->calculateCancelMoneyAmountsClient();
    $voucherPrice = $voucherCancelAmount['voucher']; //object Price, use ->text
    $paidToCarer = '';
    $paidToClient = '';

    //less than 7 days
    if ($voucherCancelAmount['notice'] == 'short') {
        $cancelledType = Yii::t('texts', 'TABLES_LESS_THAN_7_DAYS');
        $paidToCarer = BusinessRules::getClientCancelServiceShortNoticeMoneyLostPercentage() . '%';
        $paidToClient = 100 - $paidToCarer . '%';
    }//more than 7 days
    elseif ($voucherCancelAmount['notice'] == 'medium') {
        $cancelledType = Yii::t('texts', 'TABLES_MORE_THAN_7_DAYS');
        $paidToCarer = BusinessRules::getClientCancelServiceMediumNoticeMoneyLostPercentage() . '%';
        $paidToClient = 100 - $paidToCarer . '%';
    }//no carers: 100%
    elseif ($voucherCancelAmount['notice'] == 'carer') {
        $cancelledType = Yii::t('texts', 'STATUS_CARER_NOT_YET_ASSIGNED');
        $paidToCarer = BusinessRules::getClientCancelServiceLongNoticeMoneyLostPercentage() . '%';
        $paidToClient = 100 - $paidToCarer . '%';
    }
    $rawData = array(
        array(
            'column1' => $cancelledType,
            'column2' => $paidToCarer,
            'column3' => $paidToClient,
            'column4' => $voucherPrice->text
        )
    );

    $dataProvider = new CArrayDataProvider($rawData, array('keyField' => 'column1',));
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'myGrid',
        'dataProvider' => $dataProvider,
        'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
        'template' => '{items}{pager}',
        'columns' => array(
            array('name' => 'column1',
                'header' => Yii::t('texts', 'TABLES_HEADER_DAY_OF_CANCELLATION')),
            array('name' => 'column2',
                'header' => Yii::t('texts', 'TABLES_HEADER_PAID_TO_CARER')),
            array('name' => 'column3',
                'header' => Yii::t('texts', 'TABLES_HEADER_REFUND_VOUCHER')),
            array('name' => 'column4',
                'header' => Yii::t('texts', 'TABLES_HEADER_YOUR_VOUCHER'),
                'type' => 'raw')
        ),
    ));
    ?>
    <div class="rc-container-button">
        <?php
        echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL_VISIT'), array('onClick' => "js:showDialog()", 'class' => 'rc-button', 'title' => Yii::t('texts', 'ALT_CLICK_TO_CANCEL_THE_VISIT')));
        ?>
    </div>
    <div id="hook" style="display:none">
        <div id="dialog"></div>
    </div>
</div>

<script type="text/javascript">
    function showDialog() {

        var url = '<?php echo CHtml::normalizeUrl(array("clientManageBookings/showCancelServiceDialog")) ?>';

        $.ajax({
            success: function(html) {

                $('#dialog').html(html);
            },
            context: this,
            type: 'post',
            url: url,
            data: {id: <?php echo $mission->id ?>, scenario: '<?php echo $scenario ?>'},
            cache: false, dataType: 'html'
        });

    }

    function dialogYes(buttonId, actionURL, grid, missionId, returnURL) {
        //$('#' + buttonId).attr("disabled", true);
        $.ajax({
            beforeSend: function() {
            },
            success: function(html) {

                if (html === 'errorDate') {
                    alert('wrong date');
                }
                else {
                    window.location.replace(returnURL);
                }
            },
            context: this,
            type: 'post',
            url: actionURL,
            data: {id: missionId, scenario: '<?php echo $scenario ?>'},
            cache: false, dataType: 'html'
        });

    }
</script>