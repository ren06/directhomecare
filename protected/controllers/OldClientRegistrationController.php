<?php

class ClientRegistrationController extends ClientCommonController {

    const LAYOUT_SCENARIO_WIZARD = 1;
    const LAYOUT_SCENARIO_EDIT = 2;

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
        );
    }

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('addServiceUser', 'populate', 'populateService', 'populateServiceUser', 'populateServiceLocation',
                    'populateCreditCard',
                    'registration', 'removeServiceUser', 'serviceLocation', 'serviceOrder', 'serviceUser', 'servicePayment',
                    'index', 'serviceConfirmation', 'newService', 'maintainServiceUsers', 'maintainServiceLocation',
                    ShowPriceAction::NAME, QuoteAction::NAME, SelectQuoteTabAction::NAME, 'signIn', 'maintainDetails', 'payPalReturn', 'selectTab',
                    SelectQuoteTabAction::NAME, 'serviceCarers',
                ),
                'expression' => "UserIdentityUser::isClient()", //'expression'=>'isset($user->role) && ($user->role==="editor")'
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('signIn', 'populate', QuoteAction::NAME, SelectQuoteTabAction::NAME, ShowPriceAction::NAME,
                    'selectTab',
                ),
                'expression' => "UserIdentityUser::isGuest()",
                'users' => array('*'), //all
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('@'), //authenticated
            ),
            array('deny', // deny all users
                'users' => array('*'), //all (? = guest)
            ),
        );
    }

    public function actionIndex() {

        if (Yii::app()->user->wizard_completed != Wizard::CLIENT_LAST_STEP_INDEX) {
            $this->redirect('quote');
        } else {
            $this->redirect('clientNewBooking/newService');
        }
    }

    public function actionMaintainDetails() {

        $this->redirect('signIn/scenario/maintain');
    }

    public function actionSignIn() {

        $view = Wizard::VIEW_SIGNIN;

        Wizard::handleClientSecurity($view);

        Wizard::setStepActive($view);

        $clientId = Yii::app()->user->id;
        $errorMessage = "";

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != Wizard::VIEW_DETAILS)) {

            //if scenario provided with the radio button (new or login)
            if (isset($_POST['customer_type'])) {

                $modelScenario = $_POST['customer_type'];
            } elseif ($clientId == null) {

                $modelScenario = Client::SCENARIO_CREATE_CLIENT;
            } else {

                //display
                $modelScenario = Client::SCENARIO_UPDATE_CLIENT;
            }

            //NAVIGATION
            if (isset($_GET['navigation'])) {

                if ($_GET['navigation'] == 'next') {

                    //rename step from sign in to details
                    $nextStep = Wizard::getNextStep($view);
                    Wizard::changeLabel($view, Yii::t('texts', 'WIZARD_CLIENT_DETAILS'));
                } elseif ($_GET['navigation'] == 'back') {

                    $nextStep = Wizard::getPreviousStep($view);
                    $this->redirect(array($nextStep));
                }
            } elseif (isset($_GET['toView'])) {

                $nextStep = $_GET['toView'];

                if (Wizard::isStepBefore($view, $nextStep)) {
                    $this->redirect(array($nextStep));
                }
            }

            //SAVE
            $request = Yii::app()->request;

            $clientParam = $request->getPost('Client');

            if (isset($clientParam)) {

                //FIGURE OUT IF UPDATE OR INSERT SCENARIO             
                if (isset($clientId)) { //or Client::SCENARIO_UPDATE_CLIENT
                    //update
                    $client = Client::loadModel($clientId);
                    $client->setScenario(Client::SCENARIO_UPDATE_CLIENT);
                } else {

                    //create or login
                    $client = new Client($modelScenario);
                }

                //for every scenario
                $client->attributes = $clientParam;

                //TODO REMOVE TEST ONLY !!!
                //$client->first_name = 'Dear';
                //$client->last_name = 'Client';
                //VALIDATE
                $valid = $client->validate();
                $errors = $client->getErrors();

                //SAVE/UPDATE Client
                if ($valid) {

                    //Save/Create Client (not if login scenario)
                    if ($modelScenario == Client::SCENARIO_CREATE_CLIENT || $modelScenario == Client::SCENARIO_UPDATE_CLIENT) {

                        $results = $client->save();
                        if ($modelScenario == Client::SCENARIO_CREATE_CLIENT) {
                            $emailResponse = Emails::sendToClient_RegistrationConfirmation($client);
                        }
                    }

                    //log in if necessary
                    if (Yii::app()->user->isGuest == true) {

                        $identity = new UserIdentityUser($client->email_address, $clientParam['password']);

                        $code = $identity->authenticate();

                        if ($code == true) {

                            Yii::app()->user->login($identity);

                            $client = Client::loadModel(Yii::app()->user->id);

                            $clientWizard = $client->wizard_completed;
                            $clientLastStep = Wizard::CLIENT_LAST_STEP_INDEX;

                            if ($clientWizard != $clientLastStep) {
                                //continue the current wizard with next step

                                Wizard::setStepCompleted($view);
                                $this->handleWizardStepCompleted($client, $view);

                                $this->redirect(array($nextStep));
                            } else {

                                //continue with the new mission wizard
                                Wizard::initStepArrayClientWzd2('clientNewBooking');
                                Wizard::setStepCompleted(Wizard::VIEW_QUOTE);
                                $nextStep = Wizard::getNextStep($view);
                                $controller = Yii::app()->session['controller'];
                                $action = $controller . '/' . $nextStep;

                                $this->redirect(array($action));
                            }
                        } else {

                            //logon failed, set the scenario back to create in case the user click the radio button again
                            $client->setScenario(Client::SCENARIO_CREATE_CLIENT);

                            //show error message
                            $errorMessage = Yii::t('texts', 'ERROR_EMAIL_PASSWORD_INCORRECT');
                        }
                    } else {
                        //redirect to next screen
                        Wizard::setStepCompleted($view);
                        $this->redirect(array($nextStep));
                    }
                }
            } else {

                //when user comes back to this screen after registering (both fields are grey, no post data), just go to next screen
                assert(Yii::app()->user->isGuest == false);
                Wizard::setStepCompleted($view);
                $this->redirect(array($nextStep));
            }
        } else {

            //Display empty
            if ($clientId == null) {

                //first time the user is on the screen
                $client = new Client(Client::SCENARIO_CREATE_CLIENT);
            } else {

                //Display existing
                $client = Client::loadModel($clientId);
                $client->setScenario(Client::SCENARIO_UPDATE_CLIENT);
            }
        }

        $this->render('details', array('client' => $client, 'errorMessage' => $errorMessage));
    }

    public function actionServiceUser() {

        $view = Wizard::VIEW_SERVICE_USER;

        $clientId = Yii::app()->user->id;

        Wizard::handleClientSecurity($view);

        Wizard::setStepActive($view);

        $client = Client::loadModel($clientId);

        $serviceUsers = $client->serviceUsers;

        $conditionErrors = array();

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {
            
            //Handle BACK that does not save or trigger error messages
            if (isset($_GET['navigation'])) {

                if ($_GET['navigation'] == 'back') {

                    $nextStep = Wizard::getPreviousStep($view);
                    $this->redirect(array($nextStep));
                }
            } elseif (isset($_GET['toView'])) {

                $nextStep = $_GET['toView'];

                if (Wizard::isStepBefore($view, $nextStep)) {
                    $this->redirect(array($nextStep));
                }
            }

            //when the user has pressed back in the next screen
            if (!empty($serviceUsers)) {

                foreach ($serviceUsers as &$serviceUser) {

                    ServiceUser::deleteServiceUser($serviceUser->id);
                }

                unset($serviceUsers);
            }

            //save first time
            if (!empty($_POST['ServiceUser'])) {

                //clear service users from before
                unset($serviceUsers);

                $valid = true;

                foreach ($_POST['ServiceUser'] as $i => $serviceUserData) {

                    $serviceUser = new ServiceUser();
                    $serviceUser->setAttributes($serviceUserData);

                    //check date validity
                    $month = 1; //$_POST['Date'][$i]['Month'];
                    $day = 1; //$_POST['Date'][$i]['Day'];
                    $year = $_POST['Date'][$i]['Year'];

                    if (checkdate($month, $day, $year)) {

                        $day = sprintf("%02d", $day);
                        $month = sprintf("%02d", $month);

                        $date_value = "$year-$month-$day";
                        $serviceUser->date_birth = $date_value;
                    } else {

                        unset($serviceUser->date_birth); //unset the value so that error is reported in the model
                    }

                    if (!$serviceUser->validate()) {

                        $errors = $serviceUser->getErrors();

                        $valid = false;
                    } else {
                        $vl = $serviceUser->save();
                        $err = $serviceUser->errors;
                        $id = $serviceUser->id;

                        $clientServiceUser = new ClientServiceUser();

                        $clientServiceUser->id_client = $clientId;
                        $clientServiceUser->id_service_user = $serviceUser->id;
                        $clientServiceUser->validate();
                        $err = $clientServiceUser->errors;
                        $clientServiceUser->save();
                    }

                    $serviceUsers[] = $serviceUser;

                    $i++;
                }

                $j = 0;

                foreach ($serviceUsers as $serviceUser) {

                    $request = Yii::app()->request;
                    $result = Condition::saveConditions($serviceUser, $request, $j, false, true);

                    $conditionErrors = array_merge($conditionErrors, $result);

                    $serviceUser->refresh();

                    $j++;
                }

                //get updated service users with conditions
                $client->refresh();
                //$serviceUsers = $client->serviceUsers;

                if ($valid == true && empty($conditionErrors)) {

                    if (empty($conditionErrors)) {

                        $serviceUsers = $client->serviceUsers;
                        $serviceUserIds = array();
                        foreach ($serviceUsers as $serviceUser) {
                            $serviceUserIds[] = $serviceUser->id;
                        }

                        Session::setSelectedServiceUsers($serviceUserIds);

                        //handle navigation
                        if (isset($_GET['navigation'])) {

                            if ($_GET['navigation'] == 'next') {

                                $nextStep = Wizard::getNextStep($view);
                            } elseif ($_GET['navigation'] == 'back') {

                                $nextStep = Wizard::getPreviousStep($view);
                            }
                        } elseif (isset($_GET['toView'])) {

                            $nextStep = $_GET['toView'];
                        }

                        Wizard::setStepCompleted($view);

                        $this->handleWizardStepCompleted($client, $view);

                        //redirect to next screen
                        $this->redirect(array($nextStep));
                    }
                }
            }
        }

        if (empty($serviceUsers)) {

            $serviceUsers[] = new ServiceUser();
        }

        $this->render('serviceUsers', array('client' => $client, 'serviceUsers' => $serviceUsers, 'conditionErrors' => $conditionErrors));
    }

    public function actionServiceLocation() {

        $view = Wizard::VIEW_SERVICE_LOCATION;

        Wizard::handleClientSecurity($view);

        $clientId = Yii::app()->user->id;

        $client = Client::loadModel($clientId);

        $serviceLocations = $client->clientLocations;

        Wizard::setStepActive($view);

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            //handle BACK that does not save or trigger error messages
            if (isset($_GET['navigation'])) {

                if ($_GET['navigation'] == 'back') {

                    $nextStep = Wizard::getPreviousStep($view);
                    $this->redirect(array($nextStep));
                }
            } elseif (isset($_GET['toView'])) {

                $nextStep = $_GET['toView'];

                if (Wizard::isStepBefore($view, $nextStep)) {
                    $this->redirect(array($nextStep));
                }
            }

            //when the user has pressed back in the next screen
            if (!empty($serviceLocations)) {

                foreach ($serviceLocations as &$serviceLocation) {

                    //$serviceLocation is an Address model
                    //delete from DB, then data is recreated (easiest way since user can add and delte service users, too hard to update)
                    Address::deleteServiceLocationAddresses($client->id);
                }
            }

            //save current data
            if (!empty($_POST['Address'])) {

                //clear service location from before
                unset($serviceLocations);

                $i = 0;
                $valid = true;

                foreach ($_POST['Address'] as $addressData) {

                    $address = new Address();
                    $address->setAttributes($addressData);
                    $address->data_type = Address::TYPE_SERVICE_LOCATION_MASTER_DATA;

                    if (!$address->validate()) {

                        $errors = $address->getErrors();
                        $serviceLocations[] = $address;
                        $valid = false;
                    } else {

                        $address->save();

                        //Create association
                        $clientLocationAddress = new ClientLocationAddress();
                        $clientLocationAddress->id_client = $client->id;
                        $clientLocationAddress->id_address = $address->id;

                        $clientLocationAddress->save(false);

                        //store location in session
                        Session::setSelectedServiceLocation($address->id);
                    }

                    $i++;
                }

                if ($valid == true) {

                    Wizard::setStepCompleted($view);

                    $this->handleWizardStepCompleted($client, $view);

                    //handle navigation
                    if (isset($_GET['navigation'])) {

                        if ($_GET['navigation'] == 'next') {

                            $nextStep = Wizard::getNextStep($view);
                        } elseif ($_GET['navigation'] == 'back') {

                            $nextStep = Wizard::getPreviousStep($view);
                        }
                    } elseif (isset($_GET['toView'])) {

                        $nextStep = $_GET['toView'];
                    }


                    //redirect to next screen
                    $this->redirect(array($nextStep));
                }
            }
        } else {

            // First time the user come to the screen, create an empty model
            if (empty($serviceLocations)) {
                $serviceLocations[] = new Address();
            }
        }

        $this->render('serviceLocations', array('client' => $client, 'serviceLocations' => $serviceLocations));
    }

    public function actionServicePayment() {

        $this->handleServicePayment(self::SCENARIO_REGISTRATION_BOOKING);
    }

    public function actionAddServiceUser($index) {

        if ($index == -1) {
            Yii::app()->session['serviceUserIndex'] = Yii::app()->session['serviceUserIndex'] + 1;
            $index = Yii::app()->session['serviceUserIndex'];
        }

        $clientId = Yii::app()->user->id;

        // if user enters ULR in browser
        if ($clientId == null) {
            $this->redirect(array($this->VIEW_SIGNIN));
        }

        $client = Client::loadModel($clientId);

        $serviceUser = new ServiceUser();

        $output = $this->renderPartial('_serviceUser', array(
            'serviceUser' => $serviceUser,
            'conditionErrors' => null,
            'index' => $index,
                ), true);

        echo $output;
    }

    public function actionRemoveServiceUser($index) {

        Yii::app()->session['serviceUserIndex'] = Yii::app()->session['serviceUserIndex'] - 1;
    }

    public function actionPopulate() {

        Wizard::setStepActive(Wizard::VIEW_SIGNIN);

        $client = new Client(Client::SCENARIO_CREATE_CLIENT);


        $client->first_name = Random::getRandomClientFirstName();
        $client->last_name = Random::getRandomClientLastName();


        $client->email_address = Random::getRandomEmail($client->first_name, $client->last_name);
        $client->password = 'test';
        $client->repeat_password = 'test';
        //$client->date_birth = Random::getRandomDateBirth(1940, 1965);

        $client->terms_conditions = '1';

        $this->render('details', array('client' => $client, 'errorMessage' => ''));
    }

    public function actionPopulateCreditCard() {

        Wizard::setStepActive(Wizard::VIEW_SERVICE_PAYMENT);

        $client = Client::loadModel(Yii::app()->user->id);
        $client->setScenario(Client::SCENARIO_PAYMENT_CLIENT);
        $client->first_name = Random::getRandomClientFirstName();
        $client->last_name = Random::getRandomClientLastName();
        $client->mobile_phone = Random::getRandomMobilePhone();

        $creditCard = new CreditCard();
        $creditCard->card_number = '5587402000012011'; //Random::getRandomNumber(16);
        //$creditCard->card_type = CreditCard::TYPE_MASTERCARD_DEBIT;
        $creditCard->last_three_digits = '415'; //Random::getRandomNumber(3);
        $creditCard->expiry_date = '2014-09-30';
        //$creditCard->name_on_card = $client->fullName;

        $quote = Session::getSelectedValidQuote();

        $billingAddress = new Address();
        $selectedBillingAddressRadioButton = '';
        $selectedCreditCardRadioButton = '';
        $errorMessages = array();
        $scenario = self::SCENARIO_REGISTRATION_BOOKING;
        $creditCards = null;
        //$serviceLocations = $client->clientLocations;
        //common to both ClientRegistrationController and ClientServiceController
        $this->render('/clientRegistration/servicePayment', array('quote' => $quote, 'scenario' => $scenario, 'client' => $client,
            'creditCard' => $creditCard, 'creditCards' => $creditCards, 'selectedCreditCardRadioButton' => $selectedCreditCardRadioButton,
            'errorMessages' => $errorMessages,
            'billingAddress' => $billingAddress, 'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));
    }

    public function actionPopulateServiceUser() {

        $clientId = Yii::app()->user->id;

        Wizard::setStepActive(Wizard::VIEW_SERVICE_USER);

        $client = Client::loadModel($clientId);

        $serviceUser = new ServiceUser();

        $serviceUser->gender = rand(Constants::GENDER_FEMALE, Constants::GENDER_MALE);

        $serviceUser->first_name = Random::getRandomServiceUserFirstName($serviceUser->gender);
        $serviceUser->last_name = Random::getRandomServiceUserLastName($serviceUser->gender);

        $dateBirth = Random::getRandomDateBirth(1900, 1940);

        $serviceUser->date_birth = $dateBirth;
        $objectCondition = new ServiceUserCondition();
        $objectCondition->id_service_user = $serviceUser->id;
        $objectCondition->id_condition = 45;

        $conditions[] = $objectCondition;

        $objectCondition = new ServiceUserCondition();
        $objectCondition->id_service_user = $serviceUser->id;
        $objectCondition->id_condition = 44;

        $conditions[] = $objectCondition;

        $objectCondition = new ServiceUserCondition();
        $objectCondition->id_service_user = $serviceUser->id;
        $objectCondition->id_condition = 49;

        $conditions[] = $objectCondition;

        $objectCondition = new ServiceUserCondition();
        $objectCondition->id_service_user = $serviceUser->id;
        $objectCondition->id_condition = 50;

        $conditions[] = $objectCondition;

        $serviceUser->serviceUserConditions = $conditions;

        $serviceUsers[] = $serviceUser;


        $this->render('serviceUsers', array('client' => $client, 'serviceUsers' => $serviceUsers, 'conditionErrors' => array()));
    }

    public function actionPopulateServiceLocation() {

        Wizard::setStepActive(Wizard::VIEW_SERVICE_LOCATION);

        $clientId = Yii::app()->user->id;
        $client = Client::loadModel($clientId);

        $servicLocation = new Address();

        $servicLocation->address_line_1 = Random::getRandomAddressLine1();
        $servicLocation->address_line_2 = Random::getRandomAddressLine2();
        $servicLocation->city = 'London';
        $servicLocation->post_code = Random::getRandomUKPostCode();


        $servicLocations[] = $servicLocation;


        $this->render('serviceLocations', array('client' => $client, 'serviceLocations' => $servicLocations));
    }

    /**
     * Set the wizard step completed property     
     * 
     * @param type $care current carerr
     * @param type $view current view
     */
    private function handleWizardStepCompleted($client, $view) {

        $currentStepIndex = Wizard::getCompletedStepIndex($view);
        $wizardCompleted = $client->wizard_completed;

        if ($currentStepIndex > $wizardCompleted) {
            $client->wizard_completed = $currentStepIndex;
            $client->save(false);
        }
    }

    public function actionSelectTab($selectedTabIndex) {

        switch ($selectedTabIndex) {
            case 0:
                $selectedTab = Constants::TAB_HOURLY_ONE;
                break;

            case 1:
                $selectedTab = Constants::TAB_HOURLY_FOURTEEN;
                break;

            case 2:
                $selectedTab = Constants::TAB_HOURLY_REGULARLY;
                break;
        }

        Session::setSelectedTab($selectedTab);
    }

}