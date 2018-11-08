<?php

/**
 * Description of request
 *
 * @author Renaud Theuillon
 */
class ReadHttpRequest {

    public static function readStartDateTime() {

        //Start Time
        $hour = $_REQUEST['StartHour'];

        str_pad($hour, 2, '0', STR_PAD_LEFT);

        $minute = $_REQUEST['StartMinute'];

        $timeStart = $hour . ':' . $minute . ':00';

        $dbDateStart = Calendar::convert_DisplayDate_DBDate($_REQUEST['start_date']);

        if (isset($timeStart) && isset($dbDateStart)) {

            return $dbDateStart . ' ' . $timeStart;
        }
    }

    public static function readEndDate() {

        if (isset($_REQUEST['end_date'])) {
            return Calendar::convert_DisplayDate_DBDate($_REQUEST['end_date']);
        }
    }

    public static function readEndDateTime() {

        //End Time
        if (isset($_REQUEST['EndHour']) && isset($_REQUEST['EndMinute'])) {

            $hour = $_REQUEST['EndHour'];

            if (strlen($hour) == 1) {
                $hour = '0' . $hour;
            }

            $minute = $_REQUEST['EndMinute'];

            $timeEnd = $hour . ':' . $minute . ':00';
        }

        $dbEndStart = self::readEndDate();

        if (isset($timeEnd) && isset($dbEndStart)) {

            return $dbEndStart . ' ' . $timeEnd;
        }
    }

    public static function readBookingLiveIn() {

        $model = Booking::create(Booking::TYPE_LIVE_IN);

        //Start Time
        $hour = $_GET['StartHour'];

        str_pad($hour, 2, '0', STR_PAD_LEFT);

        $minute = $_GET['StartMinute'];

        $timeStart = $hour . ':' . $minute . ':00';

        $dbDateStart = Calendar::convert_DisplayDate_DBDate($_GET['start_date']);

        if (isset($timeStart) && isset($dbDateStart)) {

            $model->start_date_time = self::readStartDateTime(); //$dbDateStart . ' ' . $timeStart;
        }

        //End Time
        if (isset($_GET['EndHour']) && isset($_GET['EndMinute'])) {

            $hour = $_GET['EndHour'];

            if (strlen($hour) == 1) {
                $hour = '0' . $hour;
            }

            $minute = $_GET['EndMinute'];

            $timeEnd = $hour . ':' . $minute . ':00';
        }

        if (isset($_GET['end_date'])) {
            $dbEndStart = Calendar::convert_DisplayDate_DBDate($_GET['end_date']);
        }

        if (isset($timeEnd) && isset($dbEndStart)) {

            $model->end_date_time = self::readEndDateTime(); //$dbEndStart . ' ' . $timeEnd;
        }

        $model->recurring = $_GET['Recurring'];

        $rec = $model->recurring;

        if ($model->recurring) {

            $model->setScenario(Booking::SCENARIO_RECURRING);
        } else {
            $model->setScenario(Booking::SCENARIO_END_DATE);
        }

        return $model;
    }

    public static function readBookingHourlyDaysAjax() {

        $request = Yii::app()->request;
        $formDataRaw = $request->getParam('formData');
        $formData = array();
        parse_str($formDataRaw, $formData); //HourlyQuoteDayForm
        
        $model = new HourlyQuoteSimpleForm();
        
        foreach($formData['HourlyQuoteDayForm'] as $key => $data){
            
            $dayForm = new HourlyQuoteDayForm();
            $dayForm->attributes = $data;
            $model->dayForms[$key] = $dayForm;
        }
                       
        return $model;
    }

    public static function readBookingHourlyDays() {

        $model = new HourlyQuoteSimpleForm();

        if (isset($_REQUEST['HourlyQuoteDayForm'])) {

            foreach ($_REQUEST['HourlyQuoteDayForm'] as $key => $dayFormData) {

                $dayForm = new HourlyQuoteDayForm();
                $dayForm->attributes = $dayFormData;
                $dayForm->validate();
                
                $model->dayForms[$key] = $dayForm;
            }
        }

        return $model;
    }

