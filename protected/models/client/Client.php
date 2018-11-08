<?php

/**
 * This is the model class for table "tbl_client".
 *
 * The followings are the available columns in table 'tbl_client':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email_address
 * @property string $password
 * @property string $date_birth
 * @property string $mobile_phone
 * @property integer $wizard_completed
 * @property string $created
 * @property string $modified
 * @property string $terms_conditions
 *
 * The followings are the available model relations:
 * @property Address[] $tblAddresses
 * @property LiveInRequest[] $liveInRequests
 * @property ServiceLocation[] $serviceLocations
 * @property ServiceUser[] $serviceUsers
 */
class Client extends ActiveRecord {
    //const SCENARIO_DISPLAY_CLIENT = 'displayClient';

    const SCENARIO_CREATE_CLIENT_EMAIL = 'createClientEmail';
    const SCENARIO_CREATE_CLIENT_ALL = 'createClientAll';
    const SCENARIO_UPDATE_CLIENT = 'updateClient';
    const SCENARIO_LOGIN_CLIENT = 'loginClient';
    const SCENARIO_PAYMENT_CLIENT = 'paymentClient';

    public $repeat_password;
    public $repeat_email_address;

    /**
     * Returns the static model of the specified AR class.
     * @return Client the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_client';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('first_name, last_name, email_address, password, repeat_password, date_birth, mobile_phone', 'filter', 'filter' => 'strip_tags'),
            array('first_name, last_name, email_address, password, repeat_password, date_birth, mobile_phone', 'filter', 'filter' => 'trim'),
            array('email_address, password, repeat_password, first_name, last_name', 'required', 'on' => self::SCENARIO_CREATE_CLIENT_ALL),
            array('email_address', 'required', 'on' => self::SCENARIO_CREATE_CLIENT_EMAIL),
            array('first_name, last_name', 'filter', 'filter' => array($this, 'filter_uc_lc')),
            array('password', 'length', 'max' => 40, 'on' => array(self::SCENARIO_CREATE_CLIENT_ALL)),
            array('email_address', 'filter', 'filter' => 'strtolower'),
            array('email_address', 'filter', 'filter' => 'trim'),
            array('email_address', 'email'),
            array('email_address', 'check_email_address', 'on' => array(self::SCENARIO_CREATE_CLIENT_ALL)),
            array('terms_conditions', 'required', 'requiredValue' => 1, 'message' => Yii::t('texts', 'ERROR_PLEASE_READ_AND_ACCEPT_THE_TERMS'), 'on' => self::SCENARIO_PAYMENT_CLIENT),
            //array('date_birth', 'required', 'message' => Yii::t('texts', 'ERROR_ENTER_A_VALID_DATE_OF_BIRTH'), 'on' => array(self::SCENARIO_CREATE_CLIENT, self::SCENARIO_UPDATE_CLIENT)),
            array('first_name, last_name, email_address', 'required', 'on' => self::SCENARIO_UPDATE_CLIENT),
            array('email_address, password', 'required', 'on' => self::SCENARIO_LOGIN_CLIENT),
            array('email_address', 'check_change_email_address', 'on' => self::SCENARIO_UPDATE_CLIENT),
            array('first_name, last_name, mobile_phone', 'required', 'on' => self::SCENARIO_PAYMENT_CLIENT),
            array('first_name, last_name', 'length', 'max' => 50),
            array('email_address', 'length', 'max' => 80),
            array('email_address', 'email'),
            array('wizard_completed', 'safe'),
            //array('mobile_phone', 'required', 'on' => self::SCENARIO_UPDATE_CLIENT),
            array('mobile_phone', 'length', 'max' => 16), //, 'on' => self::SCENARIO_UPDATE_CLIENT),
            array('mobile_phone', 'match', 'pattern' => '/^\(?0( *\d\)?){9,10}$/', 'message' => Yii::t('texts', 'ERROR_ENTER_A_VALID_PHONE_NUMBER')),
            // Please remove those attributes that should not be searched.
            array('id, first_name, last_name, email_address, password, date_birth, wizard_completed, terms_conditions', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Check and filter methods
     */
    //First letter capital and rest lower case
    public function filter_uc_lc($value) {

        return ucwords(strtolower(trim($value)));
    }

