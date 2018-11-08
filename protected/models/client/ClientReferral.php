<?php

/**
 * This is the model class for table "tbl_client_referral".
 *
 * The followings are the available columns in table 'tbl_client_referral':
 * @property integer $id
 * @property integer $id_client
 * @property string $email_referral
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Client $idClient
 */
class ClientReferral extends CActiveRecord {

    const STATUS_INVITED = 0;
    const STATUS_ALREADY_CLIENT = 1;
    const STATUS_USED_REFEREE_CREDITED = 2;
    const STATUS_USED_REFERRER_CREDITED = 3;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClientReferral the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_client_referral';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_client, email_referral, status', 'required'),
            array('id_client, status', 'numerical', 'integerOnly' => true),
            array('email_referral', 'length', 'max' => 80),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, email_referral, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idClient' => array(self::BELONGS_TO, 'Client', 'id_client'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'email_referral' => 'Email Referral',
            'status' => 'Status',
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
        $criteria->compare('email_referral', $this->email_referral, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function alreadyExists($clientId, $emailReferral) {

        $sql = "SELECT id FROM tbl_client_referral WHERE id_client = $clientId AND email_referral = '$emailReferral'";

        $results = Yii::app()->db->createCommand($sql)->queryAll();

        return (count($results) > 0);
    }

    public function getStatusText() {

        $amount = BusinessRules::getReferralAmount();
        
        $amount2 = BusinessRules::getRefereeAmount();

        switch ($this->status) {

            case self::STATUS_INVITED:
                return 'Invited';

            case self::STATUS_ALREADY_CLIENT:
                return 'Already a Customer!';

            case self::STATUS_USED_REFEREE_CREDITED:
                return "Has registered - You will also get £$amount for 2 hours when this person makes a booking.";

            case self::STATUS_USED_REFERRER_CREDITED:
                return "Has made a booking - You've been credited of £$amount2";
        }
    }

    /**
     * Give voucher to a Referee, should happen when they register
     * 
     * @param type $email
     */
    public static function creditReferee($client) {

        //check that the referee exits in the table
        $sql = "SELECT * FROM tbl_client_referral WHERE email_referral = '$client->email_address' AND status = " . self::STATUS_INVITED;

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if (isset($result)) {

            $amount = BusinessRules::getRefereeAmount();
            $price = new Price($amount);
            
            $unitPrice = Prices::getPrice(Constants::USER_CLIENT, 'hourly_price');

            $twoHoursPrice = $unitPrice->multiply(2);
            
            $creditAmount = $twoHoursPrice->substract($price);

            ClientTransaction::createFreeVoucher($client->id, $creditAmount);

            //update status
            $status = self::STATUS_USED_REFEREE_CREDITED;
            $rowId = $result['id'];
            $sql = "UPDATE tbl_client_referral SET status = $status WHERE id = $rowId";

            $result = Yii::app()->db->createCommand($sql)->execute();
        }
    }

    /**
     * Give voucher to a Referrer, should happen when their referree makes a booking
     * 
     * @param type $email
     */
    public static function creditReferrer($referee) { //client object
        //select the client who was the first to refer the new customer ($referee)
        $status = self::STATUS_USED_REFEREE_CREDITED;
        $sql = "SELECT DISTINCT id, id_client FROM tbl_client_referral WHERE email_referral = '$referee->email_address' AND status = $status ORDER BY id ASC";

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if (isset($result)) {

            //$client = Client::loadModel($result['id']);
            $clientId = $result['id_client'];

            $amount = BusinessRules::getReferralAmount();
            $price = new Price($amount);

            ClientTransaction::createFreeVoucher($clientId, $price);

            //update status
            $status = self::STATUS_USED_REFERRER_CREDITED;
            $rowId = $result['id'];
            $sql = "UPDATE tbl_client_referral SET status = $status WHERE id = $rowId";

            $result = Yii::app()->db->createCommand($sql)->execute();
        }
    }

}