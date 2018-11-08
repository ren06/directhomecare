<?php

class BookingHourlyOneDayForm extends CFormModel {

    public $date;
    public $startHour;
    public $startMinute;
    public $endHour;
    public $endMinute;
    
    //used for quotes and navgation
    public $showResult;
    public $firstTime;
    public $type = Constants::HOURLY;

    public function rules() {
        return array(
            // name, email, subject and body are required
            array('date, startHour, startMinute, endHour, endMinute', 'required'),
            array('startHour, startMinute, endHour, endMinute', 'numerical', 'integerOnly' => true),
            array('date', 'date', 'format' => 'd/M/yyyy'),
            array('date', 'checkDate'),
            array('endHour', 'checkEndTime'),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
                //array('startHour', 'checkDay'),
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

    public function getDurationSeconds() {

        $startTime = $this->getStartTimeTimestamp();
        $endTime = $this->getEndTimeTimestamp();

        $diffSeconds = $endTime - $startTime;

        return $diffSeconds;
    }

    public function checkDate($attribute, $params) {

        $firstAvailableDay = Calendar::getDateFromToday(BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays(), Calendar::FORMAT_DBDATE);
        $date = Calendar::convert_DisplayDate_DBDate($this->date);

        $valid = Calendar::dateIsBefore($firstAvailableDay, $date, true);

        if (!$valid) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_WE_NEED_AT_LEAST_X_DAYS', array('{numberOfDays}' => BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays())));
        }
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

    /**
     * Return a BookingHourly object pased on the quote
     */
    public function convertBookingHourly() {

        $model = new BookingHourly();
        $model->subtype = Booking::SUBTYPE_ONE_OFF;

        $date = Calendar::convert_DisplayDate_DBDate($this->date);

        $model->start_date_time = $date . ' ' . $this->getStartTime();
        $model->end_date_time = $date . ' ' . $this->getEndTime();

        //add a form day?
        $day = new DayForm();
        $day->date = $date;
        $day->startHour = $this->startHour;
        $day->startMinute = $this->startMinute;
        $day->endHour = $this->endHour;
        $day->endMinute = $this->endMinute;
        $day->scenario = $this->scenario;
        $day->selected = true;
        

//        if ($day->selected && $firstSelectedDay == null) {
//            $firstSelectedDay = $day->date;
//            $firstSelectedDayIndex = $i;
//        }
//
//        if ($day->selected) {
//            $lastSelectedDayWeek = Calendar::getDayOfWeekText($day->date);
//            $lastSelectedDayIndex = $i;
//        }

        $day->dayWeek = Calendar::getDayOfWeekNumber($date);
        $days[] = $day;
        $model->setDayForms($days);

        return $model;
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

        $this->firstTime = true;
        $this->showResult = false;
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
