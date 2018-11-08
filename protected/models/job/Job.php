<?php

/**
 * This is the model class for table "tbl_job".
 *
 * The followings are the available columns in table 'tbl_job':
 * @property integer $id
 * @property integer $id_client
 * @property string $post_code
 * @property integer $gender_carer
 * @property integer $who_for
 * @property string $language
 * @property string $first_name_user 
 * @property string $last_name_user
 * @property string $gender_user
 * @property integer $age_group
 * @property integer $mental_health
 * @property integer $physical_health
 * @property string $message
 * @property integer $status
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Client $idClient
 * @property JobActivity[] $jobActivities
 */
class Job extends CActiveRecord {

    //store ids of jobactivities
    public $formActivities = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Job the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_job';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_code, gender_carer, gender_user, age_group, mental_health, physical_health, message', 'required'),
            array('first_name_user, last_name_user', 'required', 'on' => 'for_other'),
            array('id_client, gender_carer, gender_user, who_for, age_group, mental_health, physical_health, status', 'numerical', 'integerOnly' => true),
            array('post_code', 'length', 'max' => 10),
            array('language', 'length', 'max' => 20),
            array('first_name_user, last_name_user', 'length', 'max' => 50),
            array('message', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, post_code, gender_carer, gender_user, who_for, language, first_name_user, last_name_user, age_group, mental_health, physical_health, message, status, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
            'jobActivities' => array(self::HAS_MANY, 'JobActivity', 'id_job'),
        );
    }

    public function validate($attributes = null, $clearErrors = true) {

        $valid = true;

        if (count($this->formActivities) == 0) { //for somebody else
            $this->addError('formActivities', 'Select at least one activity');
            $valid = false;
        }

        return $valid && parent::validate($attributes, $clearErrors);
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

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'post_code' => 'Post Code',
            'gender_user' => 'Gender',
            'gender_carer' => 'Gender',
            'who_for' => 'Who For',
            'language' => 'Language',
            'first_name_user' => 'First Name',
            'last_name_user' => 'Last Name',
            'age_group' => 'Age Group',
            'mental_health' => 'Mental Health',
            'physical_health' => 'Physical Health',
            'message' => 'Message',
            'status' => 'Status',
            'created' => 'Created',
            'modified' => 'Modified',
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
        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('post_code', $this->post_code, true);
        $criteria->compare('gender_user', $this->gender_user);
        $criteria->compare('gender_carer', $this->gender_carer);
        $criteria->compare('who_for', $this->who_for);
        $criteria->compare('language', $this->language, true);
        $criteria->compare('first_name_user', $this->first_name_user, true);
        $criteria->compare('last_name_user', $this->last_name_user, true);
        $criteria->compare('age_group', $this->age_group);
        $criteria->compare('mental_health', $this->mental_health);
        $criteria->compare('physical_health', $this->physical_health);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function init() {

        $this->gender_carer = Constants::GENDER_BOTH;
        $this->gender_user = Constants::GENDER_FEMALE;
        $this->message = 'Hello, I need a Carer';
        $this->formActivities = array('30');
    }

    public function getGenderUserText() {

        if ($this->gender_user == Constants::GENDER_FEMALE) {
            return 'Female';
        } else {
            return 'Male';
        }
    }

    public function getAgeGroupText() {

        $labels = AgeGroup::getAgeGroupsLabels();

        return $labels[$this->age_group];
    }

    public function getMentalHealthText() {

        $values = Condition::getConditionsForDropdonw(Condition::TYPE_MENTAL);
        return $values[$this->mental_health];
    }

    public function getPhysicalHealthText() {
        $values = Condition::getConditionsForDropdonw(Condition::TYPE_PHYSICAL);
        return $values[$this->physical_health];
    }

    public function showMap() {
        UIServices::showMap($this->post_code);
    }

    public function publishJob() {

        $exceptionList = array();
        $carers = Carer::getPotentialCarersForJob($this, $exceptionList);

        //send email and create message to carers
        foreach ($carers as $carer) {

            $this->sendNewJobEmail($carer);
            $this->createNewConversation($carer);
        }
    }

    public function sendNewJobEmail($carer) {

        Emails::sendToCarer_NewJob($this, $carer);
    }

    /**
     * Create a new conversation if did not exist,
     * @param type $carer
     */
    public function createNewConversation($carer) {

        $conversation = Conversation::getConversation($this->id_client, $carer->id);

        if (!isset($conversation)) {

            $conversation = new Conversation();
            $conversation->id_client = $this->id_client;
            $conversation->id_carer = $carer->id;

            $conversation->validate();
            $err = $conversation->errors;
            $conversation->save();
        }

        $conversation->createFirstMessageForCarer($this);
    }

    public function displayActivities() {

        $result = '';
        foreach ($this->jobActivities as $jobActivity) {
            $condition = Condition::loadModel($jobActivity->activity);
            $result .= Condition::getText($condition->name) . ', ';
        }
        $result = Util::removeEndString($result, ', ');
        return $result;
    }

    public function displayJobEmail() {

        $html = '<u>Job details</u>';
        $html .= '<br>';
        $html .= $this->getAgeGroupText() . ' ' . $this->getGenderUserText();
        $html .= '<br>';
        $html .= $this->getMentalHealthText() . ', ' . $this->getPhysicalHealthText();
        $html .= '<br>';
        $html .= 'Activities: ' . $this->displayActivities();
        $html .= '<br>';
        $result = Maps::getPostCodeData($this->post_code);
        $city = $result['city'];
        $html .= "Location:  $city " . substr($this->post_code, 0, 3);
        $html .= '<br>';
        $html .= "Message: " . $this->message;

        return $html;
    }

}