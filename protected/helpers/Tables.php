<?php

class Tables {

    const ASSIGNED_MISSIONS_GRID = 'assignedMissionsGrid';
    const SELECTED_MISSIONS_GRID = 'selectedMissionsGrid';
    const AWAITING_MISSIONS_GRID = 'awaitingMissionsGrid';
    const AVAILABLE_MISSIONS_GRID = 'availableMissionsGrid';
    const BOOKED_SERVICES_GRID = 'bookedServicesGrid';
    const CARER_VISITS_GRID = 'carerVisitGrid';

    /*
     * CONFIG
     */

    /**
     * Choose Mission
     * STATUS 0 and 1 and (UNAPPLIED and APPLIED)
     */
    public static function getCarerAvailableMissionsColumnConfig($carerActive) {

        $array = array(
            self::getRowNumberColumn(),
            //self::getStartDateColumn(),
            //self::getEndDateColumn(),
            self::getWhenColumn(),
            self::getLocationColumn(),
            self::getUserConditionColumn(),
            self::getRemunerationColumn(),
            self::getMissionTimeCreatedColumn(),
            self::getCarerAvailableMissionButtonsConfig($carerActive),
        );

        $showId = Yii::app()->params['test']['showIdTable'];

        if ($showId) {

            Util::array_insert($array, 'id', 0);
        }

        return $array;
    }

    /**
     * STATUS 2 SELECTED
     */
    public static function getCarerSelectedMissionsColumnConfig($scenario) {

        $array = array(
            //self::getStartDateColumn(),
            //self::getEndDateColumn(),
            self::getWhenColumn(),
            self::getLocationColumn(),
            //self::getServiceTypeColumn(),
            self::getUserConditionColumn(),
            self::getRemunerationColumn(),
            self::getConfirmSelectionTimeLeftColumn(),
            self::getCarerSelectedMissionButtonsConfig($scenario),
        );

        $showId = Yii::app()->params['test']['showIdTable'];

        if ($showId) {

            Util::array_insert($array, 'id', 0);
        }

        return $array;
    }

    /**
     *
     * STATUS 3 ASSIGNED
     */
    public static function getCarerAssignedMissionsColumnConfig($scenario) {

        $array = array(
            //self::getStartDateColumn(),
            //self::getEndDateColumn(),
            self::getWhenColumn(),
            self::getLocationColumn(),
            //self::getServiceTypeColumn(),
            self::getUserConditionColumn(),
            self::getRemunerationColumn(),
            self::getStatusColumn(),
            self::getCarerAssignedMissionButtonsConfig($scenario),
        );

        $showId = Yii::app()->params['test']['showIdTable'];

        if ($showId) {

            Util::array_insert($array, 'id', 0);
        }

        return $array;
    }

    /**
     * STATUS 1 APPLIED, NOT SELECTED 4, CONFIRM_SELECTION_LATE 5
     */
    public static function getCarerAwaitingMissionsColumnConfig() {

        $array = array(
            // self::getReferenceColumn(),
            //self::getStartDateColumn(),
            //self::getEndDateColumn(),
            self::getWhenColumn(),
            self::getLocationColumn(),
            //self::getServiceTypeColumn(),
            self::getUserConditionColumn(),
            self::getRemunerationColumn(),
            self::getStatusColumn(),
            self::getCarerAwaitingMissionButtonsConfig(),
        );

        $showId = Yii::app()->params['test']['showIdTable'];

        if ($showId) {

            Util::array_insert($array, 'id', 0);
        }

        return $array;
    }

    /**
     *
     * Completed missions but not credited
     */
    public static function getCarerCompletedMissionsColumnConfig($scenario) {

        $array = array(
           // self::getStartDateColumn(),
            //self::getEndDateColumn(),
            self::getWhenColumn(),
            self::getLocationColumn(),
            self::getUserConditionColumn(),
            self::getRemunerationColumn(),
            self::getDaysLeftColumn(),
            self::getCarerCompletedMissionsButtonsConfig($scenario),
        );

        $showId = Yii::app()->params['test']['showIdTable'];

        if ($showId) {

            Util::array_insert($array, 'id', 0);
        }

        return $array;
    }

