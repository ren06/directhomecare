<?php

/**
 * This is the model class for table "tbl_age_group".
 *
 * The followings are the available columns in table 'tbl_age_group':
 * @property integer $id
 * @property integer $age_group
 * @property integer $id_carer
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 */
class AgeGroup extends ActiveRecord {
    const CHILDREN = 0;
    const YOUNG_ADULTS = 1;
    const ADULTS = 2;
    const ELDERLY = 3;

    /**
     * Returns the static model of the specified AR class.
     * @return AgeGroup the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_age_group';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('age_group, id_carer', 'required'),
            array('age_group, id_carer', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, age_group, id_carer', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'age_group' => 'Age Group',
            'id_carer' => 'Id Carer',
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
        $criteria->compare('age_group', $this->age_group);
        $criteria->compare('id_carer', $this->id_carer);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

    public static function getAgeGroupsLong() {

        $ageGroups = Yii::app()->params['ageGroups'];

        $childrenMin = $ageGroups['children'][0];
        $childrenMax = $ageGroups['children'][1];

        $youngAdultMin = $ageGroups['young_adult'][0];
        $youngAdultMax = $ageGroups['young_adult'][1];

        $adultMin = $ageGroups['adult'][0];
        $adultMax = $ageGroups['adult'][1];

        $ederlyMin = $ageGroups['elderly'][0];

        return array(self::CHILDREN => "Children ($childrenMin-$childrenMax years old)",
            self::YOUNG_ADULTS => "Young adults ($youngAdultMin-$youngAdultMax years old)",
            self::ADULTS => "Adults ($adultMin-$adultMax years old)",
            self::ELDERLY => "Elderly (older than $ederlyMin years old)",
        );
    }

    public static function getAgeGroups() {

        return array(
            self::ELDERLY => "Elderly",
            self::ADULTS => "Adults",            
            self::YOUNG_ADULTS => "Young adults",
            self::CHILDREN => "Children",
            
        );
    }
    
        public static function getAgeGroupsLabels() {

        return array(self::CHILDREN => "Child",
            self::YOUNG_ADULTS => "Young adult",
            self::ADULTS => "Adult",
            self::ELDERLY => "Elderly",
        );
    }

}