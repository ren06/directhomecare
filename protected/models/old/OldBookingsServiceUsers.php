<?php

/**
 * This is the model class for table "tbl_bookings_service_users".
 *
 * The followings are the available columns in table 'tbl_bookings_service_users':
 * @property integer $id_booking
 * @property integer $id_service_user
 */
class BookingsServiceUsers extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingsServiceUsers the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_bookings_service_users';
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

    public static function assignServiceUsersToBooking($serviceUserIds, $bookingId) {

        $errors = array();

        foreach ($serviceUserIds as &$serviceUserId) {

            //load existing service user (master data)
            //create a copy service user request
            $bookingServiceUser = new BookingServiceUser();

            $serviceUser = ServiceUser::loadModel($serviceUserId);

            $bookingServiceUser->attributes = $serviceUser->attributes;
            unset($bookingServiceUser->id);
            $bookingServiceUser->save();

            //associate copy condition
            BookingServiceUsersConditions::createServiceUserConditions($serviceUserId, $bookingServiceUser->id);

            //associate service user request to live in request
            $bookingsServiceUsers = new BookingsServiceUsers();
            $bookingsServiceUsers->id_booking = $bookingId;
            $bookingsServiceUsers->id_service_user = $bookingServiceUser->id;

            $bookingsServiceUsers->validate();
            $error = $bookingsServiceUsers->getErrors();

            array_merge($errors, $error);

            $result = $bookingsServiceUsers->save();
        }

        return $errors;
    }

}