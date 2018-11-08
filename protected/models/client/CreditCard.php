<?php

/**
 * This is the model class for table "tbl_credit_card".
 *
 * The followings are the available columns in table 'tbl_credit_card':
 * @property integer $id
 * @property integer $id_client
 * @property integer $id_address
 * @property string $name_on_card
 * @property integer $card_type
 * @property string $card_number
 * @property string $last_three_digits
 * @property string $expiry_date
 * @property string $valid_from
 * @property string $valid_to
 *
 * The followings are the available model relations:
 * @property Booking[] $bookings
 * @property Address $address
 * @property Client $client
 * @property MissionPayment[] $missionPayments
 */
class CreditCard extends ActiveRecord {

    const TYPE_NONE = null;
    const TYPE_VISA_DEBIT = 0;
    const TYPE_VISA_CREDIT = 1;
    const TYPE_MASTERCARD_DEBIT = 2;
    const TYPE_MASTERCARD_CREDIT = 3;
    const VALIDATION_SCENARIO_EDIT = 'edit';
    //const VALIDATION_SCENARIO_NEW = 'new'; //new from menu Maintain Credit cards
    const KEY = '£132Fsdsds'; //TODO store somewhere else

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CreditCard the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_credit_card';
    }

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('name_on_card, card_type, card_number, last_three_digits', 'required', 'on' => 'insert'),
            array('card_number, last_three_digits', 'required', 'on' => 'insert'),
            array('id_client, card_type, card_number, last_three_digits', 'numerical', 'integerOnly' => true, 'on' => 'insert'),
            array('name_on_card', 'length', 'max' => 60),
            array('expiry_date', 'required', 'message' => Yii::t('texts', 'ERROR_EXPIRY_DATE_CANNOT_BE_PASSED')),
            array('last_three_digits', 'length', 'is' => 3, 'on' => 'insert'),
            array('card_number', 'length', 'is' => 16, 'on' => 'insert', 'message' => Yii::t('texts', 'ERROR_MUST_BE_16_DIGITS_WITHOUT_SPACE')),
            array('valid_from, valid_to', 'safe'),
            // array('card_number', 'filter', 'filter' => 'encryptCreditCard'),
            // array('card_number', 'checkValidCard', 'on' => self::VALIDATION_SCENARIO_NEW),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, name_on_card, card_type, card_number, last_three_digits, expiry_date, valid_from, valid_to', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {

        if ($this->isNewRecord) {

            $cardNum = $this->card_number;

            $cardNumber = Encryption::encrypt($this->card_number);
            $lastThreeDigits = Encryption::encrypt($this->last_three_digits);

            $this->card_number = $cardNumber;
            $this->last_three_digits = $lastThreeDigits;
        }

        $expiryDate = Encryption::encrypt($this->expiry_date);
        $this->expiry_date = $expiryDate;

        return parent::beforeSave();
    }

    public function getDecryptedTemporaryInstance() {

        $tempData = self::getCardNumberAdmin($this->id);

        $tempCard = clone $this;
        $tempCard->card_number = $tempData['card_number'];
        $tempCard->last_three_digits = $tempData['last_three_digits'];
        $tempCard->expiry_date = $tempData['expiry_date'];

        return $tempCard;
    }

    //TODO 
    public function checkValidCard() {

        $valid = PayPal::checkValidCard($this);
        if (!$valid) {
            $this->addError('error_message', Yii::t('texts', 'ERROR_CARD_DETAILS_NOT_VALID'));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bookings' => array(self::HAS_MANY, 'Booking', 'id_credit_card'),
            'address' => array(self::BELONGS_TO, 'Address', 'id_address'),
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
            'missionPayments' => array(self::HAS_MANY, 'MissionPayment', 'id_credit_card'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'id_address' => 'Id Address',
            'name_on_card' => Yii::t('texts', 'LABEL_NAME_ON_CARD'),
            'card_type' => Yii::t('texts', 'LABEL_CARD_TYPE'),
            'card_number' => Yii::t('texts', 'LABEL_CARD_NUMBER'),
            'last_three_digits' => Yii::t('texts', 'LABEL_LAST_THREE_DIGITS'),
            'expiry_date' => Yii::t('texts', 'LABEL_EXPIRY_DATE'),
            'valid_from' => Yii::t('texts', 'LABEL_VALID_FROM'), //RC don't need that
            'valid_to' => Yii::t('texts', 'LABEL_VALID_TO'), //RC don't need that
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
        $criteria->compare('id_address', $this->id_address);
        $criteria->compare('name_on_card', $this->name_on_card, true);
        $criteria->compare('card_type', $this->card_type);
        $criteria->compare('card_number', $this->card_number, true);
        $criteria->compare('last_three_digits', $this->last_three_digits, true);
        $criteria->compare('expiry_date', $this->expiry_date, true);
        $criteria->compare('valid_from', $this->valid_from, true);
        $criteria->compare('valid_to', $this->valid_to, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    //restrict access to owner
//    public function defaultScope() {
//        
//        return array(
//            'condition' => 'id_client = ' . Yii::app()->user->id,
//        );
//    }

    public static function getCardTypeOptions() {

        //RTRT
        return array(self::TYPE_NONE => Yii::t('texts', 'DROPDOWN_SELECT'), self::TYPE_VISA_DEBIT => 'Visa (debit)', self::TYPE_VISA_CREDIT => 'Visa (credit)', self::TYPE_MASTERCARD_DEBIT => 'Maestro (debit)', self::TYPE_MASTERCARD_CREDIT => 'MasterCard (credit)'); //RTRT
    }

    public function getCardTypeLabel() {

        $options = self::getCardTypeOptions();

        return $options[$this->card_type];
    }

    public function getPayPalCardTypeLabel() {

        $options = array(self::TYPE_VISA_DEBIT => 'Visa', self::TYPE_VISA_CREDIT => 'Visa', self::TYPE_MASTERCARD_DEBIT => 'MasterCard', self::TYPE_MASTERCARD_CREDIT => 'MasterCard');

        return $options[$this->card_type];
    }

    public function getExpiryMonth() {

        $timestamp = strtotime(date("Y-m-d", strtotime($this->expiry_date)));

        return date('m', $timestamp);
    }

    public function getExpiryYear() {

        $timestamp = strtotime(date("Y-m-d", strtotime($this->expiry_date)));

        return date('Y', $timestamp);
    }

    public function hasExpired() {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);
        $expiry = $this->expiry_date;

        if (Calendar::dateIsBefore($expiry, $today, true)) {

            return true;
        } else {
            return false;
        }
    }

    public function daysBeforeExpiry() {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);
        $expiry = $this->expiry_date;

        return Calendar::daysBetween_DBDate($today, $expiry);
    }

    public function handleExpiry() {

        $daysBeforeExpiry = $this->daysBeforeExpiry();

        $rule = BusinessRules::getBeforeCreditCardExpiryBeforeEmail1InDays();

        if ($daysBeforeExpiry <= $rule) {

            //check if already a record
            $existingCreditCardExpiry = CreditCardExpiry::model()->find('id_credit_card=:id_credit_card', array(':id_credit_card' => $this->id));

            if (isset($existingCreditCardExpiry)) {

                //check if new email must be sent
                $daysBeforeExpiry = $this->daysBeforeExpiry();

                $rule = BusinessRules::getBeforeCreditCardExpiryBeforeEmail2InDays();

                if ($daysBeforeExpiry <= $rule) {

                    if ($existingCreditCardExpiry->number_email_sent == 1) {

                        //send second email
                        Emails::sendToClient_CreditCardExpiry($this->client, 2);

                        $existingCreditCardExpiry->number_email_sent = 2;
                        $existingCreditCardExpiry->save();
                    }
                } else {

                    $rule = BusinessRules::getBeforeCreditCardExpiryBeforeEmail3InDays();

                    if ($daysBeforeExpiry <= $rule) {

                        if ($existingCreditCardExpiry->number_email_sent == 2) {

                            //send second email
                            Emails::sendToClient_CreditCardExpiry($this->client, 3);

                            $existingCreditCardExpiry->number_email_sent = 3;
                            $existingCreditCardExpiry->save();
                        }
                    }
                }
            } else {

                $creditCardExpiry = new CreditCardExpiry();
                $creditCardExpiry->id_credit_card = $this->id;
                $creditCardExpiry->number_email_sent = 1;

                Emails::sendToClient_CreditCardExpiry($this->client, 1);

                $creditCardExpiry->save();
            }
        }
    }

    /**
     * Save credit card and create another one as master data
     * @param type $creditCard validated credit card data
     * @param type $booking     
     */
    public static function handleCreditCard($creditCard, $clientId, $billingAddressId) {

        $cardNumber = $creditCard->card_number;
        $creditCardTransactional = clone $creditCard;
        $creditCard->id_client = $clientId;
        $creditCard->id_address = $billingAddressId;
        $creditCard->validate();
        $err = $creditCard->errors;

        // Calendar::setBookingEntitiesValidityDates($creditCard);

        $id = $creditCard->id;
        $dte = $creditCard->expiry_date;
        $creditCard->save();
        $id = $creditCard->id;

        return $creditCard;
//        $id = $creditCard->id;
//        $cardNumber = $creditCard->card_number;
//
//        $creditCardTransactional->id_booking = $booking->id;
//        Calendar::setBookingEntitiesValidityDates($creditCardTransactional);
//
//        $creditCardTransactional->validate();
//        $err = $creditCardTransactional->errors;
//        $creditCardTransactional->save();
//        $id = $creditCardTransactional->id;
    }

    /**
     * 
     * @param type $clientId Client id    
     */
    public static function getCreditCard($clientId, $dataType) {

        return self::model()->findAllByAttributes(array('id_client' => $clientId, 'valid_to' => $dataType));
    }

    public function displayShort() {

        return //$this->getCardTypeLabel() . '&#160;' . 
                'Card ' . Yii::t('texts', 'LABEL_ENDING_IN') . ' ' . self::getCardNumberLastFourDigits($this->card_number);
    }

    private static function getCardNumberLastFourDigits($cardNumber) {

        return substr($cardNumber, 12, 4);
    }

    public function isUsedBooking() {

        return (count($this->bookings) > 0);
    }

    public static function authorizeClient($creditCardId) {

        $user = Yii::app()->user->id;

        $sql = "SELECT cc.id FROM tbl_credit_card cc INNER JOIN tbl_client c ON cc.id_client = c.id WHERE c.id=$user AND cc.id = $creditCardId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if (!isset($row['id'])) {

            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }
    }

    public function getLastNumbers() {

        $temp = $this->getDecryptedTemporaryInstance();
        
        return self::getCardNumberLastFourDigits($temp->card_number);
    }

    protected function afterFind() {
        //decrypt data

        $cardNumber = Encryption::decrypt($this->card_number);
        $lastNumbers = self::getCardNumberLastFourDigits($cardNumber);

        $this->card_number = 'xxxxxxxxxxxx' . $lastNumbers;
        $this->last_three_digits = 'xxx';
        $this->expiry_date = Encryption::decrypt($this->expiry_date);

        parent::afterFind();
    }

    /**
     * 
     * CHECK AUTHORIZATION ADMIN/CRON JOBS
     * 
     * @param type $id card id
     * @return type array with keys 'card_number' 'last_three_digits' 'expiry_date'
     * @throws CHttpException Not found
     */
    public static function getCardNumberAdmin($id) {

        //TODO check admin?

        $sql = "SELECT cc.card_number, cc.last_three_digits, cc.expiry_date FROM tbl_credit_card cc WHERE cc.id = :id";

        $command = Yii::app()->db->createCommand($sql);
        $command->params = array('id' => $id);

        $row = $command->queryRow();

        if (!isset($row['card_number'])) {

            throw new CHttpException(404, 'Not found');
        } else {

            $result['card_number'] = Encryption::decrypt($row['card_number']);
            $result['last_three_digits'] = Encryption::decrypt($row['last_three_digits']);
            $result['expiry_date'] = Encryption::decrypt($row['expiry_date']);

            return $result;
        }
    }

    /**
     * Set expiry date and validate it
     * 
     * @param type $month
     * @param type $yea
     */
    public function setExpiryDate($month, $year) {
        if (is_numeric($month) && is_numeric($year) && checkdate($month, 1, $year)) {

            //add leading 0 if necessary
            $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $month = sprintf("%02d", $month);

            $date_value = "$year-$month-$day";

            if (!Calendar::dateIsBefore($date_value, Calendar::today(Calendar::FORMAT_DBDATE))) {
                $this->expiry_date = $date_value;
            }
        } else {

            unset($this->expiry_date); //unset the value so that error is reported in the model
        }
    }

//    public static function loadModel($id) {
//
//        $model = parent::loadModelAdmin($id);
//
//        $model->card_number = 'xxxx-xxxx-xxxx' . self::getCardNumberLastFourDigits($model->card_number);
//        $model->last_three_digits = 'xxx';
//
//        return $model;
//    }
//
//    public static function loadModelAdmin($id) {
//
//        $model = static::model()->resetScope()->findByPk($id);
//        if ($model === null)
//            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
//
//
//        //decrypt data
//        Yii::import('application.extensions.CreditCardFreezer.CreditCardFreezer');
//
//        $obj = new CreditCardFreezer();
//
//        $obj->setPassKey(self::KEY);
//
//        $model->card_number = $obj->_decrypt($model->card_number);
//        $model->last_three_digits = $obj->_decrypt($model->card_number);
//        $model->expiry_date = $obj->_decrypt($model->expiry_date);
//
//        return $model;
//    }
}