<?php

class BookingHourly extends Booking {

//    const SUBTYPE_ONEDAY = 1;
//    const SUBTYPE_FOUREENDAYS = 2;
//    const SUBTYPE_REGULARLY = 3;

    static function model($className = __CLASS__) {
        return parent::model($className);
    }

    private $dayForms = array();

    /**
     * Set selected days, only used for user input
     * 
     * @param type $days array of BookingHourlyDayForm
     */
    public function setDayForms($days) {
        $this->dayForms = $days;
    }

    public function getDayForms() {
        return $this->dayForms;
    }

    /**
     * To be called before booking creation the first time
     */
    public function adjustEndDate() {

        //adjust end date
        if (!$this->recurring) {

            $dayForms = $this->dayForms;

            $selectedDaysOfWeek = array();

            //buffer available days
            foreach ($dayForms as $dayForm) {
                if ($dayForm->selected) {
                    $selectedDaysOfWeek[] = $dayForm->dayWeek;
                }
            }

            $dayWeekInt = Calendar::getDayOfWeekNumber($this->end_date_time);

            while (!in_array($dayWeekInt, $selectedDaysOfWeek)) {
                $newEndDate = Calendar::getPreviousDay($this->end_date_time);
                $this->end_date_time = $newEndDate;
                $dayWeekInt = Calendar::getDayOfWeekNumber($newEndDate);
            }
        }
    }

    function defaultScope() {
        $type = self::TYPE_HOURLY;
        return array(
            'condition' => "type='$type'",
        );
    }

    public function afterConstruct() {
        parent::afterConstruct();

        $this->type = self::TYPE_HOURLY;
    }

    public function relations() {

        $parentRelations = parent::relations();

        $newRelations = array(
            'bookingHourlyDays' => array(self::HAS_MANY, 'BookingHourlyDay', 'id_booking'),
        );

        return CMap::mergeArray($parentRelations, $newRelations);
    }

    public function rules() {

        $parentRules = parent::rules();

        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $newRules = array(
            // array('end_date_time', 'required'),
            array('end_date_time end_date', 'required', 'on' => array(self::SCENARIO_END_DATE, self::SCENARIO_QUOTE_FOURTEEN)),
            array('end_date_time', 'checkEndDateChange', 'on' => array(self::SCENARIO_CHANGE_END_DATE)),
            array('end_date_time', 'checkEndDate', 'on' => array(self::SCENARIO_END_DATE, self::SCENARIO_QUOTE_FOURTEEN)),
//             array('days', 'checkDays'),
//            array('start_date_time, type, start_date', 'required'),
//            array('start_date_time', 'checkStartDate', 'except' => self::SCENARIO_CHANGE_END_DATE),
//            array('id_client, recurring, type', 'numerical', 'integerOnly' => true),
//            array('end_date_time', 'required', 'on' => self::SCENARIO_END_DATE),
//            array('end_date_time', 'checkEndDate', 'on' => self::SCENARIO_CHANGE_END_DATE),
//            array('end_date_time', 'required', 'on' => self::SCENARIO_CHANGE_END_DATE),
//            array('end_date_time', 'safe'),
//            // The following rule is used by search().
//            // Please remove those attributes that should not be searched.
//            array('id, id_client, start_date_time, end_date_time, recurring, type, created, modified', 'safe', 'on' => 'search'),
        );

        return CMap::mergeArray($parentRules, $newRules);
    }

    public function validateDayForms() {

        //$valid = parent::validate($attributes, $clearErrors);

        $valid = true;

        $atLeastOneDaySelected = false;

        $scenar = $this->scenario;

        // if ($this->scenario == Booking::SCENARIO_END_DATE || $this->scenario == Booking::SCENARIO_RECURRING) {

        foreach ($this->dayForms as $dayForm) {

            $valid2 = $dayForm->validate();

            if ($dayForm->selected) {
                $atLeastOneDaySelected = true;
            }

            $valid = $valid && $valid2;
        }

        if (!$atLeastOneDaySelected) {

            $this->addError('start_date_time', Yii::t('texts', 'ERROR_TICK_AT_LEAST_ONE_DAY'));
            $valid = false;
        }
//        } else {
//            //other validate
//        }

        $erros = $this->errors;

        return $valid;
    }

    public function getDurationSeconds() {
        
    }

