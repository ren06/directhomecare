<?php

/**
 * This is the model class for table "tbl_mission_carers".
 *
 * The followings are the available columns in table 'tbl_mission_carers':
 * @property integer $id
 * @property integer $id_mission
 * @property integer $id_applying_carer
 * @property integer $status
 * @property string $created
 * @property string $modified
 * @property integer $discarded
 *
 * The followings are the available model relations:
 * @property Mission $idMission
 * @property Carer $idApplyingCarer
 */
class MissionCarers extends ActiveRecord {

    const UNAPPLIED = 0;
    const APPLIED = 1;
    const SELECTED = 2;
    const ASSIGNED = 3;
    const NOT_SELECTED = 4;
    const CONFIRM_SELECTION_LATE = 5;
    const CANCEL_ASSIGNED = 6;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MissionCarers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_mission_carers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_mission, id_applying_carer, status', 'required'),
            array('id_mission, id_applying_carer, status, discarded', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_mission, id_applying_carer, status, created, modified, discarded', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'mission' => array(self::BELONGS_TO, 'Mission', 'id_mission'),
            'applyingCarer' => array(self::BELONGS_TO, 'Carer', 'id_applying_carer'),
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
            'id_mission' => 'Id Mission',
            'id_applying_carer' => 'Id Applying Carer',
            'status' => 'Status',
            'created' => 'Created',
            'modified' => 'Modified',
            'discarded' => 'Discarded',
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
        $criteria->compare('id_mission', $this->id_mission);
        $criteria->compare('id_applying_carer', $this->id_applying_carer);
        $criteria->compare('status', $this->status);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);
        $criteria->compare('discarded', $this->discarded);


//        $sql = "SELECT * FROM tbl_mission t1 LEFT JOIN tbl_mission_live_in t2 " .
//                "ON t1.id = t2.id_mission";
//
//
//        $count = Yii::app()->db->createCommand($sql)->queryScalar();
//
//        $dataProvider = new CSqlDataProvider($sql, array(
//                    'totalItemCount' => $count,
//                    'sort' => array(
//                        'attributes' => array(
//                            'id',
//                        ),
//                    ),
//                    'pagination' => array(
//                        'pageSize' => 10,
//                    ),
//                ));
//        
//        return $dataProvider;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id_mission DESC',
            ),
            'pagination' => array(
                'pageSize' => 20,
            ),
        ));
    }

    public function searchByMission($missionId) {

        $criteria = new CDbCriteria();
        $criteria->compare('id_mission', $missionId);
        $criteria->order = 'id_applying_carer ASC';

        return new CActiveDataProvider('MissionCarers', array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Does update, no emails sent
     * 
     * @param type $missionId
     * @param type $carerId
     */
    public static function setCarerSelected($missionId, $carerId) {

        $result = Mission::setCarerMissionStatus($carerId, $missionId, MissionCarers::SELECTED);

        ActionHistory::create($carerId, $missionId, ActionHistory::ADMIN_SELECT_CARER, ActionHistory::ADMIN);
    }

    public static function setCarerAssigned($missionId, $carerId) {

        $result = Mission::setCarerMissionStatus($carerId, $missionId, MissionCarers::ASSIGNED);

        ActionHistory::create($carerId, $missionId, ActionHistory::ADMIN_ASSIGN_CARER, ActionHistory::ADMIN);
    }

    public static function changeCarerSelected($missionId, $carerId) {

        //set current carer to late
        $selectedCarerId = Mission::getSelectedCarerId($missionId);

        Mission::setCarerMissionStatus($selectedCarerId, $missionId, MissionCarers::CONFIRM_SELECTION_LATE);

        ActionHistory::create($selectedCarerId, $missionId, ActionHistory::ADMIN_CARER_CONFIRM_LATE, ActionHistory::ADMIN);

        //set new carer to selected
        self::setCarerSelected($missionId, $carerId);

        //send email
        $carer = Carer::loadModelAdmin($carerId);
        Emails::sendToCarer_SelectedForMission($carer);
    }

    public static function changeCarerAssigned($missionId, $newCarerAssignedId) {

        $transaction = Yii::app()->db->beginTransaction();
        try {

            //get mission
            $mission = Mission::loadModelAdmin($missionId);
            //get currently assigned carer
            $currentAssignedCarer = $mission->getAssignedCarer();
            //de-assign carer by setting him to not selected
            Mission::setCarerMissionStatus($currentAssignedCarer->id, $missionId, MissionCarers::NOT_SELECTED);
            // send email that he was de-assigned
            Emails::sendToCarer_DeAssignedMission($currentAssignedCarer);

            //set new carer to assigned
            Mission::setCarerMissionStatus($newCarerAssignedId, $missionId, MissionCarers::ASSIGNED);

            //make sure it's not discarded - carer may have done it after not being selected
            Mission::setCarerMissionDiscarded($newCarerAssignedId, $missionId, false);

            //send email to newly assigned carer
            $newCarerAssigned = Carer::loadModelAdmin($newCarerAssignedId);
            Emails::sendToCarer_AssignedDirectlyMission($newCarerAssigned);

            $transaction->commit();

            Yii::app()->user->setFlash('success', "Carer successfully assigned");
        } catch (CException $e) {

            $transaction->rollBack();

            Yii::app()->user->setFlash('error', 'An error occured: ' . $e->getMessage());
        }
    }

    public function getMissionStatus() {

        $sql = "SELECT status FROM tbl_mission WHERE id=" . $this->id_mission;
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        return $row['status'];
    }

    public function getMission() {

        $sql = "SELECT id FROM tbl_mission WHERE id=" . $this->id_mission;
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        return Mission::loadModel($row['id']);
    }

    public function getStatusOptions() {

        return array(
            self::APPLIED => 'Applied',
            self::SELECTED => 'Selected',
            self::ASSIGNED => 'Assigned',
            self::NOT_SELECTED => 'Not Selected',
        );
    }

    public static function getAllStatusOptions() {
        return array(
            self::UNAPPLIED => 'Unapplied',
            self::APPLIED => Yii::t('texts', 'STATUS_APPLIED'),
            self::SELECTED => 'Selected',
            self::ASSIGNED => 'Assigned',
            self::NOT_SELECTED => Yii::t('texts', 'STATUS_SORRY_YOU_WERE_NOT_SELECTED'),
            self::CONFIRM_SELECTION_LATE => Yii::t('texts', 'STATUS_TOO_LATE_TO_CONFIRM'),
            self::CANCEL_ASSIGNED => 'Cancel Assigned',
        );
    }

    public static function getAllStatusTooltipOptions() {
        return array(
            self::UNAPPLIED => 'Unapplied',
            self::APPLIED => Yii::t('texts', 'ALT_YOU_HAVE_APPLIED_TO_THIS_MISSION_PLEASE_WAIT'),
            self::SELECTED => 'Selected',
            self::ASSIGNED => 'Assigned',
            self::NOT_SELECTED => Yii::t('texts', 'ALT_SORRY_ANOTHER_PERSON_WAS_SELECTED'),
            self::CONFIRM_SELECTION_LATE => Yii::t('texts', 'ALT_YOU_WERE_TOO_LATE_TO_CONFIRM'),
            self::CANCEL_ASSIGNED => 'Cancel Assigned',
        );
    }

    public function getStatusLabel() {

        $options = $this->getAllStatusOptions();
        return $options[$this->status];
    }

    public function getStatusTooltip() {

        $options = $this->getAllStatusTooltipOptions();
        return $options[$this->status];
    }

    /**
     * 
     * See the "mission relations" between a carer and a client
     * 
     * Used to see for instance if a given carer was ever assigned to a client through a mission
     * 
     * @param type $carerId
     * @param type $clientId
     * @param type $relationStatus
     * @param type $missionStatus
     */
    public static function isRelationExists($carerId, $clientId, $relationStatus, $missionStatus = Mission::ACTIVE) {

        $sql = "SELECT mc.id_mission FROM tbl_mission_carers mc 
                INNER JOIN tbl_mission m ON m.id = mc.id_mission
                INNER JOIN tbl_booking b ON b.id = m.id_booking
                WHERE mc.status = $relationStatus 
                AND mc.id_applying_carer = $carerId 
                AND m.status = $missionStatus 
                AND b.id_client = $clientId";


        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public static function automaticSelected($missionId, $carerId) {

        $model = new MissionCarers();
        $model->id_applying_carer = $carerId;
        $model->id_mission = $missionId;
        $model->status = MissionCarers::SELECTED;
        $model->save();
    }

}