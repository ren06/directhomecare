<?php

/**
 * This is the model class for table "tbl_mission_slot_aborted".
 *
 * The followings are the available columns in table 'tbl_mission_slot_aborted':
 * @property integer $id
 * @property integer $id_mission
 * @property string $start_date_time
 * @property string $end_date_time
 * @property integer $reported_by
 * @property integer $created_by 
 * @property integer $aborted_by 
 * @property integer $type
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Mission $idMission
 */
class MissionSlotAborted extends CActiveRecord {

    const REPORTED_BY_CLIENT = Constants::USER_CLIENT;
    const REPORTED_BY_CARER = Constants::USER_CARER;
    const ABORTED_BY_CLIENT = Constants::USER_CLIENT;
    const ABORTED_BY_CARER = Constants::USER_CARER;
    const TYPE_CARER_DID_NOT_COME = 1;
    const TYPE_CLIENT_DOES_NOT_WANT = 2;
    const CREATED_BY_CLIENT = Constants::USER_CLIENT;
    const CREATED_BY_CARER = Constants::USER_CARER;
    const CREATED_BY_ADMIN = Constants::USER_ADMIN;

    public static function getTypeCarerOptions() {
        return self::$typeCarerLabels;
    }

    public static function getTypeClientOptions() {
        return self::$typeClientLabels;
    }

    public function getTypeLabel() {

        $array1 = self::$typeCarerLabels;
        $array2 = self::$typeClientLabels;
        $array = $array1 + $array2;
        //$array = array_merge($array1, $array2);
        return $array[$this->type];
    }

    private static $typeCarerLabels = array(
        self::TYPE_CARER_DID_NOT_COME => 'Carer did not come/Late',//RTRT
    );
    private static $typeClientLabels = array(
        self::TYPE_CLIENT_DOES_NOT_WANT => 'Client did not opened the door/does not want the carer to visit',//RTRT
    );
    private static $reportedByLabels = array(
        self::REPORTED_BY_CLIENT => 'Client',
        self::REPORTED_BY_CARER => 'Carer',
    );
    private static $abortedByLabels = array(
        self::ABORTED_BY_CLIENT => 'Client',
        self::ABORTED_BY_CARER => 'Carer',
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MissionSlotAborted the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_mission_slot_aborted';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_mission, start_date_time, end_date_time, reported_by, created_by, aborted_by, type', 'required'),
            array('id_mission, reported_by, type', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_mission, start_date_time, end_date_time, reported_by, created_by, aborted_by, type, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'mission' => array(self::BELONGS_TO, 'Mission', 'id_mission'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_mission' => 'Id Mission',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'reported_by' => 'Reported By',
            'type' => 'Type',
            'created' => 'Created',
            'created_by' => 'Created By',
            'modified' => 'Modified',
        );
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

    public function validate($attributes = null, $clearErrors = true) {

        if (parent::validate($attributes, $clearErrors)) {

            if (Calendar::dateIsBefore($this->start_date_time, $this->end_date_time) == false) {
                $this->addError($this->start_date_time, 'Start date/time after End date/time');
            } else {

                //check mission within boudaries
                $mission = $this->mission;

                if ($this->getDurationInSeconds() == 0) {

                    $this->addError($this->start_date_time, 'The time slot has a duration of 0 seconds');
                } elseif (Calendar::dateIsBefore($mission->start_date_time, $this->start_date_time, true) == false || Calendar::dateIsBefore($this->end_date_time, $mission->end_date_time, true) == false) {

                    $this->addError($this->start_date_time, 'Start date/time and End date/time must be within mission');
                } else {

                    //check existing slots

                    $slots = $mission->missionSlotsAborted;
                    $overlap = false;
                    foreach ($slots as $slot) {

                        //(StartA <= EndB) and (StartB <= EndA) slot = A missionSlotsAborted = B
                        if (Calendar::dateIsBefore($slot->start_date_time, $this->end_date_time) == true && Calendar::dateIsBefore($this->start_date_time, $slot->end_date_time) == true) {
                            $overlap = true;
                            break;
                        }
                    }

                    if ($overlap) {
                        $this->addError($this->start_date_time, 'An existing slot is overlapping with this one');
                    } else {

                        return true;
                    }
                }
            }
        }

        return false;
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
        $criteria->compare('id_mission', $this->id_mission);
        $criteria->compare('start_date_time', $this->start_date_time, true);
        $criteria->compare('end_date_time', $this->end_date, true);
        $criteria->compare('reported_by', $this->reported_by);
        $criteria->compare('type', $this->type);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('created_by', $this->created_by, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getMissionSlotsAborted($missionId) {

        $criteria = new CDbCriteria();
        $criteria->condition = "id_mission=:missionId";
        $criteria->order = "start_date_time ASC";
        $criteria->params = array(':missionId' => $missionId);

        return self::model()->findAll($criteria);
    }

    public static function getMissionSlotsAbortedBy($missionId, $aborted_by) {

        $criteria = new CDbCriteria();
        $criteria->condition = "id_mission=:missionId AND aborted_by=:abortedBy";
        $criteria->order = "start_date_time ASC";
        $criteria->params = array(':missionId' => $missionId, ':abortedBy' => $aborted_by);

        return self::model()->findAll($criteria);
    }

    public static function getTypeOptions() {
        return array_merge(self::$typeCarerLabels, self::$typeClientLabels);
    }

    public function getReportedByLabel() {
        return self::$reportedByLabels[$this->type];
    }

    public static function getReportedByOptions() {
        return self::$reportedByLabels;
    }

    public function getAbortedByLabel() {
        return self::$abortedByLabels[$this->aborted_by];
    }

    public static function getAbortedByOptions() {
        return self::$abortedByLabels;
    }

    public function displayAdmin() {

        return '<b>Aborted by: </b>' . $this->getAbortedByLabel() . ' <b>From: </b>' . Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time) . ' <b>to </b>' .
                Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time) . ' <b>Type: </b>' . $this->getTypeLabel() .
                ' <b>Reported by: </b>' . $this->getReportedByLabel();
    }

    public function display($user, $showPrice = false) {

        $result = Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time, false) . '&#160;' . Yii::t('texts', 'LABEL_AND') . '&#160;' . Calendar::convert_DBDateTime_DisplayDateTimeText($this->end_date_time, false);

        if ($showPrice) {

            $result = $result . ' <b>Price: </b> ' . $this->getPriceText($user);
        }

        return $result;
    }
    
