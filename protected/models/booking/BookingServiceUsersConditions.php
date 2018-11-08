<?php

/**
 * This is the model class for table "tbl_service_user_condition_request".
 *
 * The followings are the available columns in table 'tbl_service_user_condition_request':
 * @property integer $id
 * @property integer $id_service_user
 * @property integer $id_condition
 *
 * The followings are the available model relations:
 * @property ServiceUserRequest $idServiceUser
 * @property Condition $idCondition
 */
class BookingServiceUsersConditions extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BookingServiceUsersConditions the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_booking_service_users_conditions';
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
            'serviceUser' => array(self::BELONGS_TO, 'BookingServiceUser', 'id_service_user'),
            'condition' => array(self::BELONGS_TO, 'Condition', 'id_condition'),
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

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function createServiceUserConditions($serviceUserId, $serviceUserRequestId) {

        $results = Yii::app()->db->createCommand(
                        'SELECT * FROM tbl_service_user_condition WHERE id_service_user=' . $serviceUserId
                )->queryAll();

        $values = '';

        foreach ($results as &$result) {

            $values = $values . '(' . $serviceUserRequestId . ',' . $result['id_condition'] . '),';
        }

        $values = substr($values, 0, -1);

        $sql = 'INSERT INTO tbl_booking_service_users_conditions (id_service_user, id_condition) VALUES ' . $values;
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }

}