<?php

/**
 * This is the model class for table "tbl_booking_credit_card".
 *
 * The followings are the available columns in table 'tbl_booking_credit_card':
 * @property integer $id
 * @property integer $id_booking
 * @property integer $number
 * @property string $valid_from
 * @property string $valid_to
 *
 * The followings are the available model relations:
 * @property Booking $idBooking
 */
class BookingCreditCard extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BookingCreditCard the static model class
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
		return 'tbl_booking_credit_card';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_booking, number, valid_from, valid_to', 'required'),
			array('id_booking, number', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_booking, number, valid_from, valid_to', 'safe', 'on'=>'search'),
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
			'booking' => array(self::BELONGS_TO, 'Booking', 'id_booking'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_booking' => 'Id Booking',
			'number' => 'Number',
			'valid_from' => 'Valid From',
			'valid_to' => 'Valid To',
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
		$criteria->compare('id_booking',$this->id_booking);
		$criteria->compare('number',$this->number);
		$criteria->compare('valid_from',$this->valid_from,true);
		$criteria->compare('valid_to',$this->valid_to,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}