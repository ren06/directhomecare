<?php

/**
 * This is the model class for table "tbl_carer_experience".
 *
 * The followings are the available columns in table 'tbl_carer_experience':
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property string $employer
 * @property integer $id_carer
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 */
class CarerExperience extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CarerExperience the static model class
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
		return 'tbl_carer_experience';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_carer', 'numerical', 'integerOnly'=>true),
			array('employer', 'length', 'max'=>50),
			array('start_date, end_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, start_date, end_date, employer, id_carer', 'safe', 'on'=>'search'),
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
			'idCarer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'employer' => 'Employer',
			'id_carer' => 'Id Carer',
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
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('employer',$this->employer,true);
		$criteria->compare('id_carer',$this->id_carer);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}