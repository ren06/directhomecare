<?php
//
///**
// * This is the model class for table "tbl_carer_feedback".
// *
// * The followings are the available columns in table 'tbl_carer_feedback':
// * @property integer $id
// * @property integer $id_client
// * @property integer $id_carer
// * @property integer $id_mission
// * @property integer $type
// * @property string $created
// * @property string $modified
// * @property string $text
// * @property integer $author
// * @property integer $read_only
// * 
// * The followings are the available model relations:
// * @property Carer $idCarer
// * @property Client $idClient
// * @property Mission $idMission
// */
//class CarerFeedback extends CActiveRecord {
//    const FINISHED_OK = 1;
//    const FINISHED_FAIR = 2;
//    const FINISHED_NOT_GOOD = 3;
//    
//    const FINISHED_COMPLAIN = 10;
//    const STARTED_COMPLAIN = 11;
//    const ADMIN_ANSWER = 10;
//    
//    const AUTHOR_CLIENT = 1;
//    const AUTHOR_ADMIN = 2;
//
//    /**
//     * Returns the static model of the specified AR class.
//     * @param string $className active record class name.
//     * @return CarerFeedback the static model class
//     */
//    public static function model($className=__CLASS__) {
//        return parent::model($className);
//    }
//
//    /**
//     * @return string the associated database table name
//     */
//    public function tableName() {
//        return 'tbl_carer_feedback';
//    }
//
//    /**
//     * @return array validation rules for model attributes.
//     */
//    public function rules() {
//        // NOTE: you should only define rules for those attributes that
//        // will receive user inputs.
//        return array(
//            array('id_client, id_carer, id_mission, type', 'required'),
//            array('id_client, id_carer, id_mission, type', 'numerical', 'integerOnly' => true),
//            array('text', 'length', 'max' => 255),
//            // The following rule is used by search().
//            // Please remove those attributes that should not be searched.
//            array('id, id_client, id_carer, id_mission, type, text', 'safe', 'on' => 'search'),
//        );
//    }
//
//    /**
//     * @return array relational rules.
//     */
//    public function relations() {
//        // NOTE: you may need to adjust the relation name and the related
//        // class name for the relations automatically generated below.
//        return array(
//            'carer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
//            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
//            'mission' => array(self::BELONGS_TO, 'Mission', 'id_mission'),
//        );
//    }
//
//    /**
//     * @return array customized attribute labels (name=>label)
//     */
//    public function attributeLabels() {
//        return array(
//            'id' => 'ID',
//            'id_client' => 'Id Client',
//            'id_carer' => 'Id Carer',
//            'id_mission' => 'Id Mission',
//            'type' => 'Type',
//            'text' => 'Text',
//        );
//    }
//
//    public function behaviors() {
//        return array(
//            'CTimestampBehavior' => array(
//                'class' => 'zii.behaviors.CTimestampBehavior',
//                'createAttribute' => 'created',
//                'updateAttribute' => 'modified',
//            )
//        );
//    }
//
//    /**
//     * Retrieves a list of models based on the current search/filter conditions.
//     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
//     */
//    public function search() {
//        // Warning: Please modify the following code to remove attributes that
//        // should not be searched.
//
//        $criteria = new CDbCriteria;
//
//        $criteria->compare('id', $this->id);
//        $criteria->compare('id_client', $this->id_client);
//        $criteria->compare('id_carer', $this->id_carer);
//        $criteria->compare('id_mission', $this->id_mission);
//        $criteria->compare('type', $this->type);
//        $criteria->compare('text', $this->text, true);
//
//        return new CActiveDataProvider($this, array(
//                    'criteria' => $criteria,
//                ));
//    }
//
//    public static function feedbackOptions() {
//
//        return array(self::FINISHED_OK => 'Everything went fine', self::FINISHED_FAIR => 'There were minor problems', self::FINISHED_NOT_GOOD => 'It did not go well');
//    }
//
//    public static function getComplaints($idMission, $clientId) {
//
//        $criteria = new CDbCriteria;
//
//        $criteria->compare('id_mission', $idMission);
//        $criteria->compare('id_client', $clientId);//security
//        $criteria->addInCondition('type', array(self::STARTED_COMPLAIN, self::ADMIN_ANSWER));
//        $criteria->order = 'created';
//
////                $criteria = new CDbCriteria();
////        $criteria->join = 'LEFT JOIN tbl_mission_carers ON t.id = tbl_mission_carers.id_mission';
////        $criteria->condition = 'tbl_mission_carers.id_applying_carer = ' . $carerId . ' AND tbl_mission_carers.status IN ('
////                . MissionCarers::ASSIGNED . ')' .
////                ' AND tbl_mission_carers.discarded = 0';
//        
//
//        $provider = new CActiveDataProvider('CarerFeedback', array(
//                    'criteria' => $criteria, ));
//        
//        return $provider->getData();
//    }
//
//}