    public function check_email_address($attribute, $params) {

        if (DBServices::emailAddressExists($this->email_address) == true) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_THIS_EMAIL_ADDRESS_IS_REGISTERED_PLEASE_LOGIN'));
        }
    }

    public function check_change_email_address($attribute, $params) {

        $dbClient = Client::loadModel($this->id);

        $newEmail = $this->email_address;
        $existingEmail = $dbClient->email_address;

        if ($newEmail != $existingEmail) {

            $person = DBServices::getEmailAlreadyExist($this->email_address);

            if (isset($person)) {
                $this->addError($attribute, Yii::t('texts', 'ERROR_THIS_EMAIL_ADDRESS_IS_REGISTERED_PLEASE_LOGIN'));
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bookings' => array(self::HAS_MANY, 'Booking', 'id_client'),
            'complaints' => array(self::HAS_MANY, 'Complaint', 'id_client'),
            'clientLocations' => array(self::MANY_MANY, 'Address', 'tbl_client_location_address(id_client, id_address)'),
            'serviceUsers' => array(self::MANY_MANY, 'ServiceUser', 'tbl_client_service_user(id_client, id_service_user)'), //TODO USE
            'clientTransactions' => array(self::HAS_MANY, 'ClientTransaction', 'id_client'),
            'creditCards' => array(self::HAS_MANY, 'CreditCard', 'id_client'),
            'carerSearchCriteria' => array(self::HAS_MANY, 'ClientCarerSearchCriteria', 'id_client'),
            'conversations' => array(self::HAS_MANY, 'Conversation', 'id_client', 'order' => 'conversations.modified DESC'),
            'referrals' => array(self::HAS_MANY, 'ClientReferral', 'id_client', 'order' => 'id ASC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {

        $labels = array(
            'id' => 'ID',
            'first_name' => Yii::t('texts', 'LABEL_FIRST_NAME'),
            'last_name' => Yii::t('texts', 'LABEL_LAST_NAME'),
            'email_address' => Yii::t('texts', 'LABEL_EMAIL_ADDRESS'),
            'password' => Yii::t('texts', 'LABEL_PASSWORD'),
            'repeat_password' => Yii::t('texts', 'LABEL_REPEAT_PASSWORD'),
            'date_birth' => Yii::t('texts', 'LABEL_YEAR_OF_BIRTH'),
            'terms_conditions' => 'Terms and conditions',
            'mobile_phone' => Yii::t('texts', 'LABEL_MOBILE_PHONE'),
        );

        if ($this->scenario == self::SCENARIO_CREATE_CLIENT_EMAIL) {
            $labels['email_address'] = Yii::t('texts', 'LABEL_YOUR_EMAIL_ADDRESS');
        }

        return $labels;
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
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('email_address', $this->email_address, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('date_birth', $this->date_birth, true);
        $criteria->compare('wizard_completed', $this->wizard_completed, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

    public static function clientExists($email) {

        $sql = "SELECT id FROM tbl_client WHERE email_address = '$email'";

        $results = Yii::app()->db->createCommand($sql)->queryAll();

        return (count($results) > 0);
    }

    public function check_repeat_password() {
        
    }

    public function beforeSave() {

        //id generated by DB. Make sure id is null (the form stores it as emtpy string)
        if ($this->scenario == self::SCENARIO_CREATE_CLIENT_ALL) {

            $this->password = Encryption::encryptPassword($this->email_address, $this->password);

            //always store the email address in lower cases
            $this->email_address = strtolower($this->email_address);

            $this->id = null;
        }

        //leave the id untouched when updating

        return parent::beforeSave();
    }

    public function getAllMissions() {

        return Mission::getClientMissions($this->id);
    }

    public function saveWizardCompleted() {

        return parent::save(false, array('wizard_completed')); //only updates those fields;
    }

    public function savePassword() {

        $this->password = Encryption::encryptPassword($this->email_address, $this->password);

        return parent::save(false, array('password')); //only updates those fields;
    }

    public function saveMobilePhone() {

        return parent::save(false, array('mobile_phone')); //only updates those fields;
    }

    public function getFullName() {

        return $this->first_name . ' ' . $this->last_name;
    }

    //used for unit testing
    public function getLocationsIds() {

        $ids = array();

        foreach ($this->clientLocations as $serviceUser) {

            $ids[] = $serviceUser->id;
        }

        return $ids;
    }

    //used for unit testing
    public function getServiceUsersIds() {

        $ids = array();

        foreach ($this->serviceUsers as $serviceUser) {

            $ids[] = $serviceUser->id;
        }

        return $ids;
    }

    public function delete() {

        $dbTransaction = Yii::app()->db->beginTransaction();

        try {


            foreach ($this->carerSearchCriteria as $carerSearchCriteria) {

                $carerSearchCriteria->delete();
            }

            foreach ($this->bookings as $booking) {

                $booking->delete();
            }

            foreach ($this->complaints as $complaint) {

                $complaint->delete();
            }

            foreach ($this->creditCards as $creditCard) {

//                foreach ($complaint->complaintPosts as $complaintPost) {
//                    $complaintPost->delete();
//                }

                $creditCard->delete();
            }

            //foreach ($this->clientLocations as $clientLocation) {
            Address::deleteServiceLocationAddresses($this->id);
            //    $clientLocation->delete();
            //}


            foreach ($this->serviceUsers as $serviceUser) {

                //try {
                ServiceUser::deleteServiceUser($serviceUser->id, $this->id);
                //} catch (CException $e) {
                //}
            }

            foreach ($this->clientTransactions as $clientTransaction) {

                $clientTransaction->delete();
            }

            $sql = "DELETE FROM `tbl_login_history_client` WHERE `id_client`=" . $this->id;
            Yii::app()->db->createCommand($sql)->execute();

            parent::delete();


            $dbTransaction->commit();
        } catch (CException $e) {

            $dbTransaction->rollBack();
            throw new CException($e->getMessage());
        }
    }

    public function getClientCarersNotWanted($quote, $serviceUserIds, $showMale, $showFemale, $nationality) {

        $workWithMale = false;
        $workWithFemale = false;

        foreach ($serviceUserIds as $serviceUserId) {

            $serviceUser = ServiceUser::loadModel($serviceUserId);
            $ageGrp = $serviceUser->getAgeGroup();

            $physicalWorstCondition = $serviceUser->getWorstCondition(Condition::TYPE_PHYSICAL);
            $conditions[] = $physicalWorstCondition->id;

            $mentalWorstCondition = $serviceUser->getWorstCondition(Condition::TYPE_MENTAL);
            $conditions[] = $mentalWorstCondition->id;

            $gender = $serviceUser->gender;

            if ($gender == Constants::GENDER_MALE) {
                $workWithMale = true;
            }

            if ($gender == Constants::GENDER_FEMALE) {
                $workWithFemale = true;
            }
        }

        //remove duplicates entries

        $conditions = array_unique($conditions);

        if ($quote->type == Constants::LIVE_IN) {

            $liveIn = true;
            $hourly = false;
        } else {

            $liveIn = false;
            $hourly = true;
        }

        $ageGroup[] = $ageGrp;
        $active = true;


        $carers = DBServices::getCarersNotWanted($this->id, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale);

        return $carers;
    }

    //Return all carers who are favourite, selected, or no relation (no black list)
    public function getClientCarersSelection($quote, $serviceUserIds, $showMale, $showFemale, $nationality, $maxDisplay) {

        $workWithMale = false;
        $workWithFemale = false;

        $activitiesIds = array();

        foreach ($serviceUserIds as $serviceUserId) {

            $serviceUser = ServiceUser::loadModel($serviceUserId);
            $ageGrp = $serviceUser->getAgeGroup();

            //PHYSICAL CONDITIONS
            $physicalConditionIds = $serviceUser->getPhysicalCondition()->getConditionsIdsUp();

            //MENTAL CONDITIONS
            $mentalConditionsIds = $serviceUser->getMentalCondition()->getConditionsIdsUp();


            //ACTIVITIES
            $activities = $serviceUser->getActivities();

            foreach ($activities as $activity) {
                $activitiesIds[] = $activity->id;
            }

            $gender = $serviceUser->gender;

            if ($gender == Constants::GENDER_MALE) {
                $workWithMale = true;
            }

            if ($gender == Constants::GENDER_FEMALE) {
                $workWithFemale = true;
            }
        }

        //remove duplicates entries
        //$conditions = array_unique($conditions);
        $activitiesIds = array_unique($activitiesIds);

        if ($quote->type == Constants::LIVE_IN) {

            $liveIn = true;
            $hourly = false;
        } else {

            $liveIn = false;
            $hourly = true;
        }

        $ageGroup[] = $ageGrp;
        $active = true;

        //ClientCarerRelation::RELATION_NONE does not exist in the DB, it corresponds to no record in the table
        $relations = array(ClientCarerRelation::RELATION_FAVOURITE, ClientCarerRelation::RELATION_SELECTED);

        $carers = DBServices::getFilteredCarers2($activitiesIds, $physicalConditionIds, $mentalConditionsIds, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale, null, null, $this->id, $relations, $maxDisplay, $maxDisplay);

        return $carers;
    }

    /**
     * From 1st May 2014
     *      
     */
    public static function getClientsMadeBooking() {

        $sql = "SELECT DISTINCT c.id FROM tbl_client c 
                    INNER JOIN tbl_booking b
                    ON b.id_client = c.id ";
        //WHERE b.created > '2014-05-01 00:00:00'";

        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();

        foreach ($records as $record) {

            $ids[] = $record['id'];
        }

        return Client::loadModels($ids);
    }

    /**
     * 
     * @param type $showMale
     * @param type $showFemale
     * @param type $nationality
     * @return type array with 'carers' containing selected carers and 'notWanted' for carers with status '3'
     */
    public function getMyCarers($showMale = true, $showFemale = true, $nationality = 'all', $showDeactivated = false) {

        $result = array();

        $clientId = $this->id;

        $sql = "SELECT DISTINCT id, relation, overall_score FROM view_client_carer_relation_carer 
            WHERE id_client = $clientId AND relation <> " . ClientCarerRelation::RELATION_NOT_WANTED;

        if (!$showDeactivated) {
            $sql .= " AND deactivated = 0";
        }

        if ($nationality != 'all') {
            $sql .= " AND $nationality = $nationality ";
        }

        if ($showMale && $showFemale) {
            //nothing to add to request
        } else {
            if ($showMale) {
                $sql .= " AND gender = " . Constants::GENDER_MALE;
            }
            if ($showFemale) {
                $sql .= " AND gender = " . Constants::GENDER_FEMALE;
            }
        }

        $sql .= " ORDER BY relation ASC, overall_score DESC ";

        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();
        foreach ($records as $record) {
            $ids[] = $record['id'];
        }

        $idCommaSeparated = implode(',', $ids);


        //Sort carers
        $criteria = new CDbCriteria;
        if (count($ids) > 0) {
            $criteria->order = "FIELD(id, $idCommaSeparated)";
        }

        //retrieve active records for ids of carer with relation and with no relation
        $carers = Carer::model()->findAllByPk($ids, $criteria);

        $result['carers'] = $carers;


        $sql = "SELECT id FROM view_client_carer_relation_carer 
            WHERE deactivated = 0 AND id_client = $clientId AND relation = " . ClientCarerRelation::RELATION_NOT_WANTED;

        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $idsNotWanted = array();
        foreach ($records as $record) {
            $idsNotWanted[] = $record['id'];
        }

        $carersNotWanted = Carer::loadModels($idsNotWanted);

        $result['notWanted'] = $carersNotWanted;

        return $result;
    }

    public function getBookingIndexes() {

        $sql = "SELECT id FROM tbl_booking WHERE id_client = $this->id ORDER BY created ASC";

        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $result = array();

        $i = 1;
        foreach ($records as $record) {

            $result[$record['id']] = $i;
            $i++;
        }

        return $result;
    }

    public function getVoucherBalance() {

        return ClientTransaction::getCreditBalance($this->id)->text;
    }

    public static function getReturningClients() {

        $sql = "SELECT c.id FROM tbl_client c 
                    INNER JOIN tbl_booking b
                    ON b.id_client = c.id 
                    GROUP BY c.id
                    HAVING COUNT(b.id) > 1";

        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();

        foreach ($records as $record) {

            $ids[] = $record['id'];
        }

        return Client::loadModels($ids);
    }

    public function getLastJob() {

        $clientId = $this->id;

        $sql = "SELECT * FROM tbl_job WHERE id_client = $clientId ORDER BY created DESC LIMIT 1";

        return Job::model()->findBySql($sql);
    }

}

