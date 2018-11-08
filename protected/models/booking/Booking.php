<?php

/**
 * This is the model class for table "tbl_booking".
 *
 * The followings are the available columns in table 'tbl_booking':
 * @property integer $id
 * @property integer $id_client
 * @property integer $id_credit_card
 * @property integer $id_address
 * @property string $start_date_time
 * @property string $end_date_time
 * @property integer $recurring
 * @property integer $type
 * @property integer $subtype
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Address $idAddress
 * @property Calendar $idCalendar
 * @property Client $idClient
 * @property CreditCard $idCreditCard
 * @property BookingException[] $bookingExceptions
 * @property ServiceUser[] $tblServiceUsers
 * @property Mission[] $missions
 */
class Booking extends ActiveRecord {
    //type  

    const TYPE_LIVE_IN = Constants::LIVE_IN;
    const TYPE_HOURLY = Constants::HOURLY;
    const TYPE_HOURLY_TEMP = 3;
    const TYPE_DECLINED = 4;
    const SUBTYPE_ONE_OFF = Constants::ONE_OFF;
    const SUBTYPE_TWO_FOURTEEN = Constants::TWO_FOURTEEN;
    const SUBTYPE_REGULARLY = Constants::REGULARLY;
    const SUBTYPE_SEVERAL_DAYS = Constants::SEVERAL_DAYS;
    //scenario
    const SCENARIO_RECURRING = 1;
    const SCENARIO_END_DATE = 0;
    const SCENARIO_CHANGE_END_DATE = 2; //used for validation
    const SCENARIO_QUOTE_FOURTEEN = 3; //used for quote only fourteen days
    const SCENARIO_ADMIN_CREATE = 4;
    //completion status
    const COMPLETION_NOT_STARTED = 1;
    const COMPLETION_STARTED = 2;
    const COMPLETION_FINISHED = 3;
    //visbility
    const VISIBILITY_VISIBLE = 0;
    const VISIBILITY_DISCARDED = 1;

    public $firstTime; //used for quotes and navgation
    public $showResult; //used for quotes and navgation

    /*
     * Returns BookingLiveIn or BookingHourly
     */

    public static function create($type = null) {

        if ($type == null) {
            $type = Yii::app()->params['test']['defaultService'];
        }

        switch ($type) {

            case self::TYPE_LIVE_IN:

                return new BookingLiveIn();

            case self::TYPE_HOURLY:

                return new BookingHourly();
        }
    }

    /**
     * We're overriding this method to fill findAll() and similar method result
     * with proper models.
     *
     * @param array $attributes
     * @return Car
     */
    protected function instantiate($attributes) {
        switch ($attributes['type']) {
            case self::TYPE_LIVE_IN:
                $class = 'BookingLiveIn';
                break;
            case self::TYPE_HOURLY:
                $class = 'BookingHourly';
                break;
            default:
                $class = get_class($this);
        }
        $model = new $class(null);

        return $model;
    }

