<?php

class BookingHourlyRegularlyForm extends CFormModel {

    public $startDate;
    public $endDate;
    public $recurring;
    private $dayForms = array();
    //used for quotes and navgation
    public $showResult;
    public $firstTime;
    public $type = Constants::HOURLY;

    public function rules() {
        return array(
            // name, email, subject and body are required
            array('startDate', 'required'),
            array('endDate', 'required', 'on' => Booking::SCENARIO_END_DATE),
            array('startDate', 'checkStartDate'),
            array('endDate', 'checkEndDate'),
        );
    }

    public function attributeLabels() {
        return array(
            'startDate' => Yii::t('texts', 'LABEL_START_DATE'),
            'endDate' => Yii::t('texts', 'LABEL_END_DATE'),
            'recurring' => Yii::t('texts', 'LABEL_RECURRING'),
        );
    }

    public function afterConstruct() {
        parent::afterConstruct();
        $this->generateDayForms();
    }

    public function setDayForms($days) {
        $this->dayForms = $days;
    }

    public function getDayForms() {
        return $this->dayForms;
    }

    public function validateQuote() {

        $formValid = $this->validate();
        $daysValid = $this->validateDayForms();

        $valid = $formValid && $daysValid;
        $this->showResult = $valid && !$this->firstTime;
        return $valid;
    }

    public function validateDayForms() {

        //$valid = parent::validate($attributes, $clearErrors);

        $valid = true;
        $valid2 = true;

        $atLeastOneDaySelected = false;

        $scenar = $this->scenario;

        foreach ($this->dayForms as $dayForm) {

            if ($dayForm->selected) {
                $valid2 = $dayForm->validate();
                $atLeastOneDaySelected = true;
            }

            $valid = $valid && $valid2;
        }

        if (!$atLeastOneDaySelected) {

            $this->addError('start_date_time', Yii::t('texts', 'ERROR_TICK_AT_LEAST_ONE_DAY'));
            $valid = false;
        }

        $erros = $this->errors;

        return $valid;
    }

    public function getStartDate() {

        $d = Calendar::convert_DBDateTime_DisplayDate($this->startDate);
        return $d;
    }

    public function getEndDate() {

        $d = Calendar::convert_DBDateTime_DisplayDate($this->endDate);
        return $d;
    }

    public function checkStartDate($attribute, $params) {

        $firstAvailableDay = Calendar::getDateFromToday(BusinessRules::getNewBookingDelay_Hourly_Regularly_InDays(), Calendar::FORMAT_DBDATE);

        $valid = Calendar::dateIsBefore($firstAvailableDay, $this->startDate, true);

        if (!$valid) {

            $this->addError($attribute, Yii::t('texts', 'ERROR_WE_NEED_AT_LEAST_X_DAYS', array('{numberOfDays}' => BusinessRules::getNewBookingDelay_Hourly_Regularly_InDays())));
        }
    }

    public function checkEndDate($attribute, $params) {

        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $recurring = $this->recurring;

        if (!$recurring) {

            if ($startDate != null && $endDate != null) {

                $isBefore = Calendar::dateIsBefore($this->startDate, $this->endDate, true);

                if (!$isBefore) {

                    //TODO maybe do some logic to default the dates (e.g. to the first available day)
                    $this->addError($attribute, Yii::t('texts', 'ERROR_THE_END_DATE_MUST_BE_ON_OR_AFTER_START_DATE'));
                }
            }
        }
    }

