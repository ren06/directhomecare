<?php

/**
 * This is the model class for table "tbl_mission".
 *
 * The followings are the available columns in table 'tbl_mission':
 * @property integer $id
 * @property integer $id_booking
 * @property integer $id_mission_payment
 * @property integer $type
 * @property integer $status
 * @property string start_date_time
 * @property string end_date_time
 * @property integer $discarded_by_carer
 * @property integer $discarded_by_client
 * @property integer $carer_credited
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property ActionHistory[] $actionHistories
 * @property CarerTransaction[] $carerTransactions
 * @property Complaint[] $complaints
 * @property Booking $idBooking
 * @property MissionPayment $missionPayment
 * @property MissionCarers[] $missionCarers
 * @property MissionHourly $missionHourly
 * @property MissionLiveIn $missionLiveIn
 * @property MissionSlotAborted[] $missionSlotAborteds 
 */
class Mission extends ActiveRecord {
//Mission type

    const PAGE_SIZE = 10;
    const TYPE_LIVE_IN = Constants::LIVE_IN;
    const TYPE_HOURLY = Constants::HOURLY;
//Completion status
    const NOT_STARTED = 1;
    const STARTED = 2;
    const FINISHED = 3;
//Status
    const ACTIVE = 0;
//    const CANCELLED_BY_CARER = 1;
    const CANCELLED_BY_CLIENT = 2;
    const ABORTED_BY_CLIENT = 3;
    const CANCELLED_BY_ADMIN = 4;

    public $error_text; //used by cron jobs

//    public $id_booking;
//    public $created;
//    public $modified;
//    public $type;
//    public $status;

    /*
     * Returns BookingLiveIn or BookingHourly
     */

    public static function create($type = null) {

        if ($type == null) {
            $type = Yii::app()->params['test']['defaultService'];
        }

        switch ($type) {

            case self::TYPE_LIVE_IN:

                return new MissionLiveIn();

            case self::TYPE_HOURLY:

                return new MissionHourly();
        }
    }

    protected function instantiate($attributes) {
        switch ($attributes['type']) {
            case self::TYPE_LIVE_IN:
                $class = 'MissionLiveIn';
                break;
            case self::TYPE_HOURLY:
                $class = 'MissionHourly';
                break;
            default:
                $class = get_class($this);
        }
        $model = new $class(null);

        return $model;
    }

//    protected function instantiate($attributes) {
//
//        if (isset($attributes['id'])) {
//
//            switch ($attributes['type']) {
//                case self::TYPE_LIVE_IN:
//                    $class = 'MissionLiveIn';
//                    break;
//                case self::TYPE_HOURLY:
//                    $class = 'MissionHourly';
//                    break;
//                default:
//                    $class = get_class($this);
//            }
//            $model = new $class(null);
//            $model->setChildAttributes($attributes['id']);
//        } else {
//            $class = get_class($this);
//            $model = new $class(null);
//        }
//
//        return $model;
//    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Mission the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_mission';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('id_booking', 'required'),
            array('id_booking, type', 'numerical', 'integerOnly' => true),
            array('status', 'in', 'range' => array(self::ACTIVE, self::CANCELLED_BY_CLIENT, self::ABORTED_BY_CLIENT, self::CANCELLED_BY_ADMIN)),
            // The following rule is used by search().
// Please remove those attributes that should not be searched.
            array('id, id_booking, id_mission_payment, id_address, type, status, start_end_time, end_date_time,
                discarded_by_carer, discarded_by_client, carer_credited, created, modified', 'safe', 'on' => 'search'),
            array('id, id_booking, id_mission_payment, id_address, type, status, start_date_time, end_date_time,
                discarded_by_carer, discarded_by_client, carer_credited, created, modified', 'safe', 'on' => 'edit'),
        );
    }

    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array(
            'actionHistories' => array(self::HAS_MANY, 'ActionHistory', 'id_mission'),
            'carerTransactions' => array(self::HAS_MANY, 'CarerTransaction', 'id_mission'),
            'complaintsClient' => array(self::HAS_MANY, 'Complaint', 'id_mission', 'condition' => 'created_by =' . Constants::USER_CLIENT),
            'complaintsCarer' => array(self::HAS_MANY, 'Complaint', 'id_mission', 'condition' => 'created_by =' . Constants::USER_CARER),
            'complaints' => array(self::HAS_MANY, 'Complaint', 'id_mission'),
            'address' => array(self::BELONGS_TO, 'Address', 'id_address'),
            'booking' => array(self::BELONGS_TO, 'Booking', 'id_booking'),
            'missionPayment' => array(self::BELONGS_TO, 'MissionPayment', 'id_mission_payment'),
            //'missionCarers' => array(self::HAS_MANY, 'MissionCarers', 'id_mission'),
            'missionCarers' => array(self::MANY_MANY, 'Carer', 'tbl_mission_carers(id_mission, id_applying_carer)'),
            'missionHourly' => array(self::HAS_ONE, 'MissionHourly', 'id_mission'),
            'missionLiveIn' => array(self::HAS_ONE, 'MissionLiveIn', 'id_mission'),
            'serviceUsers' => array(self::MANY_MANY, 'ServiceUser', 'tbl_mission_service_user(id_mission, id_service_user)'),
            'missionSlotsAborted' => array(self::HAS_MANY, 'MissionSlotAborted', 'id_mission'),
        );
    }

    protected function getChildAttributes() {
        
    }

