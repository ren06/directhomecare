<?php

/**
 * This is the model class for table "tbl_credit_card_expiry".
 *
 * The followings are the available columns in table 'tbl_credit_card_expiry':
 * @property integer $id
 * @property integer $id_credit_card
 * @property integer $number_email_sent
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property CreditCard $idCreditCard
 */
class CreditCardExpiry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CreditCardExpiry the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_credit_card_expiry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_credit_card, number_email_sent', 'required'),
			array('id_credit_card, number_email_sent', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_credit_card, number_email_sent, created, modified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idCreditCard' => array(self::BELONGS_TO, 'CreditCard', 'id_credit_card'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_credit_card' => 'Id Credit Card',
			'number_email_sent' => 'Number Email Sent',
			'created' => 'Created',
			'modified' => 'Modified',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_credit_card',$this->id_credit_card);
		$criteria->compare('number_email_sent',$this->number_email_sent);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}