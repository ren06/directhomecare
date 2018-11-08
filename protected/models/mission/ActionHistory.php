<?php

/**
 * This is the model class for table "tbl_action_history".
 *
 * The followings are the available columns in table 'tbl_action_history':
 * @property integer $id_carer
 * @property integer $action
 * @property integer $id_mission
 * @property string $created
 * @property string $modified
 * @property integer $triggered_by
 * 
 * The followings are the available model relations:
 * @property Carer $idCarer
 * @property Mission $idMission
 */
class ActionHistory extends CActiveRecord {

    const CARER_APPLY = 1;
    const CARER_CANCEL_APPLY = 2;
    const ADMIN_SELECT_CARER = 3;
     const ADMIN_ASSIGN_CARER = 3;
    const CARER_CONFIRM_SELECTED = 4;
    const CARER_CANCEL_SELECTED = 5;
    const CARER_CANCEL_ASSIGNED = 6;
    const ADMIN_CARER_CONFIRM_LATE = 7;
    const CLIENT_CANCEL_SERVICE = 8;
    const CLIENT_ABORT_SERVICE = 9;
    const CARER = 1;
    const ADMIN = 2;
    const CLIENT = 3;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ActionHistory the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_action_history';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_carer, action, id_mission', 'required'),
            array('id_carer, action, id_mission', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_carer, action, id_mission, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idCarer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
            'idMission' => array(self::BELONGS_TO, 'LiveInMission', 'id_mission'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id_carer' => 'Id Carer',
            'action' => 'Action',
            'id_mission' => 'Id Mission',
            'timestamp' => 'Timestamp',
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

        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('action', $this->action);
        $criteria->compare('id_mission', $this->id_mission);
        $criteria->compare('timestamp', $this->timestamp, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function create($carerId, $missionId, $action, $triggeredBy) {

        try {
            $model = new ActionHistory();
            $model->id_carer = $carerId;
            $model->id_mission = $missionId;
            $model->action = $action;
            $model->triggered_by = $triggeredBy;
            $model->save();
        } catch (CException $e) {
            
        }
    }

    public function getActions() {
        return array(
            self::CARER_APPLY => 'Applied',
            self::CARER_CANCEL_APPLY => 'Cancelled Application',
            self::CARER_CONFIRM_SELECTED => 'Confirmed Selection',
            self::CARER_CANCEL_SELECTED => 'Cancelled Selection',
            self::CARER_CANCEL_ASSIGNED => 'Cancelled Assignment',
        );
    }

    public function getActionLabel() {

        $options = $this->getStatuses();
        return $options[$this->action];
    }

}