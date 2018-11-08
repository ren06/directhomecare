<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Calendar
 *
 * @author I031360
 */
class Calendar {

    const FORMAT_DBDATE = 1; //rename en accordance avec noms ci dessous
    const FORMAT_DISPLAYDATE = 2;
    const FORMAT_TIMESTAMP = 3;
    const FORMAT_DBDATETIME = 4;
    const DROPDOWNHOURS_ALL = 0;
    const DROPDOWNHOURS_LIVEIN = 1;
    const DROPDOWNHOURS_HOURLY = 2;

    /**
     * Format example
     *
     * dbDate : 2012-12-31
     * dbDatetime : 2012-12-31 21:34:04 (string)
     * displayDate : 31/12/13
     * displayDateTime : 31/12/13 at 12:34
     * displayDateText : 31 December 2012 (short 31 Dec 2012)
     * displayDateTimeText : 31 December 2012 at 12:34 (short 31 Dec 2012 at 12:34)
     * timestamp: 242141242421 unix timestamp
     * time: 21:34:04
     * displayTime: 21:34
     *
     */

    /**
     * Convert 2013-12-01 in 12 December 2013
     * @param string $dbDate 2013-12-01
     * @param string $fullMonth if true December instead of Dec
     * @param string $separator string used as the separator between date elements
     * @return string example 12 December 2013
     */
    public static function convert_DBDate_DisplayDateText($dbDate, $fullMonth = false, $separator = ' ') {

        if ($dbDate == null) {

            return '';
        } else {

            $year = substr($dbDate, 0, 4);
            $month = substr($dbDate, 5, 2);
            $date = substr($dbDate, 8, 2);

            switch ($month) {
                case 1: ($fullMonth) ? $month = 'January' : $month = 'Jan';
                    break;
                case 2: ($fullMonth) ? $month = 'February' : $month = 'Feb';
                    break;
                case 3: ($fullMonth) ? $month = 'March' : $month = 'Mar';
                    break;
                case 4: ($fullMonth) ? $month = 'April' : $month = 'Apr';
                    break;
                case 5: ($fullMonth) ? $month = 'May' : $month = 'May';
                    break;
                case 6: ($fullMonth) ? $month = 'June' : $month = 'Jun';
                    break;
                case 7: ($fullMonth) ? $month = 'July' : $month = 'Jul';
                    break;
                case 8: ($fullMonth) ? $month = 'August' : $month = 'Aug';
                    break;
                case 9: ($fullMonth) ? $month = 'September' : $month = 'Sep';
                    break;
                case 10: ($fullMonth) ? $month = 'October' : $month = 'Oct';
                    break;
                case 11: ($fullMonth) ? $month = 'November' : $month = 'Nov';
                    break;
                case 12: ($fullMonth) ? $month = 'December' : $month = 'Dec';
                    break;
            }

            return $date . $separator . $month . $separator . $year;
        }
    }

    /**
     * Display a db date time format date in plain english
     * @param string $dbDateTime Format 2013-01-31 12:21:02
     * @param string $fullMonth if true display December, if false display Dec
     * @return string 12 Decembre 2013
     */
    public static function convert_DBDateTime_DisplayDateText($dbDatetime, $fullMonth = false, $bold = false) {
        $result = self::convert_DBDate_DisplayDateText(self::convert_DBDateTime_DBDate($dbDatetime), $fullMonth);
        if ($bold == true) {
            $result = '<b>' . $result . '</b>';
        }
        return $result;
    }

    /**
     * Display a db date format date in english format 
     * @param string $dbDateTime Format 2013-01-31 12:21:02 or dbDate 2013-01-31
     * @param string $fullMonth if true display December, if false display Dec
     * @return string 31/12/2013
     */
    public static function convert_DBDateTime_DisplayDate($dbDatetime) {

        if ($dbDatetime == null) {

            return '';
        } else {

            $year = substr($dbDatetime, 0, 4);
            $month = substr($dbDatetime, 5, 2);
            $date = substr($dbDatetime, 8, 2);

            return $date . '/' . $month . '/' . $year;
        }
    }

    /**
     * Display a db date format date in plain english
     * @param string $dbDateTime Format 2013-01-31 12:21:02
     * @param string $fullMonth if true display December, if false display Dec
     * @return string 12 Decembre 2013 at 12:30
     */
    public static function convert_DBDateTime_DisplayDateTimeText($dbDateTime, $fullMonth = false, $separator = '&#32;', $bold = false) {
        $result = ''; //RC to declare var if bold=false
        if ($bold == true) {
            $result = '<b>';
        }
        $result .= self::getDayOfWeekText($dbDateTime) . ' ' . self::convert_DBDate_DisplayDateText(self::convert_DBDateTime_DBDate($dbDateTime, $fullMonth));
        if ($bold == true) {
            $result .= '</b>';
        }
        $result .= $separator;
        $result .= self::convert_DBDateTime_DisplayTime($dbDateTime);
        return $result;
    }
    
