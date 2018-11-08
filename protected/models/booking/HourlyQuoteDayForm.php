<?php

class HourlyQuoteDayForm extends CFormModel {

    public $date;
    public $startHour;
    public $startMinute;
    public $endHour;
    public $endMinute;
    
    const ADMIN_SCENARIO = 0;

    public function rules() {
        return array(
            // name, email, subject and body are required
            array('date, startHour, startMinute, endHour, endMinute', 'required'),
            array('startHour, startMinute, endHour, endMinute', 'numerical', 'integerOnly' => true),
            array('date', 'date', 'format' => 'd/M/yyyy'),
            array('date', 'checkDate', 'except' => self::ADMIN_SCENARIO),
            array('endHour', 'checkEndTime'),
        );
    }

    public function attributeLabels() {
        return array(
            'date' => Yii::t('texts', 'LABEL_DATE'),
            'startHour' => Yii::t('texts', 'LABEL_START_TIME'),
            'endHour' => Yii::t('texts', 'LABEL_END_TIME'),
        );
    }

    public function validateQuote() {

        $valid = $this->validate();
        $this->showResult = $valid && !$this->firstTime;
        return $valid;
    }

    public function getStartTime() {

        $startTime = $this->startHour . ':' . $this->startMinute;

        return $startTime;
    }

    public function getEndTime() {

        $endTime = $this->endHour . ':' . $this->endMinute;

        return $endTime;
    }

    public function getStartTimeTimestamp() {

        return mktime($this->startHour, $this->startMinute);
    }

    public function getEndTimeTimestamp() {

        return mktime($this->endHour, $this->endMinute);
    }

    public function checkDate($attribute, $params) {

        $firstAvailableDay = Calendar::getDateFromToday(BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays(), Calendar::FORMAT_DBDATE);
        $date = Calendar::convert_DisplayDate_DBDate($this->date);

        $valid = Calendar::dateIsBefore($firstAvailableDay, $date, true);

        if (!$valid) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_WE_NEED_AT_LEAST_X_DAYS', array('{numberOfDays}' => BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays())));
        }
    }

    public function getPrice() {

        $price = Prices::getPrice(Constants::USER_CLIENT, Prices::HOURLY_PRICE);

        return $price->multiply($this->getDurationHours());
    }

    public function getDurationHours() {
        return $this->getDurationSeconds() / 3600;
    }

    public function getDurationSeconds() {

        $startTime = $this->getStartTimeTimestamp();
        $endTime = $this->getEndTimeTimestamp();

        $diffSeconds = $endTime - $startTime;

        return $diffSeconds;
    }

    public function checkEndTime() {

        $diffSeconds = $this->getDurationSeconds();

        $minDurationHours = BusinessRules::getHourlyMissionMinimumDurationInHours();

        $minDurationSeconds = $minDurationHours * 3600;

        if ($diffSeconds < $minDurationSeconds) {

//            $minutes = BusinessRules::getHourlyMissionDurationInMinutes();
//            $errorMessage = Yii::t('texts', 'ERROR_X_MINUTES_MINIMUM', array('{minutes}' => $minutes));

            $hours = BusinessRules::getHourlyMissionMinimumDurationInHours();
            $errorMessage = Yii::t('texts', 'ERROR_X_HOURS_MINIMUM', array('{hours}' => $hours));

            $this->addError('startHour', $errorMessage);
            $this->addError('startMinute', $errorMessage);
            $this->addError('endHour', $errorMessage);
            $this->addError('endMinute', $errorMessage);
            return false;
        } else {
            return true;
        }
    }

    public function initFirstTime() {

        $today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
        $rule = BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays();
        $minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);

        $this->date = $minDate;
        $this->startHour = '08';
        $this->startMinute = '00';
        $this->endHour = '10';
        $this->endMinute = '00';
    }

    public function getQuoteInfo() {

        $sessionId = Yii::app()->session->sessionID;

        $text = 'Session Id: ' . $sessionId;
        $text .= ': Type: ' . 'Hourly one day';
        $text .= ', Start: ' . $this->date . ' ' . $this->getStartTime();
        $text .= ', End: ' . $this->date . ' ' . $this->getEndTime();

        return $text;
    }

}

?>
