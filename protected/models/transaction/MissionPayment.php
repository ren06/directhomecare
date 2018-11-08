<?php

/**
 * This is the model class for table "tbl_mission_payment".
 *
 * The followings are the available columns in table 'tbl_mission_payment':
 * @property integer $id
 * @property integer $id_booking
 * @property integer $id_credit_card
 * @property string $created
 * @property string $modified
 * @property string $start_date_time
 * @property string $end_date_time
 * @property string $transaction_id
 * @property string $transaction_date
 * 
 * The followings are the available model relations:
 * @property ClientTransaction[] $clientTransactions
 * @property Mission[] $missions
 * @property CreditCard $idCreditCard
 */
class MissionPayment extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MissionPayment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_mission_payment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_booking, id_credit_card, start_date_time, end_date_time', 'required'),
            array('id_booking, id_credit_card', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_booking, id_credit_card, created, modified, start_date_time, end_date_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'clientTransactions' => array(self::HAS_MANY, 'ClientTransaction', 'id_mission_payment'),
            'missions' => array(self::HAS_MANY, 'Mission', 'id_mission_payment'),
            'creditCard' => array(self::BELONGS_TO, 'CreditCard', 'id_credit_card'),
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
            'id_credit_card' => 'Id Credit Card',
            'created' => 'Created',
            'modified' => 'Modified',
            'start_date_time' => 'Start Date Time',
            'end_date_time' => 'End Date Time',
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
        $criteria->compare('id_credit_card', $this->id_credit_card);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('start_date_time', $this->start_date_time, true);
        $criteria->compare('end_date_time', $this->end_date_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

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

    public function getDateRangeText() {

        $text = Calendar::convert_DBDateTime_DisplayDateText($this->start_date_time);
        $text = $text . ' - ' . Calendar::convert_DBDateTime_DisplayDateText($this->end_date_time);
        return $text;
    }

    public function display() {

        $dateRange = $this->getDateRangeText();
        $priceText = $this->getTotalPrice(Constants::USER_CLIENT)->text;
        $description = $this->booking->displayServiceUsersText();
        $numberVisists = count($this->missions);
        if ($numberVisists > 1) {
            $text = "$numberVisists Visits";
        } else {
            $text = "$numberVisists Visit";
        }
        $result = "Booking ($text)" . '<br>' . $dateRange . '<br>' . $priceText . '<br>' . $description;
        return $result;
    }

    /**
     * Return the transaction for the payment of this MissionPayment
     * 
     * @return type ClientTransaction
     */
    public function getPaymentTransaction() {

        return ClientTransaction::getTransaction($this->booking->id_client, $this->id, ClientTransaction::PAYMENT);
    }

    /**
     * Price for all missions under this payment and with deductions of aborted slots
     * 
     * @param type $user 1 or 2 
     * @return type Price
     */
    public function getTotalPrice($user) {

        $totalPrice = new Price(0, 'GBP');

        foreach ($this->missions as $mission) {

            $price = $mission->getTotalPrice($user);
            $totalPrice = $totalPrice->add($price);
        }

        return $totalPrice;
    }

    /**
     * Return duration for all mission in the payment in seconds
     * 
     * @return type int
     */
    public function getDuration() {

        $totalDuration = 0;

        foreach ($this->missions as $mission) {

            $duration = Calendar::duration_Seconds($mission); //$mission->calculateDurationOriginal();
            $totalDuration .= $duration;
        }

        return $totalDuration;
    }

    public function getTotalPriceWithCancel($user) {

        $totalPrice = new Price(0, 'GBP');

        foreach ($this->missions as $mission) {

            if ($mission->isCancelledByClient()) {

                $cancelledPrice = $mission->getTotalPrice($user);
                //$totalPrice = $totalPrice->add($price);
            } elseif ($mission->isCancelledByAdmin()) {

                if ($user == Constants::USER_CLIENT) {

                    $missionPrice = $mission->getTotalPrice($user);
                                        
                    $totalPrice = $totalPrice->substract($missionPrice);
                } else {
                    //not possible
                }
            } else {
                $price = $mission->getTotalPrice($user);
                $totalPrice = $totalPrice->add($price);
            }
        }

        return $totalPrice;
    }

    public function getLocationText() {

        $missions = $this->missions;

        $mission = $missions[0];

        return $mission->getServiceLocation()->display();
    }

    public function getServiceUsersText() {

        $missions = $this->missions;

        $mission = $missions[0];

        return $mission->displayServiceUsersNameHTML();
    }

    /**
     *      
     * 
     * @return string unique transaction number for payment
     */
    public static function getUniqueTransactioNumber() {

        $value = 'DH' . strtoupper(Random::getRandomLetters(4)) . Random::getRandomNumber(6);

        //check if already exists
        $sql = "SELECT transaction_id FROM tbl_mission_payment WHERE transaction_id = '$value' ";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if ($result == false) {
            return $value;
        } else {
            return self::getUniqueTransactioNumber();
        }
    }

}