    public static function readBookingHourlyOneDay() {

        //$model = Booking::create(Booking::TYPE_HOURLY);

        $model = new BookingHourlyOneDayForm();

        if (isset($_REQUEST['date'])) {
            $model->date = $_REQUEST['date'];
        }

        if (isset($_REQUEST['BookingHourlyOneDayForm'])) {
            $model->attributes = $_REQUEST['BookingHourlyOneDayForm'];
        }

        return $model;
    }

    public static function readQbBookingHourlyOneDay() {

        $request = Yii::app()->request;
        $formDataRaw = $request->getParam('formData');
        $formData = array();
        parse_str($formDataRaw, $formData);

        $date = $formData['date'];
        $oneDayForm = $formData['BookingHourlyOneDayForm'];

        $startHour = $oneDayForm['startHour'];
        $startMinute = $oneDayForm['startMinute'];

        $endHour = $oneDayForm['endHour'];
        $endMinute = $oneDayForm['endMinute'];

        $model = new BookingHourlyOneDayForm();
        $model->date = $date;
        $model->startHour = $startHour;
        $model->startMinute = $startMinute;
        $model->endHour = $endHour;
        $model->endMinute = $endMinute;

        return $model;
    }

    public static function readBookingHourlyFourteen() {

        $model = new BookingHourly(Booking::SCENARIO_QUOTE_FOURTEEN);

        if (isset($_POST['start_date'])) {
            $dbStartDate = Calendar::convert_DisplayDate_DBDate($_POST['start_date']);
            $model->start_date_time = $dbStartDate;
        }

        if (isset($_POST['end_date'])) {
            $dbEndDate = Calendar::convert_DisplayDate_DBDate($_POST['end_date']);
            $model->end_date_time = $dbEndDate;
        }

        if (isset($_POST['Recurring'])) {
            $model->recurring = $_POST['Recurring'];
        }


        $days = array();

        if (isset($_POST['DayForm'])) {

            $daysForm = $_POST['DayForm'];

            $entries = count($daysForm);

            $firstSelectedDay = null;
            $lastSelectedDay = null;
            $firstSelectedDayIndex = -1;
            $lastSelectedDayIndex = -1;

            for ($i = 0; $i < $entries; $i++) {

                $dayForm = $daysForm[$i];
                $day = new DayForm();
                $day->date = $dayForm['date'];
                $day->startHour = $dayForm['startHour'];
                $day->startMinute = $dayForm['startMinute'];
                $day->endHour = $dayForm['endHour'];
                $day->endMinute = $dayForm['endMinute'];
                $day->selected = $dayForm['selected'];
                $day->scenario = Constants::TAB_HOURLY_FOURTEEN;

                if ($day->selected && $firstSelectedDay == null) {
                    $firstSelectedDay = $day->date;
                    $firstSelectedDayIndex = $i;
                }

                if ($day->selected) {
                    $lastSelectedDayWeek = Calendar::getDayOfWeekText($day->date);
                    $lastSelectedDayIndex = $i;
                }

                $day->dayWeek = Calendar::getDayOfWeekNumber($day->date);
                $days[] = $day;
            }

            //adjust start/end date if empty boudarie
//            $count = count($days);
//
//            //start
//            $model->start_date_time = $firstSelectedDay;
//            if ($firstSelectedDayIndex != 0) {
//                for ($i = 0; $i < $firstSelectedDayIndex; $i++) {
//                    unset($days[$i]);
//                }
//            }
//
//            //end
//            if ($model->recurring == false) {
//
//                if ($lastSelectedDayWeek != Calendar::getDayOfWeekText($model->end_date_time)) {
//
//                    $time = strtotime($model->end_date_time);
//
//                    $newEndDate = date('Y-m-d', strtotime('previous ' . $lastSelectedDayWeek, $time));
//
//                    $model->end_date_time = $newEndDate;
//
//                    //remove days
//
//                    for ($i = $lastSelectedDayIndex + 1; $i < $count; $i++) {
//                        unset($days[$i]);
//                    }
//                }
//            }
        }


        $model->setDayForms($days);

        return $model;
    }

