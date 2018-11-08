<?php

/**
 * This is the model class for table "tbl_client_transaction".
 *
 * The followings are the available columns in table 'tbl_client_transaction':
 * @property integer $id
 * @property integer $id_client
 * @property integer $id_mission_payment
 * @property integer $id_mission
 * @property string $created
 * @property integer $type
 * @property string $currency
 * @property double $paid_card
 * @property double $paid_voucher
 * @property double $refund_card
 * @property double $refund_voucher
 * @property double $voucher_balance
 *
 * The followings are the available model relations:
 * @property Client $idClient
 * @property RequestOld $idRequest
 */
class ClientTransaction extends ActiveRecord {

    const PAYMENT = 0;
    const CANCELLATION = 1;
    const REFUND = 2;
    const ADJUSTMENT = 3;
    const FREE_VOUCHER = 4;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClientTransaction the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_client_transaction';
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_client, type, currency, voucher_balance', 'required'),
            array('id_client, id_mission_payment, type', 'numerical', 'integerOnly' => true),
            array('paid_card, paid_voucher, refund_card, refund_voucher, voucher_balance', 'numerical'),
            array('currency', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, id_mission_payment, created, type, currency, paid_card, paid_voucher, refund_card, refund_voucher, voucher_balance', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
            'missionPayment' => array(self::BELONGS_TO, 'MissionPayment', 'id_mission_payment'),
        );
    }

    public function behaviors() {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'TimestampBehavior',
                'createAttribute' => 'created',
                'timestampExpression' => Calendar::today(Calendar::FORMAT_DBDATETIME),
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'id_mission_payment' => 'Id Mission Payment',
            'created' => 'Created',
            'type' => 'Type',
            'currency' => 'Currency',
            'paid_card' => 'Paid Cash',
            'paid_voucher' => 'Paid Credit',
            'refund_card' => 'Reimbursed',
            'refund_voucher' => 'Credit',
            'voucher_balance' => 'Credit Balance',
        );
    }

    //restrict access to owner
