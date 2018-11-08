<?php
$slots = $booking->getMissionSlots(2, 2);
$maxSlots = count($slots);
$i = 0;

foreach ($slots as $slot) {

    if (isset($slot->missionPayment)) {
        //$payInAdvanceHours = BusinessRules::getCronPaymentInAdvanceInHours();
        //$toBePaidOn = date('Y-m-d', strtotime("-$payInAdvanceHours hours", strtotime($slot->startDateTime)));

// HIDE INVOICE        
//        
//        $invoiceLinkHtml = CHtml::link(
//                        Yii::t('texts', 'BUTTON_INVOICE'), $this->createUrl('clientManageBookings/invoice', array(
//                            'id' => $slot->missionPayment->id,
//                            'scenario' => $scenario)), array(
//                    'class' => 'rc-linkbutton-small',
//                    'title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_INVOICE')
//        ));
//
//        $rawData = array(
//            array(
//                'column1' => '<b>' . Yii::t('texts', 'LABEL_VISITS') . '</b> (' . Yii::t('texts', 'LABEL_PAID_ON') . ' ' . Calendar::convert_DBDateTime_DisplayDateText($slot->missionPayment->created) . ')',
//                //'column1' => '<b>' . Yii::t('texts', 'LABEL_PAYMENT_OF') . '&#160;' . Calendar::convert_DBDateTime_DisplayDateText($slot->missionPayment->created) . '</b>' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_PAYMENT_OF')),
//                'column2' => $invoiceLinkHtml
//            )
//        );
//
//        $dataProvider = new CArrayDataProvider($rawData, array('keyField' => false));
//
//        $this->widget('zii.widgets.grid.CGridView', array(
//            'id' => 'myGrid',
//            'dataProvider' => $dataProvider,
//            'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
//            'htmlOptions' => array('style' => 'padding:0'),
//            'template' => '{items}',
//            'rowCssClassExpression' => '"rc-row-stronggreen"',
//            'columns' => array(
//                array(
//                    'name' => 'column1',
//                    'class' => 'CDataColumn',
//                    'headerHtmlOptions' => array('style' => 'display:none'),
//                    'htmlOptions' => array('style' => 'width:225px;text-align:left'),
//                    'type' => 'raw'
//                ),
//                array(
//                    'name' => 'column2',
//                    'class' => 'CDataColumn',
//                    'headerHtmlOptions' => array('style' => 'display:none'),
//                    'htmlOptions' => array('style' => 'text-align:left'),
//                    'type' => 'raw'
//                )
//        )));

        //figure out missions to display
        $missions = $slot->missionPayment->missions;
        $dataProvider = new CActiveDataProvider('Mission');

        $filteredMissions = array();

        foreach ($missions as $mission) {

            if (!$mission->isDiscardedByClient()) {
                $filteredMissions[] = $mission;
            }
        }

        $buttonsColumnVisible = true;

        $controller = $this;

        if (count($filteredMissions) > 0) {

            $dataProvider->setData($filteredMissions);

            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'id' => Tables::CARER_VISITS_GRID,
                'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
                'htmlOptions' => array('style' => 'padding:0'),
                'template' => '{items}{pager}',
                'rowCssClassExpression' => '$data->getColor($row)',
                'columns' => array(
                    array(
                        'class' => 'CDataColumn',
                        'headerHtmlOptions' => array('style' => 'display:none'),
                        'htmlOptions' => array('style' => 'width:80px;text-align:right;padding-top:1;padding-bottom:1;'),
                        'value' => function($data, $row) use ($controller) {

                    $carer = $data->getAssignedCarer();

                    if (isset($carer)) {
                        $photo = $carer->getPhotoForClient();
                        if (isset($photo)) {

                            return $photo->showImageForClient('rc-image-bookings');
                        } else {
                            return '<img alt="Photo" class="rc-image-bookings" src="' . Yii::app()->request->baseUrl . '/images/profile-blank.jpg"/>';
                        }
                    } else {
                        return '<img alt="Photo" class="rc-image-bookings" src="' . Yii::app()->request->baseUrl . '/images/profile-blank.jpg"/>';
                    }
                },
                        'type' => 'raw',
                    ),
                    array(
                        'class' => 'CDataColumn',
                        'headerHtmlOptions' => array('style' => 'display:none'),
                        'htmlOptions' => array('style' => 'width:250px'),
                        'value' => function($data, $row) {

                    $carer = $data->getAssignedCarer();
                    if (isset($carer)) {
                        return $carer->fullName;
                    } else {
                        return Yii::t('texts', 'STATUS_CARER_NOT_YET_ASSIGNED') . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_IT_TAKES_UP_TO_X_DAYS_TO_ASSIGN_CARER', array('{days}' => BusinessRules::getDelayToFindCarerInDays())));
                    }
                },
                        'type' => 'raw',
                    ),
                    array(
                        'class' => 'CDataColumn',
                        'headerHtmlOptions' => array('style' => 'display:none'),
                        //'htmlOptions' => array('style' => ''),
                        'value' => function($data, $row) {
                    if ($data->type == Mission::TYPE_LIVE_IN) {
                        return Calendar::convert_DBDateTime_DisplayDateTimeText($data->start_date_time, true, ' ', false) . '<br>' . Calendar::convert_DBDateTime_DisplayDateTimeText($data->end_date_time, true, ' ', false);
                    } else {
                        return Calendar::convert_DBDateTime_DisplayDateTimeText($data->start_date_time, true, ' ', false) . '&#160;&#45;&#160;' . Calendar::convert_DBDateTime_Time($data->end_date_time, false); //Calendar::calculate_Duration_DisplayAll($data->start_date_time, $data->end_date_time);
                    }
                },
                        'type' => 'raw',
                    ),
                    Tables::getCarerVisitStatus(false),
                    Tables::getCarerVisitButtonConfig(false, $scenario),
                ),
            ));
        } else {
            echo '<br><b>' . Yii::t('texts', 'TABLES_NO_VISITS') . '</b>';
        }
    } else {
        //check next slot is creatable
        $payInAdvanceHours = BusinessRules::getCronPaymentInAdvanceInHours();
        //overall rule: create mission if next slot's start date is between 7 and 14 days from now
        //if now and next slot start date < 14 days: ok to create, otherwise too soon
        //s$nextSlot->startDateTime) <= $payInAdvanceHours) {
        //$lastSunday = Calendar::getLastSunday(Calendar::convert_DBDateTime_DBDate($slot->startDateTime));
        $toBePaidOn = date('Y-m-d', strtotime("-$payInAdvanceHours hours", strtotime($slot->startDateTime)));
        $duration = $slot->duration;

        $startPeriod = Calendar::convert_DBDateTime_DisplayDateText($slot->startDateTime, true, ' ', true);
        $endPeriod = Calendar::convert_DBDateTime_DisplayDateText($slot->endDateTime, true, ' ', true);

        $virtualMissions = $booking->getMissions($slot->startDateTime, $slot->endDateTime);
        $rawData = array();
        $rawDataUpcomingMissions = array();

        $j = 0;
        $line = array();
        $numberMissions = count($virtualMissions);
        $k = 0;

        foreach ($virtualMissions as $virtualMission) {

            $j++;
            $k++;
            if ($virtualMission->type == Mission::TYPE_LIVE_IN) {
                $dates = Calendar::convert_DBDateTime_DisplayDateTimeText($virtualMission->start_date_time, true, ' ', false) . '<br>' . Calendar::convert_DBDateTime_DisplayDateTimeText($virtualMission->end_date_time, true, ' ', false);
            } else {
                $dates = Calendar::convert_DBDateTime_DisplayDateTimeText($virtualMission->start_date_time, true, ' ', false) . '&#160;&#45;&#160;' . Calendar::convert_DBDateTime_Time($virtualMission->end_date_time, false); //Calendar::calculate_Duration_DisplayAll($data->start_date_time, $data->end_date_time);
            }

            $line['column' . $j] = $dates;

            if ($j == 2 || $k == $numberMissions) {
                $rawDataUpcomingMissions[] = $line;
                $j = 0;
                $line = array();
            }
        }

        $gridId = "upcoming_$booking->id" . "_$i";

        $rawData = array(
            array(
                'column1' => '<b>' . Yii::t('texts', 'LABEL_UPCOMING_VISIT') . '</b> (' . Yii::t('texts', 'LABEL_TO_BE_PAID_ON') . ' ' . Calendar::convert_DBDateTime_DisplayDateText($toBePaidOn) . ')',
                //'column1' => '<b>' . Yii::t('texts', 'LABEL_UPCOMING_PAYMENT_OF') . CHtml::encode(' ') . Calendar::convert_DBDateTime_DisplayDateText($toBePaidOn) . '</b>' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_YOU_CAN_CANCEL_FUTURE_PAYMENTS')),
                //'column2' => Yii::t('texts', 'LABEL_VISITS_FROM') . '&#160;' . $startPeriod . '&#160;' . Yii::t('texts', 'LABEL_TO') . '&#160;' . $endPeriod . ': ' . $duration . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . $booking->getUnitPriceLabel(Constants::USER_CLIENT) . '&#160;&#160;&#45;&#160;&#160;' . $slot->toPay->text . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_YOU_CAN_CANCEL_FUTURE_PAYMENTS'))
                'column2' => $numberMissions . ' visits for ' . $duration . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . $booking->getUnitPriceLabel(Constants::USER_CLIENT) . '&#160;&#160;&#45;&#160;&#160;' . $slot->toPay->text . ' - ' .
                CHtml::link(Yii::t('texts', 'LABEL_SHOW_VISITS'), '', array('class' => 'rc-link', 'onclick' => "javascript:showHideUpcomingMissions('$gridId', this);")),
            ),
        );

        $dataProvider = new CArrayDataProvider($rawData, array('keyField' => false));

        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'myGrid',
            'dataProvider' => $dataProvider,
            'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
            'htmlOptions' => array('style' => 'padding:0'),
            'template' => '{items}',
            'rowCssClassExpression' => '"rc-row-stronggreen"',
            'columns' => array(
                array(
                    'name' => 'column1',
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => 'display:none'),
                    'htmlOptions' => array('style' => 'width:471px;text-align:left;height:30px;'),
                    'type' => 'raw'
                ),
                array(
                    'name' => 'column2',
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => 'display:none'),
                    'htmlOptions' => array('style' => 'text-align:left;height:30px;'),
                    'type' => 'raw'
                )
        )));


        $dataProvider = new CArrayDataProvider($rawDataUpcomingMissions, array('keyField' => false, 'pagination' => array('pageSize' => 100)));

        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'id' => $gridId,
            'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
            'htmlOptions' => array('style' => 'padding:0; display:none'),
            'template' => '{items}{pager}',
            //'rowCssClassExpression' => '$data->getColor($row)',
            'columns' => array(
                array(
                    'name' => 'column1',
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => 'display:none'),
                    'htmlOptions' => array('style' => 'width:250px;text-align:center;height:30px;'),
                    'type' => 'raw'
                ),
                array(
                    'name' => 'column2',
                    'class' => 'CDataColumn',
                    'headerHtmlOptions' => array('style' => 'display:none'),
                    'htmlOptions' => array('style' => 'width:250px;text-align:center;height:30px;'),
                    'type' => 'raw'
                ),
        )));
    }

    $i++;
}
?>