<?php

class CarerController extends Controller {
//    const MY_MISSIONS = 1;
//    const MONEY = 2;
//    const MISSIONS_HISTORY = 3;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/layoutCarer';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actions() {

        return array(
            AddComplaintDialog::NAME => array(
                'class' => 'AddComplaintDialog',
            ),
            AddComplaint::NAME => array(
                'class' => 'AddComplaint',
            ),
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('details', 'profile', 'typeWork', 'confirmation', 'upload', 'uploadImage', 'uploadPhoto', 'uploadDiploma',
                    'documentAdd', 'showDialog', 'showPhotoDialog', 'uploadCriminalRecord', 'uploadDrivingLicence', 'uploadIdentification',
                    'diplomaCRBDelete', 'documentDelete', 'maintainDetails', 'maintainPassword', 'maintainTypeWork', 'maintainProfile', 'maintainAccountSettings',
                    'money', 'chooseMission', 'myMissions', 'registration', 'populate', 'populateTypeWork', 'index', 'getImage',
                    'cancelMotivationText', 'saveMotivationText', 'cancelPersonalText', 'savePersonalText', 'saveCarOwner',
                    'apply', 'cancelAppliedChooseMission', 'cancelAssigned', 'cancelSelected', 'cancelSelectedDialog', 'confirmApplied', 'confirmSelected', 'confirmSelectedDialog', 'confirmAssigned', 'missionDetails',
                    'cancelAppliedBookedMission', 'cancelAssignedDialog', 'cancelAppliedDialog', 'discardMission', 'cancelAppliedChooseMissionDialog',
                    'cancelAppliedDialog', 'cancelAppliedChooseMissionDialog', 'withdraw', 'missionsHistory', 'missionsVerifying', 'missionsComplaint', 'missionInvoice',
                    'complaintPage', AddComplaint::NAME, AddComplaintDialog::NAME, 'displayImage', 'displaySavedImage', 'handleCrop',
                    'addLanguage', 'deleteLanguage', 'myClients', 'conversation', 'sendMessage', 'acceptBooking', 'declineBooking',
                ),
                'expression' => "UserIdentityUser::isCarer()", //'expression'=>'isset($user->role) && ($user->role==="editor")'
                'users' => array('*'),
            ),
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('populate', 'details', 'getImageAdmin'),
                'expression' => "UserIdentityUser::isGuest()",
                'users' => array('*'),
            ),
//            array('allow', // allow all users to perform 'index' and 'view' actions
//                'actions' => array('getImageForClient'),
//                'expression' => "UserIdentityUser::isClient()",
//                'users' => array('*'),
//            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionMaintainPassword($navigation = 'display') {

        $carer = Carer::loadModel(Yii::app()->user->id);
        $address = $carer->address;

        $request = Yii::app()->request;
        $passwordParam = $request->getPost('ResetPasswordForm');

        $resetPassword = new ResetPasswordForm();

        if (isset($passwordParam)) {

            $resetPassword->attributes = $_POST['ResetPasswordForm'];

            $valid = $resetPassword->validate();

            if ($valid) {
                //check old password
                $identity = new UserIdentityUser($carer->email_address, $passwordParam['oldPassword']);

                if ($identity->authenticate()) {
                    //check passwords are the same

                    if ($passwordParam['newPassword'] == $passwordParam['newPasswordRepeat']) {

                        //update password
                        $carer->password = $passwordParam['newPassword'];

                        $carer->savePassword();

                        Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_PASSWORD_CHANGED'));

                        $resetPassword = new ResetPasswordForm();
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_TWO_PASSWORDS_DIFFERENT'));
                    }
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_OLD_PASSWORD_WRONG'));
                }
            }
        }

        $this->render('details', array('carer' => $carer, 'address' => $address, 'resetPassword' => $resetPassword, 'maintain' => true));
    }

    public function actionMaintainDetails($navigation = 'display') {

        $carer = Carer::loadModel(Yii::app()->user->id);
        $address = $carer->address;

        $carer->setScenario(Carer::SCENARIO_UPDATE_CARER);

        if ($navigation == 'save') {

            //update
            $request = Yii::app()->request;

            $carerParam = $request->getPost('Carer');
            $addressParam = $request->getPost('Address');

            $nationality = $carer->nationality;

            if ($nationality == 'none') {
                unset($carer->nationality);
            }

            $lang = $request->preferredLanguage;
            $invalidBirthDate = false;

            $valid = true;

            if (isset($carerParam, $addressParam)) {

                //POPULATE DATA FROM FORM
                $gender = $carer->gender;
                $carer->attributes = $_POST['Carer']; //model name !
                $address->attributes = $_POST['Address'];

                //work around for disabled checkboxes
                if (isset($_POST['terms_conditions'])) {

                    $carer->terms_conditions = $_POST['terms_conditions'];
                }
                if (isset($_POST['legally_work'])) {

                    $carer->legally_work = $_POST['legally_work'];
                }

                if ($carer->isBirthDateEditable()) {

                    //check date validity
                    $month = isset($_POST['Month']) ? $_POST['Month'] : '0';
                    $day = isset($_POST['Day']) ? $_POST['Day'] : '0';
                    $year = isset($_POST['Year']) ? $_POST['Year'] : '0';

                    if (checkdate($month, $day, $year)) {

                        $day = sprintf("%02d", $day);
                        $month = sprintf("%02d", $month);

                        $date_value = "$year-$month-$day";
                        $carer->date_birth = $date_value;
                    } else {

                        unset($carer->date_birth); //unset the value so that error is reported in the model
                    }
                }

                if (!$carer->isGenderEditable()) {
                    $carer->gender = $gender;
                }

                $carer->scenario = Carer::SCENARIO_UPDATE_CARER;

                $address->isNewRecord = false;

                $valid = $carer->validate();
                $errors2 = $carer->getErrors();

                $valid = $address->validate() && $valid;
                $errors = $address->getErrors();

                if ($valid) {

                    $carer->save();
                    $address->save(false);

                    Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_CHANGES_SAVED'));
                }
            }
        }

        $resetPassword = new ResetPasswordForm();

        $this->render('details', array('carer' => $carer, 'address' => $address,
            'resetPassword' => $resetPassword, 'maintain' => true));
    }

    /*
     * INIT METHOD
     */

    public function actionRegistration() {

        Wizard::initStepArrayCarer($this->id);

        $this->redirect(array('carer/details'));
    }

    /**
     * Start carer registration.
     */
    public function actionDetails() {

        $view = Wizard::VIEW_DETAILS;

        //check is user did not enter a wrong step in the RL
        $this->handleCarerSecurity($view);

        Wizard::setStepActive($view);

        $carerId = Yii::app()->user->id;
        $carer = Carer::loadModel($carerId);

        $leg = $carer->legally_work;

        if (isset($carer->address)) {
            $carer->setScenario(Carer::SCENARIO_UPDATE_CARER);
            $address = $carer->address;
        } else {
            $address = new Address();
            $carer->setScenario(Carer::SCENARIO_CREATE_CARER);
        }

        //Figure out if next or back is pressed.
        //only in this case change previous step
        $nextStep = null;

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            //SAVE
            $request = Yii::app()->request;

            $carerParam = $request->getPost('Carer');
            $addressParam = $request->getPost('Address');

            $lang = $request->preferredLanguage;
            $invalidBirthDate = false;

            $valid = true;

            if (isset($carerParam, $addressParam)) {

                //POPULATE DATA FROM FORM
                $carer->attributes = $_POST['Carer']; //model name !
                $address->attributes = $_POST['Address'];
                $address->data_type = Address::TYPE_CARER_ADDRESS;

                if ($address->validate()) {
                    $postCodeData = Maps::getPostCodeData($address->post_code);
                    $address->city = $postCodeData['city'];
                }

                //work around for disabled checkboxes
                if (isset($_POST['terms_conditions'])) {

                    $carer->terms_conditions = $_POST['terms_conditions'];
                }
                if (isset($_POST['legally_work'])) {

                    $carer->legally_work = $_POST['legally_work'];
                }

                $nationality = $carer->nationality;

                if ($nationality == 'none') {
                    unset($carer->nationality);
                }

                //check date validity
                $month = $_POST['Month'];
                $day = $_POST['Day'];
                $year = $_POST['Year'];

                if (checkdate($month, $day, $year)) {

                    //add leading 0 if necessary
                    $day = sprintf("%02d", $day);
                    $month = sprintf("%02d", $month);

                    $date_value = "$year-$month-$day";
                    $carer->date_birth = $date_value;
                } else {

                    unset($carer->date_birth); //unset the value so that error is reported in the model
                }

                //VALIDATE
                $valid = $carer->validate();
                $errorsCarer = $carer->getErrors();

                $valid = $address->validate() && $valid;
                $errorsAddress = $address->getErrors();

                //SAVE/UPDATE Carer, Address and CarerAddress
                if ($valid) {

                    //Save/Create Carer                 
                    $address->save(false);

                    $carer->id_address = $address->id;

                    $currentStepIndex = Wizard::getCompletedStepIndex($view);
                    $wizardCompleted = $carer->wizard_completed;

                    if ($currentStepIndex > $wizardCompleted) {
                        $carer->wizard_completed = $currentStepIndex;
                    }

                    $results = $carer->save(false); //if new record, scenario is set from insert to update after the save
                    //update name in session
                    Yii::app()->user->setState('full_name', $carer->first_name . ' ' . $carer->last_name);

                    if (isset($_GET['navigation'])) {

                        if ($_GET['navigation'] == 'next') {
                            $nextStep = Wizard::VIEW_TYPE_WORK;
                        }
                    } elseif (isset($_GET['toView'])) {

                        $nextStep = $_GET['toView'];
                    }

                    Wizard::setStepCompleted($view);

                    // Emails::sendToCarer_SignUp($carer);
                    //redirect to next screen
                    $this->redirect(array($nextStep));
                } else {

                    Yii::app()->user->setFlash('error', Yii::app()->params['texts']['genericFormErrorMessage']);
                }
            }
        } else {

            if (isset($carer->address)) {
                $address = $carer->address;
            } else {
                $address = new Address();
            }
            //           }
        }

        $this->render('details', array('carer' => $carer, 'address' => $address, 'maintain' => false));
    }

    public function actionMaintainTypeWork($navigation = 'display') {

        //SAVE
        if ($navigation == 'save') {

            $carer = Carer::loadModel(Yii::app()->user->id);
            $carer->setScenario(Carer::SCENARIO_UPDATE_CARER_TYPEWORK);

            $errors = $this->saveTypeWork($carer, true);

            if (count($errors) == 0) {
                Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_CHANGES_SAVED'));
            }
        } else {
            //reload Carer data
            $carer = Carer::loadModel(Yii::app()->user->id);
            $errors = array();
        }
        //Display
        $condition = $carer->carerConditions;


        $this->render('typeWork', array('carer' => $carer, 'condition' => $condition, 'maintain' => true, 'errors' => $errors, 'display' => false)); //, 'condition' => $condition));
    }

    public function actionTypeWork() {

        $view = Wizard::VIEW_TYPE_WORK;

        //check is user did not enter a wrong step in the RL
        $this->handleCarerSecurity($view);

        Wizard::setStepActive($view);

        $errors = array();
        $carer = Carer::loadModel(Yii::app()->user->id);
        $carer->setScenario(Carer::SCENARIO_UPDATE_CARER_TYPEWORK);

        //SAVE
        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            //pass carer as a reference
            $errors = $this->saveTypeWork($carer);
            $display = false;
        } else {

            //reload Carer data
            $carer = Carer::loadModel(Yii::app()->user->id);
            $display = true;
        }

        $this->render('typeWork', array('carer' => $carer, 'maintain' => false, 'errors' => $errors, 'display' => $display)); //, 'condition' => $condition));
    }

    public function actionMaintainProfile() {

        $carerId = Yii::app()->user->id;
        //reload  data
        $carer = Carer::loadModel($carerId);

        $carer->setRequiredDocumentText();

        //Display
        $carerDocuments = $carer->carerDocuments;

        $carerLanguages = $carer->carerLanguages;

        $validLanguages = true;

        if (isset($_GET['navigation']) && $_GET['navigation'] == 'save') {
            if (!empty($_POST['CarerLanguage'])) {

                //delete existing
                foreach ($carerLanguages as $carerLanguage) {
                    $carerLanguage->delete();
                }

                foreach ($_POST['CarerLanguage'] as $carerLanguageData) {

                    $carerLanguage = new CarerLanguage();
                    $carerLanguage->id_carer = $carerId;
                    $carerLanguage->setAttributes($carerLanguageData);

                    $newCarerLanguages[] = $carerLanguage;

                    if (!$carerLanguage->validate()) {

                        $validLanguages = false;
                    }
                    $err = $carerLanguage->errors;
                }

                if ($validLanguages) {

                    if (CarerLanguage::validateAll($newCarerLanguages)) {

                        foreach ($newCarerLanguages as $newCarerLanguage) {
                            $res = $newCarerLanguage->save();
                            $err = $newCarerLanguage->errors;
                        }

                        Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_CHANGES_SAVED'));

                        $this->redirect('maintainProfile');
                    } else {
                        $carerLanguages = $newCarerLanguages;
                        Yii::app()->user->setFlash('error_languages', Yii::t('texts', 'ERROR_YOU_HAVE_ENTER_IDENTICAL_LANGUAGES'));
                        Yii::app()->user->setFlash('error', Yii::t('texts', 'ERROR_YOU_HAVE_ENTER_IDENTICAL_LANGUAGES'));
                    }
                } else {
                    Yii::app()->user->setFlash('error_language', Yii::t('texts', 'ERROR'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('texts', 'ERROR_ENTER_AT_LEAST_ONE_LANGUAGE'));
                Yii::app()->user->setFlash('error_languages', Yii::t('texts', 'ERROR_ENTER_AT_LEAST_ONE_LANGUAGE'));
            }
        }
        $this->render('profile', array('carer' => $carer, 'carerDocuments' => $carerDocuments, 'carerLanguages' => $carerLanguages, 'maintain' => true));
    }

    public function actionMaintainAccountSettings() {

        $carerId = Yii::app()->user->id;
        //reload  data
        $carer = Carer::loadModel($carerId);

        if (isset($_POST['Carer'])) {
            $carer->no_job_alerts = $_POST['Carer']['no_job_alerts'];
            $carer->deactivated = $_POST['Carer']['deactivated'];
            $success = $carer->save(false);
            if ($success) {
                Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_CHANGES_SAVED'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('texts', 'ERROR'));
            }
        }

        $this->render('accountSettings', array('carer' => $carer));
    }

    public function actionProfile() {

        $view = Wizard::VIEW_PROFILE;

        $carerId = Yii::app()->user->id;

        //check is user did not enter a wrong step in the RL
        $this->handleCarerSecurity($view);

        Wizard::setStepActive($view);

        //reload Carer data
        $carer = Carer::loadModel($carerId);

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            $request = Yii::app()->request;

            $carerExperience = new CarerExperience();
            $carerExperienceParam = $request->getPost('CarerExperience');

            $carerDocuments = array();
            $valid = true;

            //always delete all diplomas - too complicated to update, read again current values
            //CarerDocument::deleteDiplomas($carerId);

            if (!empty($_POST['CarerDocument'])) {

                foreach ($_POST['CarerDocument'] as $diplomaData) {

                    $carerDocument = new CarerDocument();
                    $carerDocument->setAttributes($diplomaData);
                    $carerDocument->id_carer = $carerId;

                    //Validate Carer Documents
                    //Any erroring document is discarded - no error showed to user
                    if (!$carerDocument->validate()) {

                        $errors4 = $carerDocument->getErrors();
                    } else {
                        $carerDocuments[] = $carerDocument;
                    }
                }
            }

            //Texts
            if (isset($_POST['personal_text'])) {

                $personalText = trim($_POST['personal_text']);

                if ($personalText != '') {
                    $this->saveText($personalText, CarerDocument::TEXT_PERSONALITY);
                }
            }

            if (isset($_POST['motivation_text'])) {

                $motivationText = trim($_POST['motivation_text']);

                if ($motivationText != '') {
                    $this->saveText($motivationText, CarerDocument::TEXT_MOTIVATION);
                }
            }

            $validLanguages = true;

            if (!empty($_POST['CarerLanguage'])) {

                foreach ($_POST['CarerLanguage'] as $carerLanguageData) {

                    $carerLanguage = new CarerLanguage();
                    $carerLanguage->id_carer = $carerId;
                    $carerLanguage->setAttributes($carerLanguageData);

                    $carerLanguages[] = $carerLanguage;

                    $validLanguages = $validLanguages && $carerLanguage->validate();
                }

                if ($validLanguages) {

                    if (!CarerLanguage::validateAll($carerLanguages)) {

                        $validLanguages = false;
                        Yii::app()->user->setFlash('error_languages', Yii::t('texts', 'ERROR_YOU_HAVE_ENTER_IDENTICAL_LANGUAGES'));
                        Yii::app()->user->setFlash('error', Yii::t('texts', 'ERROR_YOU_HAVE_ENTER_IDENTICAL_LANGUAGES'));
                    }
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('texts', 'ERROR_ENTER_AT_LEAST_ONE_LANGUAGE'));
                }
            } else {
                $carerLanguages[] = new CarerLanguage();
                $validLanguages = false;
                Yii::app()->user->setFlash('error', Yii::t('texts', 'ERROR_ENTER_AT_LEAST_ONE_LANGUAGE'));
                Yii::app()->user->setFlash('error_languages', Yii::t('texts', 'ERROR_ENTER_AT_LEAST_ONE_LANGUAGE'));
            }

            //VALIDATE
            $valid = $carer->validate() && $valid && $validLanguages;

            //$errors2 = $carerDocument->getErrors();
            $errors3 = $carer->getErrors();

            //SAVE/UPDATE both Carer and CarerAddress
            if ($valid) {

                foreach ($carerLanguages as $carerLanguage) {
                    $carerLanguage->save();
                }

                $carer->scenario = Carer::SCENARIO_UPDATE_CARER;
                $carer->save();

                if (isset($photoDocument)) {
                    $photoDocument->save();
                }

                foreach ($carerDocuments as $carerDocument) {
                    if (!$carerDocument->save()) {
                        $errors3 = $carerDocument->getErrors();
                    }
                }

                if (isset($_GET['navigation'])) {

                    if ($_GET['navigation'] == 'next') {
                        $nextStep = Wizard::VIEW_CONFIRMATION;
                    } elseif ($_GET['navigation'] == 'back') {

                        $nextStep = Wizard::VIEW_TYPE_WORK;
                    }
                } elseif (isset($_GET['toView'])) {

                    $nextStep = $_GET['toView'];
                }

                $currentStepIndex = Wizard::getCompletedStepIndex($view);
                $wizardCompleted = $carer->wizard_completed;

                if ($currentStepIndex > $wizardCompleted) {
                    $carer->wizard_completed = $currentStepIndex;
                }

                $carer->save(false);

                Wizard::setStepCompleted($view);

                $this->redirect(array($nextStep));
            }
        } else {
            //carer spoken languages
            $carerLanguages = $carer->carerLanguages;

            if (!isset($carerLanguages) || empty($carerLanguages)) {
                $carerLanguages[] = new CarerLanguage();
            }
        }

        $this->render('profile', array('carer' => $carer, 'carerLanguages' => $carerLanguages, 'maintain' => false));
    }

    public function actionConfirmation() {

        $view = Wizard::VIEW_CONFIRMATION;

        //check is user did not enter a wrong step in the RL
        $this->handleCarerSecurity($view);

        $carer = Carer::loadModel(Yii::app()->user->id);
        //$carer->wizard_completed = 1;
        //$carer->saveWizardCompleted();
        Yii::app()->user->wizard_completed = Wizard::CARER_LAST_STEP_INDEX;

        Wizard::setStepActive($view);

        Wizard::setStepCompleted($view);

        $this->render('confirmation', array('carer' => $carer,));
    }

    public function actionPopulateTypeWork() {

        //SELECT ALL
        $view = Wizard::VIEW_TYPE_WORK;

        //check is user did not enter a wrong step in the RL
        $this->handleCarerSecurity($view);

        Wizard::setStepActive($view);

        $errors = array();

        $carer = Carer::loadModel(Yii::app()->user->id);
        $carer->setScenario(Carer::SCENARIO_UPDATE_CARER_TYPEWORK);

        $conditionsAll = Condition::getConditions(Condition::TYPE_ACTIVITY);
        //$conditionsAll = array_merge($conditions, Condition::getConditions(Condition::TYPE_MENTAL));
//45	administer_drugs
//43	carer_car
//30	companionship
//31	home_cleaning
//32	meal_preparation
//44	personal_care
//40	pet
//41	public_transport
//42	user_car
//39	walking
        foreach ($conditionsAll as $condition) {

            try {
                $objectCondition = new CarerCondition();
                $objectCondition->id_carer = $carer->id;
                $objectCondition->id_condition = $condition->id;

                $objectCondition->save();
            } catch (CException $e) {
                
            }
        }

        try {
            $objectCondition = new CarerCondition();
            $objectCondition->id_carer = $carer->id;
            $objectCondition->id_condition = 53; //dementia

            $objectCondition->save();

            $objectCondition = new CarerCondition();
            $objectCondition->id_carer = $carer->id;
            $objectCondition->id_condition = 49; //disabled

            $objectCondition->save();
        } catch (CException $e) {
            
        }
        //add 
        $sql = 'DELETE FROM tbl_age_group WHERE id_carer = ' . $carer->id;
        Yii::app()->db->createCommand($sql)->execute();

        try {


            $ageGroup = new AgeGroup();
            $ageGroup->id_carer = $carer->id;
            $ageGroup->age_group = '1';
            $result = $ageGroup->save();

            $ageGroup = new AgeGroup();
            $ageGroup->id_carer = $carer->id;
            $ageGroup->age_group = '2';
            $result = $ageGroup->save();

            $ageGroup = new AgeGroup();
            $ageGroup->id_carer = $carer->id;
            $ageGroup->age_group = '3';
            $result = $ageGroup->save();

            $ageGroup = new AgeGroup();
            $ageGroup->id_carer = $carer->id;
            $ageGroup->age_group = '0';
            $result = $ageGroup->save();
        } catch (CException $e) {
            
        }

        $carer->hourly_work = Constants::DB_TRUE;
        $carer->live_in = Constants::DB_TRUE;

        $carer->live_in_work_radius = 5;
        $carer->hourly_work_radius = 5;
        $carer->work_with_male = true;
        $carer->work_with_female = true;


        $this->render('typeWork', array('carer' => $carer, 'maintain' => false, 'errors' => $errors, 'display' => true));
    }

    public function actionPopulate() {

        $carerId = Yii::app()->user->id;

        if ($carerId == null) {
            Wizard::initStepArrayCarer($this->id);
        }

        Wizard::setStepActive(Wizard::VIEW_DETAILS);
        $carer = Carer::loadModel($carerId);

        $carer->setScenario(Carer::SCENARIO_CREATE_CARER);

        $address = new Address;

        $carer->first_name = Random::getRandomClientFirstName();
        $carer->last_name = Random::getRandomClientLastName();

        $carer->date_birth = Random::getRandomDateBirth(1970, 1990);
        $carer->mobile_phone = Random::getRandomMobilePhone();
        $carer->gender = Random::getRandomGender();
        $carer->terms_conditions = true;
        $carer->legally_work = true;
        $carer->nationality = 'polish';

        $address->address_line_1 = 'Flat ' . Random::getRandomNumber();
        $address->address_line_2 = 'Cambridge Gardens';
        $address->city = 'London';
        $address->post_code = Random::getRandomUKPostCode();

        $this->render('details', array('carer' => $carer, 'address' => $address, 'maintain' => false)); //'carer' : name of variable in view
    }

    public function actionIndex() {

        if (Yii::app()->user->wizard_completed == Wizard::CARER_LAST_STEP_INDEX) {

            //wizard completed
            $this->redirect(array('myMissions'));
        } else {
            $this->redirect(array('registration'));
        }
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'carer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    function saveTypeWork(&$carer, $noNavigaton = false) {

        $errors = array();

        $request = Yii::app()->request;

        //READ AND SAVE AVAILABILITY DATA
        $availabilities = $carer->carerAvailabilities;

        $daysWeeks = CarerAvailability::getDaysWeeks();
        $daysWeeksKeys = array_keys($daysWeeks);

        $timeSlots = CarerAvailability::getTimeSlots();
        $timeSlotsKeys = array_keys($timeSlots);

        $atLeastOneTimeSlot = false;

        foreach ($daysWeeksKeys as $daysWeeksKey) {

            foreach ($timeSlotsKeys as $timeSlotsKey) {

                $value = $request->getPost('timeSlot_' . $daysWeeksKey . '_' . $timeSlotsKey);

                //search value
                $availabilityExists = false;
                $carerAvailabilityId = 0;
                foreach ($availabilities as $availability) {

                    if ($availability->time_slot == $timeSlotsKey && $availability->day_week == $daysWeeksKey) {

                        $availabilityExists = true;
                        $carerAvailabilityId = $availability->id;
                        break;
                    }
                }

                if (isset($value)) {

                    $atLeastOneTimeSlot = true;

                    if (!$availabilityExists) {

                        $carerAvailability = new CarerAvailability();
                        $carerAvailability->id_carer = $carer->id;
                        $carerAvailability->day_week = $daysWeeksKey;
                        $carerAvailability->time_slot = $timeSlotsKey;

                        $carerAvailability->save();
                    }
                } elseif ($availabilityExists) {

                    CarerAvailability::model()->deleteByPk($carerAvailabilityId);
                }
            }
        }

        //Read and SaveConditions data  
        $errors = Condition::saveConditions($carer, $request, -1, false, true); //-1 is no index
        //Read Age Group Data
        $ageGroups = AgeGroup::getAgeGroups();
        $ageGroupKeys = array_keys(AgeGroup::getAgeGroups());
        $carerAgeGroups = $carer->ageGroups;

        $carerAgeGroupsKeyed = array();

        //Buffer existing Age groups as Hashkey with age group unique Id
        foreach ($carerAgeGroups as $carerAgeGroup) {

            $carerAgeGroupsKeyed[$carerAgeGroup->age_group] = $carerAgeGroup->id;
        }

        $atLeastOneAgeGroup = false;

        foreach ($ageGroupKeys as $ageGroupKey) {

            $value = $request->getPost('ageGroup_' . $ageGroupKey);

            $ageGroupExists = isset($carerAgeGroupsKeyed[$ageGroupKey]);

            if (isset($value)) {

                $atLeastOneAgeGroup = true;

                if (!$ageGroupExists) {

                    $ageGroup = new AgeGroup();
                    $ageGroup->id_carer = $carer->id;
                    $ageGroup->age_group = $ageGroupKey;

                    $ageGroup->save();
                }
            } else {

                if ($ageGroupExists) {

                    AgeGroup::model()->deleteByPk($carerAgeGroupsKeyed[$ageGroupKey]);
                }
            }
        }

        $carer->refresh(); //to get up to date conditions and age groups, not stored in carer model
        //READ CARER DATA
        $carerParam = $request->getPost('Carer');

        if (isset($carerParam)) {
            $carer->live_in = $carerParam['live_in'];
            $carer->live_in_work_radius = $carerParam['live_in_work_radius'];
            $carer->hourly_work = $carerParam['hourly_work'];
            $carer->hourly_work_radius = $carerParam['hourly_work_radius'];
            $carer->work_with_male = $carerParam['work_with_male'];
            $carer->work_with_female = $carerParam['work_with_female'];

            if ($carer->live_in == Constants::DB_FALSE && $carer->hourly_work == Constants::DB_FALSE) { //0=nothing, 1=false, 2=true
                $errors['mission_type_errors'] = Yii::t('texts', 'CARER_CONTROLLER_ERROR1');
            }
        }

        //set error messages for stuff not in carer model
        if ($atLeastOneAgeGroup == false) {

            $errors['age_group_errors'] = Yii::t('texts', 'CARER_CONTROLLER_ERROR2');
        }

        if ($atLeastOneTimeSlot == false && $carer->hourly_work == Constants::DB_TRUE) {

            $errors['time_slot_errors'] = Yii::t('texts', 'CARER_CONTROLLER_ERROR3');
        }

        //VALIDATE CARER
        $valid = $carer->validate();
        $valid = $valid && count($errors) == 0;

        $errors2 = $carer->getErrors();

        //SAVE/UPDATE condition and carer
        if ($valid) {

            //wizard scenario
            if (!$noNavigaton) {

                $currentStepIndex = Wizard::getCompletedStepIndex(Wizard::VIEW_TYPE_WORK);
                $wizardCompleted = $carer->wizard_completed;

                if ($currentStepIndex > $wizardCompleted) {
                    $carer->wizard_completed = $currentStepIndex;
                }

                $successful = $carer->save();

                if (isset($_GET['navigation'])) {

                    if ($_GET['navigation'] == 'next') {
                        $nextStep = Wizard::VIEW_PROFILE;
                    } elseif ($_GET['navigation'] == 'back') {
                        $nextStep = Wizard::VIEW_DETAILS;
                    }
                } elseif (isset($_GET['toView'])) {

                    $nextStep = $_GET['toView'];
                }

                Wizard::setStepCompleted(Wizard::VIEW_TYPE_WORK);

                $this->redirect(array($nextStep));
            } else {
                //maintain scenario
                $successful = $carer->save();
            }
        } else {
            $errors['modelError'] = 'there is a model error'; //not displayed just for logic
        }

        return $errors;
    }

    public function actionMoney() {

        $carerId = Yii::app()->user->id;
        $dataProvider = CarerTransaction::getTransactions($carerId);

        $carer = Carer::loadModel($carerId);

        if (!empty($_POST['WithdrawalForm'])) {

            $model = new WithdrawalForm();
            $model->attributes = $_POST['WithdrawalForm'];

            $model->first_name = $carer->first_name;
            $model->last_name = $carer->last_name;

            if ($model->validate()) {

                //$price = $model->amount . '.' . $model->decimals;

                //CarerTransaction::createWithdraw($carer->id, $price);

                //update acount details
                $carer->sort_code = $model->sort_code;
                $carer->account_number = $model->account_number;
                $carer->save();

                //$model->amount = '';
                //$model->decimals = '';

                Yii::app()->user->setFlash('withdrawalRequest', Yii::t('texts', 'FLASH_WITHDRAWAL_REQUEST_SENT'));

                $this->redirect('money');
            }
            else{
                
            }
        } else {

            $model = new WithdrawalForm();
            //$model->attributes = $carer->attributes;
            $model->first_name = $carer->first_name;
            $model->last_name = $carer->last_name;
            $model->sort_code = $carer->sort_code;
            $model->account_number = $carer->account_number;
        }

        $this->render('money', array('dataProvider' => $dataProvider, 'model' => $model));
    }

    public function actionMyMissions() {

        $awaitingMissionsDataProvider = Mission::getAwaitingMissionsDataProvider(Yii::app()->user->id);

        $selectedMissionsDataProvider = Mission::getSelectedMissionsDataProvider(Yii::app()->user->id);

        $assignedMissionsDataProvider = Mission::getAssignedMissionsDataProvider(Yii::app()->user->id);

        $this->render('myMissions', array(
            'awaitingMissionsDataProvider' => $awaitingMissionsDataProvider,
            'selectedMissionsDataProvider' => $selectedMissionsDataProvider,
            'assignedMissionsDataProvider' => $assignedMissionsDataProvider,
            'scenario' => NavigationScenario::CARER_BACK_TO_CURRENT_MISSIONS));
    }

    public function handleCarerSecurity($view) {

        $notStarted = !Wizard::isStarted();
        $previousStepCompleted = Wizard::getPreviousStepCompleted($view);
        $stepAllCompleted = Wizard::isStepAllCompleted();

        if ($notStarted || //wizard not started
                $previousStepCompleted == false || //user did not complete the previous step
                ($stepAllCompleted && $view != Wizard::VIEW_CONFIRMATION)) { //all steps completed: can only see confirmation view
            //new requirement: if user is in view confirmation and presses back: stay on confirmation
            if (Wizard::isStepAllCompleted()) {//used logged in after registration
                $this->redirect(array('confirmation'));
            } else {

                if (Yii::app()->user->isGuest) {

                    $this->redirect(array('/site/login'));
                } else {

                    $wizardStep = Yii::app()->user->wizard_completed;

                    //user logged in on second time (wizards steps are gone)
                    if (isset($wizardStep) && $wizardStep == Wizard::CARER_LAST_STEP_INDEX) {

                        if ($view == 'profile') {
                            $this->redirect(array('maintainProfile'));
                        } elseif ($view == 'details') {
                            $this->redirect(array('maintainDetails'));
                        } elseif ($view == 'typeWork') {
                            $this->redirect(array('maintainTypeWork'));
                        } elseif ($view == 'confirmation') {
                            $this->redirect(array('myMissions'));
                        }
                    } else {
                        //if user enters ULR in browser wihtout starting the wizard
                        throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
                    }
                }
            }
        } elseif (!isset(Yii::app()->user->id) && $view != Wizard::VIEW_DETAILS) {

            //if user enters ULR in browser wihtout starting the wizard, he's redirected to the first step
            $this->redirect(array(Wizard::VIEW_DETAILS));
        } else {

            //OK

            $steps = Yii::app()->session['steps']; //debug

            if (Yii::app()->user->isGuest) {
                $wizardStep = 0;
            } else {
                $wizardStep = Yii::app()->user->wizard_completed;
            }

            //if completed 
            if ($wizardStep == Wizard::CARER_LAST_STEP_INDEX) {

                if ($view == 'profile') {
                    $this->redirect(array('maintainProfile'));
                } elseif ($view == 'details') {
                    $this->redirect(array('maintainDetails'));
                } elseif ($view == 'typeWork') {
                    $this->redirect(array('maintainTypeWork'));
                } elseif ($view == 'confirmation') {
                    $this->redirect(array('myMissions'));
                }
            }

            //no redirect continue with wizard
        }
    }

    /**
     * Used to display image after an upload (creted to fix bug IE8)
     */
    public function actionDisplayImage() {

        $carerDocumentId = $_REQUEST['documentId'];
        $entity = $_REQUEST['entity'];
        $documentType = $_REQUEST['type'];

        $carerDocument = CarerDocument::loadModel($carerDocumentId);

        $output = $this->renderPartial('_showImageDocument', array('carerDocument' => $carerDocument, 'entity' => $entity, 'type' => $documentType), true, false);
        echo $output;
    }

    public function actionUploadImage($type) {

        $documentType = $type;

        $path = Yii::getPathOfAlias('webroot.protected.helpers.include');
        $file = $path . '/includeUploadDocument.php';
        include $file;

        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        Yii::app()->clientScript->scriptMap['ejcrop.js'] = false;

        $carer = Carer::loadModel(Yii::app()->user->id);

        $entity = FileContent::getDocumentTypeFolder($documentType);

        switch ($documentType) {
            case Document::TYPE_PHOTO:
                $carerDocument = $carer->getPhoto();
                break;
        }

        $result['documentId'] = $carerDocument->id;
        $result['entity'] = $entity;
        $result['type'] = $documentType;

        $jsonDecode = CJSON::encode($result);

        $return = $jsonDecode;

        echo $return;
    }

    public function actionUploadDiploma() {

        //does not work with IE8
        //if (Yii::app()->request->isAjaxRequest) {

        $documentType = Document::TYPE_DIPLOMA;
        $path = Yii::getPathOfAlias('webroot.protected.helpers.include');
        $file = $path . '/includeUploadDialogDocument.php';
        include $file;

        UIServices::unregisterJQueryScripts();

        $output = Yii::t('texts', 'CARER_CONTROLLER_FILE_UPLOADED');

        $html = str_replace('"', "'", $output);
        $result['html'] = $html;

        $jsonDecode = json_encode($result);

        $jsonDecode = str_replace('XXX', $html, $jsonDecode);

        $return = $jsonDecode;

        echo $return;
        //}
    }

    public function actionUploadCriminalRecord() {

        //this does not work with IE8
        ///if (Yii::app()->request->isAjaxRequest) {

        $documentType = Document::TYPE_CRIMINAL;
        $path = Yii::getPathOfAlias('webroot.protected.helpers.include');
        $file = $path . '/includeUploadDialogDocument.php';
        include $file;

        UIServices::unregisterJQueryScripts();

        $output = Yii::t('texts', 'CARER_CONTROLLER_FILE_UPLOADED');

        $html = str_replace('"', "'", $output);
        $result['html'] = $html;

        $jsonDecode = json_encode($result);

        $jsonDecode = str_replace('XXX', $html, $jsonDecode);

        $return = $jsonDecode;

        echo $return;
        //}
    }

    /**
     * For Diploma and CRB check
     */
    public function actionDocumentAdd() {

        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_POST['CarerDocument'])) {

                $fileId = Yii::app()->session['file'];
                $divId = $_POST['divId'];

                if (!isset($fileId)) {

                    $message = Yii::t('texts', 'CARER_CONTROLLER_ERROR4');

                    echo CJSON::encode(array(
                        'status' => 'saveFailure',
                        'html' => '<span id="createDialogErrorMessage' . $divId . '" class = "rc-error">' . $message . '</span>',
                    ));
                } else {

                    $model = new CarerDocument('insertDiplomaCRB');
                    $params = array();
                    parse_str($_POST['CarerDocument'], $params);

                    $id_document = $params['CarerDocument']['id_document'];

                    $model->id_document = $id_document;
                    $model->year_obtained = $params['CarerDocument']['year_obtained'];
                    $model->id_carer = Yii::app()->user->id;
                    $model->id_content = $fileId;
                    $model->status = CarerDocument::STATUS_UNAPPROVED;

                    try {

                        if ($model->save()) {

                            if (Yii::app()->request->isAjaxRequest) {

                                $documentsIndexes = Yii::app()->session['DocumentsIndexes'];

                                if (!empty($documentsIndexes)) {
                                    $maxIndex = max(array_keys($documentsIndexes));
                                    $index = $maxIndex + 1;
                                } else {
                                    $index = 0;
                                }

                                $documentsIndexes[$index] = $model->id;
                                Yii::app()->session['DocumentsIndexes'] = $documentsIndexes;

                                UIServices::unregisterJQueryScripts();

                                //generate new HTML
                                $output = $this->renderPartial('_showDocument', array(
                                    'carerDocument' => $model,
                                    'index' => $index,
                                        ), true, true);

                                echo CJSON::encode(array(
                                    'status' => 'success',
                                    'html' => $output,
                                ));

                                Yii::app()->session['file'] = null;
                            }
                        } else {

                            //CarerDocument_year_obtained
                            //$errorMessage = CActiveForm::validate(array($model), null, false);
                            $errors = $model->errors;
                            $errorMessage = '';
                            foreach ($errors as $error) {
                                $errorMessage .= $error[0] . '<br>';
                            }

                            // {"CarerDocument_id_document":["Select a document."],"CarerDocument_year_obtained":["Select a year."]
                            //$errorMessage = str_replace('CarerDocument_id_document', 'id_document' . $divId, $errorMessage);
                            //$errorMessage = str_replace('CarerDocument_year_obtained', 'year_obtained' . $divId, $errorMessage);
                            //<div id="id_documentcriminalRecords" class="rc-error clsError" style="display:none"></div>
                            //<div id="year_obtainedcriminalRecords" class="rc-error clsError" style="display:none"></div>

                            echo CJSON::encode(array(
                                'status' => 'saveFailure', //CHANGED BECAUSE OF AJAX VALIDATION PROBLEM - failure to saveFailure
                                //'div' => 'Diploma successfully added',
                                'html' => $errorMessage,
                            ));
                        }
                    } catch (Exception $e) {

                        $message = Yii::t('texts', 'CARER_CONTROLLER_ERROR5');

                        echo CJSON::encode(array(
                            'status' => 'saveFailure',
                            'html' => $message,
                        ));
                    }
                }
            }
        }
    }

    public function actionShowPhotoDialog() {

        if (Yii::app()->request->isAjaxRequest) {

            $id = $_POST['documentId'];
            $uniqueId = $_POST['uniqueId'];
            $photo = CarerDocument::loadModel($id);

            UIServices::unregisterJQueryScripts();

            //Yii::app()->clientScript->scriptMap['ejcrop.js'] = false;   //

            $output = $this->renderPartial('_photoDialog', array('photo' => $photo, 'uniqueId' => $uniqueId), true, true);

            //Yii::app()->clientScript->scriptMap['ejcrop.js'] = false;  

            echo $output;
        }
    }

    //dialog for Diplomas and CRB
    public function actionShowDialog($documentType) {

        if (Yii::app()->request->isAjaxRequest) {

            $model = new CarerDocument;

            if (!isset($_POST['CarerDocument'])) { //FIRST TIME - this method is called several time when CActiveForm's enableAjaxValidation = true 
                Yii::app()->session['file'] = null;
            }


            UIServices::unregisterJQueryScripts();
            //UIServices::unregisterAllScripts();
            //$cs = Yii::app()->clientScript;
            //$cs->registerCoreScript('jquery.yiiactiveform');

            $output = $this->renderPartial('_documentDialog', array('model' => $model, 'documentType' => $documentType), true, true);

            echo $output;
        }
    }

    /*
     * Use to delete a Photo, Driving Licence or ID, only the carer's version
     */

    public function actionDocumentDelete() {

        if (Yii::app()->request->isAjaxRequest) {

            $type = $_POST['type'];
            $active = $_POST['active'];

            $carer = Carer::loadModel(Yii::app()->user->id);

            //only in step profile of wizard 1
            if ($carer->wizard_completed != Wizard::CARER_LAST_STEP_INDEX) {

                //Texts
                if (isset($_POST['personal_text'])) {

                    $personalText = trim($_POST['personal_text']);
                    if ($personalText != '') {
                        $this->saveText($personalText, CarerDocument::TEXT_PERSONALITY);
                    }
                }

                if (isset($_POST['motivation_text'])) {

                    $motivationText = trim($_POST['motivation_text']);
                    if ($motivationText != '') {
                        $this->saveText($motivationText, CarerDocument::TEXT_MOTIVATION);
                    }
                }
            }

            //obsolete stuff still used for div ids
            $entity = FileContent::getDocumentTypeFolder($type);

            $carerDocument = null;

            UIServices::unregisterJQueryScripts();
            Yii::app()->clientScript->scriptMap['ejcrop.js'] = false;   //

            switch ($type) {
                case Document::TYPE_PHOTO:
                    $carerDocument = $carer->getPhoto();
                    $carerDocument->delete();
                    echo $this->renderPartial('_showPhoto', array('carer' => $carer), true, true);
                    break;
                case Document::TYPE_IDENTIFICATION:
                    $carerDocument = $carer->getIdentification();
                    $carerDocument->delete();
                    echo $this->renderPartial('_showImageDocument', array('entity' => $entity, 'type' => $type), true, true);
                    break;
                case Document::TYPE_DRIVING_LICENCE:
                    $carerDocument = $carer->getDrivingLicence();
                    $carerDocument->delete();
                    echo $this->renderPartial('_showImageDocument', array('entity' => $entity, 'type' => $type), true, true);
                    break;
            }
        }
    }

    /*
     * Use to delete a Diploma or Criminal Record
     */

    public function actionDiplomaCRBDelete($index) {

        if (Yii::app()->request->isAjaxRequest) {

            $documentsIndexes = Yii::app()->session['DocumentsIndexes'];

            $docId = $documentsIndexes[$index];

            $document = CarerDocument::loadModel($docId);

            $document->delete();

            unset($documentsIndexes[$index]);

            Yii::app()->session['DocumentsIndexes'] = $documentsIndexes;
        }
    }

    public function actionGetImage($fileContent, $doc) {

        $carerDocument = CarerDocument::loadModel($doc);

        //I do this stupid thing because the url must be unique to display new picture
        $file = $carerDocument->getFile();
        $crop = $carerDocument->getCropFile();

        if ($file->id == $fileContent) {

            $fileContentObj = $file;
        } else {
            $fileContentObj = $crop;
        }

        if (ob_get_contents())
            ob_end_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-length: ' . $fileContentObj->size);
        header('Content-Type: ' . $fileContentObj->extension);
        // header('Content-Disposition: attachment; filename=' . 'name');

        echo $fileContentObj->content;
    }

    public function actionCancelMotivationText() {

        if (Yii::app()->request->isAjaxRequest) {
            $carerText = CarerDocument::getMotivationText(Yii::app()->user->id, CarerDocument::UNACTIVE);

            if (isset($carerText)) {
                echo $carerText->text;
            }
        }
    }

    public function actionCancelPersonalText() {

        if (Yii::app()->request->isAjaxRequest) {
            $carerText = CarerDocument::getPersonalityText(Yii::app()->user->id, CarerDocument::UNACTIVE);

            if (isset($carerText)) {
                echo $carerText->text;
            }
        }
    }

    /**
     * 
     * @param type $text  CarerDocument::TEXT_MOTIVATION or  CarerDocument::TEXT_PERSONALITY
     * @param type $type
     */
    private function saveText($text, $type) {

        if ($type == CarerDocument::TEXT_MOTIVATION) {
            $carerText = CarerDocument::getMotivationText(Yii::app()->user->id, CarerDocument::UNACTIVE);
        } elseif ($type == CarerDocument::TEXT_PERSONALITY) {
            $carerText = CarerDocument::getPersonalityText(Yii::app()->user->id, CarerDocument::UNACTIVE);
        }

        if (!isset($carerText)) {
            $carerText = CarerDocument::createNewTextDocument($type, Yii::app()->user->id, $text);
        } else {
            $carerText->text = $text;
            $carerText->status = CarerDocument::STATUS_UNAPPROVED;
            $carerText->reject_reason = '';
            $res = $carerText->save();
        }

        if (Yii::app()->request->isAjaxRequest) {

            $data['text'] = $text;
            $data['statusText'] = $carerText->displayDocumentStatusWithStyle();
            $data['status'] = CarerDocument::STATUS_UNAPPROVED;

            echo json_encode($data);
        }

        //Emails::sendToAdmin_CarerTextToApprove();
    }

    public function actionSaveMotivationText($text) {

        $this->saveText($text, CarerDocument::TEXT_MOTIVATION);
    }

    public function actionSavePersonalText($text) {

        $this->saveText($text, CarerDocument::TEXT_PERSONALITY);
    }

    public function actionSaveCarOwner($value) {

        $carer = Carer::loadModel(Yii::app()->user->id);

        $carer->car_owner = $value;

        $carer->save();
    }

    public function actionMissionDetails($id, $scenario) {

        Mission::authorizeCarer($id);
        $mission = Mission::loadModel($id);

        $this->render('missionDetails', array('mission' => $mission, 'scenario' => $scenario));
    }

    //APPLY CHOOSE MISSION
    public function actionApply($id) {

        if (Yii::app()->request->isAjaxRequest) {

            //only apply if mission is unapplied
            $status = Mission::getCarerMissionStatus(Yii::app()->user->id, $id);

            if ($status == MissionCarers::UNAPPLIED) {

                $returnValue = Mission::apply(Yii::app()->user->id, $id);

                if ($returnValue == 2) {
                    echo 'You have the job. See in My Shifts';
                } else {
                    echo CHtml::link(Yii::t('texts', 'BUTTON_CANCEL_APPLICATION'), Yii::app()->createUrl("carer/cancelAppliedChooseMissionDialog", array("id" => $id)), array('class' => 'cancelAppliedButton rc-linkbutton-white-small'));
                }
            }

            //reload table
            //$availableMissionsDataProvider = Mission::getAvailableMissionsDataProvider(Yii::app()->user->id);
        }
    }

    //CANCEL APPLIED CHOOSE MISSION
    public function actionCancelAppliedChooseMission() {

        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
            } else {
                $id = $_GET['id'];
            }

            Mission::cancelApplied(Yii::app()->user->id, $id);

            $availableMissionsDataProvider = Mission::getAvailableMissionsDataProvider(Yii::app()->user->id);

            $output = $this->renderPartial('_missionsTable', array('id' => Tables::AVAILABLE_MISSIONS_GRID, 'dataProvider' => $availableMissionsDataProvider,
                'columns' => Tables::getCarerAvailableMissionsColumnConfig(true)), true, false);

            echo $output;
        }

        //   echo CHtml::link('Apply', Yii::app()->createUrl("carer/apply", array("id" => $id)), array('class' => 'applyButton rc-linkbutton-white-small'));
    }

    public function actionCancelAppliedDialog($id) {

        if (Yii::app()->request->isAjaxRequest) {

            echo UIServices::showMissionDialog('cancelApplied', $id, Yii::t('texts', 'NOTE_ARE_YOU_SURE_YOU_WANT_TO_CANCEL_YOUR_APPLICATION'), Yii::t('texts', 'BUTTON_I_WANT_TO_CANCEL_MY_APPLICATION'), Yii::t('texts', 'BUTTON_I_DONT_KNOW_YET'), Yii::t('texts', 'HEADER_CANCEL_APPLICATION'));
        }
    }

    public function actionCancelAppliedChooseMissionDialog($id) {

        if (Yii::app()->request->isAjaxRequest) {

            echo UIServices::showMissionDialog('cancelAppliedChoose', $id, Yii::t('texts', 'NOTE_ARE_YOU_SURE_YOU_WANT_TO_CANCEL_YOUR_APPLICATION'), Yii::t('texts', 'BUTTON_I_WANT_TO_CANCEL_MY_APPLICATION'), Yii::t('texts', 'BUTTON_I_DONT_KNOW_YET'), Yii::t('texts', 'HEADER_CANCEL_APPLICATION'));
        }
    }

    //CANCEL APPLIED BOOKED MISSION
    public function actionCancelAppliedBookedMission() {

        if (Yii::app()->request->isAjaxRequest) {

            $id = $_POST['id'];

            Mission::cancelApplied(Yii::app()->user->id, $id);

            $awaitingMissionsDataProvider = Mission::getAwaitingMissionsDataProvider(Yii::app()->user->id);

            echo $this->renderPartial('_missionsTable', array('id' => Tables::AWAITING_MISSIONS_GRID, 'dataProvider' => $awaitingMissionsDataProvider, 'columns' => Tables::getCarerAwaitingMissionsColumnConfig()));
        }
    }

    //CONFIRM SELECTED DIALOG
    public function actionConfirmSelectedDialog($id) {

        if (Yii::app()->request->isAjaxRequest) {

            echo UIServices::showMissionDialog('confirmSelected', $id, Yii::t('texts', 'CARER_CONTROLLER_CONFIRM_MISSION_NOTE'), Yii::t('texts', 'CARER_CONTROLLER_CONFIRM_MISSION_BUTTON_YES'), Yii::t('texts', 'BUTTON_I_DONT_KNOW_YET'), Yii::t('texts', 'CARER_CONTROLLER_CONFIRM_MISSION_POPUP_TITLE'));
        }
    }

    //CANCEL SELECTED DIALOG
    public function actionCancelSelectedDialog($id) {

        if (Yii::app()->request->isAjaxRequest) {

            echo UIServices::showMissionDialog('cancelSelected', $id, Yii::t('texts', 'NOTE_DO_YOU_WANT_TO_CANCEL_SELECTION'), Yii::t('texts', 'BUTTON_I_WANT_TO_CANCEL_MY_SELECTION'), Yii::t('texts', 'BUTTON_I_DONT_KNOW_YET'), Yii::t('texts', 'HEADER_CANCEL_SELECTION'));
        }
    }

    //CONFIRM SELECTED
    public function actionConfirmSelected() {

        if (Yii::app()->request->isAjaxRequest) {
            $id = $_POST['id'];

            // Mission::assign($carerId, $id);

            $mission = Mission::loadChild($id);

            $booking = $mission->booking;


            $booking->type = Booking::TYPE_HOURLY;
            $booking->save();

            $carerId = Yii::app()->user->id;

            $conversation = Conversation::getConversation($booking->client->id, $carerId);

            //change status of all missions
            foreach ($booking->missions as $mission) {
                Mission::assign($carerId, $mission->id);
            }

            //add a confirmation message for Carer
            $messageTextCarer = Yii::t('texts', 'NOTE_BOOKING_CONFIRMATION_CARER', array('{bookingId}' => $booking->id));

            //$text, $author, $type, $visibleBy
            $conversation->createMessage($messageTextCarer, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CARER);

            //add a confirmation message for Client
            $messageTextClient = Yii::t('texts', 'NOTE_BOOKING_CONFIRMATION_CLIENT', array('{bookingId}' => $booking->id));
            $conversation->createMessage($messageTextClient, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CLIENT);

            /////

            $assignedMissionsDataProvider = Mission::getAssignedMissionsDataProvider(Yii::app()->user->id);
            $selectedMissionsDataProvider = Mission::getSelectedMissionsDataProvider(Yii::app()->user->id);

            UIServices::unregisterJQueryScripts();

            $result = $this->renderPartial('_missionsTables', array(
                'assignedMissionsDataProvider' => $assignedMissionsDataProvider,
                'selectedMissionsDataProvider' => $selectedMissionsDataProvider,
                'scenario' => NavigationScenario::CARER_BACK_TO_CURRENT_MISSIONS)
                    , true, false);

            Emails::sendToCarer_ConfirmedForMission(Carer::loadModel($carerId));

            echo $result;
        }
    }

    //CANCEL SELECTED 
    public function actionCancelSelected() {

        if (Yii::app()->request->isAjaxRequest) {

            $id = $_POST['id'];

            //Mission::cancelSelected(Yii::app()->user->id, $id);
            //NEW CODE
            $mission = Mission::loadChild($id);

            $booking = $mission->booking;

            //set type to decline
            $booking->type = Booking::TYPE_DECLINED;
            $booking->save();

            $resultArray = $booking->refund(true); //$byPassMissionsNotStarted
            //notify carer and client

            $conversation = Conversation::getConversation($booking->client->id, Yii::app()->user->id);

            //add a message for Carer
            $carerMessageText = Yii::t('texts', 'NOTE_BOOKING_DECLINED_CARER', array('{bookingId}' => $booking->id));
            //$text, $author, $type, $visibleBy
            $conversation->createMessage($carerMessageText, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CARER);

            //add a message for Client
            $clientMessageText = Yii::t('texts', 'NOTE_BOOKING_DECLINED_CLIENT', array('{bookingId}' => $booking->id));
            $conversation->createMessage($clientMessageText, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CLIENT);

            //END NEW CODE

            $selectedMissionsDataProvider = Mission::getSelectedMissionsDataProvider(Yii::app()->user->id);
            $columns = Tables::getCarerSelectedMissionsColumnConfig(NavigationScenario::CARER_BACK_TO_CURRENT_MISSIONS);

            echo $this->renderPartial('_missionsTable', array('id' => Tables::ASSIGNED_MISSIONS_GRID,
                'dataProvider' => $selectedMissionsDataProvider,
                'columns' => $columns,
            ));
        }
    }

    //CANCEL ASSIGN DIALOG
    public function actionCancelAssignedDialog($id) {

        if (Yii::app()->request->isAjaxRequest) {

            echo UIServices::showMissionDialog('cancelAssigned', $id, Yii::t('texts', 'CARER_CONTROLLER_CANCEL_MISSION_NOTE'), Yii::t('texts', 'CARER_CONTROLLER_CANCEL_MISSION_BUTTON_YES'), Yii::t('texts', 'BUTTON_I_DONT_KNOW_YET'), Yii::t('texts', 'HEADER_CANCEL_MISSION'));
        }
    }

    //CANCEL ASSIGNED
    public function actionCancelAssigned() {

        if (Yii::app()->request->isAjaxRequest) {

            $id = $_POST['id'];

            Mission::authorizeCarer($id);
            $mission = Mission::loadModel($id);

            $mission->cancelAssigned(Yii::app()->user->id);

            Emails::sendToShiftAdmin_CarerCancelledAssignedMission(Yii::app()->user->id, $mission->id);

            $assignedMissionsDataProvider = Mission::getAssignedMissionsDataProvider(Yii::app()->user->id);

            echo $this->renderPartial('_missionsTable', array('id' => Tables::ASSIGNED_MISSIONS_GRID,
                'dataProvider' => $assignedMissionsDataProvider,
                'columns' => Tables::getCarerAssignedMissionsColumnConfig(NavigationScenario::CARER_BACK_TO_CURRENT_MISSIONS)));
        }
    }

    //DISCARD cancelled by client mission
    public function actionDiscardMission($id, $tableDiv) {

        if (Yii::app()->request->isAjaxRequest) {

            //$mission = Mission::loadModel($id);
            //$mission->discardByCarer();

            Mission::setCarerMissionDiscarded(Yii::app()->user->id, $id, true);
            //MissionCarers::setCarerSelected($id, Yii::app()->user->id);

            switch ($tableDiv) {

                case Tables::ASSIGNED_MISSIONS_GRID:
                    $scenario = NavigationScenario::CARER_BACK_TO_CURRENT_MISSIONS;
                    $dataProvider = Mission::getAssignedMissionsDataProvider(Yii::app()->user->id);
                    $columns = Tables::getCarerAssignedMissionsColumnConfig($scenario);
                    break;

                case Tables::SELECTED_MISSIONS_GRID:
                    $scenario = NavigationScenario::CARER_BACK_TO_CURRENT_MISSIONS;
                    $dataProvider = Mission::getSelectedMissionsDataProvider(Yii::app()->user->id);
                    $columns = Tables::getCarerSelectedMissionsColumnConfig($scenario);
                    break;

                case Tables::AWAITING_MISSIONS_GRID:
                    $dataProvider = Mission::getAwaitingMissionsDataProvider(Yii::app()->user->id);
                    $columns = Tables::getCarerAwaitingMissionsColumnConfig();
                    break;
            }


            echo $this->renderPartial('_missionsTable', array('id' => $tableDiv, 'dataProvider' => $dataProvider, 'columns' => $columns));
        }
    }

    public function actionChooseMission() {

        $carerId = Yii::app()->user->id;

        $dataProvider = Mission::getAvailableMissionsDataProvider($carerId);

        $carer = Carer::loadModel($carerId);
        $carerActive = $carer->isActive();

        $carer->setRequiredDocumentText();

        $this->render('chooseMission', array('dataProvider' => $dataProvider, 'carerActive' => $carerActive));
    }

    public function actionMissionsComplaint() {

        $dataProvider = Mission::getComplaintMissionsCarerDataProvider(Yii::app()->user->id);

        $this->render('missionsComplaint', array('dataProvider' => $dataProvider, 'scenario' => NavigationScenario::CARER_BACK_TO_OPENED_COMLAINTS));
    }

    public function actionMissionsVerifying() {

        $finishedMissions = Mission::getFinishedUnpaidMissions(Yii::app()->user->id);

        $this->render('missionsVerifying', array('finishedMissions' => $finishedMissions, 'scenario' => NavigationScenario::CARER_BACK_TO_MISSIONS_VERIFIED));
    }

    public function actionMissionsHistory() {

        $dataProvider = Mission::getMissionsHistoryDataProvider(Yii::app()->user->id);

        $this->render('missionsHistory', array('dataProvider' => $dataProvider, 'scenario' => NavigationScenario::CARER_BACK_TO_MISSIONS_HISTORY));
    }

    public function actionMissionInvoice($id, $scenario) {

        Mission::authorizeCarer($id);
        $mission = Mission::loadModel($id);

        $this->render('/invoice/invoice', array('missions' => array($mission), 'user' => Constants::USER_CARER, 'scenario' => $scenario));
    }

    public function actionComplaintPage($id, $scenario) {

        Mission::authorizeCarer($id);
        $mission = Mission::loadModel($id);

        $complaintCarers = $mission->complaintsCarer;
        if (empty($complaintCarers)) {
            $complaintCarer = null;
        } else {
            $complaintCarer = $complaintCarers[0];
        }

        $complaintClients = $mission->complaintsClient;
        if (empty($complaintClients)) {
            $complaintClient = null;
        } else {
            $complaintClient = $complaintClients[0];
        }

        $this->render('missionComplaintPage', array('mission' => $mission, 'complaintCarer' => $complaintCarer,
            'complaintClient' => $complaintClient, 'scenario' => $scenario));
    }

    public function actionHandleCrop() {


        $rootFolder = Yii::getPathOfAlias('application');
        $fileName = Yii::app()->session['fileName']; // $_POST['fileName'];
        $originalFullPath = $rootFolder . '/../assets/' . $fileName;

        if (file_exists($originalFullPath)) {

            $doc = CarerDocument::loadModel($_POST['id']);

            $docFile = $doc->getFile();

            $docWidth = $docFile->width;

            $width = 400;

            $ratio = $docWidth / $width;

            $saveToFilePath = Yii::getPathOfAlias('webroot.assets'); //. DIRECTORY_SEPARATOR . 'cropZoomTest';

            Yii::import('ext.jcrop.EJCropper');
            $jcropper = new EJCropper();
            $jcropper->thumbPath = $saveToFilePath; //'/my/images/thumbs';
            // some settings ...
            //$jcropper->jpeg_quality = 95;
            //$jcropper->png_compression = 8;
            $jcropper->targ_w = 96;
            $jcropper->targ_h = 120;

            // get the image cropping coordinates (or implement your own method)

            $x = $ratio * $_POST['imageId_x'];
            $y = $ratio * $_POST['imageId_y'];
            $h = $ratio * $_POST['imageId_h'];
            $w = $ratio * $_POST['imageId_w'];

            $coords = array('x' => $x, 'y' => $y, 'h' => $h, 'w' => $w);

            //$coords = $jcropper->getCoordsFromPost($_POST);
            // returns the path of the cropped image, source must be an absolute path.

            $fullFileName = $jcropper->crop($originalFullPath, $coords);

            //store in db
            $fileContent = new FileContentPhoto();
            $content = file_get_contents($fullFileName);

            $imageSize = getimagesize($fullFileName);
            $fileSize = filesize($fullFileName);

            //$fileContent->name = $fileName;
            $fileContent->type = 'application/octet-stream';
            $fileContent->size = $fileSize;
            //$fileContent->path = '';
            $fileContent->width = $imageSize[0];
            $fileContent->height = $imageSize[1];
            $fileContent->content = $content;
            $fileContent->extension = 'jpeg';

            $errors = $fileContent->validate();

            if ($fileContent->save()) {

                //delete temp file
                unlink($fullFileName);
            }

            $fileId = $fileContent->id;

            $doc->id_content_crop = $fileId;
            $res = $doc->save(false);
            $doc->refresh();

            UIServices::unregisterJQueryScripts();
            Yii::app()->clientScript->scriptMap['ejcrop.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.Jcrop.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.Jcrop.min.js'] = false;

            $html = $this->renderPartial('_cropAndDelete', array('photo' => $doc), true, false);

            echo $html;
        } else {
            echo 'error_file_does_not_exist';
        }
    }

//    public function actionAddComplaintDialog() {
//
//        if (Yii::app()->request->isAjaxRequest) {
//
//            $missionId = $_POST['missionId'];
//            $user = $_POST['user'];
//
//            UIServices::unregisterAllScripts();
//
//            $output = $this->renderPartial('/complaint/_createComplaintDialog', array('missionId' => $missionId, 'user' => $user), true, true);
//
//            echo $output;
//        }
//    }
//
//    public function actionAddComplaint() {
//
//        if (Yii::app()->request->isAjaxRequest) {
//
//            UIServices::unregisterAllScripts();
//
//            $missionId = $_POST['id_mission'];
//            $user = $_POST['user'];
//
//            Mission::authorizeCarer($missionId);
//            $mission = Mission::loadModel($missionId);
//
//            $author = ComplaintPost::AUTHOR_CARER;
//            $text = $_POST['ComplaintPost']['text'];
//
//            $newPost = Complaint::addPost($missionId, $user, $author, $user, $text);
//            $complaintId = $newPost->id_complaint;
//
//            Emails::sendToAdmin_NewComplaint();
//
//            $complaint = Complaint::loadModel($complaintId);
//
//            $output = $this->renderPartial('/complaint/_complaint', array('mission' => $mission, 'complaint' => $complaint, 'user' => $user)
//                    , true, true);
//
//            echo $output;
//        }
//    }

    public function actionAddLanguage() {

        $carerLanguage = new CarerLanguage();
        $availableLanguages = Languages::getLanguages();
        $availableLanguagesLevels = Languages::getLanguageLevels();

        $index = $_POST['index'];

        echo $this->renderPartial('_language', array('carerLanguage' => $carerLanguage,
            'availableLanguages' => $availableLanguages,
            'availableLanguagesLevels' => $availableLanguagesLevels,
            'index' => $index + 1,)
        );
    }

    public function actionDeleteLanguage() {

        $this->renderPartial('_languages', array('carerLanguages' => $carerLanguages));
    }

    public function actionMyClients() {

        $carerId = Yii::app()->user->id;

        $carer = Carer::loadModel($carerId);

        $this->render('myClients', array('carer' => $carer));
    }

    public function actionConversation($id) {

        $conversation = Conversation::loadModel($id);
        $carerId = Yii::app()->user->id;
        $newMessage = new Message();

        $carer = Carer::loadModel($carerId);

        //check conversation belong to carer
        if ($carer->id != $conversation->id_carer) {
            //throw exception
        }

        $this->render('carerConversation', array('conversation' => $conversation, 'newMessage' => $newMessage));
    }

    public function actionSendMessage() {

        $messageText = $_POST['newMessage'];
        $conversationId = $_POST['conversationId'];

        $conversation = Conversation::loadModel($conversationId);

        $carerMessage = $conversation->createMessage($messageText, Constants::USER_CARER, Message::TYPE_MESSAGE, Constants::USER_ALL);
        $carer = $conversation->carer;
        $client = $conversation->client;

        Emails::sendToClient_NewCarerMessage($client, $carer, $messageText, $conversationId);

        $html = $this->renderPartial('/conversation/_message', array('message' => $carerMessage, 'viewer' => Constants::USER_CARER, 'carer' => $carer, 'client' => $client), true);

        echo $html;
    }

    public function actionAcceptBooking() {

        if (Yii::app()->request->isAjaxRequest) {

            $bookingId = $_POST['bookingId'];
            $messageId = $_POST['messageId'];
            $carerId = Yii::app()->user->id;

            $message = Message::loadModel($messageId);
            $conversation = $message->conversation;

            $booking = Booking::loadModel($bookingId);
            $booking->type = Booking::TYPE_HOURLY;
            $booking->save();

            //change status of all missions
            foreach ($booking->missions as $mission) {
                Mission::assign($carerId, $mission->id);
            }

            //add a confirmation message for Carer
            $messageTextCarer = Yii::t('texts', 'NOTE_BOOKING_CONFIRMATION_CARER', array('{bookingId}' => $booking->id));

            //$text, $author, $type, $visibleBy
            $carerMessage = $conversation->createMessage($messageTextCarer, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CARER);
            $carer = $conversation->carer;
            $client = $conversation->client;

            $html = $this->renderPartial('/conversation/_message', array('message' => $carerMessage, 'viewer' => Constants::USER_CARER, 'carer' => $carer, 'client' => $client), true);

            //add a confirmation message for Client
            $messageTextClient = Yii::t('texts', 'NOTE_BOOKING_CONFIRMATION_CLIENT', array('{bookingId}' => $booking->id));
            $conversation->createMessage($messageTextClient, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CLIENT);

            echo $html;
        }
    }

    public function actionDeclineBooking() {

        if (Yii::app()->request->isAjaxRequest) {

            $bookingId = $_POST['bookingId'];
            $messageId = $_POST['messageId'];

            //do refund
            $booking = Booking::loadModel($bookingId);

            //set type to decline
            $booking->type = Booking::TYPE_DECLINED;
            $booking->save();

            $resultArray = $booking->refund(true); //$byPassMissionsNotStarted
            //notify carer and client
            $message = Message::loadModel($messageId);
            $conversation = $message->conversation;

            //add a message for Carer
            $messageText = Yii::t('texts', 'NOTE_BOOKING_DECLINED_CARER', array('{bookingId}' => $booking->id));
            //$text, $author, $type, $visibleBy
            $carerMessage = $conversation->createMessage($messageText, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CARER);
            $carer = $conversation->carer;
            $client = $conversation->client;
            $html = $this->renderPartial('/conversation/_message', array('message' => $carerMessage, 'viewer' => Constants::USER_CARER, 'carer' => $carer, 'client' => $client), true);

            //add a message for Client
            $messageText = Yii::t('texts', 'NOTE_BOOKING_DECLINED_CLIENT', array('{bookingId}' => $booking->id));
            $conversation->createMessage($messageText, Constants::USER_ADMIN, Message::TYPE_ADMIN, Constants::USER_CLIENT);
        }
    }

}

