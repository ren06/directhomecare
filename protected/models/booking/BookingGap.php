<?php

/**
 * This is the model class for table "tbl_booking_gap".
 *
 * The followings are the available columns in table 'tbl_booking_gap':
 * @property integer $id
 * @property integer $id_booking
 * @property string $start_date_time
 * @property string $end_date_time
 *
 * The followings are the available model relations:
 * @property Booking $idBooking
 */
class BookingGap extends ActiveRecord {

    const TYPE_HOLIDAY = 1;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingGap the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_booking_gap';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(          
            array('start_date, end_date, start_time, end_time', 'required', 'on' => 'insert'),
            array('id_booking', 'numerical', 'integerOnly' => true),
            array('start_date', 'checkValidDates'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_booking, start_date_time, end_date_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'booking' => array(self::BELONGS_TO, 'Booking', 'id_booking'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_booking' => 'Id Booking',
            'start_date_time' => 'Start Date Time',
            'end_date_time' => 'End Date Time',
            'start_date' => Yii::t('texts', 'LABEL_START_DATE'),
            'start_time' => Yii::t('texts', 'LABEL_START_TIME'),
            'end_time' => Yii::t('texts', 'LABEL_END_TIME'),
            'end_date' => Yii::t('texts', 'LABEL_END_DATE'),
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
        $criteria->compare('start_date_time', $this->start_date_time, true);
        $criteria->compare('end_date_time', $this->end_date_time, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function checkValidDates($attribute, $params) {

        if ($this->start_date_time != null && $this->end_date_time != null) {

            $isBefore = Calendar::dateIsBefore($this->start_date_time, $this->end_date_time, false);

            if (!$isBefore) {

                //TODO maybe do some logic to default the dates (e.g. to the first available day)
                $this->addError($attribute, 'The start date must be before the end date.');
            } else {

                //MINIMUM START DATE
                //either last mission paid end date or this + minimum mission duration
                //get last created mission (use enddatetime)

                $lastMission = Booking::getLastMission($this->id_booking);

                $lastMissionEndDateTime = $lastMission->end_date_time;

                $errorMessage = null;

                $hoursBetween = Calendar::hoursBetween_DBDateTime($lastMissionEndDateTime, $this->start_date_time);

                if ($hoursBetween < BusinessRules::getNewBookingLiveInMinimumDurationInHours()
                        && $lastMissionEndDateTime != $this->start_date_time) {

                    $valid = false;
                    $errorMessage = 'The last mission paid has a end date on ' . $lastMission->end_date_time .
                            '. A mission needs to be at least for ' . BusinessRules::getNewBookingLiveInMinimumDurationInHours() . ' hours.';
                }
            }
        }
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

}