    /**
     *
     * History missions
     */
    public static function getCarerHistoryMissionsColumnConfig($scenario) {

        $array = array(
            //self::getStartDateColumn(),
            //self::getEndDateColumn(),
            self::getWhenColumn(),
            self::getLocationColumn(),
            //self::getServiceTypeColumn(),
            self::getUserConditionColumn(),
            self::getStatusColumn(),
            self::getCarerHistoryMissionsButtonsConfig($scenario),
        );

        $showId = Yii::app()->params['test']['showIdTable'];

        if ($showId) {

            Util::array_insert($array, 'id', 0);
        }

        return $array;
    }

    /*
     * Columns configs
     */

    private static function getReferenceColumn() {

        return array(
            'class' => 'CDataColumn', // can be omitted, default
            'id' => 'reference',
            //'name' => 'id', TO DISABLE SORTING
            'header' => Yii::t('texts', 'TABLES_HEADER_REFERENCE'),
            'value' => function($data, $row) {

                return BusinessLogic::getReference($data);
            },
            'type' => 'raw',
        );
    }

    private static function getRowNumberColumn() {

        return array(
            'class' => 'CDataColumn', // can be omitted, default
            'id' => 'reference',
            'headerHtmlOptions' => array('style' => 'width:34px'),
            //'name' => 'id', TO DISABLE SORTING
            'header' => Yii::t('texts', 'TABLES_HEADER_MAP'),
            'value' => function($data, $row) {

                return $row + 1;
            },
            'type' => 'raw',
        );
    }

    private static function getWhenColumn() {

        return array(
            'header' => Yii::t('texts', 'When'),
            'htmlOptions' => array('style' => 'width:130px'),
            'class' => 'CDataColumn', // can be omitted, default
            'name' => 'start_date',
            'value' => function($data, $row) {
                return $data->getDateTimeDuration();
                // return Calendar::convert_DBDateTime_DisplayDateTimeText($data->start_date_time);
            },
            'type' => 'raw',
        );
    }

