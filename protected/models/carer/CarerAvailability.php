<?php

/**
 * This is the model class for table "tbl_carer_availability".
 *
 * The followings are the available columns in table 'tbl_carer_availability':
 * @property integer $id
 * @property integer $day_week
 * @property integer $time_slot
 * @property integer $id_carer
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 */
class CarerAvailability extends CActiveRecord {
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    const _06_10 = 1;
    const _10_14 = 2;
    const _14_18 = 3;
    const _18_22 = 4;
    const _22_06 = 5;
    
    
    /**
     * Returns the static model of the specified AR class.
     * @return AvailabilityCarer the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_carer_availability';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('day_week, time_slot, id_carer', 'required'),
            array('day_week, time_slot, id_carer', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, day_week, time_slot, id_carer', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idCarer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'day_week' => 'Day Week',
            'time_slot' => 'Time Slot',
            'id_carer' => 'Id Carer',
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
        $criteria->compare('day_week', $this->day_week);
        $criteria->compare('time_slot', $this->time_slot);
        $criteria->compare('id_carer', $this->id_carer);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public static function getDaysWeeks() {

        return array(
            self::MONDAY => 'Monday',
            self::TUESDAY => 'Tuesday',
            self::WEDNESDAY => 'Wednesday',
            self::THURSDAY => 'Thursday',
            self::FRIDAY => 'Friday',
            self::SATURDAY => 'Saturday',
            self::SUNDAY => 'Sunday',
        );
    }

    public static function getTimeSlots() {

        return array(self::_06_10 => '6am - 10am',
            self::_10_14 => '10am - 2pm',
            self::_14_18 => '2pm - 6pm',
            self::_18_22 => '6pm - 10pm',
            self::_22_06 => '10pm - 6am',
        );
    }

    public function defaultScope() {
        return array('order' => '`day_week` ASC');
    }

}