    public static function convert_DBDateTime_DisplayDateTimeTextConcise($dbDateTime, $separator = '&#32;') {
        $result = ''; 

        $result .= self::getDayOfWeekText($dbDateTime, self::FORMAT_DBDATE, true) 
                . ' ' . self::convert_DBDate_DisplayDateText(self::convert_DBDateTime_DBDate($dbDateTime, false));

        $result .= $separator;
        $result .= self::convert_DBDateTime_DisplayTime($dbDateTime);
        return $result;
    }

    /**
     * Extract time from dbDateTime
     * @param string $dbDateTime example 2013-01-31 12:21:02
     * @return string String 12:21:02
     */
    public static function convert_DBDateTime_Time($dbDateTime, $includeSeconds = true) {

        if (isset($dbDateTime)) {

            if ($includeSeconds) {
                $pattern = 'H:i:s';
            } else {
                $pattern = 'H:i';
            }

            return date_format(new DateTime($dbDateTime), $pattern);
        }
    }

    /**
     * Extract time from dbDateTime
     * @param string $dbDateTime example 2013-01-31 12:21:02
     * @return string String 12:21:02
     */
    public static function convert_DBDateTime_Timestamp($dbDateTime) {

        if (isset($dbDateTime)) {
            $dt = new DateTime($dbDateTime);
            return $dt->getTimestamp();
        }
    }

    /**
     * Extract display time from dbDateTime
     * @param string String example 2013-01-31 12:21:02
     * @return string String 12:21
     */
    public static function convert_DBDateTime_DisplayTime($dbDateTime) {

        if (isset($dbDateTime)) {
            return date_format(new DateTime($dbDateTime), 'H:i');
        }
    }

    /**
     * Extract dbDate from dbDateTime
     * @param string $dbDateTime example 2013-01-31 12:21:02
     * @return string 2013-01-31
     */
    public static function convert_DBDateTime_DBDate($dbDateTime) {

        if (isset($dbDateTime)) {

            return date_format(new DateTime($dbDateTime), 'Y-m-d');
        }
    }

    /**
     * Extract dbDate from dbDateTime
     * @param string $dbDateTime example 2013-01-31 12:21:02
     * @return string 2013-01-31
     */
    public static function convert_DisplayDate_DBDate($displayDate) {

        if (isset($displayDate) && !empty($displayDate)) {

            $timestamp = strtotime(str_replace('/', '-', $displayDate));
            return date('Y-m-d', $timestamp);
            //return date_format(new DateTime($timestamp), 'Y-m-d');
        } else {
            return null;
        }
    }

    public static function convert_Time_Minute($dbTime) {

        return substr($dbTime, 3, 2);
    }

    public static function convert_Time_Hour($dbTime) {

        return substr($dbTime, 0, 2);
    }

    /*
     *  Logic
     */

    public static function getWeekRange($date) {

        //$myDateTime = DateTime::createFromFormat('d/m/Y', $date);
        //$newDateString = $myDateTime->format('Y-m-d');

        $ts = strtotime($date);

        if (date('w', $ts) == 0) {

            //sunday
            return array(
                date('Y-m-d', strtotime('last monday', $ts)),
                date('Y-m-d', $ts),
            );
        } else {

            $start = strtotime('monday this week', $ts);
            return array(date('Y-m-d', $start),
                date('Y-m-d', strtotime('next sunday', $start)));
        }
    }

    public static function weeksBetween_DBDate($startDate, $endDate) {

        //convert 01/12/2012 to timestamp
        $startDate = DateTime::createFromFormat('Y-m-d', $startDate)->getTimestamp();
        $endDate = DateTime::createFromFormat('Y-m-d', $endDate)->getTimestamp();

        $day_of_week = date("w", $startDate);
        $fromweek_start = $startDate - ($day_of_week * 86400) - ($startDate % 86400);
        $diff_days = self::daysBetween_Timestamp($startDate, $endDate);
        $diff_weeks = intval($diff_days / 7);
        $seconds_left = ($diff_days % 7) * 86400;

        if (($startDate - $fromweek_start) + $seconds_left > 604800)
            $diff_weeks++;

        return $diff_weeks;
    }

    /**
     * Return the number of days between 2 timestamps 
     */
    public static function daysBetween_Timestamp($startDate, $endDate) {

        $fromday_start = mktime(0, 0, 0, date("m", $startDate), date("d", $startDate), date("Y", $startDate));
        $diff = $endDate - $startDate;
        $days = intval($diff / 86400); // 86400  / day

        if (($startDate - $fromday_start) + ($diff % 86400) > 86400)
            $days++;

        return $days;
    }

