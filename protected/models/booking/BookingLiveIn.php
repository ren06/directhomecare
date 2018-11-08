<?php

class BookingLiveIn extends Booking {

    static function model($className = __CLASS__) {
        return parent::model($className);
    }

    function defaultScope() {
        $type = self::TYPE_LIVE_IN;
        return array(
            'condition' => "type='$type'",
        );
    }

    /**
     * Here is handle default choice (recurring or end date)
     */
    public function afterConstruct() {
        parent::afterConstruct();

        $this->type = self::TYPE_LIVE_IN;
        $this->recurring = false;
    }

    public function rules() {

        $parentRules = parent::rules();

        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $newRules = array(
            array('end_date_time', 'checkEndDate', 'on' => self::SCENARIO_CHANGE_END_DATE),
        );

        return CMap::mergeArray($parentRules, $newRules);
    }

    public function getNumberDaysHoursMinutes($display = false) {

        $seconds = Calendar::calculate_Duration_Seconds($this->start_date_time, $this->end_date_time);

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($seconds, $display);


//        $startStamp = $start->getTimestamp();
//        $endStamp = $end->getTimestamp();
//
//        $result = Calendar::daysHoursMinutesBetween($startStamp, $endStamp);
//
//        if ($display) {
//            return $result["d"] . ' days ' . $result["h"] . ' hours ' . $result["m"] . ' minutes ' . $result["s"] . ' seconds';
//        } else {
//            return $result;
//        }
    }

    public function getMinimumAbortDate() {

        $dayLimit = BusinessRules::getClientAbortMissionLiveInTimeLimitInDays();

        $currentDay = Calendar::today();
        $currentTime = Calendar::getTimeNow();

        $startTime = $this->getStartTime();
        $startDate = $this->getStartDate();

        $endDate = $this->getEndDate();

        if (Calendar::isTimeBefore($startTime, $currentTime)) {

            $result = Calendar::addDays($currentDay, $dayLimit + 1);
        } else {

            $result = Calendar::addDays($currentDay, $dayLimit);
        }

        return $result;
    }

    //TODO check start date Booking why 2 functions
    public function checkStartDate($attribute, $params) {

        $firstAvailableDay = Calendar::getDateFromToday(BusinessRules::getNewBookingDelayLiveInDays(), Calendar::FORMAT_DBDATE);

        $valid = Calendar::dateIsBefore($firstAvailableDay, $this->start_date_time, true);

        if (!$valid) {

            $this->addError($attribute, Yii::t('texts', 'ERROR_WE_NEED_AT_LEAST_X_DAYS', array('{numberOfDays}' => BusinessRules::getNewBookingDelayLiveInDays())));
        }

        //check end date ony if in SCENARIO_END_DATE
        if ($this->scenario == self::SCENARIO_END_DATE) {

            $start = $this->start_date_time;
            $end = $this->end_date_time;

            if ($end != null) {

                $minDays = BusinessRules::getNewBookingLiveInMinimumDurationInDays();

                $start = date('Y-m-d', strtotime("+ $minDays day", strtotime($start)));

                $isBefore = Calendar::dateIsBefore($start, $end, true);

                if (!$isBefore) {

                    //TODO maybe do some logic to default the dates (e.g. to the first available day)
                    $this->addError($attribute, Yii::t('texts', 'ERROR_THE_END_DATE_MUST_BE_X_HOURS', array('{numberOfHours}' => BusinessRules::getNewBookingLiveInMinimumDurationInHours())));
                }
            }
        }
    }

    public function checkEndDate($attribute, $params) {

        $val = $this->start_date_time;
        $val = $this->end_date_time;

        if ($this->start_date_time != null && $this->end_date_time != null) {

            $isBefore = Calendar::dateIsBefore($this->start_date_time, $this->end_date_time, false);

            if (!$isBefore) {

                //TODO maybe do some logic to default the dates (e.g. to the first available day)
                $this->addError($attribute, Yii::t('texts', 'ERROR_THE_END_DATE_MUST_BE_X_HOURS', array('{numberOfHours}' => BusinessRules::getNewBookingLiveInMinimumDurationInHours())));
            } else {

                //MINIMUM END DATE
                //either last mission paid end date or this + minimum mission duration
                //get last created mission (use enddatetime)

                $lastMission = Booking::getLastMission($this->id);

                $lastMissionEndDateTime = $lastMission->end_date_time;

                $hoursBetween = Calendar::hoursBetween_DBDateTime($lastMissionEndDateTime, $this->end_date_time);
                $ruleHours = BusinessRules::getNewBookingLiveInMinimumDurationInHours();
                if ($hoursBetween < 0) {

                    $errorMessage = Yii::t('texts', 'ERROR_WITH_THIS_FUNCTION_YOU_CAN_STOP_FUTURE');

                    $this->addError($attribute, $errorMessage);
                } elseif ($hoursBetween < $ruleHours && $lastMissionEndDateTime != $this->end_date_time) {

                    $errorMessage = Yii::t('texts', 'ERROR_THE_END_DATE_MUST_BE_X_HOURS', array('{numberOfHours}' => BusinessRules::getNewBookingLiveInMinimumDurationInHours()));

                    $this->addError($attribute, $errorMessage);
                }
            }
        }
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

        //Yii::log('bookindId ' . $this->id, CLogger::LEVEL_ERROR);

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

            $duration = Calendar::calculate_Duration_DisplayAll($startDateTime, $endDateTime);

            $slot = new BookingLiveInSlot($startDateTime, $endDateTime, $duration, null, $missionPayment);

            $slots[] = $slot;
        }

