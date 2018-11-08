<?php

/**
 * This is the model class for table "tbl_complaint_post".
 *
 * The followings are the available columns in table 'tbl_complaint_post':
 * @property integer $id
 * @property integer $id_complaint
 * @property integer $author
 * @property integet $visible_by 
 * @property string $text
 * @property string $created
 * @property string $modified
 *
 */
class ComplaintPost extends CActiveRecord {

    const AUTHOR_CARER = Constants::USER_CARER;
    const AUTHOR_CLIENT = Constants::USER_CLIENT;
    const AUTHOR_ADMIN = Constants::USER_ADMIN;
    const VISIBLE_BY_CARER = Constants::USER_CARER;
    const VISIBLE_BY_CLIENT = Constants::USER_CLIENT;
    const VISIBLE_BY_ADMIN = Constants::USER_ADMIN;
    const VISIBLE_BY_ALL = Constants::USER_ALL;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ComplaintPost the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_complaint_post';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_complaint, author, visible_by, text', 'required'),
            array('id_complaint, author, visible_by', 'numerical', 'integerOnly' => true),
            array('text', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_complaint, author, text, visible_by, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'complaint' => array(self::BELONGS_TO, 'Complaint', 'id_complaint'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_complaint' => 'Id Client Complaint',
            'author' => 'Author',
            'text' => 'Text',
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
        $criteria->compare('id_complaint', $this->id_complaint);
        $criteria->compare('author', $this->author);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function createPost($complaintId, $author, $visibleBy, $text) {

        $post = new ComplaintPost();
        $post->author = $author;
        $post->text = $text;
        $post->id_complaint = $complaintId;
        $post->visible_by = $visibleBy;
        $post->validate();
        $errors = $post->getErrors();
        $post->save();

        return $post;
    }

    public function authorLabel() {

        if ($this->author == ComplaintPost::AUTHOR_CLIENT) {
            echo 'Reply by client';
        } elseif ($this->author == ComplaintPost::AUTHOR_CARER) {
            echo 'Reply by carer';
        } else {
            echo 'Reply by admin';
        }
    }

    public function visibleByLabel() {

        switch ($this->visible_by) {

            case ComplaintPost::VISIBLE_BY_ADMIN;
                echo 'Visible by admin';
                break;
            case ComplaintPost::VISIBLE_BY_ALL;
                echo 'Visible by carer and client';
                break;
            case ComplaintPost::VISIBLE_BY_CARER;
                echo 'Visible by carer';
                break;
            case ComplaintPost::VISIBLE_BY_CLIENT;
                echo 'Visible by client';
                break;
        }
    }

}