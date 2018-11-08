<?php

/**
 * This is the model class for table "tbl_booking_live_in".
 *
 * The followings are the available columns in table 'tbl_booking_live_in':
 * @property integer $id
 * @property integer $id_request
 * @property string $start_date_time
 * @property string $end_date_time
 * @property integer $recurring
 *
 * The followings are the available model relations:
 * @property RequestLiveInOld $idRequest
 * @property RequestException[] $requestExceptions
 */
class BookingLiveIn extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingLiveIn the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_booking_live_in';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('start_date_time', 'required'),
            array('recurring', 'numerical', 'integerOnly' => true),
            array('end_date_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_request, start_date_time, end_date_time, recurring', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idRequest' => array(self::BELONGS_TO, 'RequestLiveIn', 'id_request'),
            'requestExceptions' => array(self::HAS_MANY, 'RequestException', 'id_request'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_request' => 'Id Request',
            'start_date_time' => 'Start Date Time',
            'end_date_time' => 'End Date Time',
            'recurring' => 'Recurring',
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
        $criteria->compare('id_request', $this->id_request);
        $criteria->compare('start_date_time', $this->start_date_time, true);
        $criteria->compare('end_date_time', $this->end_date_time, true);
        $criteria->compare('recurring', $this->recurring);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function create($formModel) {

        $model = new BookingLiveIn();
        $model->recurring = $formModel->recurring;

        $model->start_date_time = $formModel->start_date . ' ' . $formModel->start_time;

        if (!$model->recurring) {

            $model->end_date_time = $formModel->end_date . ' ' . $formModel->end_time;
        }

        $model->save();

        return $model;
    }

    public static function handleFirstPayment($formModel) {

        $transaction = Yii::app()->db->beginTransaction();
        
        $booking = self::create($formModel);
              
        $service = OldNewService::create($booking);
        
        
        
        //create live in request
        //create parent object
        $request = parent::create($clientId, RequestOld::REQUEST_LIVE_IN);

        $this->id_request = $request->id;

        $valid = $this->validate();
        $liveInRequestErrors = $this->getErrors();
        $this->disableBehavior('CTimestampBehavior');
        $this->save();

        //create a copy of service location
        $serviceLocationRequest = ServiceAddress::create($serviceLocationId, $request->id, ServiceAddress::TYPE_SERVICE);


        $this->refresh(); //make sure creation date is picked up

        $created = $this->created;
        //IMPORTANT: LiveInMissions created in afterSave();
        //ASSOCIATE SERVICE USERS WITH LIVE IN REQUEST
        $assignUsersErrors = $this->assignServiceUsers($serviceUserIds);

        //Create billing address

        if ($billingAddress instanceof ServiceAddress) {
            //new address entered by the user
            $billingAddress->id_request = $this->id_request;
            $billingAddress->type = ServiceAddress::TYPE_BILLING;
            $billingAddress->save();

            //add it to master data service location
            $address = new Address();
            $address->attributes = $billingAddress->attributes;
            $address->id = null;
            $address->save();

            $location = new ServiceLocationAddress();
            $location->id_client = $clientId;
            $location->id_address = $address->id;
            $location->save();
        } else {
            //existing service location
            $serviceLocationRequest = ServiceAddress::create($serviceLocationId, $request->id, ServiceAddress::TYPE_BILLING);
        }


        $errors = array_merge($liveInRequestErrors, $assignUsersErrors);

        if (empty($errors)) {

            $this->refresh();

            //create payment
            $this->createPayment();

            $transaction->commit();
        } else {
            $transaction->rollBack();
        }
    }

}