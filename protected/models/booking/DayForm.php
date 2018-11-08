<?php


class DayForm extends CFormModel {

    //used by form
    public $date;
    public $startHour;
    public $startMinute;
    public $endHour;
    public $endMinute;
    
    //used for backend
    public $startDateTime;
    public $endDateTime;
    
    //not really used anymore...
    public $selected;
    public $dayWeek;
    public $scenario; //14 days or regularly

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('startHour', 'checkDay'),
        );
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

    public function getDurationHours() {
        return $this->getDurationSeconds() / 3600;
    }

    public function getPrice() {

        $price = Prices::getPrice(Constants::USER_CLIENT, Prices::HOURLY_PRICE);

        return $price->multiply($this->getDurationHours());
    }

    public function checkDay() {

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

    public function getDayOfWeekText($short = true) {

        if (isset($this->date)) {
            return Calendar::getDayOfWeekText($this->date, Calendar::FORMAT_DBDATE, $short);
        } else {
            return Calendar::getDayOfWeekTextFromNumber($this->dayWeek);
        }
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
//    public function attributeLabels() {
//        return array(
//            'email' => 'Email address',
//            'verifyCode' => 'Verification Code',
//        );
//    }
}

?>