    /**
     * return number of days involved in the quote
     * 
     * if recurring return INF
     */
    public function getDays() {
        //number of days quote involves
        if ($this->recurring == false) {
            $numberDays = Calendar::daysBetween_DBDate($this->start_date_time, $this->end_date_time) + 1;
        } else {
            $numberDays = INF;
        }

        return $numberDays;
    }

    /**
     * Get quote price and breakdown
     * 
     * @return type BookingHourlyQuotePrice 
     */
    public function getQuoteTotalPrice() {

        $result = $this->calculateQuoteDayBreakdown($this->start_date_time);

        return $result;
    }

    /**
     * 
     * Next payment USED??
     * 
     * @return type BookingHourlyQuotePrice
     */
//    public function getQuoteNextPayment() {
//
//        $quotePrice = $this->getQuoteTotalPrice(true);
//
//        $lastDayBreakdown = $quotePrice->daysBreakdown[count($quotePrice->daysBreakdown) - 1];
//        $lastDayBreakdownDate = $lastDayBreakdown->date;
//
//        return $this->calculateQuoteDayBreakdown($lastDayBreakdownDate, false);
//    }

    /**
     * 
     * Get the end date of the quote. Its maximum is start date + 14 days (Business rule), also return
     * 
     * @param type $startDateTime
     * @return type array endDate and max number of days.
     */
    public function getQuoteEndDate($startDateTime) {

        //14 days
        $maxDays = BusinessRules::getHourlyBookingPaymentMaximumDays();

        //if booking recurring add 14 days to start date
        if ($this->recurring) {
            $endDateTime = Calendar::addDays($startDateTime, $maxDays, Calendar::FORMAT_DBDATETIME);
            $bookingDays = $maxDays;
        } else {
            //not recurring
            $endDateTime = $this->end_date_time;
            $bookingDays = Calendar::daysBetween_DBDate($startDateTime, $this->end_date_time) + 1;

            if ($bookingDays > $maxDays) {
                $bookingDays = $maxDays;
            }
        }

        $result = array();
        $result['bookingDays'] = $bookingDays;
        $result['endDateTime'] = $endDateTime;

        return $result;
    }

    /**
     * Return the end date of a slot starting at given start date
     * 
     * @param type $startDate
     * @return type date endDate
     */
    public function getSlotEndDate($startDate) {

        //14 days
        $maxDaysRule = BusinessRules::getHourlyBookingPaymentMaximumDays();

        $maxDays = $maxDaysRule - 1;

        //if booking recurring add 14 days to start date
        if ($this->recurring) {
            $endDate = Calendar::addDays($startDate, $maxDays, Calendar::FORMAT_DBDATE);
            $endDateResult = $endDate;
        } else {
            //not recurring
            $endDate = $this->end_date_time;

            $endDateResult = Calendar::addDays($startDate, $maxDays, Calendar::FORMAT_DBDATE);

            if (Calendar::dateIsBefore($endDate, $endDateResult)) {

                $endDateResult = $endDate;
            }
        }

        return $endDateResult;
    }

