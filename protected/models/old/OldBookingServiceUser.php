<?php

/**
 * This is the model class for table "tbl_booking_service_user".
 *
 * The followings are the available columns in table 'tbl_booking_service_user':
 * @property integer $id
 * @property integer $id_client
 * @property string $first_name
 * @property string $last_name
 * @property string $date_birth
 * @property integer $gender
 *
 * The followings are the available model relations:
 * @property LiveInRequest[] $tblLiveInRequests
 * @property ServiceUserConditionRequest[] $serviceUserConditionRequests
 */
class BookingServiceUser extends ServiceUser {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingServiceUser the static model class
     */
    public static function model($className=__CLASS__) {
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
            array('id_client, first_name, last_name, date_birth, gender', 'required'),
            array('id_client, gender', 'numerical', 'integerOnly' => true),
            array('first_name, last_name', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, first_name, last_name, date_birth, gender', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
   public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
            //'bookingServiceUsersConditions' => array(self::HAS_MANY, 'BookingServiceUsersConditions', 'id_service_user'),
            'bookings' => array(self::MANY_MANY, 'Booking', 'tbl_bookings_service_users(id_service_user, id_booking)'),
            //'bookingServiceUsers' => array(self::MANY_MANY, 'BookingServiceUser', 'tbl_bookings_service_users(id_booking, id_service_user)'),
            'conditions' => array(self::MANY_MANY, 'Condition', 'tbl_booking_service_users_conditions(id_service_user, id_condition)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'date_birth' => 'Date Birth',
            'gender' => 'Gender',
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
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('date_birth', $this->date_birth, true);
        $criteria->compare('gender', $this->gender);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function loadModel($id) {
        $model = self::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    public static function loadModels($ids) {
        $models = self::model()->findAllByPk($ids);
        if ($models === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $models;
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