    public function displayInvoice1() {
        
        $result = Yii::t('texts', 'STATUS_INTERRUPTED_BY') . '&#32;';
        $result .= $this->getAbortedByLabel() . '&#32;';
        $result .= Yii::t('texts', 'STATUS_BETWEEN') . '&#58;';

        return $result;
    }
    
    public function displayInvoice2() {

        $result = Calendar::convert_DBDateTime_DisplayDateTimeText($this->start_date_time, false, '&#32;', false) . '<br />' . Yii::t('texts', 'LABEL_AND') . '<br />' . Calendar::convert_DBDateTime_DisplayDateTimeText($this->end_date_time, false, '&#32;', false);

        return $result;
    }

    public function getPriceText($user, $textAbortedByClient = true) {

        //si client: 
        //invoice du client: display "no deduction" (slot aborted price =0)
        //invoice carer: no deduction
        //si carer:
        //invoice carer: display negtive price base sur le prix carer, utilise prix date de creation de la mission
        //invoice client: display negative, base sur prix du service
        //who aborted
        if ($this->aborted_by == self::ABORTED_BY_CLIENT) {
            
            if($textAbortedByClient){
                return 'No deduction';
            }
            else{
                return '£0.00';
            }
           
        } elseif ($this->aborted_by == self::ABORTED_BY_CARER) {

            return '-' . $this->getPrice($user)->text;
        }
    }

    public function isAbortedByClient() {
        return ($this->aborted_by == self::ABORTED_BY_CLIENT);
    }

    public function isAbortedByCarer() {
        return ($this->aborted_by == self::ABORTED_BY_CARER);
    }

    public function getPrice($user) {

        return Prices::getMissionAbortedSlotPrice($this, $user);
    }

    public function getDurationText() {

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($this->getDurationInSeconds(), true);
    }

    public function getDuration() {

        return Calendar::convert_Seconds_DayHoursMinutesSeconds($this->getDurationInSeconds());
    }

    public function getDurationInSeconds() {

        return Calendar::duration_Seconds($this);
    }

    public function getStart_Time() {

        return Calendar::convert_DBDateTime_Time($this->start_date_time);
    }

    public function getEnd_Time() {

        return Calendar::convert_DBDateTime_Time($this->end_date_time);
    }

    public function getTextInvoice(){
        
        if ($this->aborted_by == self::ABORTED_BY_CLIENT) {

            return Yii::t('texts', 'STATUS_NO_REFUND_TO_CLIENT');
        } elseif ($this->aborted_by == self::ABORTED_BY_CARER) {

            return Yii::t('texts', 'STATUS_REFUNDED_TO_CLIENT_ON_CARD');
        }        
    }
    
}