    private static function getStartDateColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_START'),
            'headerHtmlOptions' => array('style' => 'width:90px'),
            'class' => 'CDataColumn', // can be omitted, default
            //'name' => 'start_date', TO DISABLE SORTING
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->start_date_time, true, '&#32;', false);
            },
            'type' => 'raw',
        );
    }

    private static function getEndDateColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_END'),
            'headerHtmlOptions' => array('style' => 'width:90px'),
            'class' => 'CDataColumn', // can be omitted, default
            //'name' => 'end_date', TO DISABLE SORTING
            'value' => function($data, $row) {
                return Calendar::convert_DBDateTime_DisplayDateTimeText($data->end_date_time, true, '&#32;', false);
            },
            'type' => 'raw',
        );
    }

    private static function getLocationColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_LOCATION'),
            'headerHtmlOptions' => array('style' => 'width:85px'),
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                $serviceLocation = $data->serviceLocation;
                return $serviceLocation->displayShort();
            },
            'type' => 'raw',
        );
    }

    public static function getUserConditionColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_MISSION'),
            'class' => 'CDataColumn',
            'headerHtmlOptions' => array('style' => 'width:240px'),
            'htmlOptions' => array('style' => 'text-align:left;'),
            'value' => function($data, $row) {
                $value = $data->displayServiceTypeUsersConditionsHTML();
                $tooltip = $data->displayActivitiesTooltip();
                $value .= UIServices::renderTooltip('[<span class="rc-link-toolbox" style="color:#808080">' . Yii::t('texts', 'TOOLTIP_HELP_TO_PROVIDE'), $tooltip) . '</span>]';

                return $value;
            },
            'type' => 'raw',
        );
    }

    private static function getQuantityColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_QUANTITY'), //SIXE TO DO
            'class' => 'CDataColumn',
            'value' => function($data, $row) {
                return $data->getNumberDays();
            },
            'type' => 'raw',
        );
    }

    private static function getMissionTimeCreatedColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_TIME_LEFT_TO_APPLY'),
            //'headerHtmlOptions' => array('style' => 'width:80px'),
            'class' => 'CDataColumn',
            'value' => function($data, $row) {

                $hoursSinceCreation = Calendar::hoursBetween_DBDateTime($data->created, Calendar::today(Calendar::FORMAT_DBDATETIME));

                $hours = $businessRules = BusinessRules::getNewMissionAdvertisedTimeInHours();

                $timeLeftToApply = ( $hours - $hoursSinceCreation ) . Yii::t('texts', 'TABLES_HOURS');

                if ($timeLeftToApply < 0) {
                    return Yii::t('texts', 'LABEL_URGENT');
                } else {
                    return $timeLeftToApply;
                }
            },
            'type' => 'raw',
        );
    }

    private static function getConfirmSelectionTimeLeftColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_TIME_LEFT_TO_CONFIRM'),
            //'headerHtmlOptions' => array('style' => 'width:80px'),
            'class' => 'DataColumn',
            'value' => function($data, $row) {

                if ($data->isActive()) {

                    if (Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::CONFIRM_SELECTION_LATE) {
                        return $data->getCarerStatusLabel() . UIServices::renderHelpTooltip($data->getCarerStatusTooltip());
                    } else {

                        $hoursLeft = $data->getTimeLeftConfirm();

                        if ($hoursLeft > 0) {
                            return $hoursLeft . Yii::t('texts', 'TABLES_HOURS') . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_YOU_HAVE_BEEN_SELECTED'));
                            //return '<span class="qTipTooltip rc-link-toolbox" tooltip="' . Yii::t('texts', 'ALT_YOU_HAVE_BEEN_SELECTED') . '">' . $hoursLeft . Yii::t('texts', 'TABLES_HOURS') . '</span>';
                        } else {
                            return Yii::t('texts', 'LABEL_HURRY_UP_TO_CONFIRM') . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_SOMEONE_ELSE_WILL_BE_SELECTED'));
                            //return '<span class="qTipTooltip rc-link-toolbox" tooltip="' . Yii::t('texts', 'ALT_SOMEONE_ELESE_WILL_BE_SELECTED') . '">' . Yii::t('texts', 'LABEL_HURRY_UP_TO_CONFIRM') . '</span>';
                        }
                    }
                } else {

                    return $data->getStatusLabel() . UIServices::renderHelpTooltip($data->getStatusTooltip());

                    //return '<span class="rc-statusandlabel-red qTipTooltip rc-link-toolbox" tooltip="' . $data->getStatusTooltip() . '">' . $data->getStatusLabel() . '</span>';
                }
            },
            'htmlOptions' => function($data, $row) {
                return array();
            },
            'type' => 'raw',
        );
    }

    private static function getStatusColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_STATUS'),
            'class' => 'CDataColumn',
            //'headerHtmlOptions' => array('style' => 'width:80px'),
            'value' => function($data, $row) {

                $complaintResult = '';

                $textSpanClass = '';
                $tooltipValue = '';
                $textSpan = '';

                if ($data->isActive()) {

                    if (Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::ASSIGNED) {
                        $text = $data->getCompletionStatusLabel(Constants::USER_CARER);
                        $tooltipValue = $data->getCompletionStatusTooltip(Constants::USER_CARER);
                    } else {
                        $text = $data->getCarerStatusLabel();
                        $tooltipValue = $data->getCarerStatusTooltip(Constants::USER_CARER);
                    }

                    //Complaint extra text
                    $complaintStatus = $data->getComplaintStatus(Constants::USER_CARER);

                    if ($complaintStatus != '') {
                        $complaintResult .= '<br />' . $complaintStatus;
                    }
                } else { //Cancelled
                    $textSpanClass = 'rc-statusandlabel-red';
                    $text = $data->getStatusLabel();
                    $tooltipValue = $data->getStatusTooltip();
                }

                $textSpan = "<span class='$textSpanClass'>$text</span>";

                $tooltipSpan = UIServices::renderHelpTooltip($tooltipValue);

                return $textSpan . $tooltipSpan . $complaintResult;
            },
            'type' => 'raw',
        );
    }

    private static function getRemunerationColumn() {

        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_WAGE'),
            'headerHtmlOptions' => array('style' => 'width:80px'),
            'class' => 'CDataColumn',
            'value' => function($data, $row) {

//                if ($data->type == Constants::LIVE_IN) {
//                    $text = $data->getNumberDays() . Yii::t('texts', 'TABLES_DAYS') . '<br />' . $data->getOriginalTotalPrice(Constants::USER_CARER)->text;
//                } else {
//                    $text = $data->getNumberHours() . Yii::t('texts', 'TABLES_HOURS') . '<br />' . $data->getOriginalTotalPrice(Constants::USER_CARER)->text;
//                }

                $text = $data->getDurationText() . '<br />' . $data->getOriginalTotalPrice(Constants::USER_CARER)->text;

                if ($data->isActive()) {

                    return $text;
                } elseif ($data->status == Mission::CANCELLED_BY_CLIENT) {

                    $missionId = $data->id;

                    $transaction = CarerTransaction::getTransaction(Yii::app()->user->id, $missionId, CarerTransaction::CREDIT_CANCEL_CLIENT);

                    if ($transaction) {
                        return '<span class="rc-statusandlabel-red">' . Yii::t('texts', 'LABEL_INDEMNITY') . '&#58;&#32;' . $transaction->getPaidCredit()->text . '</span>';
                    } else {
                        return $text;
                    }
                }
            },
            'type' => 'raw',
        );
    }

    private static function getDaysLeftColumn() {


        return array(
            'header' => Yii::t('texts', 'TABLES_HEADER_TIME_LEFT_TO_BE_PAID'),
            // 'headerHtmlOptions' => array('style' => 'width:80px'),
            'htmlOptions' => function($data, $row) {

                return array();
            },
            'class' => 'DataColumn',
            'value' => function($data, $row) {

                if ($data->hasComplaint()) {

                    $result = '<span class="rc-statusandlabel-red">' . Yii::t('texts', 'STATUS_OPENED_COMPLAINT') . '</span>';
                    $result .= UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_THERE_IS_AN_OPENED_COMPLAINT'));

                    return $result;
                } else {

                    $days = $data->getTimeLeftToBePaid();
                    $tooltipText = Yii::t('texts', 'ALT_TIME_LEFT_TO_BE_PAID', array('{numberDays}' => BusinessRules::getDelayToGiveFeedbackInDays()));

                    $tooltipSpan = UIServices::renderHelpTooltip($tooltipText);

//                    $result = '<span class="rc-statusandlabel-red qTipTooltip rc-link-toolbox" tooltip="' .
//                            $tooltip . '">' .
//                            $days . '&#160;' . Yii::t('texts', 'LABEL_DAYS') . '</span>';
                    $text = $days . '&#160;' . Yii::t('texts', 'LABEL_DAYS');

                    return $text . $tooltipSpan;
                }
            },
            'type' => 'raw',
        );
    }

    /*
     * GROUP BUTTON CONFIGS
     */

    private static function getCarerAwaitingMissionButtonsConfig() {

        return array(
            'class' => 'ButtonColumn',
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:145px'),
            'template' => '{cancelApplied} {notSelected} {cancelledByClient}',
            'evaluateID' => true,
            'buttons' => array(
                'cancelApplied' => self::getCancelAppliedMyMissionsButtonConfig(),
                'notSelected' => self::getNotSelectedButtonConfig(self::AWAITING_MISSIONS_GRID),
                'cancelledByClient' => self::getMissionCancelledByClientButtonConfig(self::AWAITING_MISSIONS_GRID),
            ),
        );
    }

    private static function getCarerSelectedMissionButtonsConfig($scenario) {

        return array(
            'class' => 'ButtonColumn',
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:145px'),
            'template' => '{missionDetails} {confirmSelected} {cancelSelected} {cancelledByClient} {tooLate}',
            'evaluateID' => true,
            'buttons' => array(
                'confirmSelected' => self::getConfirmSelectedButtonConfig(),
                'cancelSelected' => self::getCancelSelectedButtonConfig(),
                'missionDetails' => self::getMissionDetailsButtonConfig($scenario),
                'cancelledByClient' => self::getMissionCancelledByClientButtonConfig(self::SELECTED_MISSIONS_GRID),
                'tooLate' => self::getTooLateToConfirmButtonConfig(),
            ),
        );
    }

    private static function getCarerAssignedMissionButtonsConfig($scenario) {

        return array(
            'class' => 'ButtonColumn',
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:145px'),
            'template' => '{missionDetails} {cancelAssigned} {discardCancelByClient} {complaint}',
            'evaluateID' => true,
            'buttons' => array(
                'cancelAssigned' => self::getCancelAssignedButtonConfig(),
                'missionDetails' => self::getMissionDetailsButtonConfig($scenario),
                'discardCancelByClient' => self::getMissionCancelledByClientButtonConfig(self::ASSIGNED_MISSIONS_GRID),
                'complaint' => self::getCarerComplaintButtonConfig($scenario),
            ),
        );
    }

    private static function getCarerCompletedMissionsButtonsConfig($scenario) {

        return array(
            'class' => 'ButtonColumn',
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:145px'),
            'template' => '{missionDetails} {complaint}',
            'buttons' => array(
                'missionDetails' => array(
                    'label' => Yii::t('texts', 'BUTTON_DETAILS'),
                    'url' => 'Yii::app()->createUrl("carer/missionDetails", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('class' => 'rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_DETAILS')),
                    'visible' => 'true',
                ),
                'complaint' => self::getCarerComplaintButtonConfig($scenario),
            ),
        );
    }

    private static function getCarerHistoryMissionsButtonsConfig($scenario) {

        return array(
            'class' => 'ButtonColumn',
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:145px'),
            'template' => '{missionDetails} {invoice} {complaint}',
            'buttons' => array(
                'missionDetails' => array(
                    'label' => Yii::t('texts', 'BUTTON_DETAILS'),
                    'url' => 'Yii::app()->createUrl("carer/missionDetails", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('class' => 'rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_DETAILS')),
                    'visible' => 'true',
                ),
                'invoice' => array(
                    'label' => Yii::t('texts', 'BUTTON_INVOICE'),
                    'url' => 'Yii::app()->createUrl("carer/missionInvoice", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_INVOICE'), 'class' => 'rc-linkbutton-white-small'),
                    'visible' => 'true',
                ),
                'complaint' => self::getCarerComplaintButtonConfig($scenario),
            ),
        );
    }

    private static function getCarerAvailableMissionButtonsConfig($carerActive) {

        return array(
            'class' => 'ButtonColumn', // can be omitted, default
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:145px'),
            'template' => '{applyMission} {cancelApplied}',
            'buttons' => array(
                'applyMission' => self::getApplyButtonConfig($carerActive),
                //'confirmMission' => self::getApplyButtonConfig2($carerActive),
                'cancelApplied' => self::getCancelAppliedChooseMissionButtonConfig(),
            //'cancelApplied' => self::getCancelAppliedMyMissionsButtonConfig(),
            //'cancelledByClient' => self::getMissionCancelledByClientButtonConfig(self::AWAITING_MISSIONS_GRID),
            ),
        );
    }

    /*
     * Button configs
     */

    // MISSION DETAILS
    private static function getMissionDetailsButtonConfig($scenario) {

        return array(
            'label' => Yii::t('texts', 'BUTTON_DETAILS'), //Text label of the button.
            'url' => 'Yii::app()->createUrl("carer/missionDetails", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
            //'imageUrl' => 'Yii::app()->createUrl("users/email", array("id"=>$data->id))',
            'options' => array('class' => 'rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_DETAILS')),
            'visible' => '(Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::SELECTED || Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::ASSIGNED) && $data->isActive()',
        );
    }

    private static function getCarerComplaintButtonConfig($scenario) {

        return array(
            'label' => Yii::t('texts', 'BUTTON_COMPLAINT'),
            'url' => 'Yii::app()->createUrl("carer/complaintPage", array("id"=>$data->id, "scenario" =>' . $scenario . '))',
            'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_COMPLAIN'), 'class' => 'rc-linkbutton-white-small'),
            'visible' => '$data->isStarted() || $data->isFinished()',
        );
    }

    //DISCARD cancelled by client mission
    private static function getMissionCancelledByClientButtonConfig($tableDiv) {

        $buttonName = 'discard' . $tableDiv;

        return array(
            'label' => Yii::t('texts', 'BUTTON_DISCARD'),
            'url' => 'Yii::app()->createUrl("carer/discardMission", array("id"=>$data->id, "tableDiv" => "' . $tableDiv . '"))',
            'options' => array('id' => '"' . $buttonName . '" . $row', 'tooltip' => Yii::t('texts', 'ALT_CLICK_TO_HIDE'), 'class' => 'rc-linkbutton-white-small qTipTooltip',
                'ajax' => array(
                    'type' => 'POST',
                    'url' => "js:$(this).attr('href')",
                    'success' => 'js:function(html) { $("#' . $tableDiv . '").html(html);}',
                ),
            ),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == (MissionCarers::SELECTED || MissionCarers::ASSIGNED)
                 && $data->status == Mission::CANCELLED_BY_CLIENT',
        );
    }

    // CONFIRM SELECTED
    private static function getConfirmSelectedButtonConfig() {

        $tableDiv = Tables::SELECTED_MISSIONS_GRID;
        $buttonName = 'confirmSelected' . $tableDiv;

        return array(
            'label' => Yii::t('texts', 'BUTTON_CONFIRM'), //Text label of the button.
            'url' => 'Yii::app()->createUrl("carer/confirmSelectedDialog", array("id"=>$data->id))',
            'options' => array('id' => '"' . $buttonName . '" . $row', 'class' => 'confirmSelectedButton rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_CONFIRM'),
                'ajax' => array('type' => 'POST',
                    'url' => "js:$(this).attr('href')", // + /buttonId/ + $(this).attr('id')",
                    'update' => '#dialog',
                ),
            ),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::SELECTED 
                 && $data->isActive()',
        );
    }

    //CANCEL SELECTED
    private static function getCancelSelectedButtonConfig() {

        $tableDiv = Tables::SELECTED_MISSIONS_GRID;
        $buttonName = 'cancelSelected' . $tableDiv;

        return array(
            'label' => Yii::t('texts', 'BUTTON_CANCEL_SELECTION'), //Text label of the button.
            'url' => 'Yii::app()->createUrl("carer/cancelSelectedDialog", array("id"=>$data->id))',
            'options' => array('id' => '"' . $buttonName . '" . $row', 'class' => 'cancelSelectedButton rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_CANCEL_SELECTION'),
                'ajax' => array('type' => 'POST',
                    'url' => "js:$(this).attr('href')", // + /buttonId/ + $(this).attr('id')",
                    'update' => '#dialog',
                ),
            ),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::SELECTED 
                && $data->isActive()',
        );
    }

    //CANCEL ASSIGNED
    private static function getCancelAssignedButtonConfig() {

        $tableDiv = Tables::ASSIGNED_MISSIONS_GRID;
        $buttonName = 'cancelAssign' . $tableDiv;

        return array(
            'label' => Yii::t('texts', 'BUTTON_CANCEL_MISSION'), //Text label of the button.
            'url' => 'Yii::app()->createUrl("carer/cancelAssignedDialog", array("id"=>$data->id))',
            'options' => array('id' => '"' . $buttonName . '" . $row', 'class' => 'cancelAssignedButton rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_CANCEL_MISSION'),
                'ajax' => array('type' => 'POST',
                    'url' => "js:$(this).attr('href')",
                    'update' => '#dialog',
                ),
            ),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::ASSIGNED 
                 && $data->isActive() && $data->isNotStarted()',
        );
    }

    // APPLY
    private static function getApplyButtonConfig($carerActive) {

        if ($carerActive) {

            return array(
                'label' => Yii::t('texts', 'BUTTON_APPLY'),
                'url' => 'Yii::app()->createUrl("carer/apply", array("id"=>$data->id))',
                'options' => array('class' => 'applyButton rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_APPLY')),
                'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::UNAPPLIED
              && $data->isActive() && !$data->isShortListed(Yii::app()->user->id)',
            );
        } else {

            return array(
                'label' => Yii::t('texts', 'BUTTON_COMPLETE_PROFILE'),
                'url' => 'Yii::app()->createUrl("carer/maintainProfile")',
                'options' => array('class' => 'rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_GO_TO_PROFILE')),
                'visible' => 'true',
            );
        }
    }

    //APPLY SHORT_LISTED