    public function beforeSave() {

        if ($this->isNewRecord) {
            $this->created = new CDbExpression('NOW()');
        }

        //$this->updated_at = new CDbExpression('NOW()');

        return parent::beforeSave();
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Booking the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_booking';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('start_date_time, type, start_date', 'required'),
            array('start_date_time', 'checkStartDate', 'except' => array(self::SCENARIO_CHANGE_END_DATE, self::SCENARIO_ADMIN_CREATE)),
            array('id_client, recurring, type, id_calendar', 'numerical', 'integerOnly' => true),
            array('end_date_time', 'required', 'on' => self::SCENARIO_END_DATE),
            array('end_date_time', 'required', 'on' => self::SCENARIO_CHANGE_END_DATE),
            array('end_date_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, start_date_time, end_date_time, recurring, type, id_calendar, created, modified', 'safe', 'on' => 'search'),
        );
    }

    public function checkStartDate($attribute, $params) {
        
    }

    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'location' => array(self::BELONGS_TO, 'Address', 'id_address'),
            'calendar' => array(self::BELONGS_TO, 'Calendar', 'id_calendar'),
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
            'creditCard' => array(self::BELONGS_TO, 'CreditCard', 'id_credit_card'),
            'bookingExceptions' => array(self::HAS_MANY, 'BookingException', 'id_booking'),
            'serviceUsers' => array(self::MANY_MANY, 'ServiceUser', 'tbl_booking_service_user(id_booking, id_service_user)'),
            'missions' => array(self::HAS_MANY, 'Mission', 'id_booking'),
            'missionPayments' => array(self::HAS_MANY, 'MissionPayment', 'id_booking'),
            'bookingGaps' => array(self::HAS_MANY, 'BookingGap', 'id_booking'),
            'carers' => array(self::HAS_MANY, 'BookingCarers', 'id_booking'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() { //RTRT
        return array(
            'id' => 'ID',
            'start_date_time' => Yii::t('texts', 'LABEL_START_DATE'),
            'start_date' => Yii::t('texts', 'LABEL_START_DATE'), //RC to use the label start_date in CriteriaLiveIn
            'end_date' => Yii::t('texts', 'LABEL_END_DATE'),
            'end_date_time' => Yii::t('texts', 'LABEL_END_DATE'),
            'start_time' => Yii::t('texts', 'LABEL_START_TIME'),
            'end_time' => Yii::t('texts', 'LABEL_END_TIME'),
            'recurring' => Yii::t('texts', 'LABEL_UNTIL_NOTICE'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('start_date_time', $this->start_date_time, true);
        $criteria->compare('end_date_time', $this->end_date_time, true);
        $criteria->compare('recurring', $this->recurring);
        $criteria->compare('type', $this->type);
        $criteria->compare('subtype', $this->subtype);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider(Booking::model()->resetScope(), array(
            'criteria' => $criteria,
        ));
    }

    public function searchPerClient() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('start_date_time', $this->start_date_time, true);
        $criteria->compare('end_date_time', $this->end_date_time, true);
        $criteria->compare('recurring', $this->recurring);
        $criteria->compare('type', $this->type);
        $criteria->compare('subtype', $this->subtype);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider(Booking::model()->resetScope(), array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'start_date_time DESC',
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));
    }

    /**
     * Time Stamp behaviour
     */
    public function behaviors() {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'TimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
                'timestampExpression' => Calendar::today(Calendar::FORMAT_DBDATETIME),
            )
        );
    }

//TODO find a way for $mission->booking
    //restrict access to owner
