<?php

class ClientNewBookingController extends ClientCommonController {

    private $widgetScenario = ServiceUsersWidget::MISSION_SCENARIO;

    public function actions() {

        return array(
            ShowPriceAction::NAME => array(
                'class' => 'ShowPriceAction',
            ),
            SelectQuoteTabAction::NAME => array(
                'class' => 'SelectQuoteTabAction',
            ),
            QuoteAction::NAME => array(
                'class' => 'QuoteAction',
            ),
            'serviceUsers.' => array('class' => 'application.components.widgets.service_users.ServiceUsersWidget',
                'saveUser' => array('scenario' => $this->widgetScenario),
                'addUser' => array('scenario' => $this->widgetScenario),
                'cancelEditUser' => array('scenario' => $this->widgetScenario),
                'selectServiceUser' => array(),
            ),
            'serviceLocations.' => array('class' => 'application.components.widgets.service_locations.ServiceLocationsWidget',
                'saveLocation' => array('scenario' => $this->widgetScenario),
                'addLocation' => array('scenario' => $this->widgetScenario),
                'cancelEditLocation' => array('scenario' => $this->widgetScenario),
            ),
        );
    }

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('quote', 'newService', 'newServiceHomepage', 'maintainServiceLocation', 'serviceUser',
                    'serviceUsers.saveUser', 'serviceUsers.addUser', 'serviceUsers.cancelEditUser', 'serviceUsers.cancelNewUser', 'serviceUsers.deleteUser',
                    'serviceUsers.selectServiceUser',
                    'serviceLocations.saveLocation', 'serviceLocations.addLocation', 'serviceLocations.cancelEditLocation', 'serviceLocations.cancelNewLocation',
                    'serviceLocations.deleteLocation', 'payPalReturn', 'serviceLocation', 'serviceOrder', 'servicePayment', 'serviceConfirmation',
                    'newLocationDialog', 'createBillingAddress', 'selectBillingAddress', 'serviceLocations.selectLocation',
                    'setCarerFavourite', 'selectCarerNationality', 'serviceCarers', 'showMoreCarers', 'quickBooking', 'qbChangeHours',
                    ShowPriceAction::NAME, SelectQuoteTabAction::NAME, QuoteAction::NAME,
                    'index',
                ),
                'expression' => "UserIdentityUser::isClient()", //'expression'=>'isset($user->role) && ($user->role==="editor")'
                'users' => array('*'),
            ),
            array('allow', 'actions' => array('setCarerRelation', 'carerSelectionRefresh', 'selectCarers'), 'users' => array('*')),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {

        $this->redirect(array('site/index'));
    }

    /*
     * Init method when user clicks the "New Mission" menu
     */

    public function actionNewService() {

        //Delete sessions stuff
        Session::initNewBooking();

        Wizard::initStepArrayClientWzd2($this->id);

        $quoteLiveIn = Session::getSelectedLiveInQuote();
        $quoteHourly = Session::getSelectedHourlyQuote();

        $this->render('/clientRegistration/quote', array('quoteLiveIn' => $quoteLiveIn, 'quoteHourly' => $quoteHourly, 'showResult' => false));
    }

    /*
     * Init method when Client presses "Get Quote" in the homepage
     */

    public function actionNewServiceHomepage() {

        $liveInRequest = Yii::app()->session[Session::LIVE_IN_REQUEST_SELECTED];

        //Delete sessions stuff
        Session::initNewBooking();

        Wizard::initStepArrayClientWzd2($this->id);

        $this->render('/clientRegistration/quote', array('liveInRequest' => $liveInRequest, 'showSelectionCriteria' => true, 'showResult' => true));
    }

