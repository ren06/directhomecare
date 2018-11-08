<?php

class HourlyQuoteSimpleForm extends CFormModel {

    public $dayForms = array();
    //used for quotes and navgation
    public $showResult;
    public $firstTime;
    public $errorMessage;
    public $type = Constants::HOURLY;

    public function validate($attributes = null, $clearErrors = true) {

        $valid = parent::validate($attributes, $clearErrors) && $this->validateDaysRange();

        foreach ($this->dayForms as $dayForm) {

            $valid = $valid && $dayForm->validate();
        }

        $this->showResult = $valid && !$this->firstTime;

        return $valid;
    }

    public function validateDaysRange() {

        $dayForms = $this->dayForms;

        $keys = array_keys($dayForms);

        for ($i = 0; $i < count($dayForms); $i++) {

            $refForm = $dayForms[$keys[$i]];

            for ($j = $i + 1; $j < count($dayForms); $j++) {

                $form = $dayForms[$keys[$j]];

                if ($form->date == $refForm->date) {

                    if (($refForm->getStartTimeTimestamp() < $form->getEndTimeTimestamp()) && ($refForm->getEndTimeTimestamp() > $form->getStartTimeTimestamp(0))) {
                        $this->addError('errorMessage', 'Some visits are overlapping with each other.');
                        return false;
                    }
                }
                //check overalpp
            }
        }

        return true;
    }

    /**
     * Return a BookingHourly object pased on the quote
     */
    public function convertBookingHourly() {

        $model = new BookingHourly();
        $model->subtype = Booking::SUBTYPE_SEVERAL_DAYS;

        $startDate = null;
        $endDate = null;

        foreach ($this->dayForms as $dayForm) {

            $date = Calendar::convert_DisplayDate_DBDate($dayForm->date);

            $currentStartDate = $date . ' ' . $dayForm->getStartTime();
            $currentEndDate = $date . ' ' . $dayForm->getEndTime();

            if ($startDate == null) {
                $startDate = $currentStartDate;
            } else {
                if (Calendar::dateIsBefore($currentStartDate, $startDate)) {
                    $startDate = $currentStartDate;
                }
            }

            if ($endDate == null) {
                $endDate = $currentEndDate;
            } else {
                if (Calendar::dateIsBefore($endDate, $currentEndDate)) {
                    $endDate = $currentEndDate;
                }
            }

            //add a form day?
            $day = new DayForm();
            $day->date = $date;
            $day->startHour = $dayForm->startHour;
            $day->startMinute = $dayForm->startMinute;
            $day->endHour = $dayForm->endHour;
            $day->endMinute = $dayForm->endMinute;
            $day->scenario = $dayForm->scenario;
            $day->selected = true;

            $day->startDateTime = $date . ' ' . $dayForm->startHour . ':' . $dayForm->startMinute . ':00';
            $day->endDateTime = $date . ' ' . $dayForm->endHour . ':' . $dayForm->endMinute . ':00';

            $day->dayWeek = Calendar::getDayOfWeekNumber($date);
            $days[] = $day;
        }

        //used for legacy reasons, not really useful
        $model->start_date_time = $startDate;
        $model->end_date_time = $endDate;

        $model->setDayForms($days);

        return $model;
    }

    public function initFirstTime() {

        $dayForm = new HourlyQuoteDayForm();

        $today = Calendar::today(Calendar::FORMAT_DISPLAYDATE);
        $rule = BusinessRules::getNewBookingDelay_Hourly_OneOff_InDays();
        $minDate = Calendar::addDays($today, $rule, Calendar::FORMAT_DISPLAYDATE);

        $dayForm->date = $minDate;
        $dayForm->startHour = '08';
        $dayForm->startMinute = '00';
        $dayForm->endHour = '10';
        $dayForm->endMinute = '00';

        $this->firstTime = true;
        $this->showResult = false;

        $this->dayForms[] = $dayForm;
    }

}

?>
