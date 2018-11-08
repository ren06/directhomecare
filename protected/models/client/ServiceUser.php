<?php

/**
 * This is the model class for table "tbl_service_user".
 *
 * The followings are the available columns in table 'tbl_service_user':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $date_birth
 * @property integer $gender
 * @property string $note
 * 
 * The followings are the available model relations:
 * @property Booking[] $tblBookings
 * @property Client[] $tblClients
 * @property ServiceUserCondition[] $serviceUserConditions
 */
class ServiceUser extends ActiveRecord {

    const TYPE_MASTER_DATA = 0;
    const TYPE_TRANSACTIONAL_DATA = 1;

    /**
     * Returns the static model of the specified AR class.
     * @return ServiceUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_service_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('first_name, last_name, note', 'filter', 'filter' => 'strip_tags'),
            array('first_name, last_name, note', 'filter', 'filter' => 'trim'),
            array('first_name, last_name', 'required'),
            array('first_name, last_name', 'filter', 'filter' => array($this, 'filter_uc_lc')),
            array('gender', 'numerical', 'integerOnly' => true),
            array('first_name, last_name', 'length', 'max' => 50),
            array('note', 'length', 'max' => 500),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, first_name, last_name, date_birth, gender', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Check and filter methods
     */
    //First letter capital and rest lower case
    public function filter_uc_lc($value) {

        return ucwords(strtolower(trim($value)));
    }

    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            //NEW
            'bookings' => array(self::MANY_MANY, 'Booking', 'tbl_booking_service_user(id_service_user, id_booking)'),
            'clientServiceUsers' => array(self::HAS_MANY, 'ClientServiceUser', 'id_service_user'),
            'missions' => array(self::MANY_MANY, 'Mission', 'tbl_mission_service_user(id_service_user, id_mission)'),
            'conditions' => array(self::MANY_MANY, 'Condition', 'tbl_service_user_condition(id_service_user, id_condition)'),
            //OLD
            'bookingServiceUsers' => array(self::HAS_MANY, 'BookingServiceUser', 'id_service_user'),
            //'clientServiceUsers' => array(self::HAS_MANY, 'ClientServiceUser', 'id_service_user'),
            'missionServiceUsers' => array(self::HAS_MANY, 'MissionServiceUser', 'id_service_user'),
            //'serviceUserConditions' => array(self::MANY_MANY, 'Condition', 'tbl_service_user_condition(id_service_user, id_condition)'),
            'serviceUserConditions' => array(self::HAS_MANY, 'ServiceUserCondition', 'id_service_user'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'ID Client',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'date_birth' => 'Date of birth',
            'gender' => 'Gender',
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
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
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('date_birth', $this->date_birth, true);
        $criteria->compare('gender', $this->gender);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public static function deleteServiceUser($id, $clientId = null) {

        //delete from DB, then data is recreated (easiest way since user can add and delte service users, too hard to update)

        if ($clientId == null) {
            $clientId = Yii::app()->user->id;
        }

        //ClientServiceUser::model()->deleteAllByAttributes(array('id_client' => $clientId, 'id_service_user' => $id));
        ServiceUserCondition::deleteServiceUserConditions($id);
        ClientServiceUser::deleteClientServiceUser($id);
        ServiceUser::model()->deleteByPk($id);

        //delete live in request associations
    }

    public function getFullName() {

        return $this->first_name . ' ' . $this->last_name;
    }

    public function displayNameConditions() {

        $text = $this->fullName . '<br />' . $this->displayWorstConditions();

        return $text;
    }

    public function displayAgeGenderConditions($compact = false) {

        if ($compact) {
            $separator = '<br />';
        } else {
            $separator = ' - ';
        }

        $text = $this->ageGroupText . $separator . $this->genderText . '<br />' . $this->displayWorstConditions($compact = true);

        return $text;
    }

    public function displayAgeGenderConditionsActivities($separator = '') {
        //ByRC: modified in roder not to use the function displayAgeGenderConditions
        $separator = ' - ';
        $conditions = $this->serviceUserConditions;
        $mentalCondition = Condition::getWorstConditionText(Condition::TYPE_MENTAL, $conditions);
        $physicalCondition = Condition::getWorstConditionText(Condition::TYPE_PHYSICAL, $conditions);

        $text = $this->ageGroupText . $separator . $this->genderText . '<br /><span class="rc-note">' . Yii::t('texts', 'HEADER_PHYSICAL_CONDITION') . '&#58;&#160;</span>' . $physicalCondition . '<br /><span class="rc-note">' . Yii::t('texts', 'HEADER_MENTAL_CONDITION') . '&#58;&#160;</span>' . $mentalCondition;

        $text .= '<br /><span class="rc-note">' . Yii::t('texts', 'HEADER_ACTIVITY') . '&#58;</span><br />' . $this->displayActivities('<br />');

        return $text;
    }

    public function getPhysicalCondition() {
        //improve code
        $conditions = $this->conditions;

        foreach ($conditions as $condition) {

            if ($condition->type == Condition::TYPE_PHYSICAL) {
                return $condition;
            }
        }
        return null;
    }

    public function getMentalCondition() {

        //improve code
        $conditions = $this->conditions;

        foreach ($conditions as $condition) {

            if ($condition->type == Condition::TYPE_MENTAL) {
                return $condition;
            }
        }
        return null;
    }

    //array of ServiceUserConditionRequest
    public function getActivities() {

        $conditions = $this->conditions;

        $result = array();

        foreach ($conditions as $condition) {

            if ($condition->type == Condition::TYPE_ACTIVITY) {
                $result[] = $condition;
            }
        }

        return $result;
    }

    public function displayActivities($separator = '<br />') {

        $activities = $this->getActivities();

        $text = '';
        $i = 0;
        $max = count($activities);

        foreach ($activities as $activity) {

            $i++;
            $text .= $activity->getText($activity->name);

            if ($i < $max) {
                $text .= $separator;
            }
        }

        return $text;
    }

    public function displayAdmin() {

        $text = '';

        $text .= $this->fullName . '<br>';
        $text .= $this->getGenderText() . '<br>';
        $text .= $this->getAgeGroupText() . '<br>';
        $text .= $this->displayWorstConditions() . '<br>';
        $text .= $this->note . '<br>';

        return $text;
    }

    public function getWorstCondition($type) {

        return Condition::getWorstCondition($type, $this->serviceUserConditions);
    }

    public function displayWorstConditions($compact = false) {

        //array of ServiceUserConditionRequest
        $conditions = $this->serviceUserConditions;

        $id = $this->id;

        $mentalCondition = Condition::getWorstConditionText(Condition::TYPE_MENTAL, $conditions);
        $physicalCondition = Condition::getWorstConditionText(Condition::TYPE_PHYSICAL, $conditions);

        //TODO improve this with two relations one for mental and one for physical

        if ($compact) {
            $separator = '</br>';
        } else {
            $separator = ' - ';
        }

        $text = $mentalCondition . $separator . $physicalCondition;

        return $text;
    }

    public function getGenderText() {

        return ($this->gender == Constants::GENDER_MALE) ? 'Male' : 'Female';
    }

    public function getAge() {

        return Calendar::calculate_Age($this->date_birth);
    }

    public function getAgeGroup() {

        $ageGroups = Yii::app()->params['ageGroups'];

        $childrenMax = $ageGroups['children'][1];
        $youngAdultMax = $ageGroups['young_adult'][1];
        $adultMax = $ageGroups['adult'][1];

        $age = $this->getAge();

        if ($age < $childrenMax) {
            return AgeGroup::CHILDREN;
        } elseif ($age < $youngAdultMax) {
            return AgeGroup::YOUNG_ADULTS;
        } elseif ($age < $adultMax) {
            return AgeGroup::ADULTS;
        } else {
            return AgeGroup::ELDERLY;
        }
    }

    public function getAgeGroupText() {

        $values = AgeGroup::getAgeGroups();

        return $values[$this->getAgeGroup()];
    }

    public function setValidityDates() {

//       //get client id
//        $clientId = $this->clients[0]->id;
//        
//        $attributes = array('id_client' => $clientId, 'id_service_user' => $this->id);
//
//        $result = ClientServiceUser::model()->findByAttributes($attributes);
//
//        if (isset($result)) {
//
//            $currentRecord = $result[0];
//            $currentRecord->valid_to = Calendar::getDateTimeNow(self::FORMAT_DB);
//            $currentRecord->save();
//        }
//
//        $this->valid_from = Calendar::getDateTimeNow(self::FORMAT_DB);
//        $this->valid_to = self::INFINTY_DATE;
    }

    /**
     * 
     * Copy service user and their conditions
     * 
     * if type is master data, duplicated the entry in tbl_client_service_user
     * 
     * @param type $serviceUserId
     * @return type ServiceUser
     */
    public static function copy($serviceUserId, $type) {

        $serviceUserOriginal = ServiceUser::loadModel($serviceUserId);

        $newServiceUser = clone $serviceUserOriginal;
        unset($newServiceUser->id);
        $newServiceUser->isNewRecord = true;
        $newServiceUser->data_type = $type;
        $newServiceUser->save();

        $conditions = $serviceUserOriginal->serviceUserConditions;

        foreach ($conditions as $conditionOriginal) {

            $newCondition = clone $conditionOriginal;
            unset($newCondition->id);
            $newCondition->isNewRecord = true;
            $newCondition->id_service_user = $newServiceUser->id;
          
            $newCondition->save();
        }

        if ($type == self::TYPE_MASTER_DATA) {

            $result = Yii::app()->db->createCommand("select id_client from tbl_client_service_user where id_service_user = $serviceUserId ")->queryRow();

            $clientId = $result['id_client'];

            $newClientServiceUser = new ClientServiceUser();
            $newClientServiceUser->id_service_user = $newServiceUser->id;
            $newClientServiceUser->id_client = $clientId;
            $newClientServiceUser->data_type = $type;
            $newClientServiceUser->save();
        }

        return $newServiceUser;
    }

    public static function assignToBooking($serviceUserIds, $bookingId) {

        foreach ($serviceUserIds as &$serviceUserId) {

            //load existing service user (master data)
            //create a copy service user request
            $bookingServiceUser = new BookingServiceUser();
            $bookingServiceUser->id_booking = $bookingId;
            $bookingServiceUser->id_service_user = $serviceUserId;

            $bookingServiceUser->save();
        }
    }
    
    /**
     * 
     * Creates entries in tbl_mission_service_user
     * 
     * @param type $serviceUserIds
     * @param type $missionId
     */
    public static function assignToMission($serviceUserIds, $missionId) {

        foreach ($serviceUserIds as &$serviceUserId) {

            $missionServiceUser = new MissionServiceUser();
            $missionServiceUser->id_mission = $missionId;
            $missionServiceUser->id_service_user = $serviceUserId;

            $missionServiceUser->save();
        }
    }

    /**
     * Used for booking creation
     * 
     * @param type $serviceUserIds
     * @param type $missionId
     */
    public static function copyAndAssignToMission($serviceUserIds, $missionId) {

        foreach ($serviceUserIds as &$serviceUserId) {

            $newServiceUser = self::copy($serviceUserId);

            //load existing service user (master data)
            //create a copy service user request
            $missionServiceUser = new MissionServiceUser();
            $missionServiceUser->id_mission = $missionId;
            $missionServiceUser->id_service_user = $newServiceUser->id;

            $missionServiceUser->save();
        }
    }

    public function isUsedBooking() {

        return (count($this->bookingServiceUsers) > 0);
    }

    public function delete() {


//            'clientServiceUsers' => array(self::HAS_MANY, 'ClientServiceUser', 'id_service_user'),
//            'missionServiceUsers' => array(self::HAS_MANY, 'MissionServiceUser', 'id_service_user'),
//            //'serviceUserConditions' => array(self::MANY_MANY, 'Condition', 'tbl_service_user_condition(id_service_user, id_condition)'),
//            'serviceUserConditions' => array(self::HAS_MANY, 'ServiceUserCondition', 'id_service_user'),


        parent::delete();
    }

    public function updateNote() {

        $ids = array();

        $bookings = $this->bookings;

        foreach ($bookings as $booking) {

            foreach ($booking->missions as $mission) {

                $serviceUsers = $mission->serviceUsers;

                foreach ($serviceUsers as $serviceUser) {

                    if ($serviceUser->first_name == $this->first_name && $serviceUser->last_name == $this->last_name && $serviceUser->date_birth == $this->date_birth) {

                        $ids[] = $serviceUser->id;
                    }
                }
            }
        }

        $ids = array_unique($ids);

        if (!empty($ids)) {

            $idsCondition = implode(',', $ids);

            $note = $this->note;

            $sql = "UPDATE tbl_service_user SET note='$note'
                WHERE id IN( $idsCondition)";

            Yii::app()->db->createCommand($sql)->execute();
        }
    }

//    public static function copy($serviceUserId, $type) {
//
//        $serviceUser = self::loadModel($serviceUserId);
//
//        //copy service user
//        $newServiceUser = clone $serviceUser;
//        $newServiceUser->data_type = $type;
//        unset($newServiceUser->id);
//        $newServiceUser->isNewRecord = true;
//
//        $newServiceUser->insert();
//
//        //copy conditions relations
//        //copy client relation
//
//        return $newServiceUser;
//    }
}