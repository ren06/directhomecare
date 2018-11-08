<?php

/**
 * This is the model class for table "tbl_mission_live_in".
 *
 * The followings are the available columns in table 'tbl_mission_live_in':
 * @property integer $id_mission
 * @property string $start_date_time
 * @property string $end_date_time
 *
 * The followings are the available model relations:
 * @property Mission $id
 */
class MissionHourly extends Mission {

    static function model($className = __CLASS__) {
        return parent::model($className);
    }

    function defaultScope() {
        $type = self::TYPE_HOURLY;
        return array(
            'condition' => "type='$type'",
        );
    }

    public function afterConstruct() {
        parent::afterConstruct();

        $this->type = self::TYPE_HOURLY;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $parentRules = parent::rules();

//        return array(
//            array('id_mission, start_date_time, end_date_time', 'required'),
//            array('id_mission', 'numerical', 'integerOnly' => true),
//            // The following rule is used by search().
//            // Please remove those attributes that should not be searched.
//            array('id_mission, start_date_time, end_date_time', 'safe', 'on' => 'search'),
//        );

        return $parentRules;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {

        $parentRelations = parent::relations();

        $relations = array(
                //  'mission' => array(self::BELONGS_TO, 'Mission', 'id_mission'),
        );

        return array_merge($relations, $parentRelations);
    }

    public function getNumberDaysHoursMinutes($display = false) {

        $seconds = Calendar::calculate_Duration_Seconds($this->start_date_time, $this->end_date_time);

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($seconds, $display);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'start_date_time' => 'Start date',
            'end_date_time' => 'End date',
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
        $criteria->compare('start_date_time', $this->start_date_time, true);
        $criteria->compare('end_date_time', $this->end_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function createMissionHourly($bookingId, $missionPaymentId, $serviceLocationId, $serviceUserIds, $startDateTime, $endDateTime) {

        //set all attributes
        $mission = new MissionHourly();

        $mission->id_address = $serviceLocationId;
        $mission->id_booking = $bookingId;
        $mission->id_mission_payment = $missionPaymentId;
        $mission->type = self::TYPE_HOURLY;
        $mission->start_date_time = $startDateTime;
        $mission->end_date_time = $endDateTime;

        $mission->validate();
        $er = $mission->errors;
        $mission->save();

        //Associate service users to mission    
        ServiceUser::assignToMission($serviceUserIds, $mission->id);

        return $mission;
    }

    public function getEntityPrice($user) {

        $creationDate = $this->created;

        return Prices::getPrice($user, Prices::HOURLY_PRICE, $creationDate);
    }

    /**
     * Return the price when mission was bought
     * 
     * @param type $user user point of view
     * @return type Price
     */
    public function getOriginalTotalPrice($user) {

        return Prices::getOriginalTotalPrice($this, $user);
    }

    public function abortByClient($abortDate) {

        switch ($this->getCompletionStatus()) {

            case self::FINISHED:
                //do nothing
                break;

            case self::STARTED:
                break;

            case self::NOT_STARTED:
                break;
        }

        //remove from database the record
        $this->status = self::CANCELLED_BY_CLIENT;
        $this->myUpdate();

        //warn the carer that the mission has been cancelled
        $assignedCarer = $this->getAssignedCarer();

        if (isset($assignedCarer)) {

            Emails::sendToCarer_ClientCancelledMission($carer->id, $this->id, $currentStatus);

            ActionHistory::create($assignedCarer->id, $this->id, ActionHistory::CLIENT_ABORT_SERVICE, ActionHistory::CLIENT);
        }
    }

    /**
     * Return assigned carer for the mission
     * 
     * COULD BE MOVED TO MOTHER CLASS
     */
    public function getSelectedCarer() {

        $sql = "SELECT id_applying_carer FROM tbl_mission_carers WHERE id_mission=" . $this->id . " AND status=" . MissionCarers::SELECTED;
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row == null) {

            return null;
        } else {
            return Carer::loadModel($row['id_applying_carer']);
        }
    }

    /**
     * Get carer status for this mission
     * 
     * COULD BE MOVE TO MOTHER CLASS
     */
    public function getCarerStatus($carerId) {

        $sql = "SELECT status FROM tbl_mission_carers WHERE id_applying_carer = " . $carerId . " AND id_mission=" . $this->id;
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        return $row['status'];
    }

    /*
     * Use for Google Maps Window
     * 
     * COULD BE MOVE TO MOTHER CLASS
     * 
     */

    public function displayHTML() {

        $result = '&#160;&#45;&#160;' . $this->serviceLocation->displayShort('&#32;') . '<br />';
        $result .= Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time, false) . '&#32;' . Yii::t('texts', 'LABEL_TO') . '&#32;' . Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time, false) . '<br />';
        $result .= $this->displayServiceUsersConditionsHTML('<br />');
        $result .= $this->getNumberDaysHoursMinutes(true) . '&#160;&#45;&#160;' . $this->getOriginalTotalPrice(Constants::USER_CARER)->text;

        return $result;
    }

