<?php

/**
 * This is the model class for table "tbl_service".
 *
 * The followings are the available columns in table 'tbl_service':
 * @property integer $id
 * @property integer $id_booking
 * @property integer $type
 * @property integer $status
 * @property integer $discarded
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property ClientTransaction[] $clientTransactions
 * @property Mission[] $missions
 * @property Booking $idBooking
 * @property ServiceAddress[] $serviceAddresses
 * @property BookingServiceUser[] $tblServiceUserRequests
 */
class OldNewService extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OldNewService the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_service';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_booking, type', 'required'),
            array('id_booking, type, status, discarded', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_booking, type, status, discarded, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'clientTransactions' => array(self::HAS_MANY, 'ClientTransaction', 'id_service'),
            'missions' => array(self::HAS_MANY, 'Mission', 'id_service'),
            'booking' => array(self::BELONGS_TO, 'Booking', 'id_booking'),
            'serviceAddresses' => array(self::HAS_MANY, 'ServiceAddress', 'id_service'),
            'serviceUsers' => array(self::MANY_MANY, 'ServiceUserRequest', 'tbl_service_service_user(id_service, id_service_user)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_booking' => 'Id Booking',
            'type' => 'Type',
            'status' => 'Status',
            'discarded' => 'Discarded',
            'created' => 'Created',
            'modified' => 'Modified',
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
        $criteria->compare('type', $this->type);
        $criteria->compare('status', $this->status);
        $criteria->compare('discarded', $this->discarded);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
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

    public static function loadModel($id) {

        $model = self::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    public static function create($booking) {

        $service = new OldNewService();
        $service->id_booking = $booking->id;
        $service->type = $booking->type;

        $service->validate();
        $errors = $service->errors;
        $service->save();

        return $service;
    }



    /*
     * Accepts Booking and LiveInRequestForm
     */

    public function createFirstPayment($model, $clientId) {

        $payment = Prices::calculatePaymentBreakdown($model, Constants::USER_CLIENT, $clientId);

        ClientTransaction::createPayment($clientId, $this->id, $payment['paidCash'], $payment['paidCredit'], $payment['remainingCreditBalance']);
    }

    public function getServiceUsers() {

        return $this->serviceUsers;
    }

    /**
     * Display the conditions of the service user as a HMTL list
     */
    public function displayServiceUsersConditionsHTML($compact = false) {

        $serviceUsers = $this->serviceUsers;

        $result = "<ol>";

        foreach ($serviceUsers as &$serviceUser) {

            $text = $serviceUser->displayAgeGenderConditions($compact);

            $result = $result . '<li>' . $text . '</li>';
        }

        return $result . '</ol> ';
    }

    public function displayServiceTypeUsersConditionsHTML($compact = false) {

        $serviceUsers = $this->serviceUsers;

        $result = "<ol>";

        foreach ($serviceUsers as &$serviceUser) {

            $text = $serviceUser->displayAgeGenderConditions($compact);

            $result = $result . '<li>' . $text . '</li>';
        }

        $result = $result . '</ol> ';

        return $this->getTypeLabel() . '<br>' . $result;
    }

}