<?php

/**
 * This is the model class for table "tbl_booking_service_user".
 *
 * The followings are the available columns in table 'tbl_booking_service_user':
 * @property integer $id_booking
 * @property integer $id_service_user
 */
class BookingServiceUser extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingServiceUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_booking_service_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_booking, id_service_user', 'required'),
            array('id_booking, id_service_user', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_booking, id_service_user', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id_booking' => 'Id Booking',
            'id_service_user' => 'Id Service User',
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

        $criteria->compare('id_booking', $this->id_booking);
        $criteria->compare('id_service_user', $this->id_service_user);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function getServiceUsersIds($serviceId) {

        $records = self::model()->findAll('id_booking=:id_booking', array(':id_booking' => $serviceId));
        $ids = array();

        foreach ($records as $record) {

            $ids[] = ($record->id_service_user);
        }

        return $ids;
    }



}