    /**
     * 
     * Return the number of days between 2 dbDates or dbDateTime string
     * 
     * @param type $startDate 2012-12-01 or 2012-12-01 12:00:01
     * @param type $endDate 2012-12-01 or 2012-12-01 12:00:01
     * @return type integer. ceil is used (1.8 days return 2 days)
     */
    public static function daysBetween_DBDate($startDateTime, $endDateTime) {


        $days = (strtotime($endDateTime) - strtotime($startDateTime)) / (60 * 60 * 24);

//        $date1 = new DateTime($startDate);
//        $date2 = new DateTime($endDate);
//
//        $diff = $date2->diff($date1, true);
//
//        $days = $diff->d;

        return ceil($days); //buf with time change
    }

    /**
     * 
     * Calculate number of days between 2 dates, including the given dates. 
     * If dates identical, return 1
     * 
     * @param type $startDateTime
     * @param type $endDateTime
     */
    public static function numberDays_DBDate($startDateTime, $endDateTime) {

        $days = (strtotime($endDateTime) - strtotime($startDateTime)) / (60 * 60 * 24);

        $value = $days + 1;

        return $value;
    }

    /**
     * Number hours between two dbDateTime dates (dates have to be within the same month!)
     * 
     * rounds fraction up if optional parameter not set. Otherwise return a decimal number
     */
    public static function hoursBetween_DBDateTime($startDateTime, $endDateTime, $roundUp = true) {

        $hours = (strtotime($endDateTime) - strtotime($startDateTime)) / (60 * 60);
        if ($roundUp) {
            return ceil($hours); //buf with time change
        } else {
            return $hours;
        }
    }

    public static function getStartAndEndDate($week, $year) {

        //week seems to start at 0

        $time = strtotime("1 January $year", Calendar::today(Calendar::FORMAT_TIMESTAMP));
        $day = date('w', $time);
        $time += ( (7 * $week) + 1 - $day) * 24 * 3600;
        $return[0] = date('Y-m-d', $time);
        $time += 6 * 24 * 3600;
        $return[1] = date('Y-m-d', $time);
        return $return;
    }

    /**
     * Give the previous day of a given dbDate
     * @param type $date dbDate
     * @return type dbDate
     */
    public static function getPreviousDay($date) {

        $stro = strtotime($date); //convert to timestamp
        $stro2 = strtotime('-1 day', $stro); //result in timestamp
        $result = date('Y-m-d', $stro2); //convert into string
        return $result;
    }

    /**
     * Give the next day of a given dbDate
     * @param type $date dbDate
     * @return type dbDate
     */
    public static function getNextDay($date) {

        $stro = strtotime($date); //convert to timestamp
        $stro2 = strtotime('+1 day', $stro); //result in timestamp
        $result = date('Y-m-d', $stro2); //convert into string
        return $result;
    }

    public static function getLastSunday($dbDate = null) {

        if ($dbDate == null) {

            $time = Calendar::today(Calendar::FORMAT_TIMESTAMP);
        } else {
            $time = strtotime($dbDate);
        }

        $today = date('l', $time);

        if ($today == 'Sunday') {
            return date('Y-m-d', $time);
        } else {
            return date('Y-m-d', strtotime('previous Sunday', $time));
        }
    }

    /**
     * Return name of day of the week
     * 
     * @param type $dateTime
     * @return type text day of week (Monday, Thursday...)
     */
    public static function getDayOfWeekText($dateTime, $format = self::FORMAT_DBDATE, $short = true) {

        if ($format == self::FORMAT_DISPLAYDATE) {
            $dateTime = Calendar::convert_DisplayDate_DBDate($dateTime);
        }
        if ($short) {
            return date('D', strtotime($dateTime));
        } else {
            return date('l', strtotime($dateTime));
        }
    }

    /**
     * 
     * return 1 for Monday, 7 for Sunday
     * 
     * @param type dateTime
     * @return type integer
     */
    public static function getDayOfWeekNumber($dateTime, $format = self::FORMAT_DBDATE) {

        if ($format == self::FORMAT_DISPLAYDATE) {
            $dateTime = Calendar::convert_DisplayDate_DBDate($dateTime);
        }

        return date('N', strtotime($dateTime));
    }

    /**
     * 1 return Monday, 2 Tuesday etc...
     * 
     * @param type $dayWeekNumber
     */
    public static function getDayOfWeekTextFromNumber($dayWeekNumber) {

        $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

        return $weekdays[$dayWeekNumber - 1];
    }