//    public function getMissionChild() {
//
//        $type = $this->type;
//
//        if ($type == null) {
//            $id = $this->id;
//            $type = self::fetchType($id);
//        }
//
//        switch ($this->type) {
//
//            case self::MISSION_LIVE_IN:
//
//                if (!$this->missionLiveIn) {
//                    throw new CException('Mission has no child');
//                }
//                $object = $this->missionLiveIn;
//                $object->refresh();
//                return $object;
//
//            case self::MISSION_HOURLY:
//
//                return $this->missionHourly;
//        }
//    }

    public function getStartDateTime() {

        return Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time);
    }

    public function getEndDateTime() {

        return Calendar::convert_DBDateTime_DisplayDateTimeText($this->end_date_time);
    }

    public function getStartDate() {

        return Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time);
    }

    public function getEndDate() {

        return Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time);
    }

    public function getStart_Date() {

        return Calendar::convert_DBDateTime_DBDate($this->start_date_time);
    }

    public function getStartTime() {

        return substr($this->start_time, 0, 5);
    }

    public function getEndTime() {

        return substr($this->end_time, 0, 5);
    }

    public function getStart_Time() {

        $time = Calendar::convert_DBDateTime_Time($this->start_date_time);
        return $time;
    }

    public function getEnd_Time() {

        $time = Calendar::convert_DBDateTime_Time($this->end_date_time);
        return $time;
    }

    public function getStartHour() {
        $startHour = $this->getStartTime();
        return Calendar::convert_Time_Hour($startHour);
    }

    public function getStartMinute() {
        $startHour = $this->getStartTime();
        return Calendar::convert_Time_Minute($startHour);
    }

    public function getEndHour() {
        $endHour = $this->getEndTime();
        return Calendar::convert_Time_Hour($endHour);
    }

    public function getEndMinute() {
        $endHour = $this->getEndTime();
        return Calendar::convert_Time_Minute($endHour);
    }

    public function getEnd_Date() {

        return Calendar::convert_DBDateTime_DBDate($this->end_date_time);
    }

    /**
     * Return the service loaction object this mision is related to
     */
    public function getServiceLocation() {

        return $this->address;
    }

    public function isActive() {

        return $this->status == self::ACTIVE;
    }

    public function isCancelledByClient() {

        return $this->status == self::CANCELLED_BY_CLIENT;
    }

    public function isCancelledByAdmin() {

        return $this->status == self::CANCELLED_BY_ADMIN;
    }

    /*
     * Calculate duration of mission minus aborted slots
     * @return array of days hours minutes
     */

    public function calculateDurationWithAbortedSlots() {

        $originalTimeInSeconds = Calendar::duration_Seconds($this);

//get all slots
        $slots = $this->getMissionSlotsAborted();

        $totalAbortTimeInSeconds = 0;
        foreach ($slots as $slot) {

            $totalAbortTimeInSeconds += Calendar::duration_Seconds($slot);
        }

        $totalDuration = $originalTimeInSeconds - $totalAbortTimeInSeconds;

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($totalDuration);
    }

    public function calculcateDurationAbortedSlots() {

//get all slots
        $slots = $this->getMissionSlotsAborted();

        $totalAbortTimeInSeconds = 0;
        foreach ($slots as $slot) {

            $totalAbortTimeInSeconds += Calendar::duration_Seconds($slot);
        }

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($totalAbortTimeInSeconds);
    }

    /**
     * 
     * @return type array
     */
    public function calculateDurationOriginal() {

        $originalTimeInSeconds = Calendar::duration_Seconds($this);

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($originalTimeInSeconds);
    }

    /**
     * 
     * @param type $user Constants::USER_CLIENT or USER_CARER
     * @return type Price object
     */
    function getUnitPriceText($user) {

        switch ($this->type) {

            case self::TYPE_LIVE_IN:
                return Prices::getPriceText($user, Prices::LIVE_IN_DAILY_PRICE, $this->missionPayment->created);
                break;
            case self::TYPE_HOURLY:
                return Prices::getPriceText($user, Prices::HOURLY_PRICE, $this->missionPayment->created);
                break;
        }
    }

    /**
     * 
     * @return type string
     */
    public function calculateDurationOriginalText() {

        return Calendar::calculate_Duration_DisplayAll($this->start_date_time, $this->end_date_time);
    }

    public function displayAbortedSlots($user, $showPrice) {

        $html = '';

//get aborted by carer
        $slots = $this->getMissionSlotsAbortedBy(MissionSlotAborted::ABORTED_BY_CARER);

        if (count($slots) > 0) {

            if ($user == Constants::USER_CARER) {
                $html .= '<br /><p class="rc-statusandlabel-red">' . Yii::t('texts', 'NOTE_VISIT_MODIFIED_BY_THE_CARER') . '&#58;';
            } elseif ($user == Constants::USER_CLIENT) {
                $html .= '<br /><p class="rc-statusandlabel-red">' . Yii::t('texts', 'NOTE_VISIT_MODIFIED_BY_THE_CARER') . '&#58;';
            }

            foreach ($slots as $slot) {
                $html .= '<br />' . $slot->display($user);
            }
            $html .= '</p>';
        }

//get aborted by client        
        $slots = $this->getMissionSlotsAbortedBy(MissionSlotAborted::ABORTED_BY_CLIENT);

        if (count($slots) > 0) {

            if ($user == Constants::USER_CARER) {
                $html .= '<br /><p class="rc-statusandlabel-red">' . Yii::t('texts', 'NOTE_VISIT_MODIFIED_BY_THE_CLIENT') . '&#58;';
            } elseif ($user == Constants::USER_CLIENT) {
                $html .= '<br /><p class="rc-statusandlabel-red">' . Yii::t('texts', 'NOTE_VISIT_MODIFIED_BY_THE_CLIENT') . '&#58;';
            }

            foreach ($slots as $slot) {
                $html .= '<br />' . $slot->display($user);
            }
            $html .='</p>';
        }

        return $html;
    }

    public function getTypeLabel() {

        switch ($this->type) {

            case self::TYPE_LIVE_IN:
                return Yii::t('texts', 'LABEL_LIVE_IN_CARE');
                break;
            case self::TYPE_HOURLY:
                return Yii::t('texts', 'LABEL_HOURLY_CARE');
                break;
        }
    }

    /**
     * Load the actual request object (live in or hourly)
     */
    public static function loadChild($id) {

        switch (self::fetchType($id)) {
            case self::TYPE_LIVE_IN:
                return MissionLiveIn::loadModel($id);
                break;
            case self::TYPE_HOURLY:
                return MissionHourly::loadModel($id);
                break;
        }
    }

    /**
     * Return the type of the given request
     */
    public static function fetchType($id) {

        $sql = "SELECT type FROM tbl_mission WHERE id=$id";
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        return $row['type'];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_booking' => 'Id Request',
            'created' => 'Created',
            'modified' => 'Modified',
            'type' => 'Type',
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
        $criteria->compare('id_booking', $this->id_booking);
        //$criteria->compare('created', $this->created, true);
        //$criteria->compare('modified', $this->modified, true);
        //$criteria->compare('type', $this->type);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
            'pagination' => array(
                'pageSize' => 20,
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

//    public function defaultScope() {
//        return array(
//            'condition' => 't.id_client = ' . Yii::app()->user->id,
//        );
//    }

    public function scopes() {
        return array(
            'all' => array(
                'condition' => 't.status IN (' . self::ACTIVE . ',' . self::CANCELLED_BY_CLIENT . ',' . self::CANCELLED_BY_ADMIN . ')',
            ),
            'status' => array(
                'condition' => 'tbl_mission_carers.status=1',
            ),
            'cancelledMission' => array(
                'condition' => 'tbl_mission_carers.status=1',
            ),
            'cancelledMission' => array(
                'condition' => 'tbl_mission_carers.status=1',
            ),
//            'recently' => array(
//                'order' => 'create_time DESC',
//                'limit' => 5,
//            ),
        );
    }

    public function getMissionSlotsAborted() {

        return MissionSlotAborted::getMissionSlotsAborted($this->id);
    }

    public function getMissionSlotsAbortedBy($apportedBy) {

        return MissionSlotAborted::getMissionSlotsAbortedBy($this->id, $apportedBy);
    }

    /**
     * Return the status of a given carer and mission relation (MissionCarers)
     */
    public static function getCarerMissionStatus($carerId, $missionId) {

        $sql = "SELECT status FROM tbl_mission_carers WHERE id_mission=" . $missionId . " AND id_applying_carer=" . $carerId;
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row == null) {

            return MissionCarers::UNAPPLIED;
        } else {
            return $row['status'];
        }
    }

    /**
     * Return MissionCarers records by status
     */
    public function getMissionCarersByStatus($status) {

        $missionId = $this->id;

        $criteria = new CDbCriteria();
        $criteria->condition = "id_mission=:missionId AND status=:status";
        $criteria->params = array(':missionId' => $missionId, ':status' => $status);

        return MissionCarers::model()->findAll($criteria);
    }

    public function getColor($row) {

        $class = $row % 2 ? "even" : "odd";
//$status = self::getCarerMissionStatus(Yii::app()->user->id, $this->id);

        $missionStatus = $this->status;

        if ($missionStatus == self::CANCELLED_BY_CLIENT || $missionStatus == self::CANCELLED_BY_ADMIN) {
//$class = 'rc-row-greyed'; Not greyed if cancelled
        } else {

            if ($this->getCompletionStatus() == Mission::STARTED) {

                $class = 'rc-row-pinked';
            }
        }

        return $class;
    }

    public function hasComplaint() {

        return (count($this->complaints) > 0);
    }

    /**
     *  Return true if missin has a complaint resolved or not
     */
    public function hasComplaintClient() {

        $complaints = $this->complaintsClient;

        if (count($complaints) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function hasComplaintCarer() {

        $complaints = $this->complaintsCarer;

        if (count($complaints) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function setCarerCredited() {

        $missionId = $this->id;

        $sql = "UPDATE tbl_mission SET carer_credited = 1 WHERE id=$missionId";
        Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     *  Return true if missin has a unresolved complaint (any)
     */
    public function hasUnresolvedComplaint() {

        $complaints = $this->complaints;

        if (count($complaints) > 0) {

            foreach ($complaints as $complaint) {
                if ($complaint->solved == Complaint::UNSOLVED) {
                    return true;
                }
            }

            return false;
        } else {
            return false;
        }
//
//        $criteria = new CDbCriteria();
//        $criteria->join = 'LEFT JOIN tbl_mission_live_in t2 ON t.id = t2.id_mission LEFT JOIN tbl_request_live_in t3 ON t.id_booking = t3.id_booking';
//        $criteria->condition = " ADDTIME(t2.end_date, t3.start_time) <= DATE_SUB(NOW(), INTERVAL $sinceNumberDays day)";
//
//        return self::model()->findAll($criteria);
    }

    public static function getClientMissionsDataProvider($clientId) {

        //$missions = self::getClientMissions($clientId);

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_booking tb ON t.id_booking = tb.id';
        $criteria->condition = "tb.id_client=:clientId AND tb.type = " . Booking::TYPE_HOURLY ;
        $criteria->order = ' t.start_date_time DESC';
        $criteria->params = array(':clientId' => $clientId);


        $dp = new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));

        //$dp->setData($missions);

        return $dp;
    }

    /**
     * Return all missions belonging to given client
     */
    public static function getClientMissions($clientId) {

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_booking tb ON t.id_booking = tb.id';
        $criteria->condition = "tb.id_client=:clientId";
        $criteria->order = ' t.start_date_time DESC';
        $criteria->params = array(':clientId' => $clientId);

        return self::model()->findAll($criteria);
    }

    public static function getMissionsClientCount($clientId) {

        $sql = "SELECT COUNT(tbl_mission.id) FROM tbl_mission LEFT JOIN tbl_booking ON tbl_mission.id_booking = tbl_booking.id " .
                " WHERE tbl_booking.id_client = " . $clientId . " AND tbl_booking.type = " . Booking::TYPE_HOURLY;

        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num;
    }

    public function getCancelledByCarerMission() {
// Warning: Please modify the following code to remove attributes that
// should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('id_booking', $this->id_booking);
//$criteria->compare('start_date', $this->start_date, true);
//$criteria->compare('end_date', $this->end_date, true);
//$criteria->compare('start_date', $this->start_date, true);
//$criteria->compare('end_date', $this->end_date, true);
        $criteria->compare('status', $this->status, true);

        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
        $criteria->condition = 'tbl_mission_carers.status IN ('
                . MissionCarers::CANCEL_ASSIGNED . ')';


        return new CActiveDataProvider(Mission::model()->resetScope()->all(), array(
            'criteria' => $criteria,
        ));
    }

    public static function getAvailableMissionsDataProvider($carerId) {

        //tout sauf status assigned or selected
        //$businessRules = Yii::app()->params['businessRules'];
        $hours = BusinessRules::getNewMissionAdvertisedTimeInHours();
        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);
        $createdDate = Calendar::addHours($today, $hours, Calendar::FORMAT_DBDATETIME);

        $criteria = new CDbCriteria();
        $criteria->condition = 'NOT EXISTS (SELECT * FROM tbl_mission_carers as t2 WHERE t2.status IN (' .
                MissionCarers::ASSIGNED . ',' /* . MissionCarers::SELECTED . ',' */ . MissionCarers::NOT_SELECTED .
                ',' . MissionCarers::CONFIRM_SELECTION_LATE . ',' . MissionCarers::CANCEL_ASSIGNED . ')' .
                ' AND t.id = t2.id_mission )' .
                ' AND NOT EXISTS (SELECT * FROM tbl_mission_carers as t3 WHERE t3.status = ' . MissionCarers::SELECTED . ' 
                     AND t.id = t3.id_mission AND t3.id_applying_carer = :carerId ) ' .
                ' AND t.created < :createdDate' . //DATE_SUB(NOW(), INTERVAL :hour HOUR )
                ' AND t.carer_credited = 0' .
                ' AND t.status = ' . Mission::ACTIVE;
        $criteria->order = 't.start_date_time ASC';
        $criteria->params = array(':createdDate' => $createdDate, ':carerId' => $carerId);

        return new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));
    }

    /**
     * Get missions which are assigned to carer and not finished nor discarded
     */
    public static function getAssignedMissionsDataProvider($carerId) {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
        $criteria->condition = 'tbl_mission_carers.id_applying_carer = :carerId AND tbl_mission_carers.status IN (:status)' .
                ' AND ( t.end_date_time > :today )' .
                ' AND tbl_mission_carers.discarded = 0';
        //               ' AND t.discarded_by_carer = 0';
        $criteria->order = ' t.start_date_time ASC';
        $criteria->params = array(':carerId' => $carerId, ':status' => MissionCarers::ASSIGNED, ':today' => $today);

        return new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));
    }

    public static function getAllCarerAssignedMissions($carerId) {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
        $criteria->condition = 'tbl_mission_carers.id_applying_carer = :carerId AND tbl_mission_carers.status IN ('
                . MissionCarers::ASSIGNED . ')' .
                ' AND (tbl_mission.end_date_time > :today )' .
                ' AND tbl_mission_carers.discarded = 0';
        $criteria->params = array(':carerId' => $carerId, ':today' => $today);

        return self::model()->findAll($criteria);
    }

    public static function getCurrentMissionCount($carerId) {

//the following works but is inconsistant when changind dates
//        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);
//        
//        $sql = "SELECT COUNT(tbl_mission_carers.id) FROM tbl_mission LEFT JOIN tbl_mission_carers ON tbl_mission.id = tbl_mission_carers.id_mission " .
//                " WHERE tbl_mission_carers.id_applying_carer = $carerId" .
//                " AND tbl_mission_carers.status IN (" . MissionCarers::SELECTED . "," . MissionCarers::ASSIGNED . ',' . MissionCarers::APPLIED . ')' .
//                " AND ( tbl_mission.end_date_time > '" . $today . "')" .
//                " AND tbl_mission_carers.discarded = 0 ";
//
//        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        $num = count(self::getAssignedMissionsDataProvider($carerId)->getData());
        $num += count(self::getAwaitingMissionsDataProvider($carerId)->getData());
        $num += count(self::getSelectedMissionsDataProvider($carerId)->getData());

        return $num;
    }

    public static function getComplaintMissionsCarerCount($carerId) {

        $sql = "SELECT * FROM tbl_complaint " .
                " WHERE tbl_complaint.solved = " . Complaint::UNSOLVED .
                " AND tbl_complaint.id_carer = " . $carerId .
                " GROUP BY tbl_complaint.id_mission ";

        $command = Yii::app()->db->createCommand($sql);

        $models = $command->queryAll();

        return count($models);
    }

    public static function getComplaintMissionsClientDataProvider($clientId) {

        $criteria = new CDbCriteria();
        $criteria->select = 'u.id, id_address, id_booking, id_mission_payment, u.type, status, start_date_time, end_date_time, discarded_by_carer, discarded_by_client, carer_credited, cancel_by_client_date, u.created, u.modified';
        $criteria->alias = 'u';
        $criteria->join = 'LEFT JOIN tbl_complaint ON tbl_complaint.id_mission = u.id ';
        $criteria->condition = " tbl_complaint.id_client = :clientId AND tbl_complaint.solved = " . Complaint::UNSOLVED;
        $criteria->order = ' u.start_date_time DESC';
        $criteria->group = ' tbl_complaint.id_mission';
        $criteria->params = array(':clientId' => $clientId);

        $dp = new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
        )));

        //$dp->model->setDbCriteria($criteria);
        //$models = self::model()->findAll($criteria);
        //$dp->setData($models);

        return $dp;
    }

    public static function getComplaintMissionsClientCount($clientId) {

//        $sql = "SELECT COUNT(tbl_complaint.id) FROM tbl_complaint " .
//                " WHERE tbl_complaint.solved = " . Complaint::UNSOLVED .
//                " AND tbl_complaint.id_client = " . $clientId;
////" GROUP BY tbl_complaint.id_mission ";
//
//        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_complaint ON tbl_complaint.id_mission = t.id ';
        $criteria->condition = " tbl_complaint.id_client = :clientId AND tbl_complaint.solved = " . Complaint::UNSOLVED;
        $criteria->order = ' t.start_date_time DESC';
        $criteria->group = ' tbl_complaint.id_mission';
        $criteria->params = array(':clientId' => $clientId);

        //$res = self::model()->count($criteria);

        $models = self::model()->findAll($criteria);

        return count($models);
    }

    public static function getVerifyingMissionCount($carerId) {

        $daysToGiveFeedback = BusinessRules::getDelayToGiveFeedbackInDays();

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $sql = "SELECT COUNT(tbl_mission.id) FROM tbl_mission LEFT JOIN tbl_mission_carers ON tbl_mission.id = tbl_mission_carers.id_mission " .
                " WHERE tbl_mission.end_date_time <= '$today' " .
                ' AND carer_credited = 0' .
                " AND tbl_mission_carers.id_applying_carer = $carerId " .
                " AND tbl_mission_carers.status = " . MissionCarers::ASSIGNED .
                " AND tbl_mission.status = " . Mission::ACTIVE;


        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num;
    }

    public static function getHistoryMissionCount($carerId) {

        $sql = "SELECT COUNT(tbl_mission.id) FROM tbl_mission LEFT JOIN tbl_mission_carers ON tbl_mission.id = tbl_mission_carers.id_mission " .
                " WHERE tbl_mission_carers.id_applying_carer = $carerId " .
                " AND tbl_mission_carers.status IN (" . MissionCarers::ASSIGNED . "," . MissionCarers::CANCEL_ASSIGNED . ") " .
                " AND tbl_mission.carer_credited = 1";

        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num;
    }

    public static function getSelectedMissionsDataProvider($carerId) {

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
        $criteria->condition = 'tbl_mission_carers.id_applying_carer = :carerId' .
                ' AND tbl_mission_carers.status IN (' . MissionCarers::SELECTED . ', ' . MissionCarers::CONFIRM_SELECTION_LATE . ')' .
                ' AND tbl_mission_carers.discarded = 0';
        $criteria->order = ' t.start_date_time ASC';
        $criteria->params = array(':carerId' => $carerId);

        return new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));
    }

    public static function getAwaitingMissionsDataProvider($carerId) {

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
        $criteria->condition = 'tbl_mission_carers.id_applying_carer = :carerId AND tbl_mission_carers.status IN ('
                . MissionCarers::APPLIED . ', ' . MissionCarers::NOT_SELECTED . ')' .
                ' AND tbl_mission_carers.discarded = 0';
        $criteria->params = array(':carerId' => $carerId);
        $criteria->order = ' t.start_date_time ASC';

        return new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));
    }

    public static function getComplaintMissionsCarerDataProvider($carerId) {

        $criteria = new CDbCriteria();
        $criteria->select = 'u.id, id_address, id_booking, id_mission_payment, u.type, status, start_date_time, end_date_time, discarded_by_carer, discarded_by_client, carer_credited, cancel_by_client_date, u.created, u.modified';
        $criteria->alias = 'u';
        $criteria->join = 'LEFT JOIN tbl_complaint ON u.id = tbl_complaint.id_mission';
        $criteria->condition = "tbl_complaint.id_carer = " . $carerId .
                " AND tbl_complaint.solved = " . Complaint::UNSOLVED . " ";
        $criteria->group = " tbl_complaint.id_mission";
        $criteria->order = ' u.start_date_time DESC';

        $dp = new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            )
                )
        );

        //$models = self::model()->findAll($criteria);
        //$dp->setData($models);

        return $dp;
    }

    public static function getMissionsHistoryDataProvider($carerId) {

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
        $criteria->condition = 'tbl_mission_carers.id_applying_carer = :carerId' .
                ' AND tbl_mission_carers.status IN (' . MissionCarers::ASSIGNED . ', ' . MissionCarers::CANCEL_ASSIGNED . ')' .
                ' AND t.carer_credited = 1';
        $criteria->order = ' t.start_date_time DESC';
        $criteria->params = array(':carerId' => $carerId);

        return new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'start_date_time DESC',
            ),
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));
    }

    /**
     * SAS
     * 
     * @param type $carerId
     * @return CActiveDataProvider
     */
    public static function getFinishedUnpaidMissions($carerId) {

        //show mission wich have been completed

        $daysToGiveFeedback = BusinessRules::getDelayToGiveFeedbackInDays();

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $criteria = new CDbCriteria();
        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
        $criteria->condition = ' t.end_date_time <= DATE_SUB(:today, INTERVAL 0 day)' .
                ' AND carer_credited = 0' .
                ' AND t.status = :missionStatus' .
                ' AND tbl_mission_carers.id_applying_carer = :carerId' .
                ' AND tbl_mission_carers.status = :missionCarersStatus';
        $criteria->params = array(':carerId' => $carerId, ':today' => $today,
            ':missionCarersStatus' => MissionCarers::ASSIGNED,
            ':missionStatus' => Mission::ACTIVE,
        );

        return new CActiveDataProvider('Mission', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'start_date_time DESC',
            ),
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE,
            ),
        ));
    }

    /**
     * User by cron job to credit carers, the cron job uses more logic
     * 
     * Missions selected are 
     *  - finished for more than 7 days
     *  - not credited
     *  - active    
     * 
     * @param type $sinceNumberDays
     * @return type
     */
    public static function getFinishedUncreditedMissions($sinceNumberDays) {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $criteria = new CDbCriteria();
        //$criteria->join = 'LEFT JOIN tbl_mission_live_in t2 ON t.id = t2.id_mission LEFT JOIN tbl_request_live_in t3 ON t.id_booking = t3.id_booking';
        $criteria->condition = " t.end_date_time <= DATE_SUB(:today, INTERVAL :sinceNumberDays day) AND carer_credited = 0 AND status =:status";
        $criteria->params = array(':sinceNumberDays' => $sinceNumberDays, ':today' => $today, ':status' => self::ACTIVE);

        return self::model()->findAll($criteria);
    }

    /*
     * Create new MissionCarers and set its status to APPLIED
     */

    public static function apply($carerId, $missionId) {

        //check mission and carer not already assigned
        $status = self::getCarerMissionStatus($carerId, $missionId);

        if ($status == MissionCarers::UNAPPLIED) {

            $model = new MissionCarers();
            $model->id_applying_carer = $carerId;
            $model->id_mission = $missionId;

//            //if carer who applies is the short listed one give the mission straight away
//            $mission = self::loadModel($missionId);
//
//            if ($mission->isShortListed($carerId)) {
//                $returnValue = 2;
//                $model->status = MissionCarers::ASSIGNED;
//                Emails::sendToCarer_ConfirmedForMission(Carer::loadModel($carerId));
//            } else {
            $returnValue = 1;
            $model->status = MissionCarers::APPLIED;
//            }

            $model->save();

            ActionHistory::create($carerId, $missionId, ActionHistory::CARER_APPLY, ActionHistory::CARER);

            return $returnValue;
        } else {
            throw new CException('Carer already has a record with status ' . $status);
        }
    }

    /*
     * Set status to ASSIGNED and other carers to NOT_SELECTED
     */

    public static function assign($carerId, $missionId) {

        $missionCarers = self::getMissionCarers($missionId);

        foreach ($missionCarers as $missionCarer) {

            $model = MissionCarers::loadModel($missionCarer->id);

            if ($missionCarer->id_applying_carer == $carerId) {

                $model->status = MissionCarers::ASSIGNED;
            } else {
                $model->status = MissionCarers::NOT_SELECTED;
            }

            $model->save();
        }

        ActionHistory::create($carerId, $missionId, ActionHistory::CARER_CONFIRM_SELECTED, ActionHistory::CARER);
    }

    /*
     * Cancel carer application : remove the entry from tbl_mission_carers
     */

    public static function cancelApplied($carerId, $missionId) {

//remove from database the record
        $sql = "DELETE FROM tbl_mission_carers WHERE id_mission=" . $missionId . " AND id_applying_carer=" . $carerId;
        $row = Yii::app()->db->createCommand($sql)->query();

        ActionHistory::create($carerId, $missionId, ActionHistory::CARER_CANCEL_APPLY, ActionHistory::CARER);
    }

    /**
     * Delete all recrods for tbl_mission_carers and changed the created date of the mission so it's possible to reapply on chose mission
     * @param type $missionId
     */
    public function readvertiseMission() {

        $this->clearAllCarers();

        $this->created = Calendar::today(Calendar::FORMAT_DBDATETIME);
        $this->modified = null;
        $this->save(false);

        $booking = $this->booking;

        $carers = $booking->carers;
        $carerIds = array();
        foreach ($carers as $carer) {
            $carerIds[] = $carer->id;
        }

        //send emails to all carers but selected ones
        //Emails::sendToCarer_All_NewJob($carerIds);
        $postCode = $booking->location->post_code;
        $city = $booking->location->city;

        $potentialCarers = Carer::getPotentialCarersForMission($postCode, $booking, $carerIds);

        Emails::sendToCarers_Potential_NewJob($potentialCarers, $postCode, $city);

        //send emails to selected carers
        foreach ($carers as $carer) {
            Emails::sendToCarer_Shortlisted_NewJob($carer, $postCode, $city);
        }
    }

    /**
     * Remove all carers relations to the given mission (any status)
     * @param type $missionId
     */
    public function clearAllCarers() {

        $sql = "DELETE FROM tbl_mission_carers WHERE id_mission=" . $this->id;
        Yii::app()->db->createCommand($sql)->execute();
    }

    /*
     * Cancel a mission a user was selected for 
     */

    public static function cancelSelected($carerId, $missionId) {

        $sql = "DELETE FROM tbl_mission_carers WHERE id_mission=" . $missionId . " AND id_applying_carer=" . $carerId;
        $row = Yii::app()->db->createCommand($sql)->query();

//Emails::sendToAdmin_CarerCancelledSelectedMission($carerId, $missionId);

        ActionHistory::create($carerId, $missionId, ActionHistory::CARER_CANCEL_SELECTED, ActionHistory::CARER);
    }

    /**
     * Cancel by Direct homecare admin
     * 
     * clear all carer-missions statuses and handle client refund if flag true
     * 
     * @return true if successful, false if not, with setting error_text variable
     */
    public function cancelByAdmin($refund = false, $dbTransaction = true) {

        if ($dbTransaction) {
            $dbTransaction = Yii::app()->db->beginTransaction();
        }

        try {

            if ($this->status == Mission::CANCELLED_BY_ADMIN) {
                throw new CException('Mission already cancelled');
            } else {

                $this->clearAllCarers();

                $this->status = Mission::CANCELLED_BY_ADMIN;
                $this->save(false);

                if ($refund) {

                    $missionPrice = $this->getOriginalTotalPrice(Constants::USER_CLIENT);
                    $client = $this->booking->client;
                    $missionPayment = $this->missionPayment;
                    $creditCard = $missionPayment->creditCard;

                    if ($creditCard == null) {
                        throw CException('No credit card to do refund - used deleted it');
                    }

                    //Make reimbursement
                    //If voucher were used use NonReferencedCredit
                    $clientTransation = $missionPayment->getPaymentTransaction();

                    $handler = BorgunHandler::getInstance();

                    $paidCash = $clientTransation->getPaidCash();
                    $paidVoucher = $clientTransation->getPaidCredit();

                    //determine what should be paid back

                    if ($missionPrice->isLowerOrEqual($paidCash)) {

                        if ($paidCash->amount == 0) {
                            $refundAmount = null;
                            $refundVoucher = $missionPrice;
                        } else {
                            //refund cash amount
                            $refundAmount = $missionPrice;
                            $refundVoucher = null;
                        }
                    } else {

                        //partial payment cash
                        $refundAmount = $paidCash;
                        $refundVoucher = $missionPrice->substract($paidCash);

                        assert($paidVoucher->amount == $refundVoucher->amount);
                    }

                    //Make a Borgun refund
                    if ($refundAmount->amount > 0) {

                        $transactionRef = $missionPayment->transaction_id;
                        $transactionDate = $missionPayment->transaction_date;
                        $creditCardNumber = $creditCard->getDecryptedTemporaryInstance()->card_number;

                        $successful = $handler->doRefund($transactionRef, $refundAmount, $creditCardNumber, $transactionDate);
                    } else {

                        $successful = true;
                    }

                    if (!$successful) {

                        //$array = $handler->getResponse();
                        //$this->status = Mission::ACTIVE; //
                        $dbTransaction->rollBack();
                        $this->error_text = $handler->getLongErrorMessage();
                        return false;
                    } else {

                        //Create transaction for reimbursement
                        ClientTransaction::createReimbursment($client->id, $missionPayment->id, $this->id, $refundAmount, $refundVoucher, true);

                        if ($dbTransaction) {
                            $dbTransaction->commit();
                        }

                        return true;
                    }
                }
                return true;
            }
        } catch (CException $e) {
            if ($dbTransaction) {
                $this->error_text = $e->getMessage();
                $dbTransaction->rollBack();

                return false;
            } else {
                throw $e;
            }
        }
    }

    /**
     * The carer cancels an assigned mission
     * 
     * @param type $carerId
     * @param type $missionId
     */
    public function cancelAssigned($carerId) {

        $limitHours = BusinessRules::getMissionReadvertiseLiveInMissionInHours();

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);
        $missionStartDate = $this->start_date_time;
        $difference = Calendar::hoursBetween_DBDateTime($today, $missionStartDate, false);