    /**
     * 
     * @return typeCOULD BE MOVE TO MOTHER CLASS
     */
    public function displayDates() {

        return 'From: &#160;&#160;<b>' . Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time) . '&#160;&#160;&#160;' . $this->startTime . '</b><br />' .
                'To: &#160;&#160;&#160;&#160;&#160;&#160;&#160;<b>' . Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time) . '&#160;&#160;&#160;' . $this->endTime . '</b><br />';
    }

    /**
     * Display dates like:
     * From 12 March 2013 untill Further notice 
     * From 12 March 2013 untill 03 April 2013 
     */
    public function displayDates2($time = false) {

        $text = 'From '; //RTRT
        $text = '<b>';
        $id = $this->id;
        $startDate = $this->start_date_time;
        $endDate = $this->end_date_time;
        if ($time) {
            $text .= Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time, true);
        } else {
            $text .= Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time, true);
        }
        $text .= '</b>';
        $text .= ' until ';
        $text .= '<b>';

        if ($time) {
            $end = $this->end_date_time;
            $text .= Calendar::convert_DBDateTime_DisplayDateTimeText($this->end_date_time, true);
        } else {
            $text .= Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time, true);
        }

        $text .= '</b>';

        return $text;
    }

    //for admin to check prices
    public function displayMissionPayments() {

        $text = $this->displayDates() . '<p>';

        $text .= $text . $this->getNumberDays() . Yii::t('texts', 'DAYS') . '<br />';
        $text .= 'Paid by Client: ' . $this->getOriginalTotalPrice(Constants::USER_CLIENT)->text . '<br />';
        $text .= 'Paid by Client after Abort: ' . $this->getTotalPrice(Constants::USER_CLIENT)->text . '<br />';
        $text .= 'Original Price for Carer: ' . $this->getOriginalTotalPrice(Constants::USER_CARER)->text . '<br />';
        $text .= 'Pay for Carer: ' . $this->getTotalPrice(Constants::USER_CARER)->text . '<br>';
        $text .= '</p>';

        return $text;
    }

    public function displayMissionAdmin() {

        $text = '<b>Client</b><br>' . $this->booking->client->FullName . '<br />';
        $text .= '<b>Service users</b><br />' . $this->displayServiceUsersAdmin();
        $text .= '<b>Dates</b><br>';
        $text .= $this->displayDates() . '<br />';
        $text .= '<b>Location</b><br>';
        $text .= $this->serviceLocation->display('&#160;') . '<br />';
        $text .= 'Note: ' . $this->serviceLocation->explanation;

        return $text;
    }

    public function refresh() {

        //get data from 
        $this->loadParentData();
        parent::refresh();
    }

    private function loadParentData() {

        $parent = parent::model()->findByPk((int) $this->id_mission);
        $this->id = $parent->id;
        $this->id_booking = $parent->id_booking;
        $this->created = $parent->created;
        $this->modified = $parent->modified;
        $this->type = $parent->type;
        $this->status = $parent->status;
    }
    
    public function getDateTimeDuration(){
        
         $start = Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time);
         $end = Calendar::convert_DBDateTime_Time($this->end_date_time, false);
         $duration = Calendar::hoursBetween_DBDateTime($this->start_date_time, $this->end_date_time);
         
         return $start . ' to ' . $end . " ($duration" . "h)";
         
    }

}