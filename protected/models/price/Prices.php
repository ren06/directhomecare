<?php

/**
 * This is the model class for table "tbl_prices".
 *
 * The followings are the available columns in table 'tbl_prices':
 * @property integer $id
 * @property string $name
 * @property integer $user
 * @property double $amount
 * @property string $currency
 * @property string $valid_from
 * @property string $valid_to
 */
class Prices extends CActiveRecord {

    const LIVE_IN_DAILY_PRICE = 'live_in_daily';
    const LIVE_IN_DAILY_PRICE_BANK_HOLIDAY = 'live_in_daily_bank_holiday';
    const LIVE_IN_DAILY_PRICE_WEEK_END = 'live_in_daily_week_end';
    const HOURLY_PRICE = 'hourly_price';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Prices the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_prices';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, user, amount, currency, valid_from, valid_to', 'required'),
            array('amount', 'numerical'),
            array('name, user', 'length', 'max' => 30),
            array('currency', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, user, amount, currency, valid_from, valid_to', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'name' => 'Name',
            'user' => 'User',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'valid_from' => 'Start date',
            'valid_to' => 'End date',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('user', $this->user, true);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('currency', $this->currency, true);
        $criteria->compare('valid_from', $this->valid_from, true);
        $criteria->compare('valid_to', $this->valid_to, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Return the price. If date is null return price of today, otherwise return price at given date
     */
    public static function getPrice($user, $name, $date = null) {

        //strip the time
        if ($date == null) {
            $date = Calendar::today(Calendar::FORMAT_DBDATE);
        }
        $date = date("Y-m-d", strtotime($date));

        setlocale(LC_MONETARY, 'en_GB');

        $currency = Yii::app()->params['countryCurrencies']['en_GB'];

        $sql = "SELECT `amount` FROM `tbl_prices` WHERE name = '$name' AND currency = '$currency' AND valid_from <= '$date' AND valid_to > '$date' AND user = '$user'";

        $row = Yii::app()->db->cache(60*60*24)->createCommand($sql)->queryRow();
//                ->select('amount')
//                ->from('tbl_prices')
//                ->where('name=:name and currency=:currency and valid_from<:date and valid_to>:date and user=:user', array(':name' => $name, ':currency' => $currency, ':date' => $date, ':user' => $user))
//                ->queryRow();

        $price = $row['amount'];
        $price = doubleval($price);
        //$price = number_format($price, 2) . "\n";

        return new Price($price, $currency);
    }
    
    public static function getPriceDisplay ($user, $name, $date = null) {
        
        $price = self::getPrice($user, $name, $date = null);

        $result = $price->text;

        return $result;
    }

    public static function getPriceText($user, $name, $date = null) {

        $price = self::getPrice($user, $name, $date = null);

        $result = $price->text;
        if ($name == self::LIVE_IN_DAILY_PRICE) {

            $result .= '&#160;' . Yii::t('texts', 'LABEL_PER_DAY');
        } else {
            $result .= '&#160;' . Yii::t('texts', 'LABEL_PER_HOUR');
        }

        return $result;
    }

    /**
     * 
     */
    public static function calculateLiveInMissionPrice($user, $startDateTime, $endDateTime) {

        $seconds = Calendar::calculate_Duration_Seconds($startDateTime, $endDateTime);

        $normalDays = Calendar::convert_Seconds_DayHoursMinutesSeconds($seconds);

        $total = 0;

        if ($normalDays['d'] > 0 || $normalDays['h'] > 0 || $normalDays['m'] > 0) {

            $dailyPriceNormal = Prices::getPrice($user, Prices::LIVE_IN_DAILY_PRICE);

            $priceNormalDays =
                    ($dailyPriceNormal->amount * $normalDays['d']) + (($dailyPriceNormal->amount / 24) * $normalDays['h']) + (($dailyPriceNormal->amount / 1440) * $normalDays['m']);

            $total += $priceNormalDays;
        } else {
            assert(false);
        }

        return new Price($total, $dailyPriceNormal->currency);
    }

    public static function calculateHourlyMissionPrice($user, $startDateTime, $endDateTime) {

        $seconds = Calendar::calculate_Duration_Seconds($startDateTime, $endDateTime);

        $normalDays = Calendar::convert_Seconds_DayHoursMinutesSeconds($seconds);

        $total = 0;

        if ($normalDays['d'] > 0 || $normalDays['h'] > 0 || $normalDays['m'] > 0) {

            $hourlyPriceNormal = Prices::getPrice($user, Prices::HOURLY_PRICE);

            $priceNormalDays =
                    + (($hourlyPriceNormal->amount) * $normalDays['h']) + (($hourlyPriceNormal->amount / 60) * $normalDays['m']);

            $total += $priceNormalDays;
        } else {
            assert(false);
        }

        return new Price($total, $hourlyPriceNormal->currency);
    }

    /**
     * Return the total price minus the mision aborted price
     */
    public static function getTotalPrice($model, $user, $live = false) {

        $originalPrice = self::getOriginalTotalPrice($model, $user, $live);

        $abortedSlotsPrice = self::getMissionAbortedSlotsPrice($model, $user);

        return $originalPrice->substract($abortedSlotsPrice);
    }

    public static function getMissionAbortedSlotPrice($slot, $user) {

        if ($user == Constants::USER_CARER) {
            $priceDate = $slot->mission->created;
        } elseif ($user == Constants::USER_CLIENT) {
            $priceDate = $slot->mission->created;
        }

        $dailyPriceNormal = Prices::getPrice($user, Prices::LIVE_IN_DAILY_PRICE, $priceDate);

        if ($slot->isAbortedByClient()) {

            return new Price(0, $dailyPriceNormal->currency);
        } elseif ($slot->isAbortedByCarer()) {

            $durationInSeconds = Calendar::duration_Seconds($slot);

            $normalDays = Calendar::convert_Seconds_DayHoursMinutesSeconds($durationInSeconds);
            $total = 0;

            if ($normalDays['d'] > 0 || $normalDays['h'] > 0 || $normalDays['m'] > 0) {

                $priceNormalDays =
                        ($dailyPriceNormal->amount * $normalDays['d']) + (($dailyPriceNormal->amount / 24) * $normalDays['h']) + (($dailyPriceNormal->amount / 1440) * $normalDays['m']);

                $total += $priceNormalDays;
            }

            return new Price($total, $dailyPriceNormal->currency);
        }
    }

    //Calculate Price of aborted slot from the user point of view
    public static function getMissionAbortedSlotsPrice($model, $user) {

        $slots = $model->getMissionSlotsAborted();

        $totalPrice = new Price(0, 'GBP');

        foreach ($slots as $slot) {

            $price = self::getMissionAbortedSlotPrice($slot, $user);

            if (!isset($totalPrice)) {
                $totalPrice = $price;
            } else {
                $totalPrice = $totalPrice->add($price);
            }
        }

        return $totalPrice;
    }

    /**
     * Return Total price for original dates
     * 
     * $model MissionHourly or MissionLiveIn
     */
    public static function getOriginalTotalPrice($model, $user, $live = false) {

        $priceDate = null;

        if ($model instanceof Mission) {

            //get parent LiveInRequest creation date
            if ($live == false) {
                $priceDate = $model->created;
            }
        } else {
            assert(false);
        }

        $totalPrice = new Price();
        
        $durationInSeconds = Calendar::duration_Seconds($model);

        $dayHoursMinuteBreakdown = Calendar::convert_Seconds_DayHoursMinutesSeconds($durationInSeconds);

        if ($dayHoursMinuteBreakdown['d'] > 0 || $dayHoursMinuteBreakdown['h'] > 0 || $dayHoursMinuteBreakdown['m'] > 0) {

            if ($model instanceof MissionHourly) {
                $hourlyPriceNormal = Prices::getPrice($user, Prices::HOURLY_PRICE, $priceDate);
                $priceAmount = ($hourlyPriceNormal->amount * $dayHoursMinuteBreakdown['h']) + (($hourlyPriceNormal->amount / 60) * $dayHoursMinuteBreakdown['m']);
                $price = new Price($priceAmount);
            } else {
                $dailyPriceNormal = Prices::getPrice($user, Prices::LIVE_IN_DAILY_PRICE, $priceDate);
                $priceAmount = ($dailyPriceNormal->amount * $dayHoursMinuteBreakdown['d']) + (($dailyPriceNormal->amount / 24) * $dayHoursMinuteBreakdown['h']) + (($dailyPriceNormal->amount / 1440) * $dayHoursMinuteBreakdown['m']);
                $price = new Price($priceAmount);
            }

            $totalPrice = $totalPrice->add($price);
        }

        $startDateTime = Calendar::convert_DBDateTime_Timestamp($model->start_date_time);
        $endDateTime = Calendar::convert_DBDateTime_Timestamp($model->end_date_time);

        //not implemented
        $bankHolidayDays = 0; //Calendar::numberBankHolidayDayshoursMinutes($startDateTime, $endDateTime, Calendar::FORMAT_DBDATE);
        //add difference of price for every day hour minute during a bank holiday
        if ($bankHolidayDays > 0) {

            $dailyPriceBankHoliday = Prices::getPrice($user, Prices::LIVE_IN_DAILY_PRICE_BANK_HOLIDAY, $priceDate);

            //add $bankHolidayDays['d'] etc...
            // $totalPrice = $totalPrice->add($price);
        }

        return $totalPrice;
    }

    public function calculate() {

        //figure out if credit, if yes use it to pay
        $price = $this->calculateFirstPayment($user);

        $currency = $price->currency;

        if ($user == Constants::USER_CLIENT) {
            $credit = ClientTransaction::getCreditBalance(Yii::app()->user->id);
        } else {
            $credit = new Price(0, $currency); //carer can't have credit
        }

        $paidCredit = new Price(0, $currency);
        $paidCash = new Price(0, $currency);
        $remainingCreditBalance = new Price(0, $currency);

        if ($credit->amount > 0) {

            if ($credit->amount >= $price->amount) {

                $paidCredit = $price;
                $remainingCreditBalance = $credit->substract($price);
            } else {

                $paidCash = $price->substract($credit);
                $paidCredit = $credit;
            }
        } else {
            $paidCash = $price;
        }

        return array('toPay' => $price, 'paidCash' => $paidCash, 'paidCredit' => $paidCredit, 'remainingCreditBalance' => $remainingCreditBalance);
    }

    /**
     * 
     * return an array containing paidCash, paidCredit remainingCreditBalance
     */
    public static function calculatePaymentBreakdown($clientId, $price) {

        $currency = $price->currency;

        $credit = ClientTransaction::getCreditBalance($clientId);

        $paidCredit = new Price(0, $currency);
        $paidCash = new Price(0, $currency);
        $remainingCreditBalance = new Price(0, $currency);

        if ($credit->amount > 0) {

            if ($credit->amount >= $price->amount) {

                $paidCredit = $price;
                $remainingCreditBalance = $credit->substract($price);
            } else {

                $paidCash = $price->substract($credit);
                $paidCredit = $credit;
            }
        } else {
            $paidCash = $price;
        }

        return array('toPay' => $price, 'paidCash' => $paidCash, 'paidCredit' => $paidCredit, 'remainingCreditBalance' => $remainingCreditBalance);
    }

    public static function getLiveInMarginRate() {

        $clientPrice = Prices::getPrice(Constants::USER_CLIENT, Prices::LIVE_IN_DAILY_PRICE);
        $carerPrice = Prices::getPrice(Constants::USER_CARER, Prices::LIVE_IN_DAILY_PRICE);

        return $carerPrice->amount / $clientPrice->amount;
    }

    public static function getHourlyMarginRate() {
        
    }

}