    public static function readBookingHourlyRegularly() {

        $model = new BookingHourlyRegularlyForm();

        if (isset($_POST['startDate'])) {
            $dbStartDate = Calendar::convert_DisplayDate_DBDate($_POST['startDate']);
            $model->startDate = $dbStartDate;
        }

        if (isset($_POST['endDate'])) {
            $dbEndDate = Calendar::convert_DisplayDate_DBDate($_POST['endDate']);
            $model->endDate = $dbEndDate;
        }

        if (isset($_POST['Recurring'])) {
            $model->recurring = $_POST['Recurring'];

            if ($model->recurring == 1) {
                $model->scenario = Booking::SCENARIO_RECURRING;
            } else {
                $model->scenario = Booking::SCENARIO_END_DATE;
            }
        }

        $days = array();

        $daysForm = $_POST['DayForm'];

        $entries = count($daysForm);

//        $firstSelectedDay = null;
//        $lastSelectedDay = null;
//        $firstSelectedDayIndex = -1;
//        $lastSelectedDayIndex = -1;

        for ($i = 0; $i < $entries; $i++) {

            $dayForm = $daysForm[$i];
            $day = new DayForm();

            $day->startHour = $dayForm['startHour'];
            $day->startMinute = $dayForm['startMinute'];
            $day->endHour = $dayForm['endHour'];
            $day->endMinute = $dayForm['endMinute'];
            $day->selected = $dayForm['selected'];
            $day->scenario = Constants::TAB_HOURLY_REGULARLY;

//            if ($day->selected && $firstSelectedDay == null) {
//                $firstSelectedDay = $day->date;
//                $firstSelectedDayIndex = $i;
//            }
//
//            if ($day->selected) {
//                $lastSelectedDayWeek = Calendar::getDayOfWeekText($day->date);
//                $lastSelectedDayIndex = $i;
//            }
            //$day->dayWeek = Calendar::getDayOfWeekNumber($day->date);
            $day->dayWeek = $i + 1;
            $days[] = $day;
        }

        $model->setDayForms($days);

        return $model;
    }

    public static function readDayForm() {

        $form = array();
        parse_str($_POST['formDays'], $form);
        if (isset($form['BookingHourlyDayForm'])) {

            $formDays = $form['BookingHourlyDayForm'];
            $previousJsValues = array();
            foreach ($formDays as $formDay) {

                $weekDay = Calendar::getDayOfWeekNumber($formDay['date']);
                $previousValues[$weekDay] = array(
                    'startHour' => $formDay['startHour'],
                    'startMinute' => $formDay['startMinute'],
                    'endHour' => $formDay['endHour'],
                    'endMinute' => $formDay['endMinute'],
                    'selected' => $formDay['selected'],
                );
            }
            return $previousValues;
        } else {
            return null;
        }
    }

    public static function readFindCarersSelected() {

        $carerIds = array();
        $post = $_GET;
        foreach ($post as $key => $value) {
            if (Util::startsWith($key, 'selected_carers_')) {
                $carerIds[] = Util::lastCharactersAfter($key, 'selected_carers_');
            }
        }

        return $carerIds;
    }

    public static function readFindCarersCriteria() {

        $request = Yii::app()->request;
        $filtersRaw = $request->getParam('filters');

        if (!isset($filtersRaw)) {
            $filters = $_GET;
        } else {
            $filters = array();
            parse_str($filtersRaw, $filters);
        }

        $criteria = $filters;

        $postCodeRaw = $criteria['postCode'];
        $postCodeTrimmed = trim(strtoupper($postCodeRaw));
        $postCode = CHtml::encode($postCodeTrimmed);
        if (empty($postCode)) {
            $postCode = null;
        }

        $criteria['postCode'] = $postCode;

        if (!isset($filters['showMale'])) {
            $criteria['showMale'] = false;
        }

        if (!isset($filters['showFemale'])) {
            $criteria['showFemale'] = false;
        }

        //store them in session 
        Session::setFindCarersCriteria($criteria);

        return $criteria;
    }

}

?>