//        if ($difference > $limitHours) {
//
//            //new logic, no more status CANCEL_ASSIGNED but the record is deleted which re-apply
//            //$this->readvertiseMission();
//        } else {
            //cancel mission by direct homecare and refund
            $this->cancelByAdmin(true);
///        }

        //later for automation
        $status = $this->getCompletionStatus();

        switch ($status) {

            case self::NOT_STARTED:

                break;

            case self::STARTED:

                break;

            case self::FINISHED:

                break;
        }

        //warn admin
        //Emails::sendToAdmin_CarerCancelledAssignedMission($carerId, $this->id);

        ActionHistory::create($carerId, $this->id, ActionHistory::CARER_CANCEL_ASSIGNED, ActionHistory::CARER);
    }

    /*
     * Set existing MissionCarers status
     */

    public static function setCarerMissionStatus($carerId, $missionId, $status) {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $sql = "UPDATE tbl_mission_carers SET status = $status , modified = '$today' WHERE id_mission= $missionId AND id_applying_carer=$carerId";
        return Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * 
     * @param type $carerId carer
     * @param type $missionId mission
     * @param type $value 0 not discarded, 1 discarded
     * @return type
     */
    public static function setCarerMissionDiscarded($carerId, $missionId, $value) {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        if ($value == false) {
            $value = 0;
        }

        if ($value == true) {
            $value = 1;
        }

        $sql = "UPDATE tbl_mission_carers SET discarded = $value, modified = '$today' WHERE id_mission= $missionId AND id_applying_carer=$carerId";
        return Yii::app()->db->createCommand($sql)->execute();
    }

    /**
     * Return assigned carer for the mission
     */
    public static function getSelectedCarerId($missionId) {

        $sql = "SELECT id_applying_carer FROM tbl_mission_carers WHERE id_mission=" . $missionId . " AND status=" . MissionCarers::SELECTED;
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row == null) {

            return null;
        } else {
            return $row['id_applying_carer'];
        }
    }

    /**
     * Get all the MissionCarers objects for given mission
     */
    public static function getMissionCarers($liveInMissionId) {

        $criteria = new CDbCriteria();
        $criteria->condition = "id_mission = $liveInMissionId";
        return MissionCarers::model()->findAll($criteria);
    }

    /**
     * Return missions where no carer is selected and which are not cancelled
     */
    public static function getMissionsNoCarerSelected() {

        $sql = "SELECT id_mission FROM (
                    SELECT id_mission, max(
                    tbl_mission_carers.status ) AS maximum
                    FROM tbl_mission_carers
                    INNER JOIN tbl_mission ON tbl_mission_carers.id_mission = tbl_mission.id
                    WHERE tbl_mission.status = 0
                    GROUP BY id_mission
                    ) AS t2
                WHERE maximum = 1 
                ORDER BY id_mission ASC";

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        if ($rows == null) {

            return null;
        } else {

            $ids = array();
            foreach ($rows as $row) {

                $ids[] = $row['id_mission'];
            }
        }

        $criteria = new CDbCriteria;

        $criteria->order = "id DESC";

        return static::model()->findAllByPk($ids, $criteria);
    }

    /**
     * Return mission where no carer has applied, mission must be active (status 0)
     */
    public static function getMissionsNoCarerApplied() {

        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = ' . self::ACTIVE . ' AND NOT EXISTS (SELECT * FROM tbl_mission_carers as t2 WHERE t.id = t2.id_mission)';
        $criteria->order = 'id DESC';

        return self::model()->findAll($criteria);
    }

    /**
     * Get Mission with given status
     */
    public static function getMissionsByStatus($status) {

        $sql = "SELECT id_mission FROM tbl_mission_carers WHERE status=" . $status;
        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        if ($rows == null) {

            return null;
        } else {

            $ids = array();
            foreach ($rows as $row) {

                $ids[] = $row['id_mission'];
            }
        }

        $criteria = new CDbCriteria;

        $criteria->order = "id DESC";

        return static::model()->findAllByPk($ids, $criteria);
    }

    public static function getMissionsCarerAssignedNotStarted() {

        $assigned = MissionCarers::ASSIGNED;
        $sql = "SELECT id_mission FROM tbl_mission_carers mc INNER JOIN tbl_mission m ON mc.id_mission = m.id "
                . "WHERE mc.status= $assigned "
                . "AND m.start_date_time > NOW()";

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        if ($rows == null) {

            return null;
        } else {

            $ids = array();
            foreach ($rows as $row) {

                $ids[] = $row['id_mission'];
            }
        }

        $criteria = new CDbCriteria;

        $criteria->order = "id DESC";

        return static::model()->findAllByPk($ids, $criteria);
    }

    /**
     * Return mission where carer has not confirmed yet
     */
    public static function getMissionsCarerNotConfirmed() {

        return self::getMissionsByStatus(MissionCarers::SELECTED);
    }

    /**
     * Return mission where carer is assigned
     */
    public static function getMissionsCarerAssigned() {

        return self::getMissionsByStatus(MissionCarers::ASSIGNED);
    }

    public function discardByClient() {

        $this->discarded_by_client = true;
        $succesful = $this->save(false);
    }

    public function isDiscardedByClient() {

        return $this->discarded_by_client;
    }

    /**
     * Can only be called by carer who is selected/assigned to this mission
     */
    public function discardByCarer() {

        $this->discarded_by_carer = true;
        $this->save(false);
    }

    public static function isDiscarded($carerId, $missionId) {

        $sql = "SELECT discarded FROM tbl_mission_carers t1 LEFT JOIN tbl_live_in_mission t2 ON t1.id_mission = t2.id " .
                " WHERE t1.id_applying_carer = " . $carerId . " AND id_mission=" . $missionId;
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        return $row['discarded'];
    }

    public static function getStatusOptions($html = true) {

        if ($html) {
            return array(
                self::ACTIVE => 'Active',
                //self::CANCELLED_BY_CARER => Yii::t('texts', 'CANCELLED_BY_CARER'), Not used
                self::CANCELLED_BY_CLIENT => '<span class="rc-statusandlabel-red">' . Yii::t('texts', 'STATUS_CANCELLED_BY_CLIENT') . '</span>',
                //self::ABORTED_BY_CLIENT => 'Aborted by client', Not used
                self::CANCELLED_BY_ADMIN => '<span class="rc-statusandlabel-red">' . Yii::t('texts', 'STATUS_CANCELLED_BY_ADMIN') . '</span>',
            );
        } else {
            return array(
                self::ACTIVE => 'Active',
                //self::CANCELLED_BY_CARER => Yii::t('texts', 'CANCELLED_BY_CARER'), Not used
                self::CANCELLED_BY_CLIENT => Yii::t('texts', 'STATUS_CANCELLED_BY_CLIENT'),
                //self::ABORTED_BY_CLIENT => 'Aborted by client', Not used
                self::CANCELLED_BY_ADMIN => Yii::t('texts', 'STATUS_CANCELLED_BY_ADMIN'),
            );
        }
    }

    public static function getStatusTooltipOptions() {

        return array(
            self::ACTIVE => 'Active',
            //self::CANCELLED_BY_CARER => Yii::t('texts', 'CANCELLED_BY_CARER'), Not used
            self::CANCELLED_BY_CLIENT => Yii::t('texts', 'ALT_THIS_MISSION_HAS_BEEN_CANCELLED_BY_THE_CLIENT'),
            //self::ABORTED_BY_CLIENT => 'TO TEXT', Not used
            self::CANCELLED_BY_ADMIN => Yii::t('texts', 'ALT_THIS_MISSION_HAS_BEEN_CANCELLED_BY_THE_ADMINISTRATOR'),
        );
    }

    public static function getCompletionStatusOptionsClient() {

        return array(self::NOT_STARTED => Yii::t('texts', 'STATUS_VISIT_NOT_STARTED'),
            self::STARTED => Yii::t('texts', 'STATUS_VISIT_STARTED'),
            self::FINISHED => Yii::t('texts', 'STATUS_VISIT_COMPLETED'),
        );
    }

    public static function getCompletionStatusOptionsCarer() {

        return array(self::NOT_STARTED => Yii::t('texts', 'STATUS_MISSION_NOT_STARTED'),
            self::STARTED => Yii::t('texts', 'STATUS_MISSION_STARTED'),
            self::FINISHED => Yii::t('texts', 'STATUS_MISSION_COMPLETED'),
        );
    }

    public static function getCompletionStatusTooltipOptionsClient() {

        return array(self::NOT_STARTED => Yii::t('texts', 'STATUS_VISIT_NOT_STARTED'),
            self::STARTED => Yii::t('texts', 'STATUS_VISIT_STARTED'),
            self::FINISHED => Yii::t('texts', 'STATUS_VISIT_COMPLETED'),
        );
    }

    public static function getCompletionStatusTooltipOptionsCarer() {

        return array(self::NOT_STARTED => Yii::t('texts', 'STATUS_MISSION_NOT_STARTED'),
            self::STARTED => Yii::t('texts', 'STATUS_MISSION_STARTED'),
            self::FINISHED => Yii::t('texts', 'STATUS_MISSION_COMPLETED'),
        );
    }

    public function getCompletionStatusTooltip($user) {

        if ($user == Constants::USER_CLIENT) {

            $options = self::getCompletionStatusTooltipOptionsClient();
        } else {

            $options = self::getCompletionStatusTooltipOptionsCarer();
        }

        $completionStatus = $this->getCompletionStatus();

        $result = $options[$completionStatus];

        return $result;
    }

    public function getCompletionStatusLabel($user) {

        if ($user == Constants::USER_CLIENT) {

            $options = self::getCompletionStatusOptionsClient();
        } else {

            $options = self::getCompletionStatusOptionsCarer();
        }

        $completionStatus = $this->getCompletionStatus();

        $result = $options[$completionStatus];

        if (count($this->missionSlotsAborted) > 0) {

            $result .= '<br /><span class="rc-statusandlabel-red">' . Yii::t('texts', 'STATUS_MODIFIED_SEE_DETAILS') . '</span>';
        }
        return $result;
    }

    public function getStatusLabel() {
        $array = $this->getStatusOptions();
        return $array[$this->status];
    }

    public function getStatusTooltip() {
        $array = $this->getStatusTooltipOptions();
        return $array[$this->status];
    }

    public function isFinished() {

        if ($this->getCompletionStatus() == self::FINISHED) {
            return true;
        } else {
            return false;
        }
    }

    public function isStarted() {

        if ($this->getCompletionStatus() == self::STARTED) {
            return true;
        } else {
            return false;
        }
    }

    public function isNotStarted() {

        if ($this->getCompletionStatus() == self::NOT_STARTED) {
            return true;
        } else {
            return false;
        }
    }

    public function getCompletionStatus() {

        $endDateTime = Calendar::convert_DBDateTime_Timestamp($this->end_date_time);
        $startDateTime = Calendar::convert_DBDateTime_Timestamp($this->start_date_time);

        $now = Calendar::today(Calendar::FORMAT_TIMESTAMP);

        if ($now < $startDateTime) {

            return self::NOT_STARTED;
        } elseif ($now > $endDateTime) {

            return self::FINISHED;
        } else {

            return self::STARTED;
        }
    }

    public function displayServiceTypeUsersConditionsHTML($compact = false) {

        $serviceUsers = $this->serviceUsers;
        $result = $this->getTypeLabel() . '<br />';
        $userNumber = 1;
        foreach ($serviceUsers as &$serviceUser) {

            $text = $userNumber . ' - ' . $serviceUser->displayAgeGenderConditions($compact) . '<br />';

            $result .= $text;

            $userNumber = $userNumber + 1;
        }

        return $result;
    }

    public function displayActivitiesTooltip() {

        $serviceUsers = $this->serviceUsers;

        $activitiesResults = array();

        foreach ($serviceUsers as &$serviceUser) {

            $activities = $serviceUser->getActivities();

            foreach ($activities as $activity) {

                if (!array_key_exists($activity->id, $activitiesResults)) {

                    $activitiesResults[$activity->id] = $activity;
                }
            }
        }
        $result = '';
        foreach ($activitiesResults as $activitiesResult) {

            $text = Condition::getTextCarer($activitiesResult->name) . '<br />';
            $result .= $text;
        }

        return $result;
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

    public function displayServiceUsersNameConditionsHTML() {

        $serviceUsers = $this->serviceUsers;
        $result = '';
        $userNumber = 1;
        foreach ($serviceUsers as &$serviceUser) {

            $text = '<p>' . $userNumber . ' - ' . $serviceUser->fullName . '<br />' . $serviceUser->displayAgeGenderConditionsActivities() . '<br />';
            $text .= '<span class="rc-note">' . Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_USER') . '&#58;</span><br />';
            if (isset($serviceUser->note) && $serviceUser->note != '') {
                $text .= $serviceUser->note;
            } else {
                $text .= Yii::t('texts', 'LABEL_NO_NOTES');
            }
            $text .= '</p>';

            $result .= $text;

            $userNumber = $userNumber + 1;
        }
        return $result;
    }

    /**
     * Use in table column
     */
    public function displayServiceUsersConditionsHTML($compact = false) {

        $serviceUsers = $this->serviceUsers;

        $result = '<ol>';

        foreach ($serviceUsers as &$serviceUser) {

            $text = $serviceUser->displayAgeGenderConditions($compact);

            $result = $result . '<li>' . $text . '</li>';
        }

        return $result . '</ol> ';
    }

    public function getTimeLeftFeeback() {

        $endDateTime = $this->end_date_time;
        $now = Calendar::today(Calendar::FORMAT_DBDATETIME);

        $daysLeft = Calendar::daysBetween_DBDate($now, $endDateTime);
        $feedback = BusinessRules::getDelayToGiveFeedbackInDays();
        $result = $daysLeft + $feedback;

        return $result;
    }

    /**
     * Return the time left for the carers to be paid, it's actually the same as getTimeLeftFeeback
     */
    public function getTimeLeftToBePaid() {


        $now = Calendar::today();

        $daysLeft = Calendar::daysBetween_DBDate($now, $this->end_date_time, true);

        $daysFeedback = BusinessRules::getDelayToGiveFeedbackInDays();

        $result = $daysLeft + $daysFeedback;

        return $result;
    }

    /**
     * Get time left for a carer to confirm a mission
     */
    public function getTimeLeftConfirm() {

        $carerStatus = $this->getMissionCarersByStatus(MissionCarers::SELECTED);

        $record = $carerStatus[0];

        $hoursSinceSelection = Calendar::hoursBetween_DBDateTime($record->modified, Calendar::today(Calendar::FORMAT_DBDATETIME));
        $hours = BusinessRules::getTimeToConfirmSelectedMissionInHours();
        $result = $hours - $hoursSinceSelection;

        return $result;
    }

    /**
     * Return assigned carer for the mission
     * 
     * @return type Carer
     */
    public function getAssignedCarer() {

        $carers = $this->getAssociatedCarers(MissionCarers::ASSIGNED);
        return $carers[0];
    }

    /**
     * 
     * @return type string
     */
    public function getAssignedCarerName() {

        $carer = $this->getAssignedCarer();

        if (isset($carer)) {
            return $carer->getFullName();
        } else {
            return Yii::t('texts', 'STATUS_CARER_NOT_YET_ASSIGNED');
        }
    }

    /**
     * Return selected carer for the mission
     */
    public function getSelectedCarer() {

        $carers = $this->getAssociatedCarers(MissionCarers::SELECTED);
        return $carers[0];
    }

    public function getCarerStatusLabel() {

        $status = self::getCarerMissionStatus(Yii::app()->user->id, $this->id);
        $options = MissionCarers::getAllStatusOptions();

        return $options[$status];
    }

    public function getCarerStatusTooltip() {

        $status = self::getCarerMissionStatus(Yii::app()->user->id, $this->id);
        $options = MissionCarers::getAllStatusTooltipOptions();

        return $options[$status];
    }

    /**
     * Return number of days in the Mission (days only). Rounded up
     * @return type integer
     */
    public function getNumberDays() {

        return Calendar::daysBetween_DBDate($this->start_date_time, $this->end_date_time);
    }

    /**
     * Return number of hours (rounded up)
     * @return type hours
     */
    public function getNumberHours() {
        return Calendar::hoursBetween_DBDateTime($this->start_date_time, $this->end_date_time);
    }

    /**
     * Display number of days hours and minutes
     */
    public function getDurationText() {

        return Calendar::calculate_Duration_DisplayAll($this->start_date_time, $this->end_date_time, false);
    }

    /**
     * Return all the carers which are related to this mission for the given status. 0 returns all
     */
    public function getAssociatedCarers($status = 0) {

        $sql = "SELECT id_applying_carer FROM tbl_mission_carers WHERE id_mission=" . $this->id;

        if ($status != 0) {
            $sql .= " AND status=" . $status;
        }

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        if ($rows == null) {
            return null;
        } else {

            $ids = array();

            foreach ($rows as $row) {
                $ids[] = $row['id_applying_carer'];
            }
            return Carer::loadModels($ids);
        }
    }

    /**
     * @return type 12/01/2014 10:00 to 14:00
     */
    public function displayDateTimes() {

        $date = Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time);
        $startTime = Calendar::convert_DBDateTime_DisplayTime($this->start_date_time);
        $endTime = Calendar::convert_DBDateTime_DisplayTime($this->end_date_time);

        return "$date $startTime to $endTime";
    }

    public function displayMissionShort($boldDates = true) {

        return $this->getTypeLabel() . ' - ' .
                Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time, false, '&#32;', $boldDates) .
                '&#160;' . Yii::t('texts', 'LABEL_TO') . '&#160;' .
                Calendar::convert_DBDateTime_DisplayDateTimeText($this->end_date_time, false, '&#32;', $boldDates) .
                ' - ' . $this->displayServiceUsersText();
    }

    public function displayForCarerCompensation() {
        $text = 'Id: ' . $this->id;
        $text .= ' - Date ' . Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time, false, ' at ', false);
        $text .= ' - Client ' . $this->booking->client->fullName;
        return $text;
    }

    /**
     * 
     * Pay carers once mission is finished
     * 
     * Used by cron jobs
     * 
     * @throws Exception
     */
    public static function creditCarers() {

        $transaction = Yii::app()->db->beginTransaction();

        try {

            //read all finished missions

            $daysToGiveFeedback = BusinessRules::getDelayToGiveFeedbackInDays();

            $missions = Mission::getFinishedUncreditedMissions($daysToGiveFeedback);

            echo "Number of visits finished by more than 7 days, uncredited, active and without open complants: " . count($missions) . '<br />';

            foreach ($missions as $mission) {

                echo "<h3>Mission: " . $mission->id . '</h3>';

                if ($mission->hasUnresolvedComplaint()) {

                    //pay carer
                    echo 'Mission id : ' . $mission->id . '<br />';
                    echo 'Has unresolved complaint - <b>cannot pay</b><br />';
                    //create new transaction
                } else {

                    echo 'No complaint or unresolved complaint: <b>can pay</b><br />';

                    //create carer transaction
                    $toPay = $mission->getTotalPrice(Constants::USER_CARER);

                    $carer = $mission->getAssignedCarer();
                    if ($carer == null) {
                        // $carerId = ; //TODO SHOULD NEVER HAPPEN IN PRODUCTION  
                        echo 'No carer assigned, skipping record';
                        continue;
                    } else {
                        $carerId = $carer->id;
                    }

                    echo 'to Pay' . $toPay->amount;

                    CarerTransaction::createCreditPayment($carerId, $mission->id, $toPay);

                    //set carer_credited to 1
                    $mission->setCarerCredited();
                }

                echo $mission->id . '<br />';
                echo $mission->displayMissionPayments(Constants::USER_CARER);
                echo '<hr />';
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }

        echo '<hr>';
        echo '<br /> Finished';
    }

    public function isCancelButtonVisible() {

        $hours = BusinessRules::getClientCancelServiceBeforeStartInHours();
        $now = Calendar::today(Calendar::FORMAT_DBDATETIME);

        if ($this->isActive()) {

            $difference = Calendar::hoursBetween_DBDateTime($now, $this->start_date_time, false);

            if ($difference >= $hours) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isFeebackButtonVisible() {

        return $this->isActive() && ($this->isStarted() || $this->isFinished());
    }

    public function isCorrectTimeButtonVisible() {
        return $this->isActive() && $this->isFinished();
    }

    /**
     * The client cancels the mission before it has started
     * 
     * Warns affected carers if any by email
     */
    public function cancelByClient($dbTransaction = true) {

        if ($this->status != self::CANCELLED_BY_CLIENT) { //make sure the mission is not already cancelled
            if ($dbTransaction) {
                $transaction = Yii::app()->db->beginTransaction();
            }

            try {

                //set record to cancelled by client, and the date
                $this->status = self::CANCELLED_BY_CLIENT;
                $this->cancel_by_client_date = Calendar::today(Calendar::FORMAT_DBDATETIME);

                $this->save(false);

                //$cancelMoneyDays = $this->calculateCancelMoneyDays();
                //warn every carer that the mission has been cancelled
                $associatedCarers = $this->getAssociatedCarers();

                if (isset($associatedCarers)) {

                    foreach ($associatedCarers as $carer) {

                        $currentStatus = $this->getCarerStatus($carer->id);

                        if ($currentStatus == MissionCarers::APPLIED || $currentStatus == MissionCarers::SELECTED || $currentStatus == MissionCarers::ASSIGNED) {

                            Emails::sendToCarer_AssignedMissionCancelledByClientOrAdmin(Carer::loadModel($carer->id));

                            Yii::log('Email sent to ' . $carer->id . ' who has status ' . $currentStatus, CLogger::LEVEL_ERROR, 'client_cancel_mission');

                            if ($currentStatus == MissionCarers::ASSIGNED) {

                                if (Yii::app()->params['test']['debugUser'] == true && Yii::app()->params['test']['debugCancelByClientCreditCarer'] == false) {
                                    Yii::log('Mission cancelled with no Carer Credit', CLogger::LEVEL_ERROR, 'client_cancel_mission');
                                } else {
                                    $cancelMoneyAmountsCarer = $this->calculateCancelMoneyAmountsCarer();

                                    CarerTransaction::createCreditCancelClient($carer->id, $this->id, $cancelMoneyAmountsCarer);
                                }
                            }
                        }

                        ActionHistory::create($carer->id, $this->id, ActionHistory::CLIENT_CANCEL_SERVICE, ActionHistory::CLIENT);
                    }
                }

                $cancelVoucherAmounts = $this->calculateCancelMoneyAmountsClient();

                $voucher = $cancelVoucherAmounts['voucher'];

                ClientTransaction::createCancellation($this->booking->id_client, $this->id_mission_payment, $this->id, $voucher);

                if ($dbTransaction) {
                    $transaction->commit();
                }
            } catch (CException $e) {
                if ($dbTransaction) {
                    $this->error_text = $e->getMessage();
                    $dbTransaction->rollBack();
                } else {
                    throw $e;
                }
            }
        }
    }

    public function calculateCancelMoneyAmountsClient() {

        $carer = $this->getAssignedCarer();

        $totalMissionPrice = $this->getOriginalTotalPrice(Constants::USER_CLIENT);

        //if carer is assigned voucher 100%
        if (!isset($carer)) {

            $notice = 'carer';
            $voucher = $totalMissionPrice;
        } else {

            //7 days
            $SHORT_NOTICE_HOURS = BusinessRules::getClientCancelServiceShortNoticeInHours();

            $dateNow = Calendar::today(Calendar::FORMAT_DBDATETIME);
            $missionStartTime = $this->start_date_time;

            $hoursBeforeStart = Calendar::hoursBetween_DBDateTime($dateNow, $missionStartTime, false);

            if ($hoursBeforeStart < $SHORT_NOTICE_HOURS) {

                $notice = 'short';
                $rate = BusinessRules::getClientCancelServiceShortNoticeMoneyLostPercentage(); //25%
            } else {

                $notice = 'medium';
                $rate = BusinessRules::getClientCancelServiceMediumNoticeMoneyLostPercentage(); //10%
            }

            $voucher = $totalMissionPrice->multiply((100 - $rate) / 100);
        }

        return array('voucher' => $voucher, 'notice' => $notice);
    }

    /**
     * 
     * @return type Price
     */
    public function calculateCancelMoneyAmountsCarer($useClientCancelDate = false) {

        $totalMissionPrice = $this->getOriginalTotalPrice(Constants::USER_CARER);

        $SHORT_NOTICE_HOURS = BusinessRules::getClientCancelServiceShortNoticeInHours();

        if ($useClientCancelDate) {
            $dateNow = $this->cancel_by_client_date;
        } else {
            $dateNow = Calendar::today(Calendar::FORMAT_DBDATETIME);
        }

        $hoursBeforeStart = Calendar::hoursBetween_DBDateTime($dateNow, $this->start_date_time, false);

        if ($hoursBeforeStart < $SHORT_NOTICE_HOURS) {//if less than 7 days (in hours)
            $rate = BusinessRules::getClientCancelServiceShortNoticeMoneyLostPercentage(); //25%
        } else {

            $rate = BusinessRules::getClientCancelServiceMediumNoticeMoneyLostPercentage(); //10%
        }

        $compensation = $totalMissionPrice->multiply(($rate) / 100);

        return $compensation;
    }

    /**
     * Return the price including cancellations
     * 
     * @param type $user user point of view
     * @return type Price
     */
    public function getTotalPrice($user) {

        return Prices::getTotalPrice($this, $user, false);
    }

    /**
     * 
     * Return the price with current live price
     * 
     * @param type $user user point of view
     * @return type Price
     */
    public function getTotalLivePrice($user) {

        return Prices::getOriginalTotalPrice($this, $user, true);
    }

    /**
     * 
     * Return the price when it was paid
     * 
     * @param type $user user point of view
     * @return type Price
     */
    public function getOriginalTotalPrice($user) {

        return Prices::getOriginalTotalPrice($this, $user, false);
    }

//    public function calculateCancelMoneyDays($testStartDate = null, $testEndDate = null) {
//
//        $result = array('daysShort' => 0, 'daysMedium' => 0, 'daysLong' => 0);
//
//        $SHORT_NOTICE_HOURS = BusinessRules::getClientCancelServiceShortNoticeInHours();
//
//        //TEST
//        if ($testStartDate != null && $testEndDate != null) {
//            $this->start_date = $testStartDate; //'2012-12-07';
//            $this->end_date = $testEndDate; //'2012-12-09';
//        }
//
//        $missionLengthDays = $this->getNumberDays();
//
//        $dateNow = Calendar::today(Calendar::FORMAT_DBDATETIME);
//
//        //get number of days of the mission within 72 hours
//        // date - nombre d'heuere
//        $hoursBeforeStart = Calendar::hoursBetween_DBDateTime($dateNow, $this->start_date_time, false);
//
//        if ($hoursBeforeStart < $SHORT_NOTICE_HOURS) {
//
//            $hours = $SHORT_NOTICE_HOURS - $hoursBeforeStart;
//
//            $daysShortNotice = ceil($hours / 24);
//
//            if ($missionLengthDays < $daysShortNotice) {
//                $daysShortNotice = $missionLengthDays;
//
//                $result['daysShort'] = $daysShortNotice;
//                return $result;
//            }
//
//            //next days MEDIUM notice
//            $daysMeidumNotice = $missionLengthDays - $daysMeidumNotice;
//
//            $result['daysShort'] = $daysShortNotice;
//            $result['daysMeidum'] = $daysMeidumNotice;
//            return $result;
//        } else {
//            $result['daysMedium'] = $missionLengthDays;
//            return $result;
//        }
//
//        return false;
//    }

    public function displayCancelServiceByClient() {

        return Yii::t('texts', 'STATUS_CANCELLED_BY_CLIENT');
    }

    public function displayCancelServiceByAdmin() {

        return Yii::t('texts', 'STATUS_CANCELLED_BY_ADMIN');
    }

    public static function getMissionsCancellableByAdmin() {

        $hours = BusinessRules::getCancelByAdminHoursBeforeMissionStart();

        //status is ACTIVE, no carer assigned, and start_date_time in less than 48 hours
        $today = Calendar::today();

        $startDateCompare = Calendar::addHours($today, $hours, Calendar::FORMAT_DBDATETIME);

        $criteria = new CDbCriteria();

        $criteria->condition = "t.status = :status AND t.start_date_time < :startDateCompare " .
                " AND NOT EXISTS (SELECT id FROM tbl_mission_carers as t2 WHERE t2.status = :statusAssigned AND t2.id_mission = t.id)";
        $criteria->params = array(':status' => self::ACTIVE, ':startDateCompare' => $startDateCompare, ':statusAssigned' => MissionCarers::ASSIGNED);

        return self::model()->findAll($criteria);
    }

    /**
     * 
     * Display status of a cancelled mission
     * 
     * @param type $user point of view
     */
    public function displayCancel($user) {

        $result = '';

        if (!$this->isActive()) {
            $result = '<p class="rc-statusandlabel-red">';
            if ($this->status == Mission::CANCELLED_BY_ADMIN) {
                if ($user == Constants::USER_CLIENT) {
                    $result .= Yii::t('texts', 'NOTE_VISIT_CANCELLED_BY_THE_ADMIN_DISPLAY_CLIENT');
                }
            } elseif ($this->status == Mission::CANCELLED_BY_CLIENT) {
                if ($user == Constants::USER_CLIENT) {
                    $result .= Yii::t('texts', 'NOTE_VISIT_CANCELLED_BY_THE_CLIENT_DISPLAY_CLIENT');
                } else {
                    $result .= Yii::t('texts', 'NOTE_VISIT_CANCELLED_BY_THE_CLIENT_DISPLAY_CARER');
                }
            }
            $result .= '</p>';
        }

        return $result;
    }

    /**
     * Check current user has privilege for this
     * @param type $missionId
     */
    public static function authorizeClient($missionId) {

        $user = Yii::app()->user->id;

        $sql = "SELECT m.id FROM tbl_booking b INNER JOIN tbl_mission m ON b.id = m.id_booking WHERE b.id_client=$user AND m.id = $missionId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if (!isset($row['id'])) {

            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }
    }

    public static function authorizeCarer($missionId) {

        $user = Yii::app()->user->id;
        $selected = MissionCarers::SELECTED;
        $assigned = MissionCarers::ASSIGNED;

        $sql = "SELECT mc.id FROM tbl_mission_carers mc INNER JOIN tbl_mission m ON m.id = mc.id_mission "
                . "WHERE mc.id_applying_carer=$user AND m.id = $missionId AND mc.status IN($selected, $assigned)";
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if (!isset($row['id'])) {

            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }
    }

    public function getInvoiceRawData($user) {

        $line = array(
            'date' => Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time, false, ' ', false),
            'service' => $this->getTypeLabel(),
            'provider' => $this->getAssignedCarerName(),
            'quantity' => $this->calculateDurationOriginalText(),
            'unitPrice' => $this->getUnitPriceText($user),
            'total' => $this->getOriginalTotalPrice($user)->text,
        );
        $rawData[] = $line;

//now check if mission was cancelled
//check slots
        $slotsByClient = $this->getMissionSlotsAborted();

        if (count($slotsByClient) > 0) {

            foreach ($slotsByClient as $slotByClient) {

                $line = array(
                    'date' => '',
                    'service' => $slotByClient->displayInvoice1($user, false),
                    'provider' => $slotByClient->displayInvoice2($user, false),
                    'quantity' => $slotByClient->getDurationText(),
                    'unitPrice' => $slotByClient->getTextInvoice(),
                    'total' => $slotByClient->getPriceText($user, false),
                );

                $rawData[] = $line;
            }
        }

        if ($this->isCancelledByClient()) {

            if ($user == Constants::USER_CLIENT) {

                $cancelAmounts = $this->calculateCancelMoneyAmountsClient();
                $text = Yii::t('texts', 'STATUS_REFUNDED_AS_VOUCHER_ON_NEXT_INVOICES') . '&#32;&#40;' . $cancelAmounts['voucher']->text . '&#41;';
                $total = '£0.00';

                $line = array(
                    'date' => '',
                    'service' => $this->getStatusLabel(),
                    'provider' => '',
                    'quantity' => '',
                    'unitPrice' => $text,
                    'total' => $total,
                );

                $rawData[] = $line;
            } elseif ($user == Constants::USER_CARER) {


                $cancelAmounts = $this->calculateCancelMoneyAmountsCarer();
                $text = Yii::t('texts', 'STATUS_PAID_COMPENSATION');
                $total = $cancelAmounts->text;

                $line = array(
                    'date' => '',
                    'service' => $this->getStatusLabel(),
                    'provider' => '',
                    'quantity' => '',
                    'unitPrice' => '',
                    'total' => '-' . $this->getOriginalTotalPrice($user)->text
                );

                $rawData[] = $line;

                $line = array(
                    'date' => '',
                    'service' => '',
                    'provider' => '',
                    'quantity' => '',
                    'unitPrice' => $text,
                    'total' => $total,
                );

                $rawData[] = $line;
            }
        }

        if ($this->isCancelledByAdmin()) {

            $line = array(
                'date' => '',
                'service' => $this->getStatusLabel(),
                'provider' => '',
                'quantity' => '',
                'unitPrice' => Yii::t('texts', 'STATUS_REFUNDED_TO_CLIENT_ON_CARD'),
                'total' => '-' . $this->getOriginalTotalPrice($user)->text,
            );

            $rawData[] = $line;
        }

        return $rawData;
    }

    public function getComplaintStatus($user) {

//Open complaint: please respond
//Open complaint: please wait
//Solved complaint
// * @property integer $solved
// * @property string $created

        $complaints = $this->complaintsClient;

        if (count($complaints) == 0) {
            return '';
        } else {

            $compaintCarerSolved = true;

//only one
            foreach ($this->complaintsCarer as $complaintCarer) {

                if ($complaintCarer->solved == false) {

                    $compaintCarerSolved = false;
                } else {

                    $compaintCarerSolved = true;
                }
            }

            $compaintClientSolved = true;

//only one
            foreach ($this->complaintsClient as $complaintClient) {

                if ($complaintClient->solved == false) {

                    $compaintClientSolved = false;
                } else {

                    $compaintClientSolved = true;
                }
            }

            if ($compaintClientSolved && $compaintCarerSolved) {

                return Yii::t('texts', 'STATUS_SOLVED_COMPLAINT');
            } else {

                $carerStatus = 'nothing';
                $clientStatus = 'nothing';

                if ($user == Constants::USER_CLIENT) {

                    if ($compaintClientSolved == false) {

                        if (isset($complaintClient)) {

                            if ($complaintClient->getLastPostAuthor() == Constants::USER_ADMIN && ($complaintClient->getLastPostVisibleBy() == Constants::USER_CLIENT || $complaintClient->getLastPostVisibleBy() == Constants::USER_ALL)) {
                                $clientStatus = 'respond';
                            } else {
                                $clientStatus = 'wait';
                            }
                        } else {
                            $clientStatus = 'wait';
                        }
                    } else {
                        $clientStatus = 'wait';
                    }

                    if ($compaintCarerSolved == false) {
                        if (isset($complaintCarer)) {

                            if ($complaintCarer->getLastPostAuthor() == Constants::USER_ADMIN && ($complaintCarer->getLastPostVisibleBy() == Constants::USER_CLIENT || $complaintCarer->getLastPostVisibleBy() == Constants::USER_ALL)) {
                                $carerStatus = 'respond';
                            } else {
                                $carerStatus = 'wait';
                            }
                        } else {
                            $carerStatus = 'wait';
                        }
                    } else {
                        $carerStatus = 'wait';
                    }
                } elseif ($user == Constants::USER_CARER) {

                    if ($compaintCarerSolved == false) {

                        if (isset($complaintCarer)) {

                            if ($complaintCarer->getLastPostAuthor() == Constants::USER_ADMIN && ($complaintCarer->getLastPostVisibleBy() == Constants::USER_CARER || $complaintCarer->getLastPostVisibleBy() == Constants::USER_ALL)) {
                                $carerStatus = 'respond';
                            } else {
                                $carerStatus = 'wait';
                            }
                        } else {
                            $carerStatus = 'wait';
                        }
                    } else {
                        $carerStatus = 'wait';
                    }

                    if ($compaintClientSolved == false) {

                        if (isset($complaintClient)) {

                            if ($complaintClient->getLastPostAuthor() == Constants::USER_ADMIN && ($complaintClient->getLastPostVisibleBy() == Constants::USER_CARER || $complaintClient->getLastPostVisibleBy() == Constants::USER_ALL)) {
                                $clientStatus = 'respond';
                            } else {
                                $clientStatus = 'wait';
                            }
                        } else {
                            $clientStatus = 'wait';
                        }
                    } else {
                        $clientStatus = 'wait';
                    }
                }

                if ($clientStatus == 'respond' || $carerStatus == 'respond') {
                    return '<span class="rc-statusandlabel-red">' . Yii::t('texts', 'STATUS_OPENED_COMPLAINT_PLEASE_RESPOND') . '</span>';
                } elseif ($clientStatus == 'wait' && $carerStatus == 'wait') {
                    return '<span class="rc-statusandlabel-red">' . Yii::t('texts', 'STATUS_OPENED_COMPLAINT_WAIT_FOR_MESSAGE') . '</span>';
                } else {
                    assert(false);
                }
            }
        }
    }

    public function displayServiceUsersAdmin() {

        $serviceUsers = $this->serviceUsers;

        $text = '';
        foreach ($serviceUsers as $serviceUser) {

            $text = $text . $serviceUser->displayAdmin() . '<br>';
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

    public function isShortListed($carerId) {

        $shortListedCarersIds = $this->booking->getShortListedCarersIds();

        if (in_array($carerId, $shortListedCarersIds)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getMissionsForLastMonths($monthNumber) {

        $sql = "SELECT * FROM tbl_mission           
                WHERE created BETWEEN DATE_SUB(NOW(), INTERVAL $monthNumber MONTH ) AND NOW()       
                ORDER BY id DESC";

        return self::model()->findAllBySql($sql);
    }

}
