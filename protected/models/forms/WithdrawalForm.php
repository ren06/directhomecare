<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class WithdrawalForm extends CFormModel {

    public $first_name;
    public $last_name;
    public $sort_code;
    public $account_number;
    public $amount;
    public $decimals;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            //array('sort_code, account_number, amount', 'required', 'message' => Yii::t('texts', 'TEST')),
            array('sort_code', 'required', 'message' => Yii::t('texts', 'ERROR_ENTER_A_6_DIGIT_NUMBER')),
            array('account_number', 'required', 'message' => Yii::t('texts', 'ERROR_ENTER_A_8_DIGIT_NUMBER')),
            //array('amount', 'required', 'message' => Yii::t('texts', 'ERROR_ENTER_AN_AMOUNT')),
            //array('decimals', 'default', 'value' => '00' ),
            array('sort_code', 'numerical', 'integerOnly' => true, 'message' => Yii::t('texts', 'ERROR_ENTER_A_6_DIGIT_NUMBER')),
            array('account_number', 'numerical', 'integerOnly' => true, 'message' => Yii::t('texts', 'ERROR_ENTER_A_8_DIGIT_NUMBER')),
            //array('amount', 'numerical', 'message' => Yii::t('texts', 'ERROR_ENTER_AN_AMOUNT')),
            array('sort_code', 'length', 'is' => 6, 'message' => Yii::t('texts', 'ERROR_ENTER_A_6_DIGIT_NUMBER')),
            array('account_number', 'length', 'is' => 8, 'message' => Yii::t('texts', 'ERROR_ENTER_A_8_DIGIT_NUMBER')),
            //array('decimals', 'length', 'is' => 2, 'message' => Yii::t('texts', 'ERROR_ENTER_A_2_DIGIT_NUMBER')), //RTRT
            //array('decimals', 'numerical', 'integerOnly' => true),
            //array('amount', 'numerical', 'min' => 0, 'message' => Yii::t('texts', 'Enter a value greater than 0')), //RTRT
            //array('amount', 'checkAmount'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'first_name' => Yii::t('texts', 'LABEL_FIRST_NAME'),
            'last_name' => Yii::t('texts', 'LABEL_LAST_NAME'),
            'sort_code' => Yii::t('texts', 'LABEL_SORT_CODE'),
            'account_number' => Yii::t('texts', 'LABEL_ACCOUNT_NUMBER'),
            'amount' => Yii::t('texts', 'LABEL_AMOUNT'),
            'decimals' => '',
        );
    }

    public function checkAmount($attribute, $params) {

        $currentBalance = CarerTransaction::getCreditBalance(Yii::app()->user->id);
        
        $price = $this->amount . '.' . $this->decimals;
        
        if ($price > $currentBalance->amount) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_AMOUNT_IS_HIGHER_THAN_BALANCE'));
        }
        elseif($price <= 0){
            $this->addError($attribute, Yii::t('texts', 'ERROR_MUST_BE_HIGHER_THAN_ZERO'));
        }
    }

}