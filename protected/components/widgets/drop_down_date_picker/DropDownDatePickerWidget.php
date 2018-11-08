<?php

class DropDownDatePickerWidget extends CWidget {

    public $days = array();
    public $months = array();
    public $years = array();
    public $myLocale;
    public $selectedDay = '0';
    public $selectedMonth = '0';
    public $selectedYear = '0';
    public $date;
    public $htmlOptions;
    public $index = -1;
    public $hideDay = false;
    public $hideMonth = false;
    public $scenario = 'birthDate';

    public function run() {

        $date = $this->date;
        $myLocale = Yii::app()->params['datePickerLocation']; //by RC

        if (isset($date)) {

            $timestamp = strtotime(date("Y-m-d", strtotime($date)));

            $day = intval(date('d', $timestamp));
            $month = intval(date('m', $timestamp));
            $year = date('Y', $timestamp);

            if (checkdate($month, $day, $year)) {

                $this->selectedDay = $day;
                $this->selectedMonth = $month;
                $this->selectedYear = $year;
            }
        }

        //days
        $this->days[0] = Yii::t('texts', 'DAY');

        for ($i = 1; $i <= 31; $i++) {

            $this->days[$i] = $i;
        }

        //months
        $this->months[0] = Yii::t('texts', 'MONTH');
        if ($this->scenario == 'creditCard') {
            $this->months = array_merge($this->months, Calendar::getDropDownMonthsNumber());
        } else {

            $this->months = array_merge($this->months, Yii::app()->getLocale($this->myLocale)->getMonthNames('abbreviated'));
        }

        //years
        $this->years[0] = Yii::t('texts', 'YEAR');
        if ($this->scenario == 'creditCard') {
            $years = Calendar::getDropDownCreditCardYears();
            $this->years = $this->years + $years;
        } else {
            $years = Calendar::getDropDownYears();
            $this->years = $this->years + $years;
        }

        $years = $this->years;
        
        $this->render('dropDownDatePicker');
    }

}

?>