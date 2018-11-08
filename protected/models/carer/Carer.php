<?php

/**
 * This is the model class for table "tbl_carer".
 *
 * The followings are the available columns in table 'tbl_carer':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email_address
 * @property string $password
 * @property string $date_birth
 * @property integer $gender
 * @property string $nationality
 * @property string $country_birth
 * @property string $mobile_phone
 * @property integer $live_in
 * @property integer $hourly_work
 * @property integer $live_in_work_radius
 * @property integer $hourly_work_radius
 * @property integer $work_with_male
 * @property integer $work_with_female
 * @property integer $car_owner
 * @property integer $dh_rating
 * @property string $sort_code
 * @property string $account_number
 * @property string $last_login_date
 * @property integer $wizard_completed
 * @property integer $active
 * @property integer $terms_conditions
 * @property integer $legally_work
 * @property string $created
 * @property string $modified
 * @property string $overall_score
 * @property string $overall_rating
 * @property string $active 
 * @property integer $show_homepage 
 * 
 * The followings are the available model relations:
 * @property RefAgeGroup[] $tblRefAgeGroups
 * @property RefDayWeek[] $tblRefDayWeeks
 * @property CarerAddress[] $carerAddresses
 * @property Mission[] $tblMissions
 * @property CarerExperience[] $carerExperiences
 * @property CarerFinancialTransaction[] $carerFinancialTransactions
 * @property ConditionCarer[] $conditionCarers
 * @property Document[] $documents
 * @property Mission[] $missions
 */
class Carer extends ActiveRecord {

    const SCENARIO_NEW_CARER = 'newCarer'; //carer job sign up
    const SCENARIO_CREATE_CARER = 'createCarer'; //wizard step 1 dtails
    const SCENARIO_UPDATE_CARER = 'updateCarer'; //maintain details
    const SCENARIO_UPDATE_CARER_TYPEWORK = 'updateCarerTypeWork';

    public static $genderLabels = array(Constants::GENDER_MALE => 'Male', Constants::GENDER_FEMALE => 'Female');
    public $repeat_password;