//    public function defaultScope() {
//        return array(
//            'condition' => 'id_client = ' . Yii::app()->user->id,
//        );
//    }

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
        $criteria->compare('id_mission_payment', $this->id_mission_payment);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('currency', $this->currency, true);
        $criteria->compare('paid_card', $this->paid_card);
        $criteria->compare('paid_voucher', $this->paid_voucher);
        $criteria->compare('refund_card', $this->refund_card);
        $criteria->compare('refund_voucher', $this->refund_voucher);
        $criteria->compare('voucher_balance', $this->voucher_balance);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Get all transactions for a given client (data provider)
     */
    public static function getClientTransactions($clientId) {

        $criteria = new CDbCriteria;

        $criteria->compare('id_client', $clientId);

        return new CActiveDataProvider(self::model(), array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
        ));
    }

    public static function getTransactionsClientCount($clientId) {

        $sql = "SELECT COUNT(tbl_client_transaction.id) FROM tbl_client_transaction " .
                " WHERE tbl_client_transaction.id_client = " . $clientId;

        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num;
    }

    /**
     * Get transaction for a given client missionPayment and transaction type (PAYMENT or CANCELLATION)
     */
    public static function getTransaction($clientId, $id_mission_payment, $type) {

        $criteria = new CDbCriteria;

        $criteria->compare('id_client', $clientId);
        $criteria->compare('id_mission_payment', $id_mission_payment);
        $criteria->compare('type', $type);

        return self::model()->find($criteria);
    }

    /**
     * Get transaction for a given client missionPayment and transaction for all payment types
     */
    public static function getTransactions($clientId, $id_mission_payment) {

        $criteria = new CDbCriteria;

        $criteria->compare('id_client', $clientId);
        $criteria->compare('id_mission_payment', $id_mission_payment);

        return self::model()->findAll($criteria);
    }

    /**
     * Get transactions for a missions regardelss of their types
     */
    public static function getMissionTransactions($clientId, $id_mission_payment) {

        $criteria = new CDbCriteria;

        $criteria->compare('id_client', $clientId);
        $criteria->compare('id_mission_payment', $id_mission_payment);


        return self::model()->find($criteria);
    }

    public static function createPayment($clientId, $id_mission_payment, $paidCash, $paidCredit, $balance, $type, $totalPrice, $bankReference, $cardLastDigits, $bookingId = null, $missionId = null) {

        $transaction = new ClientTransaction();
        $transaction->id_client = $clientId;
        $transaction->id_mission_payment = $id_mission_payment;
        $transaction->id_mission = $missionId;
        $transaction->id_booking = $bookingId;
        $transaction->type = $type;
        $transaction->total = $totalPrice->amount;
        $transaction->paid_card = ($paidCash->amount == 0) ? null : $paidCash->amount;
        $transaction->paid_voucher = ($paidCredit->amount == 0) ? null : $paidCredit->amount;
        $transaction->voucher_balance = $balance->amount;
        $transaction->currency = $paidCash->currency;
        $transaction->bank_reference = $bankReference;
        $transaction->card_last_digits = $cardLastDigits;

        $transaction->validate();
        $errors = $transaction->getErrors();
        $transaction->save();
    }

    public static function createCancellation($clientId, $id_mission_payment, $missionId, $refund_voucher) {

        $transaction = new ClientTransaction();
        $transaction->id_client = $clientId;
        $transaction->id_mission_payment = $id_mission_payment;
        $transaction->id_mission = $missionId;
        $transaction->type = self::CANCELLATION;
        $transaction->refund_voucher = $refund_voucher->amount;
        $transaction->currency = $refund_voucher->currency;

        $refund_voucherBalance = self::getCreditBalance($clientId);
        $newCreditBalance = $refund_voucherBalance->add($refund_voucher);
        $transaction->voucher_balance = $newCreditBalance->amount;

        $transaction->save();
    }

    /**
     * Create a client transaction of type reimbursment
     * Notify client via email
     * Reiburse the client with Borgun
     * 
     * @param type $clientId Client Id
     * @param type $id_mission_payment MissionPayment Id
     * @param type $missionId mission that was cancelled
     * @param type $refundAmount Price can be null
     * @param type $refundVoucher Price can be null
     * @param type $sendMailClient Boolean
     */
    public static function createReimbursment($clientId, $id_mission_payment, $missionId, $refundAmount, $refundVoucher, $sendMailClient) {

        assert(isset($refundAmount) || isset($refundVoucher));

        //Create transaction record
        $transaction = new ClientTransaction();
        $transaction->id_client = $clientId;
        $transaction->id_mission_payment = $id_mission_payment;
        $transaction->id_mission = $missionId;

        $transaction->type = self::REFUND;

        if (isset($refundAmount)) {
            $transaction->refund_card = $refundAmount->amount;
        }

        //refund_voucher (voucher)
        $refund_voucherBalance = self::getCreditBalance($clientId);

        $transaction->currency = $refund_voucherBalance->currency;

        if (isset($refundVoucher)) {
            $refund_voucherBalance = $refund_voucherBalance->add($refundVoucher);
            $transaction->refund_voucher = $refundVoucher->amount;
        }
        $transaction->voucher_balance = $refund_voucherBalance->amount;

        $transaction->save();

        //Notify client and admin
        if ($sendMailClient) {
            $client = Client::loadModel($clientId);
            $mission = Mission::loadModel($missionId);
            Emails::sendToClient_MissionCancelledAndReimbursed($client, $mission);          
        }
    }

    public static function getCreditBalance($clientId) {

        //get last record
        $last = self::getLast($clientId);

        if (isset($last)) {
            $amount = $last->voucher_balance;
            return new Price($last->voucher_balance, $last->currency);
        } else {
            return new Price(0, 'GBP');
        }
    }

    public static function getLast($clientId) {

        $sql = "SELECT id FROM tbl_client_transaction 
            WHERE created = (SELECT MAX(created) FROM tbl_client_transaction) 
            AND id_client=$clientId";

        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row == false) {
            return null;
        } else {
            return self::loadModel($row['id']);
        }
    }

    public function checkBalance($attributes, $params) {

        if ($this->type == self::CREDIT) {
            if (!$this->balance)
                $this->addError('balance', 'Balance empty');
        }
    }

    public function displayTransactionText() {

        switch ($this->type) {

            case self::PAYMENT:

                if ($this->id_booking == null) {

                    return $this->missionPayment->display();
                } else {
                    $booking = Booking::loadModel($this->id_booking);

                    //$dateRange = $this->getDateRangeText();
                    $priceText = $booking->getTotalPrice(Constants::USER_CLIENT)->text;
                    
                    $dateRange = '';
                    $description = $booking->displayServiceUsersText();
                    $numberVisists = count($booking->missions);
                    if ($numberVisists > 1) {
                        $text = "$numberVisists Visits";
                    } else {
                        $text = "$numberVisists Visit";
                    }
                    $result = "Booking ($text)" . '<br>' . $priceText . '<br>' . $description;
                    return $result;
                }

            case self::CANCELLATION:

                $missionId = $this->id_mission;
                $text = 'Visit Cancellation';
                if (isset($missionId)) {
                    $mission = Mission::loadModel($this->id_mission);
                    $text .= '<br>' . $mission->displayMissionShort(false);
                    $price = $mission->getTotalPrice(Constants::USER_CLIENT);
                    $text .= '<br>' . $price->text;
                } else {

                    $text .= ' - £' . $this->refund_voucher;
                }

                return $text;

            case self::REFUND:

                $missionId = $this->id_mission;
                $text = 'Visit Refund';
                if (isset($missionId)) {
                    $mission = Mission::loadModel($this->id_mission);
                    $text .= '<br>' . $mission->displayMissionShort(false);
                    $price = $mission->getTotalPrice(Constants::USER_CLIENT);
                    $text .= '<br>' . $price->text;
                } else {

                    $text .= ' - £' . ($this->refund_voucher + $this->refund_card);
                }

                return $text;

            case self::ADJUSTMENT:
                $missionId = $this->id_mission;
                $text = 'Visit Adjustment';
                if (isset($missionId)) {
                    $mission = Mission::loadModel($this->id_mission);
                    $text .= '<br>' . $mission->displayMissionShort(false);
                    //$price = $mission->getTotalPrice(Constants::USER_CLIENT);
                    //$text .= '<br>' . $price->text;
                }

                return $text;

            case self::FREE_VOUCHER:
                //$missionId = $this->id_mission;
                $text = 'Free voucher';
                $text .= ' - £' . $this->refund_voucher;
                return $text;
        }
    }

    public function getDate() {

        return Calendar::convert_DBDateTime_DisplayDateText($this->created);
    }

    public function getPaidCash() {

        return new Price($this->paid_card, $this->currency);
    }

    public function getPaidCredit() {

        return new Price($this->paid_voucher, $this->currency);
    }

    public function getReimbursed() {

        return new Price($this->refund_card, $this->currency);
    }

    public function getCredit() {

        return new Price($this->refund_voucher, $this->currency);
    }

    public function getCredit_Balance() {

        return new Price($this->voucher_balance, $this->currency);
    }

    public function getTypeLabel() {

        switch ($this->type) {

            case self::PAYMENT:
                return 'Payment'; //RTRT
            case self::CANCELLATION:
                return 'Voucher';
            case self::REFUND:
                return 'Reimbursement';
            case self::ADJUSTMENT:
                return 'Correction';
            case self::FREE_VOUCHER:
                return 'Free voucher';
        }
    }

    public function getValue() {

        switch ($this->type) {

            case self::PAYMENT:
                return $this->getPaidCash();

            case self::CANCELLATION:
                return $this->getCredit();

            case self::REFUND:
                return $this->getReimbursed();
        }
    }

    public function getCardLastDigitsText() {
        return "Card ending in " . $this->card_last_digits;
    }

    public static function createFreeVoucher($clientId, $price) {

        $transaction = new ClientTransaction();
        $transaction->id_client = $clientId;
        $transaction->id_mission_payment = null;
        $transaction->id_mission = null;
        $transaction->type = self::FREE_VOUCHER;
        $transaction->refund_voucher = $price->amount;
        $transaction->currency = $price->currency;

        $refund_voucherBalance = self::getCreditBalance($clientId);
        $newCreditBalance = $refund_voucherBalance->add($price);
        $transaction->voucher_balance = $newCreditBalance->amount;

        $transaction->save();
    }

}