    /**
     * Get the date from today in $numberDays days
     * @param type $numberDays
     * @param type $format
     * @return type date
     */
    public static function getDateFromToday($numberDays, $format = Calendar::FORMAT_DISPLAYDATE) {

        $date = self::today($format);

        return self::addDays($date, $numberDays, $format);

        //$date = mktime(0, 0, 0, date("m"), date("d") + $numberDays, date("Y"));
//        
//
//        switch ($format) {
//
//            case self::FORMAT_DBDATE:
//
//                return date('Y-m-d', $date);
//
//            case self::FORMAT_DISPLAYDATE:
//
//                return date('d/m/Y', $date);
//
//            case self::FORMAT_TIMESTAMP:
//
//                return $date;
//        }
    }

    /**
     * Return array containing value for day hours minutes second
     * @param int $seconds seconds
     * @param boolean $display if true return a text
     * @return return array with value for day hours minutes and seconds
     */
    public static function convert_Seconds_DayHoursMinutesSeconds($seconds, $display = false, $noDays = false) {

        // extract days
        $days = floor($seconds / (60 * 60 * 24));

        // extract hours
        $divisor_for_hours = $seconds % (60 * 60 * 24);
        $hours = floor($divisor_for_hours / 3600);

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);

        //adjust days hours if noDays is true
        if ($noDays) {

            $hours = $hours + ($days * 24);
            $days = 0;
        }