    /**
     * Returns the static model of the specified AR class.
     * @return Carer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_carer';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        if ($this->isNameEditable()) {
            $scenario1 = array(self::SCENARIO_CREATE_CARER, self::SCENARIO_UPDATE_CARER);
        } else {
            $scenario1 = array(self::SCENARIO_CREATE_CARER);
        }

        if ($this->isBirthDateEditable()) {
            $scenario2 = array(self::SCENARIO_CREATE_CARER, self::SCENARIO_UPDATE_CARER);
        } else {
            $scenario2 = array(self::SCENARIO_CREATE_CARER);
        }

        if ($this->isGenderEditable()) {
            $scenario3 = array(self::SCENARIO_CREATE_CARER, self::SCENARIO_UPDATE_CARER);
        } else {
            $scenario3 = array(self::SCENARIO_CREATE_CARER);
        }

        return array(
            //required            
            array('password, repeat_password', 'required', 'on' => self::SCENARIO_NEW_CARER),
            array('email_address', 'required'),
            array('email_address', 'filter', 'filter' => 'strtolower'),
            array('email_address', 'filter', 'filter' => 'trim'),
            array('email_address', 'email'),
            array('first_name, last_name', 'filter', 'filter' => array($this, 'filter_uc_lc')),
            array('first_name, last_name, nationality', 'required', 'on' => $scenario1),
            //array('first_name, last_name', 'required',),
            array('gender', 'required', 'on' => $scenario3, 'message' => Yii::t('texts', 'ERROR_GENDER_CANNOT_BE_BLANK')),
            array('mobile_phone', 'required', 'on' => array(self::SCENARIO_CREATE_CARER, self::SCENARIO_UPDATE_CARER)),
            array('mobile_phone', 'check_mobile_phone', 'on' => array(self::SCENARIO_CREATE_CARER, self::SCENARIO_UPDATE_CARER)),
            array('email_address', 'safe', 'on' => self::SCENARIO_UPDATE_CARER),
            array('terms_conditions', 'required', 'requiredValue' => 1, 'message' => Yii::t('texts', 'ERROR_PLEASE_READ_AND_ACCEPT_THE_TERMS'), 'on' => self::SCENARIO_CREATE_CARER),
            array('legally_work', 'required', 'requiredValue' => 1, 'message' => Yii::t('texts', 'ERROR_YOU_MUST_BE_ALLOWED_TO_WORK'), 'on' => self::SCENARIO_CREATE_CARER),
            array('legally_work, terms_conditions', 'safe', 'except' => self::SCENARIO_CREATE_CARER),
            array('gender, live_in, hourly_work, live_in_work_radius, hourly_work_radius, work_with_male, work_with_female, car_owner, dh_rating', 'numerical', 'integerOnly' => true),
            array('live_in, hourly_work', 'required'),
            array('repeat_password', 'check_repeat_password', 'on' => self::SCENARIO_NEW_CARER),
            array('live_in_work_radius', 'check_live_in_work_radius'),
            array('hourly_work_radius', 'check_hourly_work_radius'),
            //date
            array('date_birth', 'required', 'message' => Yii::t('texts', 'ERROR_ENTER_A_VALID_DATE_OF_BIRTH'), 'on' => $scenario2),
            array('work_with_male', 'check_work_with', 'on' => self::SCENARIO_UPDATE_CARER_TYPEWORK),
            //lengths
            array('first_name, last_name, nationality, country_birth', 'length', 'max' => 50),
            array('email_address', 'length', 'max' => 60),
            array('password', 'length', 'max' => 40, 'on' => self::SCENARIO_NEW_CARER),
            array('sort_code', 'length', 'max' => 10),
            array('account_number', 'length', 'max' => 20),
            //custom rule for checking existance of email address
            array('email_address', 'check_email_address', 'on' => self::SCENARIO_NEW_CARER),
            array('email_address', 'check_change_email_address', 'on' => self::SCENARIO_UPDATE_CARER),
            array('wizard_completed', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, first_name, last_name, email_address, password, date_birth, gender, nationality, country_birth, mobile_phone, live_in, live_in_work_radius, hourly_work_radius, work_with_male, work_with_female, car_owner, dh_rating, sort_code, account_number, wizard_completed, active, deactivated, no_job_alerts', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Check and filter methods
     */
    //First letter capital and rest lower case
    public function filter_uc_lc($value) {

        return ucwords(strtolower(trim($value)));
    }

    public function check_mobile_phone($attribute, $params) {

        $this->mobile_phone = str_replace(' ', '', $this->mobile_phone);

        if (Util::isMobilePhoneValid($this->mobile_phone)) {

            $this->mobile_phone = substr_replace($this->mobile_phone, " ", 5, 0);
        } else {
            $this->addError($attribute, Yii::t('texts', 'ERROR_MOBILE_PHONE_NOT_VALID'));
        }
    }