//    public function defaultScope() {
//        return array(
//            'condition' => 'id_client = ' . Yii::app()->user->id,
//        );
//    }

    /**
     * For BookingHourly also validates the days form
     * @return type
     */
    public function validateQuote() {

        if ($this->type == self::TYPE_LIVE_IN) {
            $valid = $this->validate();
            $this->showResult = $valid && !$this->firstTime;
            return $valid;
        } else {
            $valid = $this->validate() && $this->validateDayForms();
            $this->showResult = $valid && !$this->firstTime;
            return $valid;
        }
    }

    public function getCompletionStatus() {

        $st = $this->start_date_time;

        $startDateTimeTimestamp = Calendar::convert_DBDateTime_Timestamp($this->start_date_time);
        $nowTimeTimestamp = Calendar::FORMAT_TIMESTAMP;

        if ($nowTimeTimestamp < $startDateTimeTimestamp) {

            return self::COMPLETION_NOT_STARTED;
        } else {

            if ($this->type == self::SCENARIO_RECURRING) {

                return self::COMPLETION_STARTED;
            } else {
                $endDateTimeTimestamp = Calendar::convert_DBDateTime_Timestamp($this->end_date_time);

                if ($nowTimeTimestamp > $endDateTimeTimestamp) {

                    return self::COMPLETION_FINISHED;
                } else {

                    return self::COMPLETION_STARTED;
                }
            }
        }
    }

    public function getCompletionStatusLabel() {

        $array = self::initCompletionTypes();
        return $array[$this->getCompletionStatus()];
    }

    public static function getTypeOptions() {

        return self::initTypes();
    }

    public function getVisibilityLabel() {

        $array = self::initVisibilities();
        return $array[$this->discarded_by_client];
    }

    public static function getVisibilityptions() {

        return self::initVisibilities();
    }

    public function getTypeLabel() {

        $array = self::initTypes();
        return $array[$this->type];
    }

    public function getTotalPrice($user) {

        $price = new Price();

        foreach ($this->missions as $mission) {

            $price = $price->add($mission->getOriginalTotalPrice($user));
        }

        return $price;
    }

    /**
     * Return live unit price
     * 
     * @param type $user
     * @return type Price
     */
    public function getUnitPrice($user) {

        if ($this->type == self::TYPE_LIVE_IN) {

            return Prices::getPrice($user, Prices::LIVE_IN_DAILY_PRICE);
        } else {
            return Prices::getPrice($user, Prices::HOURLY_PRICE);
        }
    }

    public function getUnitPriceLabel($user) {

        if ($this->type == self::TYPE_LIVE_IN) {

            return Prices::getPriceText($user, Prices::LIVE_IN_DAILY_PRICE);
        } else {
            return Prices::getPriceText($user, Prices::HOURLY_PRICE);
        }
    }

    public function getRecurringLabel() {

        if ($this->recurring == true) {
            return ' - Recurring'; //RTRT
        } else {
            return '';
        }
    }

    private static function initTypes() {

        return array(
            self::TYPE_LIVE_IN => Yii::t('texts', 'LABEL_LIVE_IN_CARE'),
            self::TYPE_HOURLY => Yii::t('texts', 'LABEL_HOURLY_CARE'),
        );
    }

    private static function initVisibilities() {

        return array(
            self::VISIBILITY_VISIBLE => Yii::t('texts', 'Visible'),
            self::VISIBILITY_DISCARDED => Yii::t('texts', 'Discarded'),
        );
    }

    private static function initCompletionTypes() {

        return array(
            self::COMPLETION_NOT_STARTED => Yii::t('texts', 'not started'), //RTRT
            self::COMPLETION_STARTED => Yii::t('texts', 'started'),
            self::COMPLETION_FINISHED => Yii::t('texts', 'finished'),
        );
    }

    /**
     * Return dbDate format 2012-31-12
     */
    public function getStart_Date() {

        return Calendar::convert_DBDateTime_DBDate($this->start_date_time);
    }

    /**
     * Return dbDate format 2012-31-12
     */
    public function getEnd_Date() {

        return Calendar::convert_DBDateTime_DBDate($this->end_date_time);
    }

    /**
     * Return dbDate format 2012-31-12
     */
    public function getStartDateTime() {

        return Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time);
    }

    /**
     * Return dbDate format 2012-31-12
     */
    public function getEndDateTime() {

        return Calendar::convert_DBDateTime_DisplayDateTimeText($this->end_date_time);
    }

    /**
     * Return time format 12:31:01
     */
    public function getStart_Time() {

        return Calendar::convert_DBDateTime_DisplayTime($this->start_date_time);
    }

    /**
     * Return time format 12:31:01
     */
    public function getEnd_Time() {

        return Calendar::convert_DBDateTime_DisplayTime($this->end_date_time);
    }

    /**
     * Return displayDateText format 12 Decembre 2012 or 12/12/12
     * if text = true display with month name
     */
    public function getStartDate($text = true) {

        if ($text) {
            $d = Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time);
            return $d;
        } else {
            $d = Calendar::convert_DBDateTime_DisplayDate($this->start_date_time);
            return $d;
        }
    }

    /**
     * Return displayDateText format 12 Decembre 2012
     */
    public function getEndDate($text = true) {

        if ($text) {
            return Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time);
        } else {
            return Calendar::convert_DBDateTime_DisplayDate($this->end_date_time);
        }
    }

    public function getStartTime() {

        return Calendar::convertDisplayTime(Calendar::getTime($this->start_date_time));
    }

    public function getEndTime() {

        return Calendar::convertDisplayTime(Calendar::getTime($this->end_date_time));
    }

    /**
     * return dbDateTime
     */
    public function getEarliestEndDate() {

        $mission = self::getLastMission($this->id);
        $date = $mission->end_date_time;

        return $date;
    }

    public static function getPayableBookings() {

        $hoursInAdvance = BusinessRules::getCronPaymentInAdvanceInHours();

        $now = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $compare = Calendar::addHours($now, $hoursInAdvance, Calendar::FORMAT_DBDATETIME);

        $criteria = new CDbCriteria();
        $criteria->condition = 'start_date_time <= "' . $compare . '" AND ("' . $compare . '" <= end_date_time OR end_date_time IS NULL )';

        $models = self::model()->findAll($criteria);

        return $models;
    }

    /**
     * Return true if user can change end date of a booking, if difference between last mission paid end date and today is > getNewBookingDelayLiveInHours
     * @return boolean
     */
    public function isEndDateChangeable() {

        if ($this->recurring) {
            return true;
        } else {

            //new requirement, only regularly
            if ($this->subtype == self::SUBTYPE_REGULARLY) {

                $hoursBetween = Calendar::hoursBetween_DBDateTime(Calendar::today(Calendar::FORMAT_DBDATETIME), $this->end_date_time);

                if ($this->type == Constants::HOURLY) {
                    $value = BusinessRules::getNewBookingDelay_Hourly_Fourteen_InHours();
                } else {
                    $value = BusinessRules::getNewBookingDelayLiveInHours();
                }

                if ($hoursBetween >= $value) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function isStoppable() {

        if ($this->subtype == self::SUBTYPE_REGULARLY) {

            //check if last paid mission date = end date of booking.
            //if it's the case then the booking has been stopped already,
            $lastMission = $this->getLastMission($this->id);

            $lastMissionEndDate = $lastMission->end_date;
            $bookingEndDate = $this->end_date;

            if ($lastMissionEndDate == $bookingEndDate) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function getServiceUsers() {

        return $this->serviceUsers;
    }

    public function getServiceLocation() {

        return $this->location;
    }

    public static function getClientBookings($clientId) {

        return Booking::model()->findAllByAttributes(array('id_client' => $clientId, 'discarded_by_client' => false, 'type' => self::TYPE_HOURLY));
    }

    public static function getBookingsClientCount($clientId) {

        $sql = "SELECT COUNT(tbl_booking.id) FROM tbl_booking " .
                " WHERE tbl_booking.id_client = " . $clientId .
                " AND tbl_booking.type = " . self::TYPE_HOURLY
        ;

        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num;
    }

    /**
     * Return the mission that has the oldest end date for that booking
     * 
     * @param type $bookingId
     * @return type
     */
    public static function getLastMission($bookingId) {

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_booking t2 ON t.id_booking = t2.id';
        $criteria->condition = 't2.id = ' . $bookingId;
        $criteria->order = 't.end_date_time DESC';

        $model = Mission::model()->find($criteria);

        return $model;
    }

    /**
     * Return the last MissionPayment for this booking
     * @param type $bookingId
     * @return type
     */
    public function getLastMissionPayment() {

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_booking t2 ON t.id_booking = t2.id';
        $criteria->condition = 't2.id = :idBooking';
        $criteria->order = 't.end_date_time DESC';
        $criteria->params = array(':idBooking' => $this->id);

        $model = MissionPayment::model()->find($criteria);

        return $model;
    }

    /**
     * Display dates like:
     * From 12 March 2013 untill Further notice 
     * From 12 March 2013 untill 03 April 2013 
     */
    public function displayDates($time = false) {

        //$text = Yii::t('texts', 'LABEL_FROM') . '&#160;';
        $text = '<b>';
        $id = $this->id;
        $startDate = $this->start_date_time;
        $endDate = $this->end_date_time;
        if ($time) {
            $text .= Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time, false);
        } else {
            $text .= Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time, false);
        }
        $text .= '</b>&#160;';
        $text .= Yii::t('texts', 'LABEL_UNTIL');
        $text .= '&#160;<b>';
        if ($this->recurring == true) {
            $text .= Yii::t('texts', 'LABEL_FURTHER_NOTICE');
        } else {
            if ($time) {
                $end = $this->end_date_time;
                $text .= Calendar::convert_DBDateTime_DisplayDateTimeText($this->end_date_time, false);
            } else {
                $text .= Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time, false);
            }
        }
        $text .= '</b>';

        return $text;
    }

    /**
     * Used for logging, return string to describe the quote info
     */
    public function getQuoteInfo() {

        $sessionId = Yii::app()->session->sessionID;

        $text = 'Session Id: ' . $sessionId;
        $text .= ': Type: ' . $this->getTypeLabel();
        $text .= ', Start: ' . Calendar::convert_DBDate_DisplayDateText($this->start_date_time, false, ' ');
        $text .= ', End: ' . Calendar::convert_DBDate_DisplayDateText($this->end_date_time, false, ' ');

        if ($this->type == Constants::HOURLY) {

            $days = '';

            $formDays = $this->getDayForms();

            foreach ($formDays as $formDay) {

                if ($formDay->selected) {
                    $days .= $formDay->dayWeek . ' ' . $formDay->getStartTime() . ' to ' . $formDay->getEndTime() . ' - ';
                }
            }

            $text .= ', Days: ' . $days;
        }

        return $text;
    }

    public function displayServiceUsersText($separator = '<br />') {

        $serviceUsers = $this->serviceUsers;
        $count = count($serviceUsers);
        $users = '';
        foreach ($serviceUsers as $index => $serviceUser) {

            $users = $users . $serviceUser->fullName;

            if ($index != $count - 1) {
                $users = $users . $separator;
            }
        }

        return $users;
    }

    public function displayServiceUsersNameConditionsHTML() {

        $serviceUsers = $this->serviceUsers;

        $result = '';
        $userNumber = 1;
        foreach ($serviceUsers as &$serviceUser) {

            $text = '<p>' . $userNumber . $serviceUser->fullName . '<br />' . $serviceUser->displayAgeGenderConditions() . '</p>';
            $result .= $text;
            $userNumber = $userNumber + 1;
        }

        return $result;
    }

    /**
     * If all missions have been paid - not possible to change credit card or change the service
     * 
     * CANNOT BE CALLED BY Booking DIRECTLY ! MUST BE BookingLiveIn or BookingHourly
     */
    public function isAllPaid() {

        //switch ($this->type) {
        //    case self::TYPE_LIVE_IN:
        //a recurring booking is by definition never fully paid
        if ($this->recurring == self::SCENARIO_RECURRING) {

            return false;
        } else {

            //if no more slots everything's been paid
            $nextSlot = $this->getNextMissionSlot();

            if ($nextSlot == null) {
                return true;
            } else {
                return false;
            }
        }

        //   case self::TYPE_HOURLY:
        //}
    }

    public function getStatus() {
        
    }

    public function getStatusLabel() {
        
    }

    public function discardByClient() {

        $this->discarded_by_client = true;
        $this->save(false);
    }

    public function isDiscardButtonVisible() {

        if ($this->subtype == self::SUBTYPE_REGULARLY) {

            //if all missions created
            if ($this->getNextMissionSlot() == null) {

                $missions = $this->missions;

                //if all missions are completed or cancelled
                foreach ($missions as $mission) {

                    //if at least one mission not finished : button not visible
                    if ($mission->isFinished() == false && $mission->isActive()) {
                        return false;
                    }
                }

                return true;
            } else {
                return false;
            }
        } else {

            //TODO factorise code above and below
            $missions = $this->missions;

            //if all missions are completed or cancelled
            foreach ($missions as $mission) {

                //if at least one mission not finished : button not visible
                if ($mission->isFinished() == false && $mission->isActive()) {
                    return false;
                }
            }

            return true;
        }
    }

    public function changeServiceUsers($serviceUsersIds) {

        //delete previous associations
        BookingServiceUser::model()->deleteAll('id_booking = :bookingId', array(':bookingId' => $this->id));

        //create new ones with passed ids
        foreach ($serviceUsersIds as $serviceUsersId) {

            $bookingServiceUser = new BookingServiceUser();
            $bookingServiceUser->id_booking = $this->id;
            $bookingServiceUser->id_service_user = $serviceUsersId;
            $bookingServiceUser->save();
        }
    }

    /**
     * Return array of Price (toPay, paidCash, paidCredit, remainingCreditBalance)
     * @param type $user
     * @return type
     */
    public function calculatePayment($userRole, $clientId) {

        //figure out if credit, if yes use it to pay
        $price = $this->calculateFirstPayment($userRole);

        $currency = $price->currency;

        if ($userRole == Constants::USER_CLIENT) {
            $credit = ClientTransaction::getCreditBalance($clientId);
        } else {
            $credit = new Price(0, $currency); //carer can't have credit
        }

        $paidCredit = new Price(0, $currency);
        $paidCash = new Price(0, $currency);
        $remainingCreditBalance = new Price(0, $currency);

        if ($credit->amount > 0) {

            if ($credit->amount >= $price->amount) {

                $paidCredit = $price;
                $remainingCreditBalance = $credit->substract($price);
            } else {

                $paidCash = $price->substract($credit);
                $paidCredit = $credit;
            }
        } else {
            $paidCash = $price;
        }

        return array('toPay' => $price, 'paidCash' => $paidCash, 'paidCredit' => $paidCredit, 'remainingCreditBalance' => $remainingCreditBalance);
    }

    public function delete() {
//
//        if ($this->type == self::TYPE_HOURLY) {
//
//            foreach ($this->bookingHourlyDays as $bookingHourlyDay) {
//                $bookingHourlyDay->delete();
//            }
//        }
//
//        foreach ($this->serviceUsers as $serviceUser) {
//            foreach ($serviceUser->bookingServiceUsers as $bookingServiceUser) {
//                $bookingServiceUser->delete();
//            }
//        }
//

        foreach ($this->carers as $carer) {
            $carer->delete();
        }

        foreach ($this->serviceUsers as $serviceUser) {
            $serviceUser->delete();
        }

        foreach ($this->bookingHourlyDays as $bookingHourlyDay) {
            $bookingHourlyDay->delete();
        }

        parent::delete();
    }

    public function isAllCancelledByClient() {

        $missions = $this->missions;

        foreach ($missions as $mission) {

            if (!$mission->isCancelledByClient()) {
                return false;
            }
            return true;
        }
    }

    /**
     * Returns true if all Booking's MissionPayment are refunded
     */
    public function isAllRefunded() {

        $missionPayments = $this->missionPayments;

        $allRefunded = true;
        foreach ($missionPayments as $missionPayment) {

            if ($missionPayment->refund == 0) {
                $allRefunded = false;
                break;
            }
        }

        return $allRefunded;
    }

    /**
     * Refund a client and cancel all missions. Does not handle voucher balance at the moment
     * 
     * @return array first element true or false and message
     */
    public function refund($byPassMissionsNotStarted = false) {

        $transaction = Yii::app()->db->beginTransaction();
        try {
            //make sure all missions not started

            $missions = $this->missions;

            $missionNotStarted = true;
            $missionPayment = null;
            foreach ($missions as $mission) {

                $missionNotStart = $mission->isNotStarted();

                if (!$missionNotStart) {

                    $missionNotStarted = false;
                }
            }

            if ($missionNotStarted || $byPassMissionsNotStarted) {

                $missionPayments = $this->missionPayments;

                if (count($missionPayments == 1)) {

                    //cancel all missions, without doing refund
                    foreach ($missions as $mission) {

                        //no refund (done further in this function), dbTransaction is already on, so second parameter false
                        $mission->cancelByAdmin(false, false);
                    }

                    $missionPayment = $missionPayments[0];
                    $transactionRef = $missionPayment->transaction_id;
                    $transactionDate = $missionPayment->transaction_date;

                    $paymentTransaction = $missionPayment->getPaymentTransaction();
                    $price = $paymentTransaction->getPaidCash();

                    $creditCard = $missionPayment->creditCard;

                    $creditCardDecrypted = $creditCard->getDecryptedTemporaryInstance();
                    $creditCardNumber = $creditCardDecrypted->card_number;

                    $handler = BorgunHandler::getInstance();
                    $handler->doRefund($transactionRef, $price, $creditCardNumber, $transactionDate);

                    if ($handler->isTransactionSuccessful()) {

                        $client = $this->client;

                        //store the result in MissionPayment object
                        $missionPayment->refund = 1;
                        $missionPayment->save();

                        //create a client transaction
                        //Create transaction for reimbursement
                        ClientTransaction::createReimbursment($client->id, $missionPayment->id, $mission->id, $price, null, false);
                        //createReimbursment($clientId, $id_mission_payment, $missionId, $refundAmount, $refundVoucher, $sendMailClient) {
                        //send email to client
                        $response = Emails::sendToClient_BookingCancelledAndReimbursed($client);

                        $success = true;
                        $message = 'Success, refund of ' . $price->text . 'done for client ' . $client->id . ' ' . $client->fullName;
                    } else {

                        $xml = $handler->getXMLRequest();

                        $success = false;
                        $message = 'Error bank refund: ' . $handler->getPaymentMessage() .
                                "<div>Data used: $transactionRef $price->text $creditCardNumber $transactionDate </div>
                            <div>$xml</div>";
                    }
                } else {

                    $success = false;
                    $message = 'Error: this booking has several missionPayments - do not know which one to cancel';
                }
            } else {
                $success = false;
                $message = 'Error: at least one mission started';
            }

            if ($success) {

                //commit if payment successful
                $transaction->commit();
            } else {
                //rollback if refund not successful
                $transaction->rollBack();
            }

            return array('success' => $success, 'message' => $message);
        } catch (CException $e) {

            //roolback if any DB issue
            $transaction->rollBack();
            return array('success' => false, 'message' => $e->getMessage());
        }
    }

    public function cancelByClient($byPassMissionsNotStarted = false) {

        $transaction = Yii::app()->db->beginTransaction();
        try {
            //make sure all missions not started
            $missions = $this->missions;

            $missionNotStarted = true;
            $missionPayment = null;
            foreach ($missions as $mission) {

                $missionNotStart = $mission->isNotStarted();

                if (!$missionNotStart) {

                    $missionNotStarted = false;
                }
            }

            if ($missionNotStarted || $byPassMissionsNotStarted) {

                $missionPayments = $this->missionPayments;

                if (count($missionPayments == 1)) {

                    //cancel all missions, without doing refund
                    foreach ($missions as $mission) {

                        //no refund, dbTransaction is already on, so second parameter false
                        $mission->cancelByClient(false);
                    }
                    $success = true;
                    $message = 'All missions cancelled and vouchers created';
                } else {

                    $success = false;
                    $message = 'Error: this booking has several missionPayments - do not know which one to cancel';
                }
            } else {
                $success = false;
                $message = 'Error: at least one mission started';
            }

            if ($success) {

                //commit if payment successful
                $transaction->commit();
            } else {
                //rollback if refund not successful
                $transaction->rollBack();
            }

            return array('success' => $success, 'message' => $message);
        } catch (CException $e) {

            //roolback if any DB issue
            $transaction->rollBack();
            return array('success' => false, 'message' => $e->getMessage());
        }
    }

    /**
     * Return an array of Ids of short listed carers (client's selection)
     */
    public function getShortListedCarersIds() {

        $shortListedCarers = $this->carers;

        $shortListedCarersIds = array();
        foreach ($shortListedCarers as $shortListedCarer) {
            $shortListedCarersIds[] = $shortListedCarer->id_carer;
        }

        return $shortListedCarersIds;
    }

    public function displayForAdminEmail() {

        $text = '';
        foreach ($this->missions as $mission) {

            $text .= $mission->displayDateTimes() . '<br>';
        }

        $text .= '<br>Price: ' . $this->getTotalPrice(Constants::USER_CLIENT)->text;

        return $text;
    }

    public function displayAdmin() {

        $text = "Booking Id: $this->id <br> Client Id: $this->id_client <br>";
        $text .= 'Type: ' . get_class($this) . '<br>';

        $text .= 'Dates: ' . $this->displayDates(true) . '<br>';
        if ($this->type == Constants::HOURLY) {
            $text .= 'Days: ' . $this->getBookingHourlyDaysText() . '<br>';
        }

        $missions = $this->missions;

        $text .= 'Existing missions: ' . count($missions) . '<br>';

        $missionText = '';

        foreach ($missions as $mission) {


            $missionText .= $mission->displayMissionAdmin() . '<br>';
        }

        $text .= $missionText;



        return $text;
    }

    public function getClientTransaction() {

        $sql = "SELECT * FROM tbl_client_transaction WHERE id_booking = $this->id";

        $model = ClientTransaction::model()->findBySql($sql);

        return $model;
    }

    public function getCardLastDigits() {

        if (isset($this->creditCard)) {
            return $this->creditCard->displayShort();
        } else {
            $transaction = $this->getClientTransaction();
            if (isset($transaction)) {
                return $transaction->getCardLastDigitsText();
            } else {
                return 'Card deleted';
            }
        }
    }

    public function displayServiceUsersNameHTML() {

        $serviceUsers = $this->serviceUsers;
        $result = '';
        $userNumber = 1;
        foreach ($serviceUsers as &$serviceUser) {

            $text = '<p>' . $userNumber . ' - ' . $serviceUser->fullName . '</p>';

            $result .= $text;

            $userNumber = $userNumber + 1;
        }

        return $result;
    }

    public function getDurationInHours() {

        $hours = 0;

        foreach ($this->missions as $mission) {

            $hours = $hours + $mission->getNumberHours();
        }

        return $hours;
    }

    public function getAssignedCarer() {


        foreach ($this->missions as $mission) {

            $carer = $mission->getAssignedCarer();

            if (isset($carer)) {
                return $carer->fullName;
            }
        }

        return 'Liliana Mananova';
    }

}