    /**
     * 
     * Used to calculate price of the quote
     * 
     * @param type $startDateTime
     * @return \BookingHourlyQuotePrice
     */
    private function calculateQuoteDayBreakdown($startDateTime) {

        //new logic for subtype several days
        if ($this->subtype == Constants::SEVERAL_DAYS) {

            $totalPrice = new Price();
            $totalDuration = 0;

            foreach ($this->dayForms as $dayForm) {

                $duration = $dayForm->getDurationSeconds();
                $currentDay = $dayForm->date;
                $startTime = $dayForm->getStartTime();
                $endTime = $dayForm->getEndTime();

                $dayPrice = $dayForm->getPrice();

                $totalDuration += $duration;

                $bookingDayPrice = new BookingHourlyDayPrice($currentDay, $dayPrice, $duration, $startTime, $endTime);

                $totalPrice = $totalPrice->add($dayPrice);

                if ($totalPrice->amount < 0) {
                    $totalPrice = new Price(); //to avoid negative price
                }

                $daysBreakdown[] = $bookingDayPrice;
            }
        } else {

            $result = $this->getQuoteEndDate($startDateTime);

            $bookingDays = $result['bookingDays'];
            $endDateTime = $result['endDateTime'];

            $totalPrice = new Price();

            $daysBreakdown = array();
            $totalDuration = 0;

            //logic is different, one has n weeks one always 1
            if ($this->subtype == self::SUBTYPE_REGULARLY) {

                //Regulary
                $weeks = 2;
            } else {
                $weeks = 1;
            }

            for ($i = 0; $i < $weeks; $i++) {

                $j = 0;

                foreach ($this->dayForms as $dayForm) {

                    $daysToAdd = $i * 7;

                    $d = $dayForm->date;
                    $currentDay = Calendar::addDays($dayForm->date, $daysToAdd, Calendar::FORMAT_DBDATETIME);

                    if (Calendar::dateIsBefore($currentDay, $endDateTime, true)) {

                        if ($dayForm->selected) {

                            $dayPrice = $dayForm->getPrice();

                            $duration = $dayForm->getDurationSeconds();
                            $startTime = $dayForm->getStartTime();
                            $endTime = $dayForm->getEndTime();

                            $totalDuration += $duration;

                            $bookingDayPrice = new BookingHourlyDayPrice($currentDay, $dayPrice, $duration, $startTime, $endTime);

                            $totalPrice = $totalPrice->add($dayPrice);

                            if ($totalPrice->amount < 0) {
                                $totalPrice = new Price(); //to avoid negative price
                            }

                            $daysBreakdown[] = $bookingDayPrice;
                        }
                    } else {
                        //stop loop
                    }

                    $j++;
                }
            }
        }

        return new BookingHourlyQuotePrice($totalPrice, $daysBreakdown, $totalDuration);
    }

    public function getQuoteNumberHours() {

        $dayForms = $this->dayForms;

        foreach ($dayForms as $dayForm) {

            $durationHours = $dayForm->getDurationHours();

            $price = Prices::getPrice(Constants::USER_CLIENT, Prices::HOURLY_PRICE);
        }
    }

    public function getNumberDaysHoursMinutes($display = false) {
        
    }

    public function displayQuoteDetails() {

        return $this->getTypeLabel();
    }