//    private static function getApplyButtonConfig2($carerActive) {
//
//        if ($carerActive) {
//
//            return array(
//                'label' => 'CONFIRM JOB',
//                'url' => 'Yii::app()->createUrl("carer/myMissions")',
//                'options' => array('class' => 'applyButton rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_APPLY2')),
//                'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::SELECTED
//              && $data->isActive()',
//            );
//        } else {
//
//            return array(
//                'label' => Yii::t('texts', 'BUTTON_COMPLETE_PROFILE'),
//                'url' => 'Yii::app()->createUrl("carer/maintainProfile")',
//                'options' => array('class' => 'rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_GO_TO_PROFILE')),
//                'visible' => 'true',
//            );
//        }
//    }

    //CANCEL APPLIED
    private static function getCancelAppliedChooseMissionButtonConfig() {

        return array(
            'label' => Yii::t('texts', 'BUTTON_CANCEL_APPLICATION'),
            'url' => 'Yii::app()->createUrl("carer/cancelAppliedChooseMissionDialog", array("id"=>$data->id))',
            'options' => array('class' => 'cancelAppliedButton rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_CANCEL_APPLICATION')),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::APPLIED
             && $data->isActive()',
        );
    }

    private static function getNotSelectedButtonConfig($tableDiv) {

        $tableDiv = Tables::AWAITING_MISSIONS_GRID;
        $buttonName = 'notSelected' . $tableDiv;

        return array(
            'label' => Yii::t('texts', 'BUTTON_DISCARD'),
            'url' => 'Yii::app()->createUrl("carer/discardMission", array("id"=>$data->id, "tableDiv" => "' . $tableDiv . '"))',
            'options' => array('id' => '"' . $buttonName . '" . $row', 'class' => 'rc-linkbutton-white-small qTipTooltip ', 'tooltip' => Yii::t('texts', 'ALT_CLICK_TO_HIDE'),
                'ajax' => array(
                    'type' => 'POST',
                    'url' => "js:$(this).attr('href')",
                    'success' => 'js:function(html) { $("#' . $tableDiv . '").html(html);}',
                ),
            ),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::NOT_SELECTED
                 && $data->isActive()',
        );
    }

    private static function getTooLateToConfirmButtonConfig() {

        $tableDiv = Tables::SELECTED_MISSIONS_GRID;
        $buttonName = 'tooLate' . $tableDiv;

        return array(
            'label' => Yii::t('texts', 'BUTTON_DISCARD'),
            'url' => 'Yii::app()->createUrl("carer/discardMission", array("id"=>$data->id, "tableDiv" => "' . Tables::SELECTED_MISSIONS_GRID . '"))',
            'options' => array('id' => '"' . $buttonName . '" . $row', 'class' => 'rc-linkbutton-white-small qTipTooltip ', 'tooltip' => Yii::t('texts', 'ALT_CLICK_TO_HIDE'),
                'ajax' => array(
                    'type' => 'POST',
                    'url' => "js:$(this).attr('href')",
                    'success' => 'js:function(html) { $("#' . Tables::SELECTED_MISSIONS_GRID . '").html(html);}',
                ),
            ),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::CONFIRM_SELECTION_LATE
                 && $data->isActive()',
        );
    }

    private static function getCancelAppliedMyMissionsButtonConfig() {

        $tableDiv = Tables::AWAITING_MISSIONS_GRID;
        $buttonName = 'cancelApply' . $tableDiv;

        return array(
            'label' => Yii::t('texts', 'BUTTON_CANCEL_APPLICATION'),
            'url' => 'Yii::app()->createUrl("carer/cancelAppliedDialog", array("id"=>$data->id))',
            'options' => array('id' => '"' . $buttonName . '" . $row', 'class' => 'cancelAppliedButton rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_CANCEL_APPLICATION'),
                'ajax' => array('type' => 'POST',
                    'url' => "js:$(this).attr('href')",
                    'update' => '#dialog',
                ),),
            'visible' => 'Mission::getCarerMissionStatus(Yii::app()->user->id, $data->id) == MissionCarers::APPLIED
             && $data->isActive()',
        );
    }

    public static function getCarerVisitStatus($headerVisible) {

        if ($headerVisible) {
            $style = '';
        } else {
            $style = 'display:none';
        }

        return array(
            'class' => 'CDataColumn',
            'headerHtmlOptions' => array('style' => $style),
            'header' => Yii::t('texts', 'TABLES_HEADER_STATUS'),
            'htmlOptions' => array('style' => 'width:120px'),
            'value' => function($data, $row) {
                if ($data->isActive()) {
                    $status = $data->getCompletionStatusLabel(Constants::USER_CLIENT);
                } else {
                    $status = $data->getStatusLabel();
                }

                $complaintStatus = $data->getComplaintStatus(Constants::USER_CLIENT);

                if ($complaintStatus != '') {
                    $status .= '<br>' . $complaintStatus;
                }

                return $status;
            },
            'type' => 'raw',
        );
    }

    public static function getCarerVisitButtonConfig($headerVisible, $scenario) {

        if ($headerVisible) {
            $style = '';
        } else {
            $style = 'display:none';
        }

        return array(
            'class' => 'CButtonColumn', // can be omitted, default
            'template' => '{details} {cancel} {abort} {feedback} {correct_times}',
            'visible' => true,
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => $style),
            'htmlOptions' => array('style' => 'width:270px;'),
            'buttons' => array( 
                'details' => array(
                    'label' => Yii::t('texts', 'BUTTON_DETAILS'),
                    'url' => 'Yii::app()->createUrl("clientManageBookings/details", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_DETAILS'), 'class' => 'rc-linkbutton-white-small'),
                    'visible' => 'true',
                ),
                'cancel' => array(
                    'label' => Yii::t('texts', 'BUTTON_CANCEL'),
                    'url' => 'Yii::app()->createUrl("clientManageBookings/cancelServicePage", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('class' => 'rc-linkbutton-white-small qTipTooltip', 'title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_CANCELLATION_PAGE_SERVICE', array('{hours}' => BusinessRules::getClientCancelServiceBeforeStartInHours())),
                    ),
                    'visible' => '$data->isCancelButtonVisible() && $data->isActive()',
                ),
                'cancelDisabled' => array(
                    'label' => Yii::t('texts', 'BUTTON_CANCEL'),
                    'url' => 'Yii::app()->createUrl("clientManageBookings/cancelServicePage", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('onClick' => 'return false;', 'class' => 'rc-linkbutton-white-small-disabled qTipTooltip', 'title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_CANCELLATION_PAGE_SERVICE', array('{hours}' => BusinessRules::getClientCancelServiceBeforeStartInHours())),
                    ),
                    'visible' => '!$data->isCancelButtonVisible() && $data->isActive()',
                ),
                'abort' => array(
                    'label' => Yii::t('texts', 'BUTTON_ABORT'),
                    'url' => 'Yii::app()->createUrl("clientManageBookings/abortServicePage", array("id"=>$data->id))',
                    'options' => array('class' => 'rc-linkbutton-white-small', 'title' => Yii::t('texts', 'ALT_CLICK_TO_SEE_ABORT_PAGE_SERVICE'),
                    ),
                    'visible' => 'false',
                ),
                'feedback' => array(
                    'label' => Yii::t('texts', 'BUTTON_COMPLAINT'),
                    'url' => 'Yii::app()->createUrl("clientManageBookings/feedbackPage", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_COMPLAIN'), 'class' => 'rc-linkbutton-white-small'),
                    'visible' => '$data->isFeebackButtonVisible()',
                ),
                'correct_times' => array(
                    'label' => 'AMEND',
                    'url' => 'Yii::app()->createUrl("clientManageBookings/adjustShift", array("id"=>$data->id, "scenario"=>' . $scenario . '))',
                    'options' => array('title' => Yii::t('texts', 'ALT_CLICK_TO_ADJUST_THE_HOURS_THE_CARER_WORKED'), 'class' => 'rc-linkbutton-white-small'),
                    'visible' => '$data->isCorrectTimeButtonVisible()',
                ),
//              ,'discard' => array(
//                  'label' => Yii::t('texts', 'BUTTON_DISCARD'),
//                  'url' => 'Yii::app()->createUrl("clientManageBookings/discardMission", array("id"=>$data->id))',
//                  'options' => array(
//                  'class' => 'rc-linkbutton-white-small qTipTooltip',
//                  'tooltip' => Yii::t('texts', 'ALT_CLICK_TO_HIDE'),
//                  'ajax' => array(
//                  'type' => 'POST',
//                  'url' => "js:$(this).attr('href')",
//                  'success' => 'js:function() { $("#' . Tables::CARER_VISITS_GRID . '").empty();}',
//                  ),
//                  ),
//                  'visible' => '$data->status == (Mission::CANCELLED_BY_CLIENT || Mission::CANCELLED_BY_ADMIN)',
//                  ),
            )
        );
    }

}

?>
