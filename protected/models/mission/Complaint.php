<?php

/**
 * This is the model class for table "tbl_complaint".
 *
 * The followings are the available columns in table 'tbl_complaint':
 * @property integer $id
 * @property integer $id_client
 * @property integer $id_carer
 * @property integer $id_mission
 * @property string $created_by 
 * @property integer $type
 * @property integer $solved
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Carer $carer
 * @property Client $client
 * @property Mission $mission
 * @property ComplaintPost[] $complaintPosts
 */
class Complaint extends ActiveRecord {
    //const TYPE_FINISHED_COMPLAIN = 10;
    //const TYPE_STARTED_COMPLAIN = 11;
    //solved

    const UNSOLVED = 0;
    const SOLVED = 1;

    //type
    const TYPE_DURING_MISSION = 0;
    const TYPE_AFTER_MISSION = 1;

    //created by
    const CREATED_BY_CARER = Constants::USER_CARER;
    const CREATED_BY_CLIENT = Constants::USER_CLIENT;
    const CREATED_BY_ADMIN = Constants::USER_ADMIN;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Complaint the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_complaint';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_client, id_carer, id_mission, created_by, type, solved', 'required'),
            array('id_client, id_carer, id_mission, type, created_by, solved', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, id_carer, id_mission, type, created_by, solved, created, modified', 'safe', 'on' => 'search'),
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
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
            'mission' => array(self::BELONGS_TO, 'Mission', 'id_mission'),
            'complaintPosts' => array(self::HAS_MANY, 'ComplaintPost', 'id_complaint'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'id_carer' => 'Id Carer',
            'id_mission' => 'Id Mission',
            'created_by' => 'Created by',
            'type' => 'Type',
            'solved' => 'Solved',
            'created' => 'Created',
            'modified' => 'Modified',
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
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('id_mission', $this->id_mission);
        $criteria->compare('type', $this->type);
        $criteria->compare('solved', $this->solved);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getComplaint($missionId, $createdBy) {

        $criteria = new CDbCriteria;

        $criteria->compare('id_mission', $missionId);
        $criteria->compare('created_by', $createdBy); //security
        // $criteria->compare('id_client', Yii::app()->user->id); //security
        $criteria->order = 'created';

        return self::model()->find($criteria);
    }

    public static function createComplaint($missionId, $createdBy) {

        //create complaint
        $complaint = new Complaint();
        $complaint->id_mission = $missionId;
        $complaint->created_by = $createdBy;

        if ($createdBy == Constants::USER_CARER) {
            Mission::authorizeCarer($missionId);
        } else {
            Mission::authorizeClient($missionId);
        }

        $mission = Mission::loadModel($missionId);

        if ($mission->isStarted()) {
            $complaint->type = self::TYPE_DURING_MISSION;
        } elseif ($mission->isFinished()) {
            $complaint->type = self::TYPE_AFTER_MISSION;
        } else {
            assert(false); //not possible to create a complaint before start
        }

        $assignedCarer = $mission->getAssignedCarer();

        if (isset($assignedCarer)) {
            $carerId = $assignedCarer->id;
        } else {
            //throw new CException('Mission has no carerer');
            $carerId = 1;
        }

        $clientId = $complaint->mission->booking->id_client;
        $complaint->id_client = $clientId;
        $complaint->id_carer = $carerId;

        $complaint->solved = self::UNSOLVED;

        $t = $complaint->validate();
        $er = $complaint->errors;
        $complaint->save();

        return $complaint;
    }

    //add post to a mission, if no complaint for this mission creates one
    public static function addPost($missionId, $createdBy, $author, $visible_by, $text) {

        //can only be one complaint opened at the same time for a mission and a author
        $complaint = Complaint::getComplaint($missionId, $createdBy);

        if ($complaint == null) {
            $complaint = self::createComplaint($missionId, $createdBy);
        } else {
            $complaint->solved = Complaint::UNSOLVED;
            $complaint->save();
        }

        $post = ComplaintPost::createPost($complaint->id, $author, $visible_by, $text);
        return $post;
    }

    public static function getSolvedLabelOptions() {

        return array(self::UNSOLVED => '<span class="rc-statusandlabel-red">' . Yii::t('texts', 'STATUS_OPENED_COMPLAINT') . '<span>',
            self::SOLVED => Yii::t('texts', 'STATUS_SOLVED_COMPLAINT'));
    }

    public static function setSolvedValue($complaintId, $value) {

        $sql = "UPDATE tbl_complaint SET solved=$value WHERE id= $complaintId";
        $row = Yii::app()->db->createCommand($sql)->execute();
    }

    public function getSolvedLabel() {

        $labels = self::getSolvedLabelOptions();
        return $labels[$this->solved];
    }

    public function getTypeLabel() {

        $labels = array(self::TYPE_DURING_MISSION => 'During mission',
            self::TYPE_AFTER_MISSION => 'After mission');

        return $labels[$this->type];
    }

    public function getCreatedByLabel() {

        $labels = array(self::CREATED_BY_CARER => 'Carer',
            self::CREATED_BY_CLIENT => 'Client',
            self::CREATED_BY_ADMIN => 'Admin');

        return $labels[$this->created_by];
    }

    public static function isButtonVisible($mission, $complaint) {

        $status = $mission->getCompletionStatus();

        $note = '';
        $buttonDisplayed = false;
        if ($status == Mission::NOT_STARTED && $complaint == null) {
            $note = Yii::t('texts', 'STATUS_VISIT_NOT_STARTED');
            $buttonDisplayed = false;
        } elseif ($mission->getTimeLeftFeeback() > -1 && $complaint == null) {
            $note = Yii::t('texts', 'NOTE_YOU_CAN_WRITE_A_COMPLAINT_UP_TO', array('{daysToGiveFeedback}' => BusinessRules::getDelayToGiveFeedbackInDays())) . $mission->getTimeLeftFeeback();
            $buttonDisplayed = true;
        } elseif (isset($complaint) && $complaint->solved == Complaint::UNSOLVED) {
            $note = Yii::t('texts', 'STATUS_OPENED_COMPLAINT');
            $buttonDisplayed = true;
        } elseif ($mission->getTimeLeftFeeback() > -1 && isset($complaint) && $complaint->solved == Complaint::SOLVED) {
            $note = Yii::t('texts', 'STATUS_SOLVED_COMPLAINT');
            $buttonDisplayed = true;
        } elseif ($mission->getTimeLeftFeeback() <= -1 && $complaint == null) {
            $note = Yii::t('texts', 'NOTE_IT_IS_TOO_LATE_TO_OPEN_A_COMPLAINT');
            $buttonDisplayed = false;
        } elseif ($mission->getTimeLeftFeeback() <= -1 && (isset($complaint) && $complaint->solved == Complaint::SOLVED)) {
            $note = Yii::t('texts', 'STATUS_SOLVED_COMPLAINT');
            $buttonDisplayed = false;
        } else {
            $note = 'ERROR';
        }

        return $buttonDisplayed;
    }

    public static function getNote($mission, $complaint) {

        $status = $mission->getCompletionStatus();

        $note = '';
        $buttonDisplayed = false;
        if ($status == Mission::NOT_STARTED && $complaint == null) {
            $note = Yii::t('texts', 'STATUS_VISIT_NOT_STARTED');
            $buttonDisplayed = false;
        } elseif ($mission->getTimeLeftFeeback() > -1 && $complaint == null) {
            $note = Yii::t('texts', 'NOTE_YOU_CAN_WRITE_A_COMPLAINT_UP_TO', array('{daysToGiveFeedback}' => BusinessRules::getDelayToGiveFeedbackInDays())) . $mission->getTimeLeftFeeback();
            $buttonDisplayed = true;
        } elseif (isset($complaint) && $complaint->solved == Complaint::UNSOLVED) {
            $note = Yii::t('texts', 'STATUS_OPENED_COMPLAINT');
            $buttonDisplayed = true;
        } elseif ($mission->getTimeLeftFeeback() > -1 && isset($complaint) && $complaint->solved == Complaint::SOLVED) {
            $note = Yii::t('texts', 'STATUS_SOLVED_COMPLAINT');
            $buttonDisplayed = true;
        } elseif ($mission->getTimeLeftFeeback() <= -1 && $complaint == null) {
            $note = Yii::t('texts', 'NOTE_IT_IS_TOO_LATE_TO_OPEN_A_COMPLAINT');
            $buttonDisplayed = false;
        } elseif ($mission->getTimeLeftFeeback() <= -1 && (isset($complaint) && $complaint->solved == Complaint::SOLVED)) {
            $note = Yii::t('texts', 'STATUS_SOLVED_COMPLAINT');
            $buttonDisplayed = false;
        } else {
            $note = 'ERROR';
        }

        return '<b>' . $note . '</b>';
    }

    /**
     * if current user can reply to a post created by the opposite user (if there is at least one visible post)
     * 
     * @param type $user 1 or 2
     * @return type boolean
     */
    public function isOnePostVisibleFor($user) {

        if ($user == Constants::USER_CLIENT) {

            $createdBy = Constants::USER_CARER;
        } else {
            $createdBy = Constants::USER_CLIENT;
        }


        $sql = "SELECT COUNT(cp.id) FROM tbl_complaint_post cp LEFT JOIN tbl_complaint c ON cp.id_complaint = c.id " .
                " WHERE c.id=" . $this->id .
                " AND cp.visible_by IN (" . Constants::USER_ALL . ',' . $user . ')' .
                " AND c.created_by=" . $createdBy;


        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num > 0;
    }

    public function getLastPostAuthor() {

        $posts = $this->complaintPosts;
        $lastPost = $posts[count($posts) - 1];
        $text = $lastPost->text;
        $author = $lastPost->author;
        return $lastPost->author;
    }

    public function getLastPostVisibleBy() {

        $posts = $this->complaintPosts;
        $lastPost = $posts[count($posts) - 1];
        return $lastPost->visible_by;
    }

}