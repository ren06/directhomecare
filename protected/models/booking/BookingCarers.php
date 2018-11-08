<?php

/**
 * This is the model class for table "tbl_booking_carers".
 *
 * The followings are the available columns in table 'tbl_booking_carers':
 * @property integer $id
 * @property integer $id_booking
 * @property integer $id_carer
 * @property integer $relation
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 * @property Booking $idBooking
 */
class BookingCarers extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingCarer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_booking_carers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_booking, id_carer, relation', 'required'),
            array('id_booking, id_carer, relation', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_booking, id_carer, relation', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'carer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
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
            'id_carer' => 'Id Carer',
            'relation' => 'Relation',
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
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('relation', $this->relation);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}