        if ($display) {

            $result = '';

            if ($days > 0) {
                $result .= $days . '&#160;' . Yii::t('texts', 'LABEL_DAYS');
            }
            if (($days > 0 && $hours > 0) || ($days > 0 && $minutes > 0) || ($days > 0 && $seconds > 0)) {
                $result .= '&#32;';
            }
            if ($hours > 0) {
                $result .= $hours . Yii::t('texts', 'LABEL_HOURS');
            }
            if (($hours > 0 && $minutes > 0) || ($hours > 0 && $seconds > 0)) {
                $result .= '&#32;';
            }
            if ($minutes > 0) {
                $result .= $minutes . Yii::t('texts', 'LABEL_MINUTES');
            }
            if ($minutes > 0 && $seconds > 0) {
                $result .= '&#32;';
            }
            if ($seconds > 0) {
                $result .= $seconds . Yii::t('texts', 'LABEL_SECONDS');
            }
            
            if($result == ''){ //for nicer display when 0
                 $result = '0' . Yii::t('texts', 'LABEL_HOURS');;
            }

            return $result;
        } else {

            $obj = array(
                "d" => (int) $days,
                "h" => (int) $hours,
                "m" => (int) $minutes,
                "s" => (int) $seconds,
            );
            return $obj;
        }
    }

    /**
     * Return time() to the given format
     * @param type $format Calendar::FORMAT_DBDATE, Calendar::FORMAT_DBDATETIME etc..
     * @return type time with given format 
     */
    public static function today($format = self::FORMAT_DBDATETIME) {

        //TEST ONLY

        if (Yii::app()->params['test']['setTime'] == true && isset(Yii::app()->params['test']['today'])) {

            $today = Yii::app()->params['test']['today'];

            switch ($format) {

                case self::FORMAT_DBDATE:

                    return date_format(new DateTime($today), 'Y-m-d');

                case self::FORMAT_DBDATETIME:

                    return $today;

                case self::FORMAT_DISPLAYDATE:

                    return date_format(new DateTime($today), 'd/m/Y');
                // return date('d/m/Y', $today);

                case self::FORMAT_TIMESTAMP:

                    $dt = new DateTime($today);
                    return $dt->getTimestamp();
            }
        } else {

            date_default_timezone_set('Europe/London');

            $date = new DateTime(null); //, new DateTimeZone('Europe/Amsterdam'));

            $now = $date->getTimestamp();
//            $debug = date('Y-m-d', $now);
//            $debug2 = date('Y-m-d H:i:s');

            switch ($format) {

                case self::FORMAT_DBDATE:

                    return date('Y-m-d', $now);

                case self::FORMAT_DBDATETIME:

                    $dateF = date('Y-m-d H:i:s', $now);

                    return $dateF;

                case self::FORMAT_DISPLAYDATE:

                    return date('d/m/Y', $now);

                case self::FORMAT_TIMESTAMP:

                    return $now;
            }
        }
    }

    /**
     * Figure out durations of given MissionHourly or MissionLiveIn
     * 
     * @return int Timestamp value
     */
    public static function duration_Seconds($model) {

        $startDateTime = self::convert_DBDateTime_Timestamp($model->start_date_time);
        $endDateTime = self::convert_DBDateTime_Timestamp($model->end_date_time);

        return $endDateTime - $startDateTime;
    }

    /**
     * Only look at start date, start time, end date, and end time of given object
     */
    public static function duration_Hours($model) {

        return self::hoursBetween_DBDateTime($model->start_date_time, $model->end_date_time);
    }

    /**
     * Calculate duration between two date times in seconds
     * @param type $startDateTime
     * @param type $endDateTime
     * @return type seconds int
     */
    public static function calculate_Duration_Seconds($startDateTime, $endDateTime) {

        return self::convert_DBDateTime_Timestamp($endDateTime) - self::convert_DBDateTime_Timestamp($startDateTime);
    }

    public static function calculate_Age($birth_date) {

        $current_date = date('Y-m-d');
        $diff_in_mill_seconds = strtotime($current_date) - strtotime($birth_date);
        $age = floor($diff_in_mill_seconds / (365.2425 * 60 * 60 * 24)) + 1; //365.2425 is the no. of days in a year.  
        return $age;
    }

    /**
     * Display the duration in day hour minutes month seconds
     * 
     * @param type $startDateTime
     * @param type $endDateTime
     * @param type $noDays : if true, 2 days and 3 hours = 51 hours
     * 
     * @return string e. 12 days 2 hours 4 minutes 2 seconds
     */
    public static function calculate_Duration_DisplayAll($startDateTime, $endDateTime, $noDays = false) {

        $duration = Calendar::calculate_Duration_Seconds($startDateTime, $endDateTime);

        $result = Calendar::convert_Seconds_DayHoursMinutesSeconds($duration, true, $noDays);

        return $result;
    }

    /**
     * 
     * Split time range in slots of weeks
     * 
     * Slower algo?
     * 
     * @param type $startDate
     * @param type $endDate
     */
    public static function splitBookingHourly2($startDate, $endDate) {

        $numberDays = Calendar::daysBetween_DBDate($startDate, $endDate);

        $maxDays = BusinessRules::getHourlyBookingPaymentMaximumDays();

        $results = array();

        $j = -1;

        for ($i = 0; $i <= $numberDays; $i++) {

            $j++;

            $date = Calendar::addDays($startDate, $i, Calendar::FORMAT_DBDATE);

            if ($j == 0) {
                $startDay = $date;
            } elseif ($j == $maxDays - 1) {
                $j = -1;
                $results[] = array('startDay' => $startDay, 'endDay' => $date);
            }

            if ($i == $numberDays) {
                //last iteration
                $results[] = array('startDay' => $startDay, 'endDay' => $date);
            }
        }

        return $results;
    }

    /**
     * 
     * Split given date range in slots of 14 days (business rule)
     * 
     * @param type $startDate
     * @param type $endDate
     * @return type array ['startDay'] and ['endDay']
     */
    public static function splitBookingHourly($startDate, $endDate) {

        $maxDays = BusinessRules::getHourlyBookingPaymentMaximumDays();

        $results = array();

        $startDay = $startDate;

        while (true) {

            $endDay = Calendar::addDays($startDay, $maxDays - 1, Calendar::FORMAT_DBDATE);

            if (Calendar::dateIsBefore($endDate, $endDay)) {//too far
                if (Calendar::dateIsBefore($startDay, $endDate, true)) {
                    $results[] = array('startDay' => $startDay, 'endDay' => $endDate); //endDate
                }

                break;
            } else {
                $results[] = array('startDay' => $startDay, 'endDay' => $endDay);
                $startDay = Calendar::addDays($endDay, 1, Calendar::FORMAT_DBDATE);
            }
        }

        return $results;
    }

    /**
     * Split time range into slots of 2 weeks maximum.
     * 
     * Works only in DB format eg. 2012-12-25
     */
    public static function splitBookingLiveIn($startDate, $endDate) {

        $startRange = self::getWeekRange($startDate);
        $endRange = self::getWeekRange($endDate);

        $weeks = self::weeksBetween_DBDate($startRange[0], $endRange[1]);

        $length = BusinessRules::getLiveInMissionPaymentNumberDays();

        if ($weeks <= 3) {

            $results[] = array('startDay' => $startDate, 'endDay' => $endDate);
        } else {

            $daysBetween = self::daysBetween_DBDate($startDate, $endDate);

            if ($daysBetween <= 21) {
                $results[] = array('startDay' => $startDate, 'endDay' => $endDate);
            } else {

                $results = array();

                $starDateStamp = DateTime::createFromFormat("Y-m-d", $startDate);
                $year = $starDateStamp->format("Y");
                $week = $starDateStamp->format("W") - 1; //week starts at zero in getStartAndEndDate

                $startDay = null;
                $endDay = null;

                $lastWeekIndex = $weeks - 1;

                $firstTime = true;
                $end = false;

                while ($end == false) {

                    if ($firstTime) {

                        $startDay = $startDate;
                        $endDay = Calendar::addDays($startDay, $length, Calendar::FORMAT_DBDATE);
                        $results[] = array('startDay' => $startDay, 'endDay' => $endDay);
                        $firstTime = false;
                    } else {

                        $startDay = $endDay; //'Calendar::addDays($endDay, 1, Calendar::FORMAT_DBDATE);
                        $endDay = Calendar::addDays($startDay, $length, Calendar::FORMAT_DBDATE);
                        $results[] = array('startDay' => $startDay, 'endDay' => $endDay);
                    }

                    //if current slot end day is after entered endData, stop it
                    if (Calendar::dateIsBefore($endDate, $endDay, true)) {

                        $end = true;
                    }
                }

                //set the last slot to end date 
                $results[count($results) - 1]['endDay'] = $endDate;

                $lastSlot = $results[count($results) - 1];

                //if last slot is less than 7 days, merge it
                $lastSlotDays = self::daysBetween_DBDate($lastSlot['startDay'], $lastSlot['endDay']);

                if ($lastSlotDays < 7) {

                    //set the penultimate slot to end date and remove last one
                    $results[count($results) - 2]['endDay'] = $endDate;
                    ;
                    unset($results[count($results) - 1]);
                }
            }
        }

        return $results;
    }

    /**
     *
     * Works only in DB format eg. 2012-12-25
     */
    public static function getLiveInMissionSlots($startDate, $endDate) {

        $startRange = self::getWeekRange($startDate);
        $endRange = self::getWeekRange($endDate);

        $weeks = self::weeksBetween_DBDate($startRange[0], $endRange[1]);

        if ($weeks <= 3) {

            $results[] = array('startDay' => $startDate, 'endDay' => $endDate);
        } else {


            $results = array();

            $starDateStamp = DateTime::createFromFormat("Y-m-d", $startDate);
            $year = $starDateStamp->format("Y");
            $week = $starDateStamp->format("W") - 1; //week starts at zero in getStartAndEndDate


            for ($i = 0; $i <= $weeks; $i++) {

                if ($week == 52) {
                    $week = 0;
                    $year++;
                }

                $currentWeek = self::getStartAndEndDate($week, $year);

                if ($i == 0) {

                    if ($startDate == $currentWeek[0]) {
                        $results[] = array('startDay' => $currentWeek[0], 'endDay' => $currentWeek[1]);
                    }
                } elseif ($i == 1) {

                    $previoustWeek = self::getStartAndEndDate($week - 1, $year);

                    if ($previoustWeek[0] != $startDate) {
                        $results[] = array('startDay' => $startDate, 'endDay' => $currentWeek[1]);
                    } else {
                        $results[] = array('startDay' => Calendar::getPreviousDay($currentWeek[0]), 'endDay' => $currentWeek[1]);
                    }
                } elseif ($i == $weeks - 2) {

                    //get next week
                    $nextWeek = self::getStartAndEndDate($week + 1, $year);

                    if ($endDate == $nextWeek[1]) {

                        $results[] = array('startDay' => Calendar::getPreviousDay($currentWeek[0]), 'endDay' => $currentWeek[1]);
                    } else {

                        $results[] = array('startDay' => Calendar::getPreviousDay($currentWeek[0]), 'endDay' => $endDate);
                        break;
                    }
                } elseif ($i == $weeks - 1) {

                    $results[] = array('startDay' => Calendar::getPreviousDay($currentWeek[0]), 'endDay' => $endDate);
                    break;
                } else {

                    $results[] = array('startDay' => Calendar::getPreviousDay($currentWeek[0]), 'endDay' => $currentWeek[1]);
                }

                $week++;
            }
        }

        return $results;
    }

    /*
     *
     * Section to Fill in Drop Downs
     *
     */

    public static function getDropDownDays() {

        $days = array();
        $days[0] = Yii::t('texts', 'DAY');

        for ($i = 1; $i <= 31; $i++) {

            $days[$i] = $i;
        }
        return $days;
    }

    public static function getDropDownMonths($locale = null) {
        if ($locale == null) {
            $locale = Yii::app()->params['datePickerLocation'];
        }
        $month[0] = Yii::t('texts', 'MONTH');
        $month = array_merge($month, Yii::app()->getLocale($locale)->monthNames);

        return $month;
    }

    public static function getDropDownMonthsNumber() {

        for ($i = 1; $i <= 12; $i++) {

            $months[$i] = sprintf("%02d", $i);
        }

        return $months;
    }

    public static function getDropDownYears() {

        //get current year
        $year = date('Y');

        $yearInt = intval($year);

        $firstYear = $yearInt - 112;
        $lastYear = $yearInt - 0;

        $years = array();

        for ($yearInt = $lastYear; $yearInt >= $firstYear; $yearInt--) {

            $years[$yearInt] = $yearInt;
        }

        return $years;
    }

    public static function getDropDownCreditCardYears() {

        //get current year
        $startYear = date('Y');

        $startYearInt = intval($startYear);

        $years = array();

        for ($i = $startYearInt; $i <= $startYearInt + 6; $i++) {

            $years[$i] = $i;
        }

        return $years;
    }

    public static function getDropDownDiplomaYears() {

        //get current year
        $year = date('Y');

        $yearInt = intval($year);

        $firstYear = $yearInt;
        $lastYear = $yearInt - 60;

        $years = array();

        for ($yearInt = $firstYear; $yearInt >= $lastYear; $yearInt--) {

            $years[$yearInt] = $yearInt;
        }

        return $years;
    }

    /**
     * Hours for drop down
     * 
     * @param type $hoursType Calendar::DROPDOWNHOURS_ALL or DROPDOWNHOURS_HOURLY or DROPDOWNHOURS_LIVEIN
     * @return type
     */
    public static function getDropDownHours($hoursType) {

        $hours = array();

        switch ($hoursType) {

            case self::DROPDOWNHOURS_ALL:
                $start = 0;
                $end = 23;
                break;

            case self::DROPDOWNHOURS_LIVEIN:
                $start = 8;
                $end = 19;
                break;

            case self::DROPDOWNHOURS_HOURLY:
                $start = 6;
                $end = 22;
                break;
        }

        for ($hour = $start; $hour <= $end; $hour++) {

            $hourPad = str_pad($hour, 2, '0', STR_PAD_LEFT);

            $hours[$hourPad] = $hourPad;
        }
        return $hours;
    }

    public static function getDropDownMinutes($all = false) {

        if ($all) {
            for ($minute = 0; $minute <= 59; $minute++) {

                $minutePad = str_pad($minute, 2, '0', STR_PAD_LEFT);

                $minutes[$minutePad] = $minutePad;
            }
            return $minutes;
        } else {
            return array('00' => '00', '15' => '15', '30' => '30', '45' => '45');
        }
    }

    /**
     * 
     * Accepts dbDateTime or dbDate
     * 
     * if inclusive is true, start date == end date : return true
     * if you need to compare date with time, use timestamps
     */
    public static function dateIsBefore($beforeDbDateTime, $afterDbDateTime, $inclusive = false) {

        $beforeDate = self::convert_DBDateTime_Timestamp($beforeDbDateTime);
        $afterDate = self::convert_DBDateTime_Timestamp($afterDbDateTime);

        if ($beforeDate == $afterDate && ($inclusive == true)) {
            return true;
        }

        if ($beforeDate >= $afterDate) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Add number of days to date
     * 
     * @param type $date
     * @param type $numberDays
     * @param type $format
     * @return type
     */
    public static function addDays($date, $numberDays, $format) {

        switch ($format) {

            case self::FORMAT_DBDATE:

                $parse = strtotime($date);
                $res = date("Y-m-d", $parse);
                $res2 = strtotime($res . " +$numberDays day");

                return date('Y-m-d', $res2);

            case self::FORMAT_DISPLAYDATE:

                $dateDbDate = self::convert_DisplayDate_DBDate($date);

                return date('d/m/Y', strtotime(date("Y-m-d", strtotime($dateDbDate)) . " +$numberDays day"));

            case self::FORMAT_DBDATETIME:

                return date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +$numberDays day"));

            case self::FORMAT_TIMESTAMP:

                return strtotime(strtotime($date) . " +$numberDays day");
        }
    }

    /**
     * Add number of hours to date
     * 
     * @param type $date
     * @param type $numberDays
     * @param type $format
     * @return type
     */
    public static function addHours($date, $numberHours, $format) {

        switch ($format) {

            case self::FORMAT_DBDATE:

                return date('Y-m-d', strtotime(date("Y-m-d", strtotime($date)) . " +$numberHours hour"));

            case self::FORMAT_DISPLAYDATE:

                $dateDbDate = self::convert_DisplayDate_DBDate($date);

                return date('d/m/Y', strtotime(date("Y-m-d", strtotime($dateDbDate)) . " +$numberHours hour"));

            case self::FORMAT_DBDATETIME:

                return date('Y-m-d H:m:s', strtotime(date("Y-m-d H:i:s", strtotime($date)) . " +$numberHours hour"));

            case self::FORMAT_TIMESTAMP:

                return strtotime(strtotime($date) . " +$numberHours hour");
        }
    }

    public static function elapsedTime_FromNow($dbDateTime, $display = false) {

        $date = Calendar::convert_DBDateTime_Timestamp($dbDateTime);
        $currentTime = Calendar::today(Calendar::FORMAT_TIMESTAMP);

        $elapsed = $currentTime - $date;

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($elapsed, $display);
    }

    public static function convert_DBDateTime_Borgun_CardExpiryDate($dbDateTime) {
        $year = substr($dbDateTime, 2, 2);
        $month = substr($dbDateTime, 5, 2);

        return $year . $month;
    }

    /**
     * Convert 2013-12-31 10:20:12 to 131231102012
     * 
     * @param type $dbDateTime
     * @return string format YYMMDDhhmmss
     */
    public static function convert_DBDateTime_BorgunDateTimeShortYear($dbDateTime) {

        $year = substr($dbDateTime, 2, 2);
        $month = substr($dbDateTime, 5, 2);
        $date = substr($dbDateTime, 8, 2);
        $hour = substr($dbDateTime, 11, 2);
        $minute = substr($dbDateTime, 14, 2);
        $second = substr($dbDateTime, 17, 2);

        $dateTime = $year . $month . $date . $hour . $minute . $second;

        return $dateTime;
    }

    /**
     * 20130903182212 to 2013-09-03 18:22:12
     * 
     * @param type $borgunDateTime
     * @return string
     */
    public static function convert_BorgunDateTimeLongYear_DBDateTime($borgunDateTime) {

        //  2013-09-03 18:22:12
        $year = substr($borgunDateTime, 0, 4);
        $month = substr($borgunDateTime, 4, 2);
        $date = substr($borgunDateTime, 6, 2);
        $hour = substr($borgunDateTime, 8, 2);
        $minute = substr($borgunDateTime, 10, 2);
        $second = substr($borgunDateTime, 12, 2);

        $dbDateTime = "$year-$month-$date $hour:$minute:$second";

        return $dbDateTime;
    }

    /**
     * 130903182212 to 2013-09-03 18:22:12
     * 
     * @param type $borgunDateTime
     * @return string
     */
    public static function convert_BorgunDateTimeShortYear_DBDateTime($borgunDateTime) {

        // 131119175729
        $year = substr($borgunDateTime, 0, 2);
        $month = substr($borgunDateTime, 2, 2);
        $date = substr($borgunDateTime, 4, 2);
        $hour = substr($borgunDateTime, 6, 2);
        $minute = substr($borgunDateTime, 8, 2);
        $second = substr($borgunDateTime, 10, 2);

        $dbDateTime = "20$year-$month-$date $hour:$minute:$second";

        return $dbDateTime;
    }

    public static function convert_BorgunDateTime_DisplayDateTime($borgunDateTime) {

        $dbDateTime = self::convert_BorgunDateTimeLongYear_DBDateTime($borgunDateTime);

        return Calendar::convert_DBDateTime_DisplayDateTimeText($dbDateTime, false, '', false);
    }

    public static function convert_DBDateTime_BorgunDate($dbDateTime) {

        $year = substr($dbDateTime, 0, 4);
        $month = substr($dbDateTime, 5, 2);
        $date = substr($dbDateTime, 8, 2);

        $date = $year . $month . $date;

        return $date;
    }

    public static function getWorkingDays($startDate, $endDate) {
        $begin = strtotime($startDate);
        $end = strtotime($endDate);
        if ($begin > $end) {
            echo 'startdate is in the future! <br />';
            return 0;
        } else {
            $no_days = 0;
            $weekends = 0;
            while ($begin <= $end) {
                $no_days++; // no of days in the given interval
                $what_day = date('N', $begin);
                if ($what_day > 5) { // 6 and 7 are weekend days
                    $weekends++;
                };
                $begin+=86400; // +1 day
            };
            $working_days = $no_days - $weekends;
            return $working_days;
        }
    }

    public static function draw_calendar($month, $year) {

        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="rc-calendar">';
        $dt = DateTime::createFromFormat('!m', $month);
        $monthText = strtoupper($dt->format('F'));
        $calendar.= '<tr> <td class="rc-calendar-month" colspan="7">' . $monthText . ' ' . $year . '</td></tr>';
        /* table headings */
        $headings = array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY');
        $calendar.= '<tr class=""><td class="rc-calendar-week">' . implode('</td><td class="rc-calendar-week">', $headings) . '</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w', mktime(0, 0, 0, $month, 0, $year));
        $days_in_month = date('t', mktime(0, 0, 0, $month, 0, $year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar.= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for ($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="rc-calendar-day rc-calendar-day-past"> </td>';
            $calendar.= '<div class="day-number">' . '</div>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $calendar.= '<td class="rc-calendar-day">';
            /* add in the day number */
            $calendar.= '<span class="rc-calendar-day-number">' . $list_day . '</span>';

            /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! * */
            $cell = '<span class = "day_content">';
            $cell .= CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'rc-button-white-small button-select'));
            $cell .= '</span>';

            $calendar.= $cell;
            $calendar.= '</td>';
            if ($running_day == 6):
                $calendar.= '</tr>';
                if (($day_counter + 1) != $days_in_month):
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++;
            $running_day++;
            $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if ($days_in_this_week < 8):
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="rc-calendar-day"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';

        /* end the table */
        $calendar.= '</table>';

        /* all done, return result */
        return $calendar;
    }

}

?>
