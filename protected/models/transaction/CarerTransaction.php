<?php

/**
 * This is the model class for table "tbl_carer_transaction".
 *
 * The followings are the available columns in table 'tbl_carer_transaction':
 * @property integer $id
 * @property integer $id_carer
 * @property integer $id_mission
 * @property string $created
 * @property integer $type
 * @property string $currency
 * @property double $paid_credit
 * @property double $credit_balance
 * @property double $withdraw
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 * @property Mission $idMission
 */
class CarerTransaction extends ActiveRecord {

    const CREDIT_PAYMENT = 0; //normal payment
    const WITHDRAW = 1; //carer withdraw money
    const CREDIT_CANCEL_CLIENT = 2; //carer was credited as client cancelled the mission in a short delay
    const TRANSFER_FAILED = 3; //the carer did not provide the right account number, so their account is recredited
    const COMPENSATION = 4; //

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CarerTransaction the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_carer_transaction';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_carer, type, currency', 'required'),
            array('id_carer, id_mission, type', 'numerical', 'integerOnly' => true),
            array('paid_credit, credit_balance, withdraw', 'numerical'),
            array('currency', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_carer, id_mission, created, type, currency, paid_credit, credit_balance, withdraw', 'safe', 'on' => 'search'),
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
            'mission' => array(self::BELONGS_TO, 'Mission', 'id_mission'),
            'carerWithdrawals' => array(self::HAS_MANY, 'CarerWithdrawal', 'id_carer_transaction')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_carer' => 'Id Carer',
            'id_mission' => 'Id Mission',
            'created' => 'Created',
            'type' => 'Type',
            'currency' => 'Currency',
            'paid_credit' => 'Paid Credit',
            'credit_balance' => 'Credit Balance',
            'withdraw' => 'Paid Cash',
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

    //restrict access to owner
//    public function defaultScope() {
//        return array(
//            'condition' => 'id_carer = ' . Yii::app()->user->id,
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
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('id_mission', $this->id_mission);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('currency', $this->currency, true);
        $criteria->compare('paid_credit', $this->paid_credit);
        $criteria->compare('credit_balance', $this->credit_balance);
        $criteria->compare('withdraw', $this->withdraw);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function displayWithdrawCarer() {

        $carer = Carer::loadModel($this->id_carer);

        return Yii::t('texts', 'LABEL_TRANSFER_TO_YOUR_BANK') . '<br />' . Yii::t('texts', 'LABEL_SORT_CODE') . '&#58;&#160;' . $carer->sort_code . '&#160;&#160;&#160;' . Yii::t('texts', 'LABEL_ACCOUNT_NUMBER') . '&#58;&#160;' . $carer->account_number;
    }

    public function displayTransferFailed() {

        $carer = Carer::loadModel($this->id_carer);

        return Yii::t('texts', 'LABEL_TRANSFER_REJECTED_BY_YOUR_BANK') . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_VERIFY_ACCOUNT_AND_NAME')) . '<br />' . Yii::t('texts', 'LABEL_SORT_CODE') . '&#58;&#160;' . $carer->sort_code . '&#160;&#160;&#160;' . Yii::t('texts', 'LABEL_ACCOUNT_NUMBER') . '&#58;&#160;' . $carer->account_number;
    }

    /**
     * Used to display transaction text, even withdrawals
     */
    public function displayServiceTypeUsersConditionsHTML() {

        if ($this->type == self::WITHDRAW) { //the two types with no missions attached
            return $this->displayWithdrawCarer();
        } elseif ($this->type == self::TRANSFER_FAILED) {
            return $this->displayTransferFailed();
        } else {
            return $this->mission->displayServiceTypeUsersConditionsHTML();
        }
    }

    public static function getTransactions($carerId) {

        $criteria = new CDbCriteria;

        $criteria->compare('id_carer', $carerId);
        $criteria->order = 'id DESC';

        return new CActiveDataProvider(self::model(), array(
            'criteria' => $criteria,
//                    'sort' => array(
//                        'defaultOrder' => 'created DESC',
//                    ),
        ));
    }

    /**
     * 
     * Return transaction for given carer, mission and type 
     * 
     * @param type $carerId Carer Id
     * @param type $missionId Mission Id
     * @param type $type CarerTransaction::CARER_PAYMENT etc...
     * @return type
     */
    public static function getTransaction($carerId, $missionId, $type) {

        $criteria = new CDbCriteria;

        $criteria->compare('id_carer', $carerId);
        $criteria->compare('id_mission', $missionId);
        $criteria->compare('type', $type);

        return self::model()->find($criteria);
    }

    /**
     * Create a new credit transaction when the carer is paid after successfully completing a mission.
     * 
     * It's called "credit" as opposed to "debit" (not a reimbursment but a positive transaction)
     */
    private static function createCredit($carerId, $missionId, $credit, $type) {

        $transaction = new CarerTransaction();
        $transaction->id_carer = $carerId;
        $transaction->id_mission = $missionId;
        $transaction->type = $type;
        $transaction->paid_credit = $credit->amount;
        $transaction->currency = $credit->currency;

        $creditBalance = self::getCreditBalance($carerId);
        $newCreditBalance = $creditBalance->add($credit);
        $transaction->credit_balance = $newCreditBalance->amount;

        $transaction->validate();
        $errors = $transaction->getErrors();
        $transaction->save();
    }

    public static function createCreditPayment($carerId, $missionId, $credit) {
        self::createCredit($carerId, $missionId, $credit, self::CREDIT_PAYMENT);
    }

    public static function createCarerCompensation($carerId, $missionId, $credit) {

        self::createCredit($carerId, $missionId, $credit, self::COMPENSATION);
    }

    public static function createCreditCancelClient($carerId, $missionId, $credit) {
        self::createCredit($carerId, $missionId, $credit, self::CREDIT_CANCEL_CLIENT);
    }

    /**
     * Recredit carer
     * 
     * @param type $carerTransactionId id of the original carer transaction to recredit
     */
    public static function createFailedWithdrawal($carerTransactionId) {

        $model = self::loadModel($carerTransactionId);

        self::createCredit($model->id_carer, null, new Price($model->withdraw, $model->currency), self::TRANSFER_FAILED);
    }

    /**
     * Withdraw money from carer credit account, notice the administrator
     */
    public static function createWithdraw($carerId, $amount, $sendEmail = false) {

        $creditBalance = self::getCreditBalance($carerId);

        //check amount is below $credit balance
        if ($creditBalance->amount < $amount) {
            throw new CException('Amount is above credit balance');
        }

        $transaction = new CarerTransaction();
        $transaction->id_carer = $carerId;

        $transaction->type = self::WITHDRAW;
        $transaction->withdraw = $amount;
        $transaction->currency = $creditBalance->currency;

        $newCreditBalance = $creditBalance->amount - $amount;

        $transaction->credit_balance = $newCreditBalance;

        if ($transaction->validate()) {

            //by RC Emails::sendToAdmin_CarerWithdrawal($transaction);
            $transaction->save();

            //create a carer withdrawal
            CarerWithdrawal::createNew($transaction->id);

            $carer = Carer::loadModel($carerId);
            if ($sendEmail) {
                Emails::sendToAdmin_CarerWithdrawal($carer, $amount);
            }
        } else {

            $errors = $transaction->getErrors();
            throw CException(var_dump($errors));
        }
    }

    public static function getCreditBalance($carerId) {

        //get last record
        $last = self::getLast($carerId);

        if (isset($last)) {
            $amount = $last->credit_balance;
            return new Price($last->credit_balance, $last->currency);
        } else {
            return new Price(0, 'GBP');
        }
    }

    public function getPaidCredit() {

        return new Price($this->paid_credit, $this->currency);
    }

    /**
     * Uses the highest id for give carer.
     * 
     * @param type $carerId
     * @return type
     */
    public static function getLast($carerId) {

        $criteria = new CDbCriteria;

        $criteria->condition = 'id = (SELECT MAX(id) FROM tbl_carer_transaction WHERE 
            id_carer=:carerId) AND id_carer = :carerId';
        $criteria->params = array(':carerId' => $carerId);

        return self::model()->find($criteria);
    }

    /**
     * If the mission is not active and the transaction si a credit (not withdraw) display the button
     * @return boolean
     */
    public function isDetailslButtonVisible() {

        if (($this->type == CarerTransaction::CREDIT_PAYMENT || $this->type == CarerTransaction::CREDIT_CANCEL_CLIENT) && $this->mission->isActive()) {
            return true;
        } else {
            return false;
        }
    }

}