    public function check_email_address($attribute, $params) {

        if (DBServices::emailAddressExists($this->email_address) == true) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_THIS_EMAIL_ADDRESS_IS_REGISTERED_PLEASE_LOGIN'));
        }
    }

    public function check_repeat_password($attribute, $params) {

        if ($this->password != $this->repeat_password) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_ENTER_TWO_IDENTICAL_PASSWORDS'));
        }
    }

    public function check_change_email_address($attribute, $params) {

        $dbCarer = Carer::loadModel($this->id);

        $newEmail = $this->email_address;
        $existingEmail = $dbCarer->email_address;

        if ($newEmail != $existingEmail) {

            $person = DBServices::getEmailAlreadyExist($this->email_address);

            if (isset($person)) {
                $this->addError($attribute, Yii::t('texts', 'ERROR_THIS_EMAIL_ADDRESS_IS_REGISTERED_PLEASE_LOGIN'));
            }
        }
    }

    public function check_work_with($attribute, $params) {

        if ($this->work_with_male == false && $this->work_with_female == false) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_VALUE'));
        }
    }

    public function check_live_in_work_radius($attribute, $params) {

        if ($this->live_in == Constants::DB_TRUE && $this->live_in_work_radius < 1) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_ENTER_A_RADIUS_OF_AT_LEAST_1'));
        }
    }

    public function check_hourly_work_radius($attribute, $params) {

        if ($this->hourly_work == Constants::DB_TRUE && $this->hourly_work_radius < 1) {
            $this->addError($attribute, Yii::t('texts', 'ERROR_ENTER_A_RADIUS_OF_AT_LEAST_1'));
        }
    }

    /**
     * Relations
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'actionHistories' => array(self::HAS_MANY, 'ActionHistory', 'id_carer'),
            'ageGroups' => array(self::HAS_MANY, 'AgeGroup', 'id_carer'),
            'address' => array(self::BELONGS_TO, 'Address', 'id_address'),
            'carerAvailabilities' => array(self::HAS_MANY, 'CarerAvailability', 'id_carer'),
            'carerConditions' => array(self::HAS_MANY, 'CarerCondition', 'id_carer'),
            'carerDocuments' => array(self::HAS_MANY, 'CarerDocument', 'id_carer'),
            'carerTransactions' => array(self::HAS_MANY, 'CarerTransaction', 'id_carer'),
            'complaints' => array(self::HAS_MANY, 'Complaint', 'id_carer'),
            'missionCarers' => array(self::HAS_MANY, 'MissionCarers', 'id_applying_carer'),
            'loginHistory' => array(self::HAS_MANY, 'LoginHistoryCarer', 'id_carer'),
            'withdrawals' => array(self::HAS_MANY, 'CarerWithdrawal', 'id_carer'),
            'withdrawals' => array(self::HAS_MANY, 'CarerWithdrawal', 'id_carer'),
            'clientRelations' => array(self::HAS_MANY, 'ClientCarerRelation', 'id_carer'),
            'bookingShortListed' => array(self::HAS_MANY, 'BookingCarers', 'id_carer'),
            'carerLanguages' => array(self::HAS_MANY, 'CarerLanguage', 'id_carer'),
            'conversations' => array(self::HAS_MANY, 'Conversation', 'id_carer', 'order' => 'conversations.modified DESC'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'first_name' => Yii::t('texts', 'LABEL_FIRST_NAME'),
            'last_name' => Yii::t('texts', 'LABEL_LAST_NAME'),
            'email_address' => Yii::t('texts', 'LABEL_EMAIL_ADDRESS'),
            'password' => Yii::t('texts', 'LABEL_PASSWORD'),
            'repeat_password' => Yii::t('texts', 'LABEL_REPEAT_PASSWORD'),
            'date_birth' => Yii::t('texts', 'LABEL_DATE_OF_BIRTH'),
            'gender' => Yii::t('texts', 'LABEL_GENDER'),
            'nationality' => Yii::t('texts', 'LABEL_NATIONALITY'),
            'country_birth' => Yii::t('texts', 'LABEL_COUNTRY_OF_BIRTH'),
            'mobile_phone' => Yii::t('texts', 'LABEL_MOBILE_PHONE'),
            'live_in' => Yii::t('texts', 'LABEL_LIVE_IN_CARE'),
            'hourly_work' => Yii::t('texts', 'LABEL_HOURLY_CARE'),
            'live_in_work_radius' => Yii::t('texts', 'LABEL_LIVE_IN_CARE_RADIUS') . '&#160;&#160;',
            'hourly_work_radius' => Yii::t('texts', 'LABEL_HOURLY_CARE_RADIUS') . '&#160;&#160;',
            'work_with_male' => Yii::t('texts', 'LABEL_WORK_WITH_MALE'),
            'work_with_female' => Yii::t('texts', 'LABEL_WORK_WITH_FEMALE'),
            'driving_licence' => Yii::t('texts', 'LABEL_DRIVING_LICENCE'),
            'car_owner' => Yii::t('texts', 'LABEL_CAR_OWNER'),
            'personal_text' => Yii::t('texts', 'LABEL_PERSONAL_TEXT'),
            'dh_rating' => Yii::t('texts', 'LABEL_DIRECT_RATING'),
            'sort_code' => Yii::t('texts', 'LABEL_SORT_CODE'),
            'account_number' => Yii::t('texts', 'LABEL_ACCOUNT_NUMBER'),
            'last_login_date' => Yii::t('texts', 'LAST_LOGIN_DATE'),
            'active' => Yii::t('texts', 'LABEL_ACTIVE'),
            'terms_conditions' => '',
            'legally_work' => '',
            'deactivated' => Yii::t('texts', 'LABEL_DEACTIVATE_MY_ACCOUNT'),
            'no_job_alerts' => Yii::t('texts', 'LABEL_STOP_SENDING_JOB_ALERTS'),
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

    public function scopes() {
        return array('newJobAll' => array(
                'condition' => 'no_job_alerts=0',
            ),
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
        $criteria->compare('gender', $this->gender);
        $criteria->compare('nationality', $this->nationality, true);
        $criteria->compare('country_birth', $this->country_birth, true);
        $criteria->compare('mobile_phone', $this->mobile_phone, true);
        $criteria->compare('live_in', $this->live_in);
        $criteria->compare('live_in_work_radius', $this->live_in_work_radius);
        $criteria->compare('hourly_work_radius', $this->hourly_work_radius);
        $criteria->compare('work_with_male', $this->work_with_male);
        $criteria->compare('work_with_female', $this->work_with_female);
        $criteria->compare('car_owner', $this->car_owner);
        $criteria->compare('dh_rating', $this->dh_rating);
        $criteria->compare('sort_code', $this->sort_code, true);
        $criteria->compare('account_number', $this->account_number, true);
        $criteria->compare('wizard_completed', $this->wizard_completed);
        $criteria->compare('active', $this->active, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
    }

    public function beforeSave() {

        //id generated by DB. Make sure id is null (the form stores it as emtpy string)
        if ($this->scenario == self::SCENARIO_NEW_CARER) {

            $this->password = Encryption::encryptPassword($this->email_address, $this->password);

            //always store the email address in lower cases
            $this->email_address = strtolower($this->email_address);

            $this->id = null;
        }

        return parent::beforeSave();
    }

    public function savePassword() {

        $this->password = Encryption::encryptPassword($this->email_address, $this->password);

        return parent::save(false, array('password')); //only updates those fields;
    }

    public function saveWizardCompleted() {

        return parent::save(false, array('wizard_completed')); //only updates those fields;
    }

    public function getGenderLabel() {

        if ($this->gender != Constants::GENDER_NONE) {
            return self::$genderLabels[$this->gender];
        } else {
            return '';
        }
    }

    public function getDiplomas() {

        return CarerDocument::getDiplomas($this->id, CarerDocument::UNACTIVE);
    }

    public function getDiplomasForClient() {

        return CarerDocument::getDiplomas($this->id, CarerDocument::ACTIVE);
    }

    /**
     * Unactive
     * @return type CarerDocument
     */
    public function getPhoto() {

        return CarerDocument::getPhoto($this->id, CarerDocument::UNACTIVE);
    }

    /**
     * Approved
     * @return type CarerDocument
     */
    public function getPhotoForClient() {

        return CarerDocument::getPhoto($this->id, CarerDocument::ACTIVE, true);
    }

    public function getIdentification() {

        return CarerDocument::getIdentification($this->id, CarerDocument::UNACTIVE);
    }

    public function getIdentificationForClient() {

        return CarerDocument::getIdentification($this->id, CarerDocument::ACTIVE);
    }

    public function getDrivingLicence() {

        return CarerDocument::getDrivingLicence($this->id, CarerDocument::UNACTIVE);
    }

    public function getDrivingLicenceForClient() {

        return CarerDocument::getDrivingLicence($this->id, CarerDocument::ACTIVE);
    }

    public function getCriminalRecords() {

        return CarerDocument::getCriminalRecords($this->id, CarerDocument::UNACTIVE);
    }

    public function getCriminalRecordsForClient() {

        return CarerDocument::getCriminalRecords($this->id, CarerDocument::ACTIVE);
    }

    public function getFullName() {

        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAge() {

        return Calendar::calculate_Age($this->date_birth);
    }

    public function getAgeLabel() {

        return Calendar::calculate_Age($this->date_birth) . Yii::t('texts', 'LABEL_YEAR_OLD');
    }

    public function getNationalityLabel() {

        $nationalty = $this->nationality;
        if ($nationalty == '') {
            return 'Not provided';
        } else {

            $nationalities = Nationalities::getNationalities();
            return $nationalities[$nationalty];
        }
    }

    public function getPersonalityText() {


        return CarerDocument::getPersonalityText($this->id, CarerDocument::UNACTIVE);
    }

    /**
     * @return string personality text, empty if nothing
     */
    public function getPersonalityTextForClient() {

        $textObject = CarerDocument::getPersonalityText($this->id, CarerDocument::ACTIVE);
        if (isset($textObject)) {
            $text = $textObject->text;
        } else {
            $text = '';
        }

        return $text;
    }

    public function getMotivationText() {
        $text = CarerDocument::getMotivationText($this->id, CarerDocument::UNACTIVE);
        return $text;
    }

    /**
     * @return string motivation text, empty if nothing
     */
    public function getMotivationTextForClient() {

        $textObject = CarerDocument::getMotivationText($this->id, CarerDocument::ACTIVE);
        if (isset($textObject)) {
            $text = $textObject->text;
        } else {
            $text = '';
        }

        return $text;
    }

    /**
     * Bien code a l'arrache ce truc
     * 
     * @param type $clientView
     * @return type
     */
    public function profileDisplayAllDocuments($clientView = true) {

        if ($clientView) {
            $documents = CarerDocument::getExistingApprovedDocument($this->id);
        } else {
            $documents = CarerDocument::getExistingDocument($this->id);
        }

        $result = array();

        foreach ($documents as $document) {

            $id = $document->id;

            switch ($document->type) {

                case Document::TYPE_TEXT:
                    break;
                case Document::TYPE_PHOTO:
                    break;
                case Document::TYPE_DIPLOMA:
                    $result[] = $document->getName();
                    break;
                case Document::TYPE_IDENTIFICATION:
                    break;
                case Document::TYPE_CRIMINAL:
                    break;
            }
        }

        return $result;
    }

    /**
     * If some documents are provided and verified like passport then the carer cannot change their names
     */
    public function isNameEditable() {

        if (isset($this->id)) {

            $documents = CarerDocument::getCarerNameVerifiedDocument($this->id);

            if (isset($documents)) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function isBirthDateEditable() {

        if (isset($this->id)) {
            $id = $this->getIdentificationForClient();
            $drivingLicence = $this->getDrivingLicenceForClient();

            if (isset($id) || isset($drivingLicence)) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function isGenderEditable() {
        if (isset($this->id)) {
            $id = $this->getIdentificationForClient();
            $drivingLicence = $this->getDrivingLicenceForClient();

            if (isset($id) || isset($drivingLicence)) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * Used by admin menu
     * @return type
     */
    public function getAllAssignedMissions() {

        return Mission::getAllCarerAssignedMissions($this->id);
    }

    /**
     * Check the current active conditions and update DB field accordingly
     */
    public function setActiveOrNot() {

        $active = 0;

        if ($this->getMotivationTextForClient() != null && $this->getPersonalityTextForClient() != null &&
                ($this->getIdentificationForClient() != null || $this->getDrivingLicenceForClient() != null) && $this->getPhotoForClient() != null) {

            $active = 1;
        }
        $this->active = $active;
        $this->save(false);
    }

    /**
     * Return array of document texts. Empty is none.
     */
    public function getMissingDocuments() {

        if ($this->active) {
            return array();
        } else {
            $result = array();

            if ($this->getMotivationTextForClient() == null) {
                $result[] = 'A few sentences describing your motivation.';
            }

            if ($this->getPersonalityTextForClient() == null) {
                $result[] = 'A few sentences describing your personality.';
            }

            if ($this->getIdentificationForClient() == null && $this->getDrivingLicenceForClient() == null) {
                $result[] = 'Scan or photo of your passport, ID or driving licence.';
            }

            if ($this->getPhotoForClient() == null) {
                $result[] = 'Smiling photo of yourself.';
            }
        }

        return $result;
    }

    /**
     * Return true if the carer has had his diplomas/id cards approved
     */
    public function isActive() {

        return $this->active;
    }

    /**
     * Set flash text if all documents not provided
     */
    public function setRequiredDocumentText() {

        if (!$this->isActive()) {

            Yii::app()->user->setFlash('notice', Yii::t('texts', 'NOTE_PLEASE_COMPLETE_YOUR_PROFILE_WITH_AT_LEAST'));
        }
    }

    public function delete() {

        $dbTransaction = Yii::app()->db->beginTransaction();

        try {

            foreach ($this->bookingShortListed as $bookingShortListed) {

                $bookingShortListed->delete();
            }

            foreach ($this->actionHistories as $actionHistory) {

                $actionHistory->delete();
            }
            foreach ($this->ageGroups as $ageGroup) {

                $ageGroup->delete();
            }
            foreach ($this->carerAvailabilities as $carerAvailability) {

                $carerAvailability->delete();
            }
            foreach ($this->carerConditions as $carerCondition) {

                $carerCondition->delete();
            }

            foreach ($this->carerDocuments as $carerDocument) {

                try {
                    $carerDocument->delete();
                } catch (CException $e) {
                    
                }
            }

            foreach ($this->carerTransactions as $carerTransaction) {

                $withdrawals = $carerTransaction->carerWithdrawals;

                foreach ($withdrawals as $withdrawal) {

                    $withdrawal->delete();
                }

                $carerTransaction->delete();
            }

            foreach ($this->clientRelations as $clientRelation) {
                $clientRelation->delete();
            }

            foreach ($this->complaints as $complaint) {

                foreach ($complaint->complaintPosts as $complaintPost) {
                    $complaintPost->delete();
                }

                $complaint->delete();
            }

            foreach ($this->complaints as $complaint) {

                foreach ($complaint->complaintPosts as $complaintPost) {
                    $complaintPost->delete();
                }

                $complaint->delete();
            }
            foreach ($this->missionCarers as $missionCarer) {

                $missionCarer->delete();
            }

            foreach ($this->loginHistory as $loginHistory) {
                $loginHistory->delete();
            }

            parent::delete();

            if (isset($this->address)) {
                $this->address->delete();
            }

            $dbTransaction->commit();
        } catch (CException $e) {

            $dbTransaction->rollBack();
            throw new CException($e->getMessage());
        }
    }

    public static function getActiveCarers() {

        $criteria = new CDbCriteria();
        $criteria->condition = 'active = 1';

        $models = self::model()->findAll($criteria);

        return $models;
    }

    /**
     * 
     * @param type $number
     * @return type
     */
    public static function getRandomActiveCarers($number, $postCode = null) {

        $criteria = new CDbCriteria();
        $criteria->condition = "active = 1 AND deactivated = 0 AND show_homepage = 1 ORDER BY RAND()";
        $criteria->limit = $number;
        $criteria->distinct = true;

        if(isset($postCode)){
            
            $carers = self::getCarersFromPostCode($postCode, $booking->type, $exceptionList);
        }
        
        $models = self::model()->resetScope()->findAll($criteria);

        return $models;
    }

    /**
     * Get Client Relation object
     * 
     * @param type $clientId Client Id
     * @return type ClientCarerRelation
     */
    public function getClientRelation($clientId) {

        return ClientCarerRelation::getRelation($clientId, $this->id);
    }

    /**
     * Get Client relation label
     * @param type $clientId
     * @return type String 
     */
    public function getClientRelationLabel($clientId) {

        $clientRelation = $this->getClientRelation($clientId);

        if (isset($clientRelation)) {

            return $clientRelation->getLabel();
        } else {
            return 'No relation with you';
        }
    }

//    public function getScore() {
//
//        $carerId = $this->id;
//        $sql = "SELECT SUM(favourite) as sum_favourite, 
//                       SUM(team) as sum_team, 
//                       SUM(not_wanted) as sum_not_wanted 
//               FROM tbl_client_carer_relation WHERE id_carer = $carerId ";
//
//        $result = Yii::app()->db->createCommand($sql)->queryRow();
//  
//        $score = $result['sum_favourite'] * BusinessRules::getCarerScoreFavouriteCoeff() +
//                 $result['sum_team'] * BusinessRules::getCarerScoreTeamCoeff() +
//                 $result['sum_not_wanted'] * BusinessRules::getCarerScoreNotWantedCoeff();
//
//        return $score;
//    }
//    public function getAverageRating() {
//
//        //rating_punctuality
//        //rating_initialive
//        //rating_kindness
//        //rating_presentation
//        //rating_skills
//        //rating_overall
//
//        $carerId = $this->id;
//        $sql = "SELECT AVG(rating_punctuality) as rating_punctuality, 
//                       AVG(rating_initialive) as rating_initialive,
//                       AVG(rating_kindness) as rating_kindness, 
//                       AVG(rating_presentation) as rating_presentation,
//                       AVG(rating_skills) as rating_skills,
//                       AVG(rating_overall) as rating_overall,
//                       
//                FROM tbl_client_carer_relation WHERE id_carer = $carerId ";
//
//        $result = Yii::app()->db->createCommand($sql)->queryRow();
//
//        $score = $result['sum_favourite'] * BusinessRules::getCarerScoreFavouriteCoeff() +
//                $result['sum_team'] * BusinessRules::getCarerScoreTeamCoeff() +
//                $result['sum_not_wanted'] * BusinessRules::getCarerScoreNotWantedCoeff();
//
//        $this->score = $score;
//        $this->save(false);
//
//        return $score;
//    }

    public function getHourlyWorkText() {

        if ($this->hourly_work == Constants::DB_TRUE) {
            return 'Yes';
        } elseif ($this->hourly_work == Constants::DB_FALSE) {
            return 'No';
        } else {
            return 'N/A';
        }
    }

    public function getLiveInWorkText() {

        if ($this->live_in == Constants::DB_TRUE) {
            return 'Yes';
        } elseif ($this->live_in == Constants::DB_FALSE) {
            return 'No';
        } else {
            return 'N/A';
        }
    }

    public function getWorkWithMaleText() {

        if ($this->work_with_male) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getWorkWithFemaleText() {

        if ($this->work_with_female) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getOverallRating() {

        if ($this->overall_rating == 0) { //no rating
            if ($this->overall_score >= 0) {
                $overallRating = Random::getRandomRatingHigh();
            } else {
                $overallRating = Random::getRandomRatingLow();
            }

            $this->overall_rating = $overallRating;
            $this->save(false);
        }

        return $this->overall_rating;
    }

    /**
     * return true if at least one CRB document approved
     */
    public function isBackgroundChecked() {

        $criminalRecords = $this->getCriminalRecordsForClient();
        foreach ($criminalRecords as $criminalRecord) {
            $active = $criminalRecord->getActiveVersion();
            if (isset($active)) {
                return true;
            }
        }

        return false;
    }

    public function isIdentityVerified() {

        $identification = $this->getIdentificationForClient();

        if (isset($identification)) {

            return true;
        } else {
            $drivingLicence = $this->getDrivingLicenceForClient();
            if (isset($drivingLicence)) {
                return true;
            }
        }

        return false;
    }

    public function isUsedBefore($clientId) {

        return MissionCarers::isRelationExists($this->id, $clientId, MissionCarers::ASSIGNED);
    }

    public function displayAdmin() {

        return 'Name: ' . $this->getFullName() . ' Id: ' . $this->id;
    }

    public static function getPotentialCarersForMission($postCode, $booking, $exceptionList) {

        //return all carers that could be potentially interetsted by the mission

        $carers = self::getCarersFromPostCode($postCode, $booking->type, $exceptionList);

        //later on match mission criteria (conditions, age group..)

        return $carers;
    }

    public static function getPotentialCarersForJob($job, $exceptionList) {

        //return all carers that could be potentially interetsted by the mission

        $carers = self::getCarersFromPostCode($job->post_code, Mission::TYPE_HOURLY, $exceptionList, $job->gender_carer);

        //later on match mission criteria (conditions, age group..)

        return $carers;
    }

    public static function getCarerWithQualification($activeOnly = false) {

        $active = CarerDocument::ACTIVE;

        $sql = "SELECT * FROM tbl_carer c WHERE EXISTS (SELECT tbl_carer_document.id FROM tbl_carer_document LEFT JOIN tbl_document ON tbl_carer_document.id_document = tbl_document.id" .
                " WHERE tbl_document.type = " . Document::TYPE_DIPLOMA . " AND tbl_carer_document.id_carer = c.id AND tbl_carer_document.active = $active ) ";

        if ($activeOnly) {

            $sql .= " AND c.active = 1";
        }

        $carers = Carer::model()->findAllBySql($sql);

        return $carers;
    }

    public static function getCarersFromPostCode($postCode, $missionType, $exceptionList = null, $gender = Constants::GENDER_BOTH) {

        $result = Maps::getPostCodeData($postCode);
        $lat = $result['latitude'];
        $lon = $result['longitude'];

        if ($missionType == Constants::HOURLY) {

            $workRadius = 'c.hourly_work_radius';
        } else {
            $workRadius = 'c.live_in_work_radius';
        }

        $where = "(3958*3.1415926*sqrt((a.latitude - $lat)*(a.latitude - $lat) + cos(a.latitude/57.29578)*cos($lat/57.29578)*(a.longitude - $lon)*(a.longitude-$lon))/180) <= $workRadius";

        //do a join with carer, address, 
        $sql = "SELECT c.* FROM tbl_carer c INNER JOIN tbl_address a ON a.id = c.id_address
                                          WHERE $where AND c.active = 1 AND c.deactivated = 0 ";

        if (isset($exceptionList) && count($exceptionList) > 0 ) {

            $carerIds = implode(',', $exceptionList);

            $sql .= " AND c.id NOT IN ($carerIds)";
        }

        if ($gender == Constants::GENDER_FEMALE or $gender == Constants::GENDER_FEMALE) {
            
            $sql .= " AND gender = " . $gender;
        }

        $carers = Carer::model()->newJobAll()->findAllBySql($sql);

        return $carers;
    }

    /**
     * return Price object
     * @return type
     */
    public function getCreditBalance() {

        return CarerTransaction::getCreditBalance($this->id);
    }
    
}