        $upComingSlots = array();

        if (count($missionPayments) > 0) {

            //FIND UPCOMING SLOTS
            //run the booking split from last mission
            $lastMissionPayment = $missionPayments[$missionPaymentCount - 1];

            $startDate = $lastMissionPayment->end_date;

            if ($this->recurring) {

                $days = BusinessRules::getLiveInMissionPaymentNumberDays() * $showNextSlots;

                $endDate = date('Y-m-d', strtotime("+$days days", strtotime($lastMissionPayment->end_date_time)));
            } else {
                $endDate = $this->end_date;
            }


            if ($startDate != $endDate) {//if dates are the same: no more bookings to pay 
                $upComingSlotsRaw = Calendar::splitBookingLiveIn($startDate, $endDate);

                if (count($upComingSlotsRaw) < $showNextSlots) {
                    $showNextSlots = count($upComingSlotsRaw);
                }

                for ($j = 0; $j < $showNextSlots; $j++) {

                    $rawSlotFiltered[] = $upComingSlotsRaw[$j];
                }

                $upComingSlots = $this->addSlotInfo($rawSlotFiltered);
            }
        }

        $result = CMap::mergeArray($slots, $upComingSlots);

        return $result;
    }

    /**
     * Return first slot
     * @return type
     */
    public function getFirstMissionSlot() {

        $slots = $this->getLiveInMissionSlots();

        return $slots[0];
    }

    /**
     * Use splitBooking to figure out slots and decorate then with time and price
     * 
     * Used for quote for live in. getLiveInMissionSlots is used for next payment (cron job) 
     * 
     * @return type array of slots
     */
    public function getLiveInMissionSlots() {

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
     * Figure out next slot date range and its price, based on last MissionPayment
     * 
     * 
     * @return type slot
     */
    public function getNextMissionSlot() {

        $id = $this->id;
        //$mission = Booking::getLastMission($this->id);
        $missionPayment = $this->getLastMissionPayment();

        if ($this->recurring) {
            $endDate = date('Y-m-d', strtotime('+10 weeks', strtotime($this->start_date)));
        } else {
            $endDate = $this->end_date;
        }

        //if first mission
        if ($missionPayment == null) {
            $startDate = $this->start_date;
        } else {
            //next missions
            $startDate = Calendar::convert_DBDateTime_DBDate($missionPayment->end_date_time);
        }

        $slots = Calendar::splitBookingLiveIn($startDate, $endDate);

        //no last mission
        if ($slots[0]['startDay'] === $slots[0]['endDay']) {
            return null;
        }

        $decoratedSlots = $this->addSlotInfo($slots);

        $decoratedSlot = $decoratedSlots[0];

        return $decoratedSlot;
    }

    /**
     * add start/end time and prices from a raw slot (startDay/endDay) = not started and to pay
     */
    private function addSlotInfo($slots) {

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

            $startTime = $this->start_time;

            $startDate = $slot['startDay'];
            $endDate = $slot['endDay'];

            $startDateTime = $startDate . ' ' . $startTime;
            $endDateTime = $endDate . ' ' . $endTime;

            $duration = Calendar::calculate_Duration_DisplayAll($startDateTime, $endDateTime);

            //check created missions
            $toPay = Prices::calculateLiveInMissionPrice(Constants::USER_CLIENT, $startDateTime, $endDateTime);

            $bookingLiveInSlots[] = new BookingLiveInSlot($startDateTime, $endDateTime, $duration, $toPay);

            $i++;
        }

        return $bookingLiveInSlots;
    }

    /**
     * 
     * Return price of first payment
     * 
     * @param type $user Client or Carer
     * @return type Price
     */
    public function calculateFirstPayment($user) {

        $slot = $this->getFirstMissionSlot();

        $price = Prices::calculateLiveInMissionPrice(Constants::USER_CLIENT, $slot->startDateTime, $slot->endDateTime, $user);

        return $price;
    }

    /**
     * return number of days involved in the quote
     * 
     * if recurring return INF
     */
    public function getDays() {
        //number of days quote involves
        if ($this->recurring == false) {
            $numberDays = Calendar::daysBetween_DBDate($this->start_date_time, $this->end_date_time);
        } else {
            $numberDays = INF;
        }

        return $numberDays;
    }

    public function initFirstTime() {

        $today = Calendar::today(Calendar::FORMAT_DBDATE);
        $rule = BusinessRules::getNewBookingDelayLiveInDays();
        $minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DBDATE);

        $startDate = $minDate . ' 12:00:00';

        $this->start_date_time = $startDate;
        $this->recurring = 1;
        $this->firstTime = true;
        $this->showResult = false;
    }

    /**
     * Returns all the mission in the given data ragen
     * For the moment just return an array of one mission with given dates
     */
    public function getMissions($startDate, $endDate) {

        //check start date and end date within the booking mission
        $valid = Calendar::dateIsBefore($this->start_date_time, $startDate, true);

        if ($this->recurring == false) {
            $valid2 = Calendar::dateIsBefore($endDate, $this->end_date_time, true);

            if (!($valid && $valid2)) {
                return null;
            }
        }
        $missionLiveIn = new MissionLiveIn();

        $missionLiveIn->start_date_time = $startDate;
        $missionLiveIn->end_date_time = $endDate;

        return array($missionLiveIn);
    }

}

?>