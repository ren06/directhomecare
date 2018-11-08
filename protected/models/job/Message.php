<?php

/**
 * This is the model class for table "tbl_message".
 *
 * The followings are the available columns in table 'tbl_message':
 * @property integer $id
 * @property integer $id_conversation
 * @property integer $type
 * @property integer $author
 * @property integer $is_read
 * @property integer $visible_by
 * @property integer $id_job
 * @property string $message
 * @property integer $id_booking
 * @property string $created
 * @property string $modified
 *
 * The followings are the available model relations:
 * @property Booking $idBooking
 * @property Conversation $idConversation
 * @property Job $idJob
 */
class Message extends ActiveRecord {

    const TYPE_MESSAGE = 0;
    const TYPE_JOB_POSTING = 1;
    const TYPE_BOOKING = 2;
    const TYPE_ADMIN = 3;
    const TYPE_PREVIOUS_CUSTOMER = 4;
    const AUTHOR_CLIENT = Constants::USER_CLIENT;
    const AUTHOR_CARER = Constants::USER_CARER;
    const AUTHOR_ADMIN = Constants::USER_ADMIN;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Message the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_conversation, type, author', 'required'),
            array('id_conversation, type, author, is_read, visible_by, id_job, id_booking', 'numerical', 'integerOnly' => true),
            array('message', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_conversation, type, author, id_job, message, id_booking, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'booking' => array(self::BELONGS_TO, 'Booking', 'id_booking'),
            'conversation' => array(self::BELONGS_TO, 'Conversation', 'id_conversation'),
            'job' => array(self::BELONGS_TO, 'Job', 'id_job'),
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
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_conversation' => 'Id Conversation',
            'type' => 'Type',
            'author' => 'Author',
            'is_read' => 'Is Read',
            'id_job' => 'Id Job',
            'visible_by' => 'Visible By',
            'message' => 'Message',
            'id_booking' => 'Id Booking',
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
        $criteria->compare('id_conversation', $this->id_conversation);
        $criteria->compare('type', $this->type);
        $criteria->compare('author', $this->author);
        $criteria->compare('is_read', $this->is_read);
        $criteria->compare('visible_by', $this->visible_by);
        $criteria->compare('id_job', $this->id_job);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('id_booking', $this->id_booking);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getDateTime() {

        return Calendar::convert_DBDateTime_DisplayDateTimeTextConcise($this->created);
    }

    public static function getUnreadMessageCount($user, $id) {
        
        if ($user == Constants::USER_CLIENT) {
            $author = Constants::USER_CARER . ',' .  Constants::USER_ADMIN;           
        } else {
            $author = Constants::USER_CLIENT . ',' .  Constants::USER_ADMIN;           
        }
        
        $visibleBy = Constants::USER_ALL . ',' .  $user;

        $sql = "SELECT COUNT(m.id) FROM tbl_message m INNER JOIN tbl_conversation c ON c.id = m.id_conversation " .
                " WHERE m.author IN ($author) AND m.is_read = 0 AND m.visible_by IN ($visibleBy) "; 

        if ($user == Constants::USER_CLIENT) {
            $sql .= " AND c.id_client = $id ";
        } elseif ($user == Constants::USER_CARER) {
            $sql .= " AND c.id_carer = $id ";
        } else {
            assert(false);
        }

        $num = (int) Yii::app()->db->createCommand($sql)->queryScalar();

        return $num;
    }

    public static function getUnreadMessageCountText($user, $id) {
        $count = Message::getUnreadMessageCount($user, $id);
        if ($count > 0) {
            $countText = ' (' . $count . ')';
        } else {
            $countText = '';
        }

        return $countText;
    }

    public function isNew($viewer) {

        if ($viewer != $this->author) {

            if ($this->is_read == 0) {

                $this->is_read = 1;
                $this->save(false);

                return ' | <b>New!</b>';
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

}