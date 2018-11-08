<?php

/**
 * This is the model class for table "tbl_conversation".
 *
 * The followings are the available columns in table 'tbl_conversation':
 * @property integer $id
 * @property integer $id_carer
 * @property integer $id_client
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Message[] $messages
 */
class Conversation extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Conversation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_conversation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_carer, id_client', 'required'),
            array('id_carer, id_client', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_carer, id_client, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'messages' => array(self::HAS_MANY, 'Message', 'id_conversation', 'order' => 'messages.created DESC',),
            'carer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_carer' => 'Id Carer',
            'id_client' => 'Id Client',
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
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function createFirstMessageForCarer($job) {

        $message = new Message();
        $message->id_conversation = $this->id;
        $message->type = Message::TYPE_JOB_POSTING;
        $message->author = Message::AUTHOR_CLIENT;
        $message->id_job = $job->id;
        $message->message = $job->message;
        $message->is_read = 0;
        $message->visible_by = Constants::USER_ALL;

        $message->validate();
        $err = $message->errors;
        $message->save();

        //to update modified timestamp
        $this->save();
    }

    public function createMessage($text, $author, $type, $visibleBy) {

        $message = new Message();
        $message->id_conversation = $this->id;
        $message->type = $type;
        $message->author = $author;
        $message->message = $text;
        $message->is_read = 0;
        $message->visible_by = $visibleBy;

        $message->save();

        //to update modified timestamp
        $this->save();

        return $message;
    }

    public function createBookingMessage($booking) {

        //$text = $this->client->first_name . ' wants to hire you. Please confirm!';
        $text = 'I want to hire you. Please confirm !';
        $message = new Message();
        $message->id_conversation = $this->id;
        $message->type = Message::TYPE_BOOKING;
        $message->author = Message::AUTHOR_CLIENT;
        $message->is_read = 0;
        $message->visible_by = Constants::USER_ALL;
        $message->id_job = null;
        $message->id_booking = $booking->id;

        $message->message = $text;

        $message->validate();
        $err = $message->errors;
        $message->save();

        //to update modified timestamp
        $this->save();
    }

    public static function getConversation($clientId, $carerId) {

        $sql = "SELECT * FROM tbl_conversation c WHERE c.id_carer = $carerId AND c.id_client = $clientId ";

        return self::model()->findBySql($sql);
    }

    public function getLastMessage($viewer) {

        if ($viewer == Constants::USER_CARER) {
            $clause = " m.visible_by IN (2, 4)";
        } else {
            $clause = " m.visible_by IN (1, 4)";
        }

        $conversationId = $this->id;

        $sql = "SELECT m.* FROM tbl_message m INNER JOIN tbl_conversation c ON m.id_conversation = c.id
                   WHERE $clause AND c.id = $conversationId ORDER BY m.created DESC LIMIT 1             
                  ";

        return Message::model()->findBySql($sql);
    }

    public function isVisibleForClient() {

        //visible for client if at least one carer message

        $messages = $this->messages;

        foreach ($messages as $message) {

            $author = $message->author;

            if ($author == Message::AUTHOR_CARER) {
                return true; //if at least one reply from carer
            }
            elseif($author == Message::AUTHOR_CLIENT){
                
                if($message->type == Message::TYPE_BOOKING){
                    return true; //if at least one booking message (to handle legacy)
                }
            }                
        }

        return false;
    }

    public function getUnReadMessageeCount($user) {

        if ($user == Constants::USER_CLIENT) {
            $author = Constants::USER_CARER . ',' . Constants::USER_ADMIN;
        } else {
            $author = Constants::USER_CLIENT . ',' . Constants::USER_ADMIN;
        }

        $visibleBy = Constants::USER_ALL . ',' . $user;

        $sql = "SELECT COUNT(m.id) FROM tbl_message m WHERE m.id_conversation = " . $this->id .
                " AND m.author IN ($author) AND m.is_read = 0 AND m.visible_by IN ($visibleBy)";

        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num;
    }

    public function getUnReadMessageeCountText($user) {

        $count = self::getUnReadMessageeCount($user);

        if ($count > 0) {
            return '<b>' . $count . ' new</b>';
        } else {
            return '';
        }
    }

}