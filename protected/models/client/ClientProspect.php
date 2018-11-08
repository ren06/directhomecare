<?php

/**
 * This is the model class for table "tbl_client_prospect".
 *
 * The followings are the available columns in table 'tbl_client_prospect':
 * @property string $sessionID
 * @property string $email_address_step1
 * @property string $email_address_step2
 * @property string $created
 */
class ClientProspect extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClientProspect the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_client_prospect';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sessionID, email_address_step1, created', 'required'),
            array('sessionID, email_address_step1, email_address_step2', 'length', 'max' => 80),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('sessionID, email_address_step1, email_address_step2, created', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'sessionID' => 'Session',
            'email_address_step1' => 'Email Address Step1',
            'email_address_step2' => 'Email Address Step2',
            'created' => 'Created',
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

        $criteria->compare('sessionID', $this->sessionID, true);
        $criteria->compare('email_address_step1', $this->email_address_step1, true);
        $criteria->compare('email_address_step2', $this->email_address_step2, true);
        $criteria->compare('created', $this->created, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'created DESC',
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

    public function getPostCode() {

        $sessionId = $this->sessionID;

        $sql = "SELECT criteria_value FROM tbl_client_carer_search_criteria " .
                " WHERE criteria_name = 'postCode' AND id_session = '$sessionId'";

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if (isset($result['criteria_value'])) {
            return $result['criteria_value'];
        } else {
            return '';
        }
    }

    public static function isSessionIdExists($sessionId) {

        $sql = "SELECT sessionID FROM tbl_client_prospect " .
                " WHERE sessionID = '$sessionId'";

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if (isset($result['sessionID'])) {
            return true;
        } else {
            return false;
        }
    }

}