    /**
     * Check if all days/time are entered correctly
     * 
     * @param type $attribute
     * @param type $params
     */
    public function checkDays($attribute, $params) {

        $days = $this->dayForms;

        if (empty($days)) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_SELECT_DAYS'));
        } else {

            foreach ($days as $day) {

                if ($day->selected == true) {
                    
                }
            }
        }
    }

    public function checkStartDate($attribute, $params) {

        switch ($this->subtype) {

            case self::SUBTYPE_SEVERAL_DAYS:
                $hours = BusinessRules::getNewBookingDelay_Hourly_OneOff_InHours();
                $days = BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays();
                break;

            case self::SUBTYPE_ONE_OFF:
                $hours = BusinessRules::getNewBookingDelay_Hourly_OneOff_InHours();
                $days = BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays();
                break;

            case self::SUBTYPE_TWO_FOURTEEN:
                $hours = BusinessRules::getNewBookingDelay_Hourly_Fourteen_InHours();
                $days = BusinessRules::getNewBookingDelay_Hourly_Fourteen_InDays();
                break;

            case self::SUBTYPE_REGULARLY:
                $hours = BusinessRules::getNewBookingDelay_Hourly_Regularly_InHours();
                $days = BusinessRules::getNewBookingDelay_Hourly_Regularly_InDays();
                break;
        }

        $firstAvailableDay = Calendar::getDateFromToday($days, Calendar::FORMAT_DBDATE);

        $val = $this->start_date_time;
        $val = $this->end_date_time;
        $valid = Calendar::dateIsBefore($firstAvailableDay, $this->start_date_time, true);

        if (!$valid) {

            $this->addError($attribute, Yii::t('texts', 'ERROR_WE_NEED_AT_LEAST_X_DAYS', array('{numberOfDays}' => $days)));
        }
    }

    public function checkEndDate($attribute, $params) {

        $val = $this->start_date_time;
        $val2 = $this->end_date_time;

        $scenario = $this->scenario;

        if ($this->start_date_time != null && $this->end_date_time != null) {

            $isBefore = Calendar::dateIsBefore($this->start_date_time, $this->end_date_time, true);

            if (!$isBefore) {

                //TODO maybe do some logic to default the dates (e.g. to the first available day)
                $this->addError($attribute, Yii::t('texts', 'ERROR_THE_END_DATE_MUST_BE_ON_OR_AFTER_START_DATE'));
            } elseif ($this->scenario == self::SCENARIO_QUOTE_FOURTEEN) {

                $maxDays = 14;
                $numberDays = Calendar::numberDays_DBDate($this->start_date_time, $this->end_date_time);

                if ($numberDays > $maxDays) {
                    $this->addError($attribute, Yii::t('texts', 'ERROR_DAYS_MORE_FOURTEEN', array('{maxDays}' => $maxDays)));
                }
            }
        }
    }

    public function checkEndDateChange($attribute, $params) {

        $val = $this->start_date_time;
        $val = $this->end_date_time;

        if ($this->start_date_time != null && $this->end_date_time != null) {

            $isBefore = Calendar::dateIsBefore($this->start_date_time, $this->end_date_time, false);

            if (!$isBefore) {

                //TODO maybe do some logic to default the dates (e.g. to the first available day)
                $this->addError($attribute, Yii::t('texts', 'ERROR_THE_END_DATE_MUST_BE_ON_OR_AFTER_START_DATE'));
            } else {

                $lastPaidMission = Booking::getLastMission($this->id);

                $newEndDate = $this->end_date_time;
                $paymentEndDate = Calendar::convert_DBDateTime_DBDate($lastPaidMission->end_date_time);

                if (Calendar::dateIsBefore($newEndDate, $paymentEndDate)) {

                    $errorMessage = Yii::t('texts', 'ERROR_THE_END_DATE_MUST_BE_AFTER_LAST_MISSION');

                    $this->addError($attribute, $errorMessage);
                } else {

                    //check selected date
                    $dayForms = $this->bookingHourlyDays;

                    $selectedDaysOfWeek = array();
                    $dayWeeksText = '';

                    //buffer available days
                    foreach ($dayForms as $dayForm) {

                        $selectedDaysOfWeek[] = $dayForm->day_week;
                        $dayWeeksText .= Calendar::getDayOfWeekTextFromNumber($dayForm->day_week) . ', ';
                    }

                    $dayWeekInt = Calendar::getDayOfWeekNumber($this->end_date_time);

                    if (!in_array($dayWeekInt, $selectedDaysOfWeek)) {

                        $dayWeeksText = Util::removeEndString($dayWeeksText, ', ');

                        $errorMessage = Yii::t('texts', 'ERROR_THE_END_DATE_NOT_IN_SELECTED_DAY_WEEK', array('{weekDays}' => $dayWeeksText));

                        $this->addError($attribute, $errorMessage);
                    }
                }
            }
        }
    }

    /**
     * Called by the ajax method to generate new form for current start and end date
     * 
     * @param type $previousValues array of previous values 
     */
    public function generateDayForms($previousJsValues = null) {

        //check previous values

        if (isset($previousJsValues)) {
            $previousValues = $previousJsValues;
        } else {

            $dayForms = $this->getDayForms();

            if (isset($dayForms)) {

                foreach ($dayForms as $dayForm) {

                    $previousValues[$dayForm->dayWeek] = array(
                        'startHour' => $dayForm->startHour,
                        'startMinute' => $dayForm->startMinute,
                        'endHour' => $dayForm->endHour,
                        'endMinute' => $dayForm->endMinute,
                        'selected' => $dayForm->selected,
                        'scenario' => Constants::TAB_HOURLY_FOURTEEN,
                    );
                }
            }
        }

        $startDateTime = $this->start_date_time;
        $endDateTime = $this->end_date_time;
        $recurring = $this->recurring;

        $maxDays = 14;

        //if both dates set
        if (isset($startDateTime) && isset($endDateTime)) {

            $days = Calendar::daysBetween_DBDate($startDateTime, $endDateTime) + 1;
            if ($days > $maxDays) {
                $days = $maxDays;
            }
            $startDate = $startDateTime;
        } elseif (isset($startDateTime) && !isset($endDateTime) && !$recurring) {
            //if only start date is set
            $days = 1;
            $startDate = $startDateTime;
        } elseif (!isset($startDateTime) && isset($endDateTime) && !$recurring) {
            //if only end date is set
            $days = 1;
            $startDate = $endDateTime;
        } elseif (isset($startDateTime) && !isset($endDateTime) && $recurring) {
            //if only start date and recurring
            $days = $maxDays;
            $startDate = $startDateTime;
        } else {

            //only recurring is selected and no start date: nothing to do
            $days = 0;
        }

        $dayForms = array();

        for ($i = 0; $i < $days; $i++) {

            $dayForm = new DayForm();

            $date = Calendar::addDays($startDate, $i, Calendar::FORMAT_DBDATETIME);
            $dayWeek = Calendar::getDayOfWeekNumber($date);

            $dayForm->date = $date;

            if (isset($previousValues[$dayWeek])) {

                $dayData = $previousValues[$dayWeek];

                $dayForm->selected = $dayData['selected'];
                $dayForm->startHour = $dayData['startHour'];
                $dayForm->startMinute = $dayData['startMinute'];
                $dayForm->endHour = $dayData['endHour'];
                $dayForm->endMinute = $dayData['endMinute'];
                $dayForm->scenario = Constants::TAB_HOURLY_FOURTEEN;
            } else {

                //default selected values in the drop downs MUST BE STRING (otherwise not selected in drop down)
                $dayForm->selected = true;
                $dayForm->startHour = '08';
                $dayForm->startMinute = '00';
                $dayForm->endHour = '12';
                $dayForm->endMinute = '00';
                $dayForm->scenario = Constants::TAB_HOURLY_FOURTEEN;
            }
            $dayForms[] = $dayForm;
        }

        $this->dayForms = $dayForms;
    }

    public function calculateFirstPayment($user) {

        $result = $this->getQuoteTotalPrice($user);

        return $result->totalPrice;
    }

    /**
     * Must be called after save() ! otherwise no id
     * 
     * Translates the data capture in the form (daysForm) to a generic way of storing the data
     */
    public function saveBookingHourlyDays() {

        $dayForms = $this->dayForms;

        foreach ($dayForms as $dayForm) {

            if ($dayForm->selected) {

                $bookingHourlyDay = new BookingHourlyDay();
                $bookingHourlyDay->day_week = $dayForm->dayWeek;
                $bookingHourlyDay->start_time = $dayForm->getStartTime();
                $bookingHourlyDay->end_time = $dayForm->getEndTime();
                $bookingHourlyDay->id_booking = $this->id;

                $bookingHourlyDay->save();
            }
        }
    }

    /**
     * Gathers missions date/time between two dates
     * 
     * @param type $startDate
     * @param type $endDate
     * @return array of MissionHourly
     */
    public function getMissions($startDate, $endDate) {

        $bookingHourlyDays = $this->bookingHourlyDays; //relation

        $bookingHourlyDaysMap = array();

        //create a hashmap dayweek -> bookingHourly
        foreach ($bookingHourlyDays as $bookingHourlyDay) {

            $dayWeek = $bookingHourlyDay->day_week;
            $bookingHourlyDaysMap[$dayWeek] = $bookingHourlyDay;
        }

        //check start date and end date within the booking mission
        $valid = Calendar::dateIsBefore($this->start_date_time, $startDate, true);

        if ($this->recurring == false) {
            $valid2 = Calendar::dateIsBefore($endDate, $this->end_date_time, true);

            if (!($valid && $valid2)) {
                return null;
            }
        }

        //get the number of days between start date and end date
        $numberDays = Calendar::numberDays_DBDate($startDate, $endDate);

        $missions = array();

        //loop at these number of days from the start date
        for ($i = 0; $i < $numberDays; $i++) {

            $day = Calendar::addDays($startDate, $i, Calendar::FORMAT_DBDATE);

            //for each days get the day of the week
            $dayWeekNumber = Calendar::getDayOfWeekNumber($day);

            if (array_key_exists($dayWeekNumber, $bookingHourlyDaysMap)) {

                $record = $bookingHourlyDaysMap[$dayWeekNumber];
                $missionHourly = new MissionHourly();

                $start = $day . ' ' . $record->start_time;

                $missionHourly->start_date_time = $start;
                $missionHourly->end_date_time = $day . ' ' . $record->end_time;

                $missions[] = $missionHourly;
            }
        }

        return $missions;
    }

    /**
     * 
     * Create missions for given mission Payment
     * 
     * Also takes care in creating copies of Location and Users, as transactional data
     * 
     * @param type $missionPaymentId
     * @param type $serviceLocationId
     * @param type $serviceUserIds
     * @param type $startDate //DEPRECATED uses internal dayForms
     * @param type $endDate //DEPRECATED uses internal dayForms
     */
    public function createMissions($missionPaymentId, $serviceLocationId, $serviceUserIds) {

        //make sure all day forms are sorted by time, check on start time only as cannot overlapp
        usort($this->dayForms, function($a, $b) {
                    return strtotime($a['startDateTime']) - strtotime($b['startDateTime']);
                });


        foreach ($this->dayForms as $dayForm) {

            MissionHourly::createMissionHourly($this->id, $missionPaymentId, $serviceLocationId, $serviceUserIds, $dayForm->startDateTime, $dayForm->endDateTime);
        }
    }

    public function getHourlyMissionSlots() {

        assert(isset($this->start_date));

        if ($this->recurring) {

            //show example
            $endDate = date('Y-m-d', strtotime('+10 weeks', strtotime($this->start_date)));
        } else {
            $endDate = $this->end_date;
        }

        $slots = Calendar::splitBookingLiveIn($this->start_date, $endDate);

        return $this->addSlotInfo($slots);
    }

    /**
     * 
     * @return null if no more slot
     */
    public function getNextMissionSlot() {

        //only regularly can have next mission slots
        if ($this->subtype == self::SUBTYPE_REGULARLY) {

            $id = $this->id;
            //$mission = Booking::getLastMission($this->id);
            $missionPayment = $this->getLastMissionPayment();

            //Get start date
            if ($missionPayment == null) { //if first mission
                $startDate = $this->start_date;
            } else {
                //next missions
                $startDate = Calendar::convert_DBDateTime_DBDate($missionPayment->end_date_time);
            }

            //Get end date
            if ($this->recurring) {
                $endDate = date('Y-m-d', strtotime('+10 weeks', strtotime($this->start_date)));
            } else {
                $endDate = $this->end_date;
            }

            $slots = Calendar::splitBookingHourly($startDate, $endDate);

            //no last mission
            if ($slots[0]['startDay'] === $slots[0]['endDay']) {
                return null;
            }

            $decoratedSlots = $this->addSlotInfo($slots);

            $decoratedSlot = $decoratedSlots[0];

            return $decoratedSlot;
        } else {
            return null;
        }
    }

    /**
     * add start/end time and prices from a raw slot (startDay/endDay) = not started and to pay
     * 
     * 
     */
    public function addSlotInfo($slots) {

        $bookingLiveInSlots = array();

        $i = 0;

        foreach ($slots as $slot) {

            if ($this->recurring == true) {

                $endTime = $this->start_time;
            } else {

                $endTime = $this->start_time;

                if ($i == count($slots) - 1) {
                    $endTime = $this->end_time;
                }
            }

            $startDate = $slot['startDay'];
            $endDate = $slot['endDay'];

            //array of MissionHourly
            $slotMissions = $this->getMissions($startDate, $endDate);

            $totalDurationSeconds = 0;
            $totalPrice = new Price();

            foreach ($slotMissions as $slotMission) {

                $startDateTime = $slotMission->start_date_time;
                $endDateTime = $slotMission->end_date_time;

                $duration = Calendar::calculate_Duration_Seconds($startDateTime, $endDateTime);
                $totalDurationSeconds += $duration;

                $toPay = Prices::calculateHourlyMissionPrice(Constants::USER_CLIENT, $startDateTime, $endDateTime);
                $totalPrice = $totalPrice->add($toPay);
            }

            $durationText = Calendar::convert_Seconds_DayHoursMinutesSeconds($totalDurationSeconds, true, true);

            $slot = new BookingLiveInSlot($startDate, $endDate, $durationText, $totalPrice);
            $slot->hourlyMissions = $slotMissions;
            $bookingLiveInSlots[] = $slot;

            $i++;
        }

        return $bookingLiveInSlots;
    }

    /**
     * Use MissionPayment instead
     * 
     * @param type $showPaidSlots default true (show all), otherwise number of slots 
     * @param type $showNextSlots default true (show all - to a certain point for recurring booking), otherwise number of slots 
     */
    public function getMissionSlots($showPaidSlots = true, $showNextSlots = 2) {

        //FIND PAST SLOTS
        $criteria = new CDbCriteria();
        $criteria->condition = 'id_booking=:idBooking';
        $criteria->params = array(':idBooking' => $this->id);
        $criteria->order = 'end_date_time ASC';

        //find all existing missions(paid) for the current booking
        $missionPayments = MissionPayment::model()->findAll($criteria);

        $slots = array();

        $missionPaymentCount = count($missionPayments);

        if ($showPaidSlots === true) {
            $numberSlots = $missionPaymentCount;
        } else {
            $numberSlots = $showPaidSlots;
        }

        $firstValue = $missionPaymentCount - $numberSlots;

        if ($firstValue < 0)
            $firstValue = 0;

        for ($i = $firstValue; $i < $missionPaymentCount; $i++) {

            $missionPayment = $missionPayments[$i];

            $startDateTime = $missionPayment->start_date_time;
            $endDateTime = $missionPayment->end_date_time;

            $durationSeconds = $missionPayment->getDuration();

            $durationText = Calendar::convert_Seconds_DayHoursMinutesSeconds($durationSeconds, true, true);

            $slot = new BookingLiveInSlot($startDateTime, $endDateTime, $durationText, null, $missionPayment);

            $slots[] = $slot;
        }

        //FIND UPCOMING SLOTS, only for regularly

        if ($this->subtype == self::SUBTYPE_REGULARLY) {

            //run the booking split from last mission
            $lastMissionPayment = $missionPayments[$missionPaymentCount - 1];

            $startDa = $lastMissionPayment->end_date;

            $startDate = Calendar::addDays($startDa, 1, Calendar::FORMAT_DBDATE);

            if ($this->recurring) {

                $days = BusinessRules::getHourlyBookingPaymentMaximumDays() * $showNextSlots;

                $endDate = date('Y-m-d', strtotime("+$days days", strtotime($lastMissionPayment->end_date_time)));
            } else {
                $endDate = $this->end_date;
            }

            $upComingSlots = array();

            if (Calendar::dateIsBefore($startDate, $endDate, true)) {//if dates are the same: no more bookings to pay 
                $upComingSlotsRaw = Calendar::splitBookingHourly($startDate, $endDate);

                if (count($upComingSlotsRaw) < $showNextSlots) {
                    $showNextSlots = count($upComingSlotsRaw);
                }

                for ($j = 0; $j < $showNextSlots; $j++) {

                    $rawSlotFiltered[] = $upComingSlotsRaw[$j];
                }

                $upComingSlots = $this->addSlotInfo($rawSlotFiltered);
            }
        } else {
            $upComingSlots = array();
        }

        $result = CMap::mergeArray($slots, $upComingSlots);

        return $result;
    }

    public function dayFormsHaveErrors() {

        $dayForms = $this->dayForms;

        foreach ($dayForms as $dayForm) {

            $valid = $dayForm->validate();

            if (!$valid) {
                return true;
            }
        }

        return false;
    }

    /**
     * return array of error message array
     */
    public function getDayFormsErrorMessages() {

        $dayForms = $this->dayForms;
        $errors = array();

        foreach ($dayForms as $dayForm) {

            $valid = $dayForm->validate();
            if (!$valid) {
                $errors[] = $dayForm->getErrors();
            }
        }

        return $errors;
    }

    public function getBookingHourlyDaysText() {

        $dayForms = $this->bookingHourlyDays;

        $dayWeeksText = '';

        //buffer available days
        foreach ($dayForms as $dayForm) {

            $dayWeeksText .= Calendar::getDayOfWeekTextFromNumber($dayForm->day_week) . ', ';
        }

        $dayWeeksText = Util::removeEndString($dayWeeksText, ', ');

        return $dayWeeksText;
    }

    public function initFirstTime() {

        //USED BY FOURTEEN DAYS BOOKING ONLY

        $today = Calendar::today(Calendar::FORMAT_DBDATE);

        $days = BusinessRules::getNewBookingDelay_Hourly_Fourteen_InDays();

        $minDate = Calendar::addDays($today, $days, Calendar::FORMAT_DBDATE);

        $startDate = $minDate;
        $endDate = Calendar::addDays($minDate, 1, Calendar::FORMAT_DBDATE);

        $this->start_date_time = $startDate;
        $this->end_date_time = $endDate;

        $this->firstTime = true;
        $this->showResult = false;

        $this->generateDayForms();
    }

}

?>
