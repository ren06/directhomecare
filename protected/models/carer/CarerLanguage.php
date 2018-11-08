<?php

/**
 * This is the model class for table "tbl_carer_language".
 *
 * The followings are the available columns in table 'tbl_carer_language':
 * @property integer $id
 * @property integer $id_carer
 * @property string $language
 * @property integer $level
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 */
class CarerLanguage extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CarerLanguage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_carer_language';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_carer, language, level', 'required'),
            array('id, id_carer, level', 'numerical', 'integerOnly' => true),
            array('language', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_carer, language, level', 'safe', 'on' => 'search'),
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
            'id_carer' => 'Id Carer',
            'language' => 'Language',
            'level' => 'Level',
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
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('language', $this->language, true);
        $criteria->compare('level', $this->level);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 
     * Check if all carer languages have only one language
     * 
     * @param type $carerLanguages
     * @return type
     */
    public static function validateAll($carerLanguages) {

        if (count($carerLanguages) == 0) {
            return false;
        }

        $entries = array();
        foreach ($carerLanguages as $carerLanguage) {
            $entries[] = $carerLanguage->language;
        }

        $withoutDuplicates = array_unique($entries);

        //check if duplicate
        return (count($withoutDuplicates) == count($entries));
    }

    public static function addLanguage($carer) {
        
        $languages = $carer->carerLanguages;

        if (empty($languages)) {

            $carerLanguage = new CarerLanguage();
            $carerLanguage->id_carer = $carer->id;
            $carerLanguage->language = 'english';

            if ($carer->nationality == 'british') {
                $carerLanguage->level = 0; //native
               // $native++;
            } else {
                $carerLanguage->level = 2; //fluent
                //$fluent++;
            }

            $carerLanguage->save();

           return true;
        }
        return false;
    }

}