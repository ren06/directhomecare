<?php

/**
 * This is the model class for table "tbl_carer_withdrawal".
 *
 * The followings are the available columns in table 'tbl_carer_withdrawal':
 * @property integer $id
 * @property integer $id_carer_transaction
 * @property integer $status
 * @property string $bank_reference
 *
 * The followings are the available model relations:
 * @property CarerTransaction $carerTransaction
 */
class CarerWithdrawal extends ActiveRecord {

    const STATUS_NEW = 0;
    const STATUS_COMPLETED = 1;    
    const STATUS_FAILED = 2;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CarerWithdrawal the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_carer_withdrawal';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_carer_transaction, status', 'required'),
            array('id_carer_transaction, status', 'numerical', 'integerOnly' => true),
            array('bank_reference', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_carer_transaction, status, bank_reference', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'carerTransaction' => array(self::BELONGS_TO, 'CarerTransaction', 'id_carer_transaction'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'Id Withdrawal',
            'id_carer_transaction' => 'Id Carer Transaction',
            'status' => 'Status',
            'bank_reference' => 'Bank Reference',
        );
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

    public static function getStatusLabels() {

        return array(self::STATUS_NEW => Yii::t('texts', 'New'),
            self::STATUS_COMPLETED => Yii::t('texts', 'Completed'),           
            self::STATUS_FAILED => Yii::t('texts', 'Failed'),
        );
    }

    public function getStatusLabel() {

        $labels = self::getStatusLabels();
        return $labels[$this->status];
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
        $criteria->compare('id_carer_transaction', $this->id_carer_transaction);
        $criteria->compare('status', $this->status);
        $criteria->compare('bank_reference', $this->bank_reference, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Create a carer withdrawal record with status NEW
     * 
     * @param type $transactionId Carer transaction ID
     * @return \CarerWithdrawal object
     */
    public static function createNew($transactionId) {

        $model = new CarerWithdrawal();
        $model->id_carer_transaction = $transactionId;
        $model->status = self::STATUS_NEW;
        $model->save();
        
        return $model;
    }

    public static function getAllDataProvider() {

        $criteria = new CDbCriteria;
        $criteria->order = ' id DESC';

//        $criteria->compare('id', $this->id);
//        $criteria->compare('id_carer_transaction', $this->id_carer_transaction);
//        $criteria->compare('status', $this->status);
//        $criteria->compare('bank_reference', $this->bank_reference, true);

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
        ));
    }

}