    public function generateDayForms() {

        //check previous values

        $dayForms = $this->getDayForms();

        if (isset($dayForms)) {

            foreach ($dayForms as $dayForm) {

                $previousValues[$dayForm->dayWeek] = array(
                    'startHour' => $dayForm->startHour,
                    'startMinute' => $dayForm->startMinute,
                    'endHour' => $dayForm->endHour,
                    'endMinute' => $dayForm->endMinute,
                    'selected' => $dayForm->selected,
                    'scenario' => Constants::TAB_HOURLY_REGULARLY,
                );
            }
        }

        $dayForms = array();

        //Monday to Sunday
        for ($i = 0; $i < 7; $i++) {

            $dayForm = new DayForm();

            $dayWeek = $i + 1;

            if (isset($previousValues[$dayWeek])) {

                $dayData = $previousValues[$dayWeek];

                $dayForm->selected = $dayData['selected'];
                $dayForm->startHour = $dayData['startHour'];
                $dayForm->startMinute = $dayData['startMinute'];
                $dayForm->endHour = $dayData['endHour'];
                $dayForm->endMinute = $dayData['endMinute'];
                $dayForm->dayWeek = $dayData['dayWeek'];
                $dayForm->scenario = Constants::TAB_HOURLY_REGULARLY;
            } else {

                //default selected values in the drop downs MUST BE STRING (otherwise not selected in drop down)
                $dayForm->selected = true;
                $dayForm->startHour = '08';
                $dayForm->startMinute = '00';
                $dayForm->endHour = '12';
                $dayForm->endMinute = '00';
                $dayForm->dayWeek = $dayWeek;
                $dayForm->scenario = Constants::TAB_HOURLY_REGULARLY;
            }
            $dayForms[] = $dayForm;
        }

        $this->dayForms = $dayForms;
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
        $model->subtype = Booking::SUBTYPE_REGULARLY;

        $startDate = Calendar::convert_DisplayDate_DBDate($this->startDate);
        $endDate = Calendar::convert_DisplayDate_DBDate($this->endDate);

        $model->start_date_time = $startDate;
        $model->end_date_time = $endDate;
        $model->recurring = $this->recurring;

        if ($model->recurring) {
            $this->scenario = Booking::SCENARIO_RECURRING;
            $numberDays = 14;
        } else {
            $this->scenario = Booking::SCENARIO_END_DATE;
            $numberDays = Calendar::numberDays_DBDate($startDate, $endDate);
            if ($numberDays > 14) {
                $numberDays = 14;
            }
        }

        $dayForms = $this->getDayForms();
        $dayWeekDayForm = array();

        foreach ($dayForms as $dayForm) {

            $dayWeekDayForm[$dayForm->dayWeek] = $dayForm;
        }

        $newDayForms = array();

        //$dayForms always 7
        //match days with start date end date

        for ($i = 0; $i < 7; $i++) {

            $date = Calendar::addDays($startDate, $i, Calendar::FORMAT_DBDATE);
            $dayOfWeek = Calendar::getDayOfWeekNumber($date);
            $newDayForm = clone $dayWeekDayForm[$dayOfWeek];

            // if ($newDayForm->selected) {
            $newDayForm->date = $date;
            $newDayForms[] = $newDayForm;
        }

        $model->setDayForms($newDayForms);

        return $model;
    }

    public function dayFormsHaveErrors() {

        $dayForms = $this->dayForms;

        foreach ($dayForms as $dayForm) {

            if ($dayForm->selected) {
                $valid = $dayForm->validate();
                if (!$valid) {
                    return true;
                }
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

            //ignore not selected days
            if ($dayForm->selected) {

                $valid = $dayForm->validate();
                if (!$valid) {
                    $errors[] = $dayForm->getErrors();
                }
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

        $today = Calendar::today(Calendar::FORMAT_DBDATE);
        $rule = BusinessRules::getNewBookingDelay_Hourly_Regularly_InDays();
        $minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DBDATE);

        $startDate = $minDate;

        $this->startDate = $startDate;
        $this->recurring = 1;
        $this->firstTime = true;
        $this->showResult = false;
        $this->showResult = false;
    }

    public function getQuoteInfo() {

        $sessionId = Yii::app()->session->sessionID;

        $days = '';

        $formDays = $this->getDayForms();

        foreach ($formDays as $formDay) {

            if ($formDay->selected) {
                $days .= $formDay->dayWeek . ' ' . $formDay->getStartTime() . ' to ' . $formDay->getEndTime() . ' - ';
            }
        }

        $text = 'Session Id: ' . $sessionId;
        $text .= ': Type: ' . 'Hourly regularly';
        $text .= ', Start: ' . $this->startDate;
        $text .= ', End: ' . $this->endDate;
        $text .= ', Days: ' . $days;



        return $text;
    }

}

?>
