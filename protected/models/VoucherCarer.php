<?php

/**
 * This is the model class for table "tbl_voucher_carer".
 *
 * The followings are the available columns in table 'tbl_voucher_carer':
 * @property string $voucher_code
 * @property integer $id_carer
 * @property integer $id_client
 * @property integer $issue_number
 * @property integer $amount
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 * @property Client $idClient
 */
class VoucherCarer extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VoucherCarer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_voucher_carer';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('voucher_code, id_carer, id_client, issue_number, amount', 'required'),
            array('id_carer, id_client, issue_number, amount', 'numerical', 'integerOnly' => true),
            array('voucher_code', 'length', 'max' => 15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('voucher_code, id_carer, id_client, issue_number, amount', 'safe', 'on' => 'search'),
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
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'voucher_code' => 'Voucher Code',
            'id_carer' => 'Id Carer',
            'id_client' => 'Id Client',
            'issue_number' => 'Issue Number',
            'amount' => 'Amount',
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

        $criteria->compare('voucher_code', $this->voucher_code, true);
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('issue_number', $this->issue_number);
        $criteria->compare('amount', $this->amount);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function decodeVoucher($voucherCode) {

        $voucher = Encryption::decrypt_very_light($voucherCode);
    }

    public static function encodeVoucher($voucherCode) {

        $voucher = Encryption::encrypt_very_light($voucherCode);
        
        return $voucher;
    }

}