//    public function actionQuote() {
//
//        //add get parameters
//        $url = 'clientRegistration/quote';
//
//        if (isset($_GET['quoteType'])) {
//
//            $url .= '/quoteType/' . $_GET['quoteType'];
//        }
//
//        if (isset($_GET['navigation'])) {
//            $url .= '/navigation/' . $_GET['navigation'];
//        }
//
//        if (isset($_GET['toView'])) {
//            $url .= '/toView/' . $_GET['toView'];
//        }
//
//        $this->redirect(array($url));
//
////        $view = Wizard::VIEW_QUOTE;
////
////        Wizard::handleClientSecurity($view);
////
////        Wizard::setStepActive($view);
////
////        if (isset($_GET['quoteType'])) { //coming from homepage
////            $quoteType = $_GET['quoteType'];
////
////            Session::setSelectedQuoteType($quoteType);
////            $showResult = false;
////
////            //this function creates a new one if none exists for selected type
////            $quote = Session::getSelectedQuote();
////        } else {
////
////            $quote = Session::getSelectedQuote();
////
////            $showResult = $quote->validateQuote();
////        }
////
////        //handle navigation and save the live in request
////        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {
////
////            //handle navigation
////            if (isset($_GET['navigation'])) {
////
////                if ($_GET['navigation'] == 'next') {
////
////                    $nextStep = Wizard::getNextStep($view);
////                }
////            } elseif (isset($_GET['toView'])) {
////
////                $nextStep = $_GET['toView'];
////            }
////
////            Wizard::setStepCompleted($view);
////
////            //redirect to next screen
////            $this->redirect(array($nextStep));
////        }
////
////        $this->render('/clientRegistration/quoteMain', array('quote' => $quote, 'showResult' => $showResult));
////
////        //$this->render('/clientRegistration/quote', array('client' => $client, 'quote' => $quote, 'showResult' => $showResult));
//    }

    public function actionServiceUser() {

        $view = Wizard::VIEW_SERVICE_USER;

        Wizard::handleClientSecurity($view);

        Wizard::setStepActive($view);

        $client = Client::loadModel(Yii::app()->user->id);

        $serviceUsers = $client->serviceUsers;

        if (Session::isServiceUsersFirstTime()) {

            foreach ($serviceUsers as $serviceUser) {
                Session::setSelectedServiceUser($serviceUser->id);
            }
            Session::setServiceUsersFirstTime(false);
        }

        $errorMessage = null;

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            $noErrors = true;

            //handle navigation
            if (isset($_GET['navigation'])) {

                if ($_GET['navigation'] == 'next') {

                    if (Session::getSelectedServiceUsers() == null) {
                        $noErrors = false;
                    } else {

                        $nextStep = Wizard::getNextStep($view);
                    }
                } elseif ($_GET['navigation'] == 'back') {

                    $nextStep = Wizard::getPreviousStep($view);
                }
            } elseif (isset($_GET['toView'])) {

                //wizard button clicked
                //make sure at least one user is selected if user goes to next view

                $nextStep = $_GET['toView'];
                if (Wizard::isStepAfter($view, $nextStep)) {

                    $test = Session::getSelectedServiceUsers();

                    if (Session::getSelectedServiceUsers() == null) {
                        $noErrors = false;
                    }
                }
            }

            if ($noErrors) {

                Wizard::setStepCompleted($view);

                //redirect to next screen
                $this->redirect(array($nextStep));
            } else {
                $errorMessage = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_USER');
            }
        } else {
            if (empty($serviceUsers)) {

                $serviceUser = new ServiceUser();
                $serviceUsers[] = $serviceUser;

                $userIndexes[0] = -1; //new unassigned id
                Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;
            } else {
                //build original user indexes
                $userIndexes = array();

                for ($i = 0; $i < count($serviceUsers); $i++) {

                    $id = $serviceUsers[$i]->id;

                    $userIndexes[$i] = $id;
                }

                Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;
            }
        }

        $this->render('serviceUsers', array('client' => $client, 'serviceUsers' => $serviceUsers, 'errorMessage' => $errorMessage));
    }

    public function actionServiceLocation() {

        $view = Wizard::VIEW_SERVICE_LOCATION;

        Wizard::handleClientSecurity($view);

        Wizard::setStepActive($view);

        $client = Client::loadModel(Yii::app()->user->id);

        $serviceLocations = $client->clientLocations;

        //if there is only one service location select it
        if (count($serviceLocations) == 1) {
            if (Session::isServiceLocationsFirstTime()) {

                Session::setSelectedServiceLocation($serviceLocations[0]->id);

                Session::setServiceLocationsFirstTime(false);
            }
        }

        $errorMessage = null;

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            $noErrors = true;

            //handle navigation
            if (isset($_GET['navigation'])) {

                if ($_GET['navigation'] == 'next') {

                    $nextStep = Wizard::getNextStep($view);
                } elseif ($_GET['navigation'] == 'back') {

                    $nextStep = Wizard::getPreviousStep($view);
                }
            } elseif (isset($_GET['toView'])) {

                //wizard button clicked
                //make sure at least one user is selected if user goes to next view

                $nextStep = $_GET['toView'];
                if (Wizard::isStepAfter($view, $nextStep)) {
                    if (Session::getSelectedServiceLocation() == null) {
                        $noErrors = false;
                    }
                }
            }

            if ($noErrors) {

                Wizard::setStepCompleted($view);

                //redirect to next screen
                $this->redirect(array($nextStep));
            } else {
                $errorMessage = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_LOCATION');
            }
        } else {
            if (empty($serviceLocations)) {

                $serviceLocation = new Address();
                $serviceLocations[] = $serviceLocation;

                $userIndexes[0] = -1; //new unassigned id
                Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;
            } else {
                //build original user indexes
                $userIndexes = array();

                for ($i = 0; $i < count($serviceLocations); $i++) {

                    $id = $serviceLocations[$i]->id;

                    $userIndexes[$i] = $id;
                }

                Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;
            }
        }
        $this->render('serviceLocations', array('client' => $client, 'serviceLocations' => $serviceLocations, 'errorMessage' => $errorMessage));
    }

    public function actionServicePayment() {

        $this->handleServicePayment(self::SCENARIO_NEW_BOOKING);
    }

    public function actionSetCarerRelation() {

        if (!isset($_POST['carerId'])) {
            $mybreakpoint = 'test';
        } else {

            $carerId = $_POST['carerId'];
            $relationType = $_POST['relation'];
            $clientId = $_POST['clientId'];

            if ($relationType == 0 && $_POST['view'] == Constants::CARER_PROFILE_VIEW_MY_CARERS) {
                //special case when i My Carers, if user is un-blacklist its relation goes to 2
                //as 0 would delete the record.
                $relationType = 2;
            }

            $success = ClientCarerRelation::setCarerRelation($clientId, $carerId, $relationType);

            $this->refreshCarerList();
        }
    }

    public function actionCarerSelectionRefresh() {

        $maxDisplayCarers = Session::getSelectCarersMaxDisplay();

        $this->refreshCarerList($maxDisplayCarers);
    }

    private function refreshCarerList($maxDisplayCarers = 100) {

        if (Yii::app()->request->isAjaxRequest) {
            $processOutput = true;
            UIServices::unregisterJQueryScripts();
        } else {
            $processOutput = false;
        }

        $filters = $_POST['filters'];
        $view = $_POST['view'];

        $params = array();
        parse_str($filters, $params);

        $clientId = $params['clientId'];
        $showMale = (isset($params['showMale']) ? true : false);
        $showFemale = (isset($params['showFemale']) ? true : false);
        $formId = $params['formId'];
        //$nationality = $params['nationality'];
        $nationality = 'all';

        //refresh list
        $client = Client::loadModel($clientId);

        if ($view == Constants::CARER_PROFILE_VIEW_SELECT_TEAM) {

            $quote = Session::getQuote();
            $serviceUserIds = Session::getSelectedServiceUsers();

            $carers = $client->getClientCarersSelection($quote, $serviceUserIds, $showMale, $showFemale, $nationality, $maxDisplayCarers + 1);

            $carersNotWanted = $client->getClientCarersNotWanted($quote, $serviceUserIds, $showMale, $showFemale, $nationality);

            $showGenderSelection = true;
        } else {

            $carersAll = $client->getMyCarers($showMale, $showFemale, $nationality);
            $carers = $carersAll['carers'];
            $carersNotWanted = $carersAll['notWanted'];
            if ($view == Constants::CARER_PROFILE_VIEW_ADMIN) {
                $showGenderSelection = true;
            }
            $showGenderSelection = false;
        }

        $nationalities = DBServices::getCarersNationalities(true);

        $html = $this->renderPartial('application.views.carer.profile._carerProfilesMain', array('carers' => $carers, 'clientId' => $clientId, 'showMale' => $showMale, 'showFemale' => $showFemale,
            'nationality' => $nationality, 'nationalities' => $nationalities, 'carersNotWanted' => $carersNotWanted, 'formId' => $formId, 'carerProfileType' => 'admin', 'view' => $view,
            'maxDisplayCarers' => $maxDisplayCarers, 'showGenderSelection' => $showGenderSelection), true, $processOutput);

        echo $html;
    }

    public function actionSelectCarers() {

        $fields = array_keys($_POST);

        $selectedCarers = array();
        $favouriteCarers = array();

        $needle1 = 'carer_selected_';
        $needle2 = 'carer_favourite_';

        foreach ($fields as $field) {

            if (Util::startsWith($field, $needle1)) {
                $selectedCarers[] = array('carerId' => $_POST[$field], 'relation' => ClientCarerRelation::RELATION_SELECTED);
            } elseif (Util::startsWith($field, $needle2)) {
                $selectedCarers[] = array('carerId' => $_POST[$field], 'relation' => ClientCarerRelation::RELATION_FAVOURITE);
            }
        }

        Session::setSelectedCarers($selectedCarers);

        //after booking save
        $selectedCarers = Session::getSelectedCarers();
        $bookingId = 1;
        foreach ($selectedCarers as $selectedCarer) {

            $bookingCarer = new BookingCarer();
            $bookingCarer->id_carer = $selectedCarer['carerId'];
            $bookingCarer->id_booking = $bookingId;
            $bookingCarer->relation = $selectedCarer['relation'];
            $bookingCarer->save();
        }
    }

    public function actionShowMoreCarers() {

        $maxDisplayCarers = $_POST['maxDisplayCarers'];

        $maxDisplayCarers = $maxDisplayCarers + BusinessRules::getCarerSelectionShowMoreCarersNumber();

        Session::setSelectCarersMaxDisplay($maxDisplayCarers);

        $this->refreshCarerList($maxDisplayCarers);
    }

    public function actionQuickBooking() {


        $quote = new BookingHourlyOneDayForm();
        $quote->initFirstTime();

        $client = Client::loadModel(Yii::app()->user->id);

        $serviceLocations = $client->clientLocations;
        $serviceUsers = $client->serviceUsers;

        $this->render('quickBooking', array('quote' => $quote, 'client' => $client,
            'serviceLocations' => $serviceLocations, 'serviceUsers' => $serviceUsers));
    }

    public function actionQbChangeHours() {

        $quote = ReadHttpRequest::readQbBookingHourlyOneDay();

        $quote->firstTime = false;
        $quote->validateQuote();

        Session::setQuote($quote, 'quickBOoking');

        echo $this->renderPartial('/quoteHourly/_quoteHourlyResult', array('quote' => $quote));
    }

}