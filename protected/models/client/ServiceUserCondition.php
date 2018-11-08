<?php

/**
 * This is the model class for table "tbl_service_user_condition".
 *
 * The followings are the available columns in table 'tbl_service_user_condition':
 * @property integer $id
 * @property integer $id_service_user
 * @property integer $id_condition
 *
 * The followings are the available model relations:
 * @property Condition $idCondition
 * @property ServiceUser $idServiceUser
 */
class ServiceUserCondition extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return ServiceUserCondition the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_service_user_condition';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_service_user, id_condition', 'required'),
            array('id_service_user, id_condition', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_service_user, id_condition', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'condition' => array(self::BELONGS_TO, 'Condition', 'id_condition'),
            'serviceUser' => array(self::BELONGS_TO, 'ServiceUser', 'id_service_user'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_service_user' => 'Id Service User',
            'id_condition' => 'Id Condition',
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
        $criteria->compare('id_service_user', $this->id_service_user);
        $criteria->compare('id_condition', $this->id_condition);

        return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                ));
    }

    public static function deleteServiceUserConditions($id_service_user) {

//        //tbl_service_user, tbl_service_user_condition, tbl_condition
//$sql = "SELECT * FROM `tbl_document` JOIN `tbl_carer_document` WHERE `tbl_document`.`type` =" . Document::TYPE_DIPLOMA
//                . " AND `tbl_carer_document`.`id_carer` = " . $id_carer;

        $sql = "SELECT * FROM `tbl_service_user_condition` WHERE `tbl_service_user_condition`.`id_service_user` =" . $id_service_user;

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($rows as $row) {

            self::model()->deleteAllByAttributes(array('id_condition' => $row['id_condition'], 'id_service_user' => $row['id_service_user']));
        }
    }

    public static function isServiceUserUsed($id) {

//        $sql = "SELECT * FROM tbl_service_user_request WHERE id_service_user=$id";
//
//        $rows = Yii::app()->db->createCommand($sql)->queryAll();
//
//        return!empty($rows);
        
        return false;
    }

}