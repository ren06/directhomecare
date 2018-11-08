<?php

class SiteController extends Controller {

    public $layout = '//layouts/layoutSite';

    /**
     * Declares class-based actions.
     */
    public function actions() {

        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // 
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
            ShowPriceAction::NAME => array(
                'class' => 'ShowPriceAction',
            ),
//            SelectQuoteTabAction::NAME => array(
//                'class' => 'SelectQuoteTypeAction',
//            ),
        );
    }

    public function filters() {

        $filters = array();
        $filters[] = 'accessControl';

        if (Yii::app()->user->isGuest == true) {
            $filters[] = array(
                'COutputCache + site', //, carerJobs', //GET only so it's fine (landing page)
                'duration' => 86400,
                'requestTypes' => array('GET'),
                'varyByParam' => array('view'),
            );
        }
        return $filters;
    }

    public function accessRules() {
        return array(
            array('deny',
                'actions' => array('findCarers', 'signupLogin', 'signUp', 'changeDateTimes'
                ),
                'expression' => "UserIdentityUser::isCarer()", //'expression'=>'isset($user->role) && ($user->role==="editor")'              
            ),
            array('allow',
                'actions' => array('selectDates'),
                'expression' => 'UserIdentityUser::isClient()', //'expression'=>'isset($user->role) && ($user->role==="editor")'              
            ),
//            array('allow',
//                'actions'=>array('*'),
//                'users'=>array('*'),
//            ),
        );
    }

    public function actionImages() { //fixes a bug in site/login wiht logs
//        if (file_exists($filePath)) {
//            header('Content-Type: image/jpeg');
//            header('Content-Length: ' . filesize($filePath));
//            readfile($filePath);
//        } else {
//            return "";
//        }
        return "";
    }

    public function actionKW($pageTarget, $keyWord) {

        Yii::app()->session['adKeyWord'] = $keyWord;

        if ($pageTarget == 'index') {
            $this->redirect(array('site/index'));
        } else if ($pageTarget == 'carerJobs') {
            $this->redirect(array('site/carerJobs'));
        } else {//if ($pageTarget == 'carer-jobs.html') {
            $this->redirect(array('site/page', 'view' => $pageTarget));
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex($reset = false) {

// renders the view file 'protected/views/site/index.php'
//if user came here before show the previous selection
        if ($reset == true) {
            Session::initNewBooking();
        }
        if (Yii::app()->user->isGuest == true) {

            $this->layout = '//layouts/layoutHome';

//Session::initNewMission();

            $this->render('index'); // , array('quoteLiveIn' => $quoteLiveIn, 'quoteHourly' => $quoteHourly));
        } else {

            $this->layout = '//layouts/layoutHome';

            if (Yii::app()->user->roles == Constants::USER_CARER) {

//if the carer is involved with at least one mission, they are redirected
//to their missions. Otherwise homepage
                $carer = Carer::loadModel(Yii::app()->user->id);
                $carersMissions = $carer->missionCarers;
                if (empty($carersMissions)) {
                    $this->redirect(array('carer/myClients'));
                } else {
                    $this->redirect(array('carer/myMissions'));
                }
            } elseif (Yii::app()->user->roles == Constants::USER_CLIENT) {

                $this->redirect(array('clientManageBookings/myBookings'));

//                if (Yii::app()->user->wizard_completed == Wizard2::CLIENT_LAST_STEP_INDEX) { //user has bought at least one servie
//                    $this->redirect(array('clientManageBookings/myBookings'));
//                } else {
//
////the user has not finish the wizard
//                    $this->render('index');
//                }
            }
        }
    }

    public function actionHome() {
        $this->layout = '//layouts/layoutHome';

        if (!Yii::app()->user->isGuest) { //if user logged in
            if (Yii::app()->user->roles == Constants::USER_CARER) {
//if user is carer dedirect to their specific index (MyMissions)
                $this->redirect(array('index'));
            }
        }
//guest and clients get to see the homepage
        $this->render('index');
    }

    public function actionCarerJobs() {

        $this->layout = '//layouts/layoutHome';

        $carer = new Carer();

        $carer->setScenario(Carer::SCENARIO_NEW_CARER);

        if (isset($_POST['Carer'])) {

            $carer->email_address = $_POST['Carer']['email_address'];
            $carer->password = $_POST['Carer']['password'];
            $carer->repeat_password = $_POST['Carer']['repeat_password'];
//$carer->first_name = $_POST['Carer']['first_name'];
//$carer->last_name = $_POST['Carer']['last_name'];

            $valid = $carer->validate();

            $err = $carer->errors;

            if ($valid) {

                $password = $carer->password; //store before encrypting

                $dbTransaction = Yii::app()->db->beginTransaction();

                try {

                    if ($carer->save(false)) {

                        Emails::sendToCarer_SignUp($carer);

                        $identity = new UserIdentityUser($carer->email_address, $password);

                        if ($identity->authenticate()) {
                            Yii::app()->user->login($identity);
                        }

//Set role
                        Yii::app()->user->setState('roles', Constants::USER_CARER);

                        $dbTransaction->commit();

                        $this->redirect(array('/carer/registration'));
                    }
                } catch (CException $e) {

                    $dbTransaction->rollBack();
                    Yii::log($e->getMessage(), CLogger::LEVEL_WARNING, 'email problem');

                    $carer->addError('email_address', Yii::t('texts', 'ERROR_EMAIL_ADDRESS_NOT_VALID'));

                    $carer->setScenario(Carer::SCENARIO_NEW_CARER);
                    $carer->password = $_POST['Carer']['password'];
                    ;


                    $this->render('carerJobs', array('carer' => $carer));
                }
            }
        }

        $this->render('carerJobs', array('carer' => $carer));
    }

    public function actionPopulateSignUpCarer() {

        $this->layout = '//layouts/layoutHome'; //RCRC in progress

        $carer = new Carer(Carer::SCENARIO_NEW_CARER);
        if (Random::getRandomGender() == Constants::GENDER_FEMALE) {
            $firstName = Random::getRandomCarerFirstNameFemale();
        } else {
            $firstName = Random::getRandomCarerFirstNameMale();
        }
        $lastName = Random::getRandomCarerLastName();

        $carer->email_address = Random::getRandomEmail($firstName, $lastName);
        $carer->first_name = $firstName;
        $carer->last_name = $lastName;
        $carer->password = 'test';
        $carer->repeat_password = 'test';

        $this->render('carerJobs', array('carer' => $carer));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {

        $this->layout = '//layouts/layoutSite';

        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {

        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {

                $success = Emails::sendToAdmin_ContactForm($model->name, $model->email, $model->subject, $model->body);

                Emails::sendToPerson_ContactFormConfirmation($model->name, $model->email);

                Yii::app()->user->setFlash('contact', Yii::t('texts', 'FLASH_THANK_YOU_FOR_CONTACTING_US'));
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page for User
     */
    public function actionLogin() {

        if (Yii::app()->user->isGuest) {

            $model = new UserLoginForm();

// collect user input data
            if (isset($_POST['UserLoginForm'])) {

                $model->attributes = $_POST['UserLoginForm'];

                if ($model->validate()) {

                    $model->login();

//redirect accordingly
                    $actionPath = $this->redirectAuthenticatedUser(Yii::app()->user->returnUrl);
                    Yii::app()->controller->redirect($actionPath);
                }
            }

// display the login form
            $this->render('userLogin', array('model' => $model));
        } else {
//if user already logged in

            $actionPath = $this->redirectAuthenticatedUser(Yii::app()->user->returnUrl);
            Yii::app()->controller->redirect($actionPath);
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        Yii::app()->session->destroy();
        $this->redirect(Yii::app()->homeUrl);
    }

    /*
     * Page to request change password
     */

    public function actionForgottenPassword() {

        $model = new ForgottenPasswordForm();

        if (isset($_POST['ForgottenPasswordForm'])) {

            $model->attributes = $_POST['ForgottenPasswordForm'];

            if ($model->validate()) {

                $person = DBServices::getPerson($model->email);

                if ($person != null) {

                    $newPassword = DBServices::resetPassword($person);

                    if (Emails::sendPerson_ResetPasswordEmail($person, $newPassword)) {

                        Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_EMAIL_SUCCESSFULLY_SENT'));
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_ERROR_SENDING_EMAIL'));
                    }
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_ERROR_EMAIL_DOES_NOT_EXIST'));
                }
            }
        }

        $this->render('forgottenPassword', array('model' => $model));
    }

    public function actionChangeTempPassword($id) {

//decrypt id and password
        $decrypt = Encryption::decryptURLParam($id);
        $pieces = explode("-", $decrypt);

        $personId = $pieces[0];
        $passwordTemp = $pieces[1];

//double check the user is not logged in
        if (UserIdentityUser::isGuest()) {

            $model = new ResetPasswordForm(ResetPasswordForm::RESET_PASSWORD_FORGOTTEN);

            $request = Yii::app()->request;
            $passwordParam = $request->getPost('ResetPasswordForm');

            $resetPassword = new ResetPasswordForm(ResetPasswordForm::RESET_PASSWORD_FORGOTTEN);

            if (isset($passwordParam)) {

                $person = DBServices::getPerson($personId);
//check email exists
                if ($person != null) {

                    $model->attributes = $_POST['ResetPasswordForm'];

                    $resetPassword->attributes = $_POST['ResetPasswordForm'];

                    $valid = $resetPassword->validate();

                    if ($valid) {
//check old password
                        $identity = new UserIdentityUser($person->email_address, $passwordTemp);

                        if ($identity->authenticate()) {
//check passwords are the same

                            if ($passwordParam['newPassword'] == $passwordParam['newPasswordRepeat']) {

//update password
                                $person->password = $passwordParam['newPassword'];

                                $person->savePassword();

//log the user
                                Yii::app()->user->login($identity);

                                Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_PASSWORD_CHANGED_LOGGED'));

//$resetPassword = new ResetPasswordForm();
                            } else {
                                Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_TWO_PASSWORDS_DIFFERENT'));
                            }
                        } else {
                            Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_ERROR_TEMP_PASSWORD_INCORRECT'));
                        }
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_FIELDS_CANOT_BE_BLANK'));
                    }
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_ERROR_EMAIL_DOES_NOT_EXIST'));
                }
            }

            $this->render('changeTempPassword', array('model' => $model));
        } else {
//only guest users
//throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));

            $this->redirect(array('site/index'));
        }
    }

    public function actionStartDateSelect() {

        if (Yii::app()->request->isAjaxRequest) {

            $selDate = $_POST['selDate'];
            $previousJsValues = ReadHttpRequest::readDayForm();

            $quote = Session::getQuote();

            $quote->start_date_time = Calendar::convert_DisplayDate_DBDate($selDate);

            $quote->generateDayForms($previousJsValues);

            Session::setQuote($quote);

            $output = $this->renderPartial('/quoteHourly/_weekDays', array('quote' => $quote), true);

            echo $output;
        }
    }

    public function actionEndDateSelect() {

        if (Yii::app()->request->isAjaxRequest) {

            $selDate = $_POST['selDate'];
            $previousJsValues = ReadHttpRequest::readDayForm();

            $quote = Session::getQuote();

            $quote->end_date_time = Calendar::convert_DisplayDate_DBDate($selDate);
            $quote->recurring = false;

            $quote->generateDayForms($previousJsValues);

            Session::setQuote($quote);

            $output = $this->renderPartial('/quoteHourly/_weekDays', array('quote' => $quote), true);

            echo $output;
        }
    }

    public function actionUntilFurtherNoticeSelect() {

        if (Yii::app()->request->isAjaxRequest) {

            $quote = Session::getSelectedQuote();

            $quote->end_date_time = null;
            $quote->recurring = true;

            $quote->generateDayForms();

            Session::storeQuote($quote);

            $output = $this->renderPartial('/quoteHourly/_weekDays', array('quote' => $quote), true);

            echo $output;
        }
    }

    /**
     * New booking menu
     */
    public function actionNewBooking() {

        $this->layout = '//layouts/layoutHome';

//Session::initNewBooking();
//make sure to init the steps of wizard2 as if the user was not logged in before wizard1 was created
//Wizard::initStepArrayClientWzd2('clientNewBooking');
//OLD
//$this->render('index');
//NEW
//$this->redirect(array('/site/selectQuoteType/quoteType/2'));
        $this->redirect(array('start'));
    }

    public function actionRating() {

        $rate = $_POST['rate'];
        $pageName = $_POST['page_name'];

//select current value

        $sql = "SELECT * FROM tbl_rating_article WHERE page_name = '$pageName'";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if ($result != false) {

            $numberEntries = $result['number_entries'];
            $average = $result['average'];

            $newNumberEntries = $numberEntries + 1;

            $newAverage = (($numberEntries * $average ) + $rate ) / $newNumberEntries;
            $newAverageFormated = round($newAverage, 1);

            $sql = "UPDATE tbl_rating_article SET page_name ='$pageName', number_entries='$newNumberEntries', average='$newAverageFormated' WHERE page_name='$pageName'";
        } else {

            $newNumberEntries = 1;
            $newAverageFormated = $rate;

            $sql = "INSERT INTO tbl_rating_article VALUES ('$pageName', '$newNumberEntries', '$newAverageFormated')";
        }

        $success = Yii::app()->db->createCommand($sql)->execute();

// if ($success) {

        echo CJSON::encode(array(
            'entries' => $newNumberEntries,
            'average' => $newAverageFormated,
        ));
// }
    }

    public function actionEmailClick($controller, $emailAddress, $action, $parameters = "") {

        if ($parameters != "") {

            $parameters = str_replace("---", "/", $parameters);
        }

        $fullAction = "$controller/$action/$parameters";

        $emailAddress = rawurldecode($emailAddress);

        if (Yii::app()->user->isGuest) {

            $model = new UserLoginForm();

// collect user input data
            if (isset($_POST['UserLoginForm'])) {

                $model->attributes = $_POST['UserLoginForm'];

                if ($model->validate()) {

                    $model->login();

//redirect accordingly
                    $actionPath = $this->redirectAuthenticatedUser($fullAction, true);
//@param mixed $url the URL to be redirected to. If the parameter is an array,
//* the first element must be a route to a controller action and the rest
//* are GET parameters in name-value pairs.
                    $this->redirect($actionPath);
                }
            } else {
                $model->email_address = $emailAddress;
            }

// display the login form
            $this->render('userLogin', array('model' => $model));
        } else {

//simply redirect if user alreayd logged in
            $actionPath = $this->redirectAuthenticatedUser($fullAction, true);
            Yii::app()->controller->redirect($actionPath);
        }
    }

    public function actionShowUserLoginDialog() {

// if (Yii::app()->request->isAjaxRequest) {

        UIServices::unregisterAllScripts();
// Yii::app()->clientScript->registerCoreScript('jquery.ui');
//Yii::app()->clientScript->registerCoreScript('jquery');
        $model = new UserLoginForm();

        $output = $this->renderPartial('_userLoginDialog', array('model' => $model), false, true);

        echo $output;
//}
    }

//controller
    public function actionLoginDialog() {

        if (Yii::app()->request->isAjaxRequest) {

            $model = new UserLoginForm();

// collect user input data
            if (isset($_POST['UserLoginForm'])) {

                $model->attributes = $_POST['UserLoginForm'];

                if ($model->validate()) {

                    $model->login();

                    if (Yii::app()->user->roles == Constants::USER_CLIENT) {

                        $clientId = Yii::app()->user->id;
                        $client = Client::loadModel($clientId);

                        $wizardCompleted = $client->wizard_completed;

//check wizard is started
                        if (Wizard2::isStarted()) {

                            $currentStep = Wizard2::getActiveStepName();

//if current view
                            if ($currentStep == Wizard2::VIEW_SIGNUP_LOGIN) {

                                $path = Wizard2::redirectNext(true);

                                $url = Yii::app()->createAbsoluteUrl($path);

                                echo $url; //used in ajax windows.location
                            } else {

//leave the user where he is
                                Yii::app()->end();
                            }
                        } else {
                            $wizardCompleted = $client->wizard_completed;
                            if ($wizardCompleted == Wizard2::CLIENT_LAST_STEP_INDEX) {
//if user has finished the wizard before, bring him to him homepage (myBookings - site/index redirects there)
                                $url = Yii::app()->createAbsoluteUrl('site/index');

                                echo $url; //used in ajax windows.location
                            } else {
//leave the user where he is
                                Yii::app()->end();
                            }
                        }
                    }
                }
            }

            $this->performAjaxValidation($model);
//redirect done in ajax
        }
    }

    /**
     * Redirect the user (client or carer) where they should be once authenticated
     * 
     * @param type $fullAction action where to redirect the user if wizard completed
     * @return action
     */
    private function redirectAuthenticatedUser($fullAction, $actionPath = false) {

        $role = Yii::app()->user->roles;
        $wizardCompleted = Yii::app()->user->wizard_completed;

//Client not completed wizard
        if ($role == Constants::USER_CLIENT && $wizardCompleted != Wizard2::CLIENT_LAST_STEP_INDEX) {

//Wizard::initStepArrayClientWzd1SignIn('clientRegistration');
//return array('clientRegistration/quote');

            return array('site/index');
        } elseif ($role == Constants::USER_CARER && $wizardCompleted != Wizard::CARER_LAST_STEP_INDEX) {

//Carer not completed wizard
            return array('carer/index');
        } else {
            if ($actionPath) {
                return array($fullAction);
            } else {
                return $fullAction;
            }
        }
    }

    protected function performAjaxValidation($model) {

        if (Yii::app()->getRequest()->getIsAjaxRequest()) {

            $result = CActiveForm::validate($model);

            if ($result != '[]') {
                echo $result;
                Yii::app()->end();
            }
        }
    }

    /**
     * User selects a quote type on the homepage
     */
    public function actionSelectQuoteType($quoteType) {

//make sure new wizard is used
        $this->redirect(array('site/index'));

//reset new booking data
        Session::initNewBooking();

//user not logged in
        if (Yii::app()->user->isGuest == true) {

            Wizard::initStepArrayClientWzd1SignIn('clientRegistration');
            $this->redirect(array('clientRegistration/quote', 'quoteType' => $quoteType));
        } elseif (Yii::app()->user->roles == Constants::USER_CLIENT) {

//client is logged in            
            if (Yii::app()->user->wizard_completed == Wizard2::CLIENT_LAST_STEP_INDEX) { //user has bought at least one servie
//init wizard 2
//$this->redirect(array('clientNewBooking/quote', 'quoteType' => $quoteType));
                Wizard::initStepArrayClientWzd2('clientNewBooking');
                $this->redirect(array('clientNewBooking/quote', 'quoteType' => $quoteType));
            } else {
//init wizard 1

                Wizard::initStepArrayClientWzd1Details('clientRegistration');
                $this->redirect(array('clientRegistration/quote', 'quoteType' => $quoteType));
            }
        }
    }

    public function actionDateSelection() {

        $dateDisplay = $_POST['date'];
        $date = Calendar::convert_DisplayDate_DBDate($dateDisplay);
        echo Calendar::getDayOfWeekText($date);
    }

    public function actionMaintenance() {

        echo "The website is being maintained";
    }

    public function actionGetImageForGuest($documentId) {

        $carerDocument = CarerDocument::loadModelAdmin($documentId);

        $fileContent = $carerDocument->getCropFile();

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-length: ' . $fileContent->size);
        header('Content-Type: ' . $fileContent->extension);
// header('Content-Disposition: attachment; filename=' . 'name');

        echo $fileContent->content;
    }

    public function actionChangeLanguage($lang) {

        if (isset($lang)) {
            Yii::app()->session['lang'] = $lang;
            if (isset(Yii::app()->session['lang'])) {
                Yii::app()->language = Yii::app()->session['lang'];
            }
        }
        $this->redirect(array('index'));
    }

    public function actionUnsubscribeJobAlerts() {

        $request = Yii::app()->request;
        $email = $request->getPost('email');

        if ($email) {

            $carer = DBServices::getCarer($email);

            if (isset($carer)) {

                $carer->no_job_alerts = true;
                $carer->save(false);
                Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_CHANGES_SAVED'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_NO_EMAIL'));
            }
        }


        $this->render('unsubscribeJobAlerts', array('email' => $email));
    }

    public function actionUnsubscribeNewsletter($user) {

        $request = Yii::app()->request;
        $email = $request->getPost('email');

        if ($email) {

            //set all prospects and client with that email to no_newsetter = true
            $found = false;

            $person = DBServices::getPerson($email);

            if (isset($person)) {

                $person->no_newsletter = true;
                $person->save(false);
                $found = true;
            }

            $prospects = ClientProspect::model()->findAll("email_address_step1 = '$email'");

            if (!empty($prospects)) {

                foreach ($prospects as $prospect) {

                    $prospect->no_newsletter = true;
                    $prospect->save(false);
                }

                $found = true;
            }

            if ($found) {
                Yii::app()->user->setFlash('success', Yii::t('texts', 'FLASH_CHANGES_SAVED'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('texts', 'FLASH_NO_EMAIL'));
            }
        }

        $this->render('unsubscribeNewsletter', array('email' => $email, 'user' => $user));
    }

    public function actionCheckPostCode($postCode) {

        $result = array();

        $postCode = strip_tags(trim(strtoupper($postCode)));

        if (Maps::isValidPostCode($postCode)) {

            $postCode = Util::formatPostCode($postCode);

            $result['success'] = "true";
            //$result['redirectUrl'] = Yii::app()->createUrl('site/start', array('postCode' => $postCode));
            $result['redirectUrl'] = Yii::app()->createUrl('site/postJob', array('postCode' => $postCode));
        } else {
            $result['success'] = "false";
            $result['errorMessage'] = 'Enter a valid post code';
        }

//store post code
        Session::setPostCode($postCode);

        $resultJson = CJavaScript::jsonEncode($result);
        echo $resultJson;

        Yii::app()->end();
//}
    }

    //MUST NOT FORGET TO USE IT IN NEW BOOKING WIZARD
    public function actionStart() {

        Session::initNewBooking();
        Wizard2::initStepsClient();
        Wizard2::setActiveStep(Wizard2::VIEW_FIND_CARERS); //first step active

        $request = Yii::app()->request;
        $postCode = $request->getParam('postCode', '');

        $this->redirect(array('site/findCarers' /* , 'postCode' => $postCode */));
    }

    public function actionFindCarers() {

        $postCode = Session::getPostCode();

        $MAX_DISPLAY_CARERS = BusinessRules::getCarerSelectionShowMoreCarersNumber();

        if (!Maps::isValidPostCode($postCode)) { //make sure use did not put pad post code in the url
//throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
            $this->redirect(array('index'));
        } else {
//make sure nicely formated
            $postCode = Util::formatPostCode($postCode);
            Session::setPostCode($postCode);
        }

        $this->layout = '//layouts/layoutClient';

        $request = Yii::app()->request;

        $browserBackButton = Wizard2::adjustBrowser();

        $criteria = Session::getFindCarersCriteria();

        if (isset($_GET['person_gender'])) { //a get parameter always set when selecting a carer
//get selected carers and criteria     
//$carerIds = ReadHttpRequest::readFindCarersSelected();
            $carerId = $request->getParam('carer');
            $carerIds[] = $carerId;
            $criteria = ReadHttpRequest::readFindCarersCriteria();

            assert(isset($carerId) && $carerId != "");

//store them
            Session::setSelectedCarers($carerIds);


            if (isset($carerId)) {       //(count($carerIds) >= BusinessRules::getCarerSelectionMinimumSelected()) {
                if (!Yii::app()->user->isGuest) {
//update criteria                                                
                    ClientCarerSearchCriteria::store(Yii::app()->user->id, null, Session::getFindCarersCriteria());
                }

//store possibly new postcode
                Session::setPostCode($criteria['postCode']);

                Wizard2::redirectNext();
            } else {
                Yii::app()->user->setFlash('error', Yii::t('texts', 'ERROR_YOU_MUST_SELECT_X_CARER_MINIMUM'));
                Wizard2::redirectCurrent();
            }
        } else {

//first time or user clicked back
            $criteria = Session::getFindCarersCriteria();
            if (isset($criteria)) {
//make sure post code is updated if user went back to homepage NOT THE CASE ANYMORE AS POST CODE NOT PART OF THE URL ANYMORE                
//$postCode = $request->getParam('postCode', '');
                $criteria['postCode'] = $postCode;
            } else {
//first time
//init max display
                Session::setSelectCarersMaxDisplay($MAX_DISPLAY_CARERS);

                if (Yii::app()->user->isGuest) {
                    $useDefault = true;
                } else {
//use db value
                    $criteria = ClientCarerSearchCriteria::get(Yii::app()->user->id);

                    if (empty($criteria)) { //happens with old clients
                        $useDefault = true;
                    } else {

                        if (!isset($criteria['showMale'])) {

                            $criteria['showMale'] = true;
                        }
                        if (!isset($criteria['showFemale'])) {
                            $criteria['showFemale'] = true;
                        }

                        if (isset($criteria['postCode'])) {
//Session::setSelectedCarers($carerIds);
                            $useDefault = false;
//make sure post code is updated if user went back to homepage                
                            $postCode = $request->getParam('postCode', '');
                            if ($postCode != "") {
                                $criteria['postCode'] = $postCode;
                            }
                        } else {
                            $useDefault = true;
                        }
                    }
                }

                if ($useDefault) {
//$postCode = $request->getParam('postCode', '');
                    $criteria = array('postCode' => $postCode, 'showMale' => true, 'showFemale' => true, 'activities_condition_30' => '30',
                        'physical_condition' => '46', 'mental_condition' => '50', 'age_group' => '3',
                        'person_gender' => Constants::GENDER_FEMALE, 'type_care' => Constants::HOURLY);

                    Session::setFindCarersCriteria($criteria); //if user messages a carer who appear on the first results
                }
            }

            $carers = $this->getFilteredCarers($criteria);
        }

//quick fix
        if (!isset($criteria['type_care']) || $criteria['type_care'] == "") {
            $criteria['type_care'] = Constants::HOURLY;
        }

        $this->render('findCarers', array('carers' => $carers, 'criteria' => $criteria));
    }

    /**
     * if $criteria is null, use the POST to get the data from
     * 
     * @param type $criteria
     * @return type
     */
    private function getFilteredCarers($criteria) {
        //always on
        $active = true;
        $nationality = 'all';

        //dynamic
        $personGender = $criteria['person_gender'];
        if ($personGender == Constants::GENDER_FEMALE) {
            $workWithMale = false;
            $workWithFemale = true;
        } else {
            $workWithMale = true;
            $workWithFemale = false;
        }

        //default value if not saved before in DB
        if (!isset($criteria['showMale'])) {
            $criteria['showMale'] = false;
        }
        if (!isset($criteria['showFemale'])) {
            $criteria['showFemale'] = false;
        }
        if (!isset($criteria['type_care']) || $criteria['type_care'] == "") {
            $criteria['type_care'] = Constants::HOURLY;
        }

        $showMale = $criteria['showMale'];
        $showFemale = $criteria['showFemale'];

        $physicalId = $criteria['physical_condition'];
        $mentalId = $criteria['mental_condition'];
        $ageGroupId = $criteria['age_group'];

        if ($criteria['type_care'] == Constants::HOURLY) {
            $hourly = true;
            $liveIn = false;
        } else {
            $hourly = false;
            $liveIn = true;
        }

        $activities = array();
        foreach ($criteria as $key => $value) {

            if (Util::startsWith($key, 'activities_')) {

                $val = Util::lastCharactersAfter($key, 'activities_condition_');
                $activities[] = $val;
            }
        }

        $model = Condition::loadModel($physicalId);
        $physicals = $model->getConditionsIdsUp();

        $model = Condition::loadModel($mentalId);
        $mentals = $model->getConditionsIdsUp();

        $ageGroup = array($ageGroupId);

        $postCodeRaw = $criteria['postCode'];
        $postCode = strip_tags(trim(strtoupper($postCodeRaw)));
        if (empty($postCode)) {
            $postCode = null;
        }

        $language = 'english';

        $carers = DBServices::getFilteredCarers2($activities, $physicals, $mentals, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale, $postCode, $language);

        return $carers;
    }

    public function actionFindCarersRefresh() {

        $criteria = ReadHttpRequest::readFindCarersCriteria();
        $MAX_DISPLAY_CARERS = BusinessRules::getCarerSelectionShowMoreCarersNumber();

        $postCode = $criteria['postCode'];

        if (isset($postCode)) {

            if (Maps::isValidPostCode($postCode)) {
                $postCode = Util::formatPostCode($postCode);
            } else {
                echo 'error';
                Yii::app()->end();
            }
        }
        $request = Yii::app()->request;
        $showMore = $filtersRaw = $request->getParam('showMore');

        if ($showMore === "true") {
            $currentMaxDisplay = Session::getSelectCarersMaxDisplay();
            Session::setSelectCarersMaxDisplay($currentMaxDisplay + $MAX_DISPLAY_CARERS);
        } else {
//criteria changed, reset max display
            Session::setSelectCarersMaxDisplay($MAX_DISPLAY_CARERS);
        }

        $carers = $this->getFilteredCarers($criteria);

        $this->renderPartial('_findCarersResults', array('carers' => $carers), false, false);
    }

    public function actionSelectDates() {

        $this->layout = '//layouts/layoutClient';

        $browserBackButton = Wizard2::adjustBrowser();

        $quote = Session::getSelectedQuote();

        if (!isset($quote)) {
            //very first time
            $quote = new BookingHourlyOneDayForm();
            $quote->initFirstTime();
        } else {
//user presses next or back
            if (!empty($_REQUEST)) {

                $quote = ReadHttpRequest::readBookingHourlyDays();

                Session::setQuote($quote);

                if ($_REQUEST['nav'] === 'back') {
                    Wizard2::redirectPrevious();
                } else {
                    if ($quote->validate()) {
                        Session::setSelectedValidQuote($quote);
                        Wizard2::redirectNext();
                    } else {
                        Session::setShowErrors(true);
                        Wizard2::redirectCurrent();
                    }
                }
            } else {
//user comes back to this page, from previous page or next page
                if (Session::getShowErrors()) {
                    $quote->validate();
                } else {
                    $quote->clearErrors();
                }
                Session::setShowErrors(false);
            }
        }

        $this->render('selectDates', array('quote' => $quote));
    }

    public function actionAddDay() {

        if (Yii::app()->request->isAjaxRequest) {

            $index = Session::increaseSelectDatesMaxIndex();

            $quote = Session::getQuote();

            $dayForm = new HourlyQuoteDayForm();
            $dayForm->initFirstTime();

            $quote->dayForms[] = $dayForm;

            UIServices::unregisterJQueryScripts();

//new day form

            $htmlRaw = $this->renderPartial('/selectDates/_hourlyOneDay', array('dayForm' => $dayForm,
                'index' => $index), true, true);
            $html = utf8_encode($htmlRaw);
            $result['html'] = $html;

//update quote price
            $htmlRawPrice = $this->renderPartial('/selectDates/_hourlyResult', array('quote' => $quote), true, false);
            $htmlPrice = utf8_encode($htmlRawPrice);
            $result['htmlPrice'] = $htmlPrice;

            $resultJson = CJavaScript::jsonEncode($result);
            echo $resultJson;
        }
    }

    public function actionRemoveDay() {

        $index = $_GET['index'];

        $quote = Session::getQuote();
        unset($quote->dayForms[$index]);
        Session::setQuote($quote);

        $htmlRawPrice = $this->renderPartial('/selectDates/_hourlyResult', array('quote' => $quote), true, false);
        $htmlPrice = utf8_encode($htmlRawPrice);
        $result['htmlPrice'] = $htmlPrice;

        $resultJson = CJavaScript::jsonEncode($result);
        echo $resultJson;
    }

    public function actionChangeDateTime() {

        $quote = ReadHttpRequest::readBookingHourlyDaysAjax();

        $request = Yii::app()->request;
        $index = $request->getParam('index');
        $noValidate = $request->getParam('noValidate') == 'true' ? true : false;

        $quote->firstTime = false;

        if (!$noValidate) { //do not show error message to user, but don't dislay the price
            $valid = $quote->validate();
        } else {
            $valid = true;
        }

        Session::setQuote($quote, 'quickBooking');

        $result = array();

        if ($valid) {

            $result['success'] = "true";
        } else {
            //see if errors global or specific to a form
            if (!empty($quote->errors)) {
                $errors = $quote->errors;
            } else {
                $errors = $quote->dayForms[$index]->errors;
            }

            $errorMessage = reset($errors);

            $result['success'] = "false";
            $result['errorMessage'] = $errorMessage[0];
        }

        $htmlRaw = $this->renderPartial('/selectDates/_hourlyResult', array('quote' => $quote, 'index' => $index), true, false);

        $html = utf8_encode($htmlRaw);

        $result['html'] = $html;
        $resultJson = CJavaScript::jsonEncode($result);
        echo $resultJson;
    }

    public function actionSignupLogin() {

        $browserBackButton = Wizard2::adjustBrowser();

        $emailMessageMe = Session::getMessageMeEmail();

        if (Yii::app()->user->isGuest) {

            $this->layout = '//layouts/layoutClient';

            $client = Session::getSigninClient();

            if (!isset($client)) {
                //first time

                $client = new Client(Client::SCENARIO_CREATE_CLIENT_EMAIL);

                if (isset($emailMessageMe)) {
                    $client->email_address = $emailMessageMe;
                }
                Session::setSigninClient($client);
            } else {
                //Next or back
                $request = Yii::app()->request;
                $clientParam = $request->getPost('Client');

                if (isset($clientParam)) {

                    $modelScenario = $request->getPost('customer_type');
                    $client->setScenario($modelScenario);
                    $client->attributes = $clientParam;

                    //store new data
                    Session::setSigninClient($client);

                    //if back don't validate/save just go back
                    if ($_REQUEST['nav'] === 'back') {
                        Wizard2::redirectPrevious();
                    } else {
                        $valid = $client->validate();
                        $errors = $client->getErrors();

                        //SAVE/UPDATE Client
                        if ($valid) {

                            if ($modelScenario == Client::SCENARIO_CREATE_CLIENT_EMAIL) {

                                //store prospect table
                                $email = $client->email_address;
                                $sessionId = Yii::app()->session->sessionID;
                                $sql = "INSERT INTO tbl_client_prospect VALUES ('$sessionId', '$email', NULL, NOW(), 0)
                                        ON DUPLICATE KEY UPDATE email_address_step1='$email';";
                                Yii::app()->db->createCommand($sql)->execute();

                                //redirect signUp
                                $this->redirect(array('site/signUp'));
                            } else {
                                //log in the user
                                $identity = new UserIdentityUser($client->email_address, $clientParam['password']);

                                $code = $identity->authenticate();

                                if ($code == true) {

                                    Yii::app()->user->login($identity);

                                    $client = Client::loadModel(Yii::app()->user->id);

                                    //store criteria                                                
                                    ClientCarerSearchCriteria::store($client->id, null, Session::getFindCarersCriteria());
                                    //update session data
                                    Session::setClient($client);

                                    //update header name
                                    Yii::app()->user->setState('full_name', $client->first_name . ' ' . $client->last_name);

                                    Wizard2::redirectNext();
                                } else {

                                    //logon failed, set the scenario back to create in case the user click the radio button again
                                    //$client->setScenario(Client::SCENARIO_LOGIN_CLIENT);
                                    //show error message
                                    $errorMessage = Yii::t('texts', 'ERROR_EMAIL_PASSWORD_INCORRECT');
                                    Yii::app()->user->setFlash('error', $errorMessage);
                                }
                            }
                        } else {
                            Wizard2::redirectCurrent();
                        }
                    }
                } else {

                    if (isset($emailMessageMe)) {
                        $client->email_address = $emailMessageMe;
                    }

                    if (Session::getShowErrors()) {
                        $client->validate();
                    } else {
                        $client->clearErrors();
                    }
                    Session::setShowErrors(false);
                }
            }

            $this->render('signupLogin', array('client' => $client));
        } else {

            //if we're here user already logged in before wizard
            //or user pressed back in the next step.
            //if the former is true, must redirect to step before login

            if ($browserBackButton) {
                Wizard2::redirectPrevious();
            } else {
                //jump to userLocation directly
                Wizard2::redirectNext();
            }
        }
    }

    public function actionSignUp() {

        $currentAction = Yii::app()->controller->action->id;
        $currentStepName = Wizard2::getActiveStepName();
        if ($currentStepName != 'signupLogin') {
            if ($currentAction != $currentStepName) {
                $browserBackButton = true;
            } else {
                $browserBackButton = false;
            }
        } else {
            $browserBackButton = false;
        }

        if (Yii::app()->user->isGuest) {

            $this->layout = '//layouts/layoutClient';

            $client = Session::getClient();

            if (!isset($client)) {

                $clientProspect = Session::getSigninClient();
                $client = new Client(Client::SCENARIO_CREATE_CLIENT_ALL);
                $client->email_address = $clientProspect->email_address;

                Session::setClient($client);
            } else {
//Next ot back
                $request = Yii::app()->request;
                $clientParam = $request->getPost('Client');

                if (isset($clientParam)) {
                    $client->attributes = $clientParam;

//store new data
                    Session::setClient($client);

//if back don't validate/save just go back
                    if ($_REQUEST['nav'] === 'back') {
//Wizard2::redirectPrevious();
                        $this->redirect(array('site/signupLogin'));
                    } else {
                        $valid = $client->validate();
                        $errors = $client->getErrors();

//SAVE/UPDATE Client
                        if ($valid) {

                            $client->save();
                            $emailResponse = Emails::sendToClient_RegistrationConfirmation($client);

//delete prospect email
                            $clientProspect = Session::getSigninClient();
                            $email = $client->email_address;
                            $sessionId = Yii::app()->session->sessionID;
                            $sql = "UPDATE tbl_client_prospect SET email_address_step2 = '$email' WHERE sessionId = '$sessionId'";
                            Yii::app()->db->createCommand($sql)->execute();

//log in the user
                            $identity = new UserIdentityUser($client->email_address, $clientParam['password']);

                            $code = $identity->authenticate();

                            if ($code == true) {

                                Yii::app()->user->login($identity);

                                $client = Client::loadModel(Yii::app()->user->id);

//store criteria                                                
                                ClientCarerSearchCriteria::store($client->id, null, Session::getFindCarersCriteria());
//update session data
                                Session::setClient($client);

//update header name
                                Yii::app()->user->setState('full_name', $client->first_name . ' ' . $client->last_name);

                                Wizard2::redirectNext();
                            } else {

                                //logon failed, set the scenario back to create in case the user click the radio button again
                                $client->setScenario(Client::SCENARIO_CREATE_CLIENT_EMAIL);

                                //show error message
                                $errorMessage = Yii::t('texts', 'ERROR_EMAIL_PASSWORD_INCORRECT');
                            }
                        } else {
                            Session::setShowErrors(true);
                            $this->redirect(array('site/signUp'));
                        }
                    }
                } else {
                    if (Session::getShowErrors()) {
                        $client->validate();
                    } else {
                        $client->clearErrors();
                    }
                    Session::setShowErrors(false);
                }
            }
            $this->render('signUp', array('client' => $client));
        } else {
            if ($browserBackButton) {
                Wizard2::redirectPrevious();
            } else {
//jump to userLocation directly
                Wizard2::redirectNext();
            }
        }
    }

    public function actionPopulateSignUpLogin() {

        $this->layout = '//layouts/layoutClient';

        $client = new Client(Client::SCENARIO_CREATE_CLIENT_EMAIL);
        $client->email_address = Random::getRandomEmail(Random::getRandomClientFirstName(), Random::getRandomClientLastName());
        Session::setSigninClient($client);

        $this->redirect(array('site/signupLogin'));
    }

    public function actionPopulateSignUp() {

        $this->layout = '//layouts/layoutClient';

        $client = Session::getSigninClient();
        $client->setScenario(Client::SCENARIO_CREATE_CLIENT_ALL);

        $client->first_name = Random::getRandomClientFirstName();
        $client->last_name = Random::getRandomClientLastName();

        $client->repeat_email_address = $client->email_address;
        $client->password = 'test';
        $client->repeat_password = 'test';
        //$client->date_birth = Random::getRandomDateBirth(1940, 1965);
        //$client->terms_conditions = '1';

        Session::setClient($client);

        $this->redirect(array('site/signUp'));
    }

    public function actionSendMessageCarer() {

        $post = $_POST;

        $request = Yii::app()->request;

        if ($request->isAjaxRequest) {

            $result = array();

            $email = $request->getParam('email');
            $text = $request->getParam('text');
            $carerId = $request->getParam('carerId');

            $carer = Carer::loadModel($carerId);

            $form = new MessageMeForm();
            $form->text = $text;
            $form->email = $email;

            $criteria = Session::getFindCarersCriteria();

            if ($form->validate()) {

                Session::setMessageMeEmail($email);

                //store if guest
                if (Yii::app()->user->isGuest) {
                    $sessionId = Yii::app()->session->sessionID;
                    $sql = "INSERT INTO tbl_client_prospect VALUES ('$sessionId', '$email', NULL, NOW(), 0)
                                        ON DUPLICATE KEY UPDATE email_address_step1='$email';";
                    Yii::app()->db->createCommand($sql)->execute();

                    if (isset($criteria)) {
                        //store selection
                        ClientCarerSearchCriteria::store(null, $sessionId, $criteria);
                    }
                } else {
                    //store selection if logged in
                    if (isset($criteria)) {
                        ClientCarerSearchCriteria::store(Yii::app()->user->id, null, $criteria);
                    }
                }

                $result['success'] = "true";
                $result['message'] = 'Message successfuly sent to ' . $carer->first_name;

                //Send email
                Emails::sendToAdmin_ClientMessage($carer, $email, $text);

                //send a copy to user?
                Emails::sentToClient_CopyMessageMe($email, $text, $carer);
            } else {

                $result['success'] = "false";
                $result['message'] = 'Enter an email and a message';
            }

            $resultJson = CJavaScript::jsonEncode($result);
            echo $resultJson;

            Yii::app()->end();
        }
    }

    public function actionCalendarSelect() {


        $quote = new BookingHourlyOneDayForm();
        $cell = CHtml::button(Yii::t('texts', 'DELETE'), array('class' => 'rc-button-white-small button-delete')) . '<br />';
        $cell .= CHtml::activeDropDownList($quote, 'startHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop qb-change-hours', 'id' => 'start_hour_one_day'));
        $cell .= CHtml::activeDropDownList($quote, 'startMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop qb-change-hours', 'id' => 'start_minute_one_day'));
        $cell .= CHtml::activeDropDownList($quote, 'endHour', Calendar::getDropDownHours(Constants::HOURLY), array('class' => 'rc-drop qb-change-hours', 'id' => 'end_hour_one_day'));
        $cell .= CHtml::activeDropDownList($quote, 'endMinute', Calendar::getDropDownMinutes(), array('class' => 'rc-drop qb-change-hours', 'id' => 'end_minute_one_day'));

        echo $cell;
    }

    public function actionDestroySession() {

        $this->layout = '//layouts/layoutHome';

        Yii::app()->session->destroy();

        $this->redirect(array('index'));
    }

    public function actionStoreProspectEmail() {

        if (Yii::app()->request->isAjaxRequest) {

            $request = Yii::app()->request;
            $email = $request->getPost('email_prospect');
            $postCode = $request->getPost('post_code');

            $validator = new CEmailValidator;

            //double check post code
            if (Maps::isValidPostCode($postCode)) {

                if ($validator->validateValue($email)) {

                    $sessionId = Yii::app()->session->sessionID;

                    $clientProspect = new ClientProspect();
                    $clientProspect->sessionID = $sessionId;
                    $clientProspect->email_address_step1 = $email;
                    $clientProspect->created = Calendar::today(Calendar::FORMAT_DBDATETIME);

                    $res = $clientProspect->save();

                    $searchCriteria['postCode'] = $postCode;
                    ClientCarerSearchCriteria::store(null, $sessionId, $searchCriteria);

                    echo 'success';
                } else {
                    echo 'error';
                }
            } else {
                echo 'error';
            }
        }
    }

    public function actionPostJob() {

        $this->layout = '//layouts/layoutClient';

        $postCode = Session::getPostCode();

        $request = Yii::app()->request;

        $jobParam = $request->getPost('Job');

        $carers = Carer::getCarersFromPostCode($postCode, Mission::TYPE_HOURLY);

        if (isset($jobParam)) {

            //USER POSTED

            if (Yii::app()->user->isGuest == true) {
                $clientParam = $request->getPost('Client'); //may be emm
                $client = new Client(Client::SCENARIO_CREATE_CLIENT_ALL);
                $client->attributes = $clientParam;
                $clientValid = $client->validate();
                $errors = $client->errors;
                $createClient = true;
            } else {
                $client = Client::loadModel(Yii::app()->user->id);
                $clientValid = true;
                $createClient = false;
            }

            $job = new Job();
            $job->attributes = $jobParam;

            $job->post_code = $postCode;

            //get gender Carer
            if (!isset($_POST['gender_carer_male']) && !isset($_POST['gender_carer_female'])) {

                assert(false);
            }

            if (isset($_POST['gender_carer_male']) && $_POST['gender_carer_male'] == true) {
                $showMale = true;
            } else {
                $showMale = false;
            }

            if (isset($_POST['gender_carer_female']) && $_POST['gender_carer_female'] == true) {
                $showFemale = true;
            } else {
                $showFemale = false;
            }

            if ($showMale && $showFemale) {
                $genderCarer = Constants::GENDER_BOTH;
            } elseif ($showMale) {
                $genderCarer = Constants::GENDER_MALE;
            } else {
                $genderCarer = Constants::GENDER_FEMALE;
            }

            //get activities
            $formActivities = array();
            $postParams = $_POST;
            foreach ($postParams as $paramName => $paramValue) {
                if (Util::startsWith($paramName, 'activities_condition_')) {
                    $formActivities[] = $paramValue;
                }
            }

            $job->formActivities = $formActivities;

            $job->gender_carer = $genderCarer;

            if ($job->who_for == 0) {
                $job->scenario = 'for_other';
            }

            $jobValid = $job->validate();
            $errors2 = $job->errors;

            //use DB Transaction?? 2 jobs can be created if errors

            if ($jobValid && $clientValid) {

                //create client
                if ($createClient) {

                    $client->save();
                    //authenticate user
                    $identity = new UserIdentityUser($client->email_address, $clientParam['password']);
                    $code = $identity->authenticate();
                    if ($code == true) {
                        Yii::app()->user->login($identity);
                    }
                    
                    //check if client was referred by a friend
                    ClientReferral::creditReferee($client);

                    //send email confirmation
                    Emails::sendToClient_RegistrationConfirmation($client);
                }

                //associate job to client
                $job->id_client = $client->id;

                $job->save();

                //save activities
                foreach ($formActivities as $formActivity) {

                    $activity = new JobActivity();
                    $activity->id_job = $job->id;
                    $activity->activity = $formActivity;
                    $activity->save();
                }

                $job->publishJob();

                //send job email confirmation
                Emails::sendToClient_JobPostingConfirmation($job);

                $this->redirect(array('postJobConfirmation'));
            } else {

                $this->render('postJob', array('job' => $job, 'client' => $client, 'carers' => $carers));
                Yii::app()->end();
            }
        } else {

            //FIRST TIME
            //get last job is there was one
            if (Yii::app()->user->isGuest == false) {
                $client = Client::loadModel(Yii::app()->user->id);
                $job = $client->getLastJob();
            } else {
                $client = new Client(Client::SCENARIO_CREATE_CLIENT_ALL);
            }

            if (!isset($job)) {
                $job = new Job();
                $job->init();
                $job->post_code = $postCode;
            }

            if (!Maps::isValidPostCode($postCode)) { //make sure use did not put pad post code in the url
                //throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
                $this->redirect(array('index'));
            } else {
                //make sure nicely formated
                $postCode = Util::formatPostCode($postCode);
                Session::setPostCode($postCode);
            }
        }

        $this->render('postJob', array('job' => $job, 'client' => $client, 'carers' => $carers));
    }

    public function actionPostJobConfirmation() {

        $this->layout = '//layouts/layoutClient';
        
        $this->render('postJobConfirmation');
    }

    public function actionPopulateJobUser() {

        $this->layout = '//layouts/layoutClient';

        $postCode = Session::getPostCode();

        $carers = Carer::getCarersFromPostCode($postCode, Mission::TYPE_HOURLY);

        if (Yii::app()->user->isGuest == true) {

            $client = new Client(Client::SCENARIO_CREATE_CLIENT_EMAIL);
            $client->email_address = Random::getRandomEmail(Random::getRandomClientFirstName(), Random::getRandomClientLastName());
            $client->first_name = Random::getRandomClientFirstName();
            $client->last_name = Random::getRandomClientLastName();
            $client->password = 'test';
            $client->repeat_password = 'test';

            Session::setSigninClient($client);
        } else {
            $client = Client::loadModel(Yii::app()->user->id);
        }

        $job = new Job();
        $job->init();
        $job->first_name_user = Random::getRandomClientFirstName();
        $job->last_name_user = Random::getRandomClientLastName();

        $this->render('postJob', array('job' => $job, 'client' => $client, 'carers' => $carers));
    }

}
