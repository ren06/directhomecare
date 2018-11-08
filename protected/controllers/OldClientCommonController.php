<?php

/**
 * Common features to BookedController and Registration controller
 *
 * @author I031360
 */
class ClientCommonController extends Controller {

    const SCENARIO_REGISTRATION_BOOKING = 0;
    const SCENARIO_NEW_BOOKING = 1;

    public $layout = '//layouts/layoutClient';

//    public function actionServiceOrder() {
//
//        $view = Wizard::VIEW_SERVICE_ORDER;
//
//        Wizard::handleClientSecurity($view);
//
//        $client = Client::loadModel(Yii::app()->user->id);
//        $client->setScenario(Client::SCENARIO_UPDATE_CLIENT);
//
//        Wizard::setStepActive($view);
//
//        $request = Yii::app()->request;
//
//        //the first time we want to show the request the user made on the first screen
//        $quote = Session::getSelectedValidQuote();
//
//        if ($quote instanceof BookingHourlyOneDayForm || $quote instanceof BookingHourlyRegularlyForm) {
//            $quote = $quote->convertBookingHourly();
//        }
//
//        $selectedRadioButton = '';
//
//        $errorMessage = null;
//
//        if ($this->id == 'clientNewBooking') {//controller name
//            $serviceUsers = Session::getSelectedServiceUsers();
//            $serviceLocation = Session::getSelectedServiceLocation();
//
//            if ($serviceUsers == null || $serviceLocation == null) {
//
//                $errorMessage = Yii::t('texts', 'ERROR_SELECT_USER_AND_LOCATION');
//            }
//        }
//
//        $billingAddress = new Address();
//
//        //handle navigation and save the live in request
//        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {
//
//            //get mobile phone 
//            $clientParam = $request->getPost('Client');
//            $mobilePhone = $clientParam['mobile_phone'];
//            $firstName = $clientParam['first_name'];
//            $last_name = $clientParam['last_name'];
//
//            $client->first_name = $firstName;
//            $client->last_name = $last_name;
//            $client->mobile_phone = $mobilePhone;
//
//            if ($client->validate()) {
//
//                $valid = $client->saveMobilePhone();
//
//                //handle navigation
//                if (isset($_GET['navigation'])) {
//
//                    if ($_GET['navigation'] == 'next') {
//
//                        //Get Billing Address           
//                        if (isset($_POST['radio_button_billing_address_other'])) {
//
//                            //read address
//                            $data = $_POST['RequestAddress'];
//                            //$address = new RequestAddress();
//                            $billingAddress->attributes = $data;
//                            $billingAddress->data_type = Address::TYPE_BILLING_ADDRESS;
//
//                            $selectedRadioButton = 'radio_button_billing_address_other';
//                        } else {
//
//                            //get location ID
//                            $fieldNames = array_keys($_POST);
//
//                            foreach ($fieldNames as $fieldName) {
//
//                                if (Util::startsWith($fieldName, 'radio_button_')) {
//
//                                    $addressId = substr($fieldName, strlen('radio_button_'));
//                                    $billingAddress = Address::loadModel($addressId);
//
//                                    $selectedRadioButton = $fieldName;
//                                    break;
//                                }
//                            }
//                        }
//
//                        if ($billingAddress->validate()) {
//
//                            //$billingAddress->save();
//                            //store billing address in session, cannot store it yet as no id request created at this stage
//
//                            Session::setSelectedBillingAddress($billingAddress);
//
//
////                            //DO PAYMENT
////                            //WHEN PAYMENTS IS DONE STORE LIVE IN REQUEST       
////                            $selectedLiveInRequest = Yii::app()->session[Session::LIVE_IN_REQUEST_SELECTED];
////
////                            if ($this->id == 'clientRegistration') {
////
////                                $serviceLocationId = $client->serviceLocations[0]->id;
////                                $serviceUsers = $client->serviceUsers;
////                                foreach ($serviceUsers as &$serviceUser) {
////                                    $serviceUserIds[] = $serviceUser->id;
////                                }
////                            } else {
////
////                                $serviceUserIds = Session::getSelectedServiceUsers();
////                                $serviceLocationIds = Session::getSelectedServiceLocations();
////                                $keys = array_keys($serviceLocationIds);
////                                $serviceLocationId = $serviceLocationIds[$keys[0]];
////                            }
////
////                            //create request and missions and payment
////                            $errorMessages = $selectedLiveInRequest->store($client->id, $serviceUserIds, $serviceLocationId, $billingAddress);
////
////
////                            if (empty($errorMessages)) {
////                                $nextStep = Wizard::getNextStep($view);
////                            }
//                        }
//
//                        $nextStep = Wizard::getNextStep($view);
//                    } elseif ($_GET['navigation'] == 'back') {
//
//                        $nextStep = Wizard::getPreviousStep($view);
//                    }
//                } elseif (isset($_GET['toView'])) {
//
//                    $nextStep = $_GET['toView'];
//                }
//
//                Wizard::setStepCompleted($view);
//                $currentStepIndex = Wizard::getCompletedStepIndex($view);
//                $wizardCompleted = $client->wizard_completed;
//
//                if ($currentStepIndex > $wizardCompleted) {
//                    $client->wizard_completed = $currentStepIndex;
//                    $client->save(false);
//                }
//
//                //redirect to next screen
//                $this->redirect(array($nextStep));
//            }
//        }
//
//        //common to both ClientRegistrationController and clientNewBookingController
//
//
//        if ($this->id == 'clientRegistration') {
//            $viewName = 'serviceOrder';
//        } elseif ($this->id == 'clientNewBooking') {
//            $viewName = '/clientRegistration/serviceOrder';
//        }
//
//        $this->render($viewName, array('client' => $client, 'quote' => $quote,
//            'billingAddress' => $billingAddress, 'selectedRadioButton' => $selectedRadioButton));
//    }

    public function actionNewLocationDialog() {

        //echo UIServices::showOKDialog("New billing address", "OK", "New Billing Address"); //RTRT

        UIServices::unregisterJQueryScripts();

        $output = Yii::app()->controller->renderPartial('/clientRegistration/_createAddressDialog', array('billingAddress' => new Address), true, true);

        echo $output;
    }

    public function actionSelectBillingAddress($radioButtonId) {

        $index = strrpos($radioButtonId, 'radio_button_');

        $addressId = substr($radioButtonId, strlen('radio_button_'));
    }

    public function actionCreateBillingAddress() {

        if (isset($_POST['Address'])) {


            $model = new Address;
            $params = array();
            parse_str($_POST['Address'], $params);


            $model->id_document = $id_document;
            $model->year_obtained = $params['CarerDocument']['year_obtained'];
            $model->id_carer = Yii::app()->user->id;
            $model->id_content = $fileId;
            $model->status = CarerDocument::STATUS_UNAPPROVED;

            try {

                if ($model->save()) {

                    if (Yii::app()->request->isAjaxRequest) {


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
                    $errorMessage = CActiveForm::validate(array($model), null, false);

                    echo CJSON::encode(array(
                        'status' => 'failure',
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

    protected function handleServicePayment($scenario) {

        $view = Wizard::VIEW_SERVICE_PAYMENT;

        Wizard::handleClientSecurity($view);

        $client = Client::loadModel(Yii::app()->user->id);
        $client->setScenario(Client::SCENARIO_PAYMENT_CLIENT); //check the terms and conditions

        $quote = Session::getSelectedValidQuote();

        $billingAddress = new Address();
        $creditCard = new CreditCard();
        $selectedBillingAddressRadioButton = '';
        $selectedCreditCardRadioButton = '';

        Wizard::setStepActive($view);

        $errorMessages = '';

        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            //handle navigation
            if (isset($_GET['navigation'])) {

                if ($_GET['navigation'] == 'next') {

                    //check if payment is alloweds
                    if (Yii::app()->params['test']['allowClientPayment'] == false) {

                        throw new CHttpException(403, Yii::t('texts', 'PAYMENT_DISABLED'));
                    }

                    //GOING NEXT SCREEN

                    if (isset($_POST['Client'])) {

                        $clientParam = Yii::app()->request->getPost('Client');
                        $mobilePhone = $clientParam['mobile_phone'];
                        $firstName = $clientParam['first_name'];
                        $lastName = $clientParam['last_name'];
                        $termsConditions = $clientParam['terms_conditions'];

                        $client->first_name = $firstName;
                        $client->last_name = $lastName;
                        $client->mobile_phone = $mobilePhone;
                        $client->terms_conditions = $termsConditions;

                        $clientValid = $client->validate();
                        //$err = $client->errors;
                        $client->save();
                    }

                    //COMMON TO BOTH WZD1 et 2
                    //GET BILLING ADDRESS  
                    // NEW VERSION: NO MORE BILLING ADDRESS
//                    if (isset($_POST[UIConstants::RADIO_BUTTON_BILLING_ADDRESS_OTHER . '-new'])) {
//
//                        //NEW Address
//                        //read address
//                        $data = $_POST['Address'];
//                        $billingAddress->attributes = $data;
//                        $billingAddress->data_type = Address::TYPE_BILLING_ADDRESS;
//
//                        $selectedBillingAddressRadioButton = UIConstants::RADIO_BUTTON_BILLING_ADDRESS_OTHER . '-new';
//                    } else {
//
//                        //Get selected address
//                        //get location ID
//                        $fieldNames = array_keys($_POST);
//
//                        foreach ($fieldNames as $fieldName) {
//
//                            if (Util::startsWith($fieldName, UIConstants::RADIO_BUTTON_BILLING_ADDRESS)) {
//
//                                $addressId = substr($fieldName, strlen(UIConstants::RADIO_BUTTON_BILLING_ADDRESS));
//                                $billingAddress = Address::loadModel($addressId);
//                                $selectedBillingAddressRadioButton = $fieldName;
//                                break;
//                            }
//                        }
//                    }
                    // NEW VERSION: NO MORE BILLING ADDRESS
                    //GET CREDIT CARD DATA
                    if ($scenario == self::SCENARIO_REGISTRATION_BOOKING || //no options to select an existin card
                            ($scenario == self::SCENARIO_NEW_BOOKING && isset($_POST[UIConstants::RADIO_BUTTON_CREDIT_CARD_OTHER]) )) { //user selected a new card
                        $creditCard = new CreditCard();

                        $selectedCreditCardRadioButton = UIConstants::RADIO_BUTTON_CREDIT_CARD_OTHER;

                        $data = $_POST['CreditCard'];
                        $creditCard->attributes = $data;

                        $month = $_POST['Month'];
                        $year = $_POST['Year'];

                        if (is_numeric($month) && is_numeric($year) && checkdate($month, 1, $year)) {

                            //add leading 0 if necessary
                            $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $month = sprintf("%02d", $month);

                            $date_value = "$year-$month-$day";

                            if (!Calendar::dateIsBefore($date_value, Calendar::today(Calendar::FORMAT_DBDATE))) {
                                $creditCard->expiry_date = $date_value;
                            }
                        } else {

                            unset($creditCard->expiry_date); //unset the value so that error is reported in the model
                        }
                    } else {
                        //get selected credit card
                        //Get selected address
                        //get location ID
                        $fieldNames = array_keys($_POST);

                        foreach ($fieldNames as $fieldName) {

                            if (Util::startsWith($fieldName, UIConstants::RADIO_BUTTON_CREDIT_CARD)) {

                                $creditCardId = substr($fieldName, strlen(UIConstants::RADIO_BUTTON_CREDIT_CARD));
                                CreditCard::authorizeClient($creditCardId);
                                $creditCardEncrypted = CreditCard::loadModel($creditCardId);
                                $creditCard = $creditCardEncrypted->getDecryptedTemporaryInstance();
                                $num = $creditCard->card_number;
                                $selectedCreditCardRadioButton = $fieldName;
                                break;
                            }
                        }
                    }

                    $creditCardValid = $creditCard->validate();
                    $creditCardValidErr = $creditCard->errors;

                    //NEW VERSION NO MORE BILLING ADDRESS, USE LOCATION
                    $serviceLocationId = Session::getSelectedServiceLocation();
                    $billingAddress = Address::loadModel($serviceLocationId);
                    $billingAddressValid = $billingAddress->validate();
                    $err = $billingAddress->errors;

                   //Get selected quote    
                    $quote = Session::getSelectedValidQuote();

                    if ($quote instanceof BookingHourlyOneDayForm || $quote instanceof BookingHourlyRegularlyForm) {
                        $quote = $quote->convertBookingHourly();
                    }

                    //if enough credit by pass
                    $payment = $quote->calculatePayment(Constants::USER_CLIENT, $client->id);

                    if ($payment['toPay']->amount == $payment['paidCredit']->amount) {
                        $billingAddressValid = true;
                        $creditCardValid = true;
                        //get first credit card
                        $creditCards = $client->creditCards;
                        $creditCard = $creditCards[0];
                        $billingAddress = $creditCard->address;
                    }

                    if ($billingAddressValid && $creditCardValid && $clientValid) {

                        //CREDIT CARD AND BILLING OK - CONTINUE
                        //Calculate payment
                        $payment = $quote->calculatePayment(Constants::USER_CLIENT, $client->id);

                        $price = $payment['paidCash'];

                        if ($price->amount > 0) {

                            if (Yii::app()->params['test']['paymentTest'] == false) {
                                $environment = IPaymentHandler::ENVIRONMENT_LIVE;
                            } else {
                                $environment = IPaymentHandler::ENVIRONMENT_TEST;
                            }
                            $paymentHandler = BorgunHandler::getInstance($environment);
                            $technicalSuccess = $paymentHandler->doDirectPayment($client, $creditCard, $billingAddress, $price);
                        } else {
                            $technicalSuccess = true;
                            $transactionRef = null;
                        }

                        if ($technicalSuccess) {

                            if (isset($paymentHandler)) {
                                $transactionRef = $paymentHandler->getTransactionRef();
                                $transactionDateBorgun = $paymentHandler->getTransactionDate();
                                $transactionDate = Calendar::convert_BorgunDateTimeShortYear_DBDateTime($transactionDateBorgun);
                                $success = $paymentHandler->isTransactionSuccessful();
                            } else {
                                //no payment to do
                                $success = true;
                                $transactionDate = null;
                            }

                            if ($success) {

                                $carerIds = Session::getSelectedCarers();

                                if ($scenario == self::SCENARIO_REGISTRATION_BOOKING) {

                                    //Get service users
                                    $serviceLocationId = $client->clientLocations[0]->id;
                                    $serviceUsers = $client->serviceUsers;
                                    foreach ($serviceUsers as &$serviceUser) {
                                        $serviceUserIds[] = $serviceUser->id;
                                    }

                                    //handle first booking
                                    $bookingId = BookingHandler::handleBooking($quote, $client->id, $serviceUserIds, $serviceLocationId, $carerIds, $billingAddress, $creditCard, $transactionRef, $transactionDate);
                                } else {

                                    //Get service users
                                    $serviceUserIds = Session::getSelectedServiceUsers();
                                    $serviceLocationId = Session::getSelectedServiceLocation();

                                    //handle booking
                                    $bookingId = BookingHandler::handleBooking($quote, $client->id, $serviceUserIds, $serviceLocationId, $carerIds, $billingAddress, $creditCard, $transactionRef, $transactionDate);
                                }

                                //store booking id
                                Session::setNewBookingId($bookingId);

                                //clear selected carers
                                Session::setSelectedCarers(null);

                                //update the login name
                                Yii::app()->user->setState('full_name', $client->first_name . ' ' . $client->last_name);

                                $nextStep = Wizard::getNextStep($view);
                            } else {
                                $errorMessage = $paymentHandler->getPaymentMessage();

                                Yii::app()->user->setFlash('error', $errorMessage);
                            }
                        } else {
                            //show error message
                            //Yii::app()->user->setFlash('error', 'Error processing card' . print_r($return, true));
                            $errorMessage = $paymentHandler->getLongErrorMessage();

                            if ($errorMessage == null || $errorMessage == '') {
                                $errorMessage = 'Payment error, probably no internet connection.';
                            }

                            Yii::app()->user->setFlash('error', $errorMessage);
                        }
                    } else {

                        if ($selectedBillingAddressRadioButton != UIConstants::RADIO_BUTTON_BILLING_ADDRESS_OTHER . '-new') {
                            //reset billing address form if user selected an existing one
                            $billingAddress = new Address();
                        }
                    }
                } elseif ($_GET['navigation'] == 'back') {

                    $nextStep = Wizard::getPreviousStep($view);
                }
            } elseif (isset($_GET['toView'])) {

                $nextStep = $_GET['toView'];
            }

            Wizard::setStepCompleted($view);

            if (isset($nextStep)) {
                //redirect to next screen
                $this->redirect(array($nextStep));
            }
        }

        $creditCards = CreditCard::getCreditCard(Yii::app()->user->id, Constants::DATA_MASTER);

        //common to both ClientRegistrationController and ClientServiceController
        $this->render('/clientRegistration/servicePayment', array('quote' => $quote, 'scenario' => $scenario, 'client' => $client,
            'creditCard' => $creditCard, 'creditCards' => $creditCards, 'selectedCreditCardRadioButton' => $selectedCreditCardRadioButton,
            'errorMessages' => $errorMessages,
            'billingAddress' => $billingAddress, 'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));
    }

    public function actionServiceConfirmation() {

        $view = Wizard::VIEW_SERVICE_CONFIRMATION;

        Wizard::handleClientSecurity($view);

        $client = Client::loadModel(Yii::app()->user->id);

        $client->wizard_completed = Wizard::CLIENT_LAST_STEP_INDEX;
        $client->saveWizardCompleted();

        //store in session
        Yii::app()->user->wizard_completed = Wizard::CLIENT_LAST_STEP_INDEX;

        Wizard::setStepActive($view);
        Wizard::setStepCompleted($view);

        $bookingId = Session::getNewBookingId();
        $quote = Session::getSelectedValidQuote();

        if ($quote instanceof BookingHourlyOneDayForm || $quote instanceof BookingHourlyRegularlyForm) {
            $quote = $quote->convertBookingHourly();
        }

        //common to both ClientRegistrationController and clientNewBookingController
        $this->render('/clientRegistration/serviceConfirmation', array('client' => $client, 'quote' => $quote, 'bookingId' => $bookingId));

        //Wizard::clearWizardSteps();
    }



    public function actionServiceCarers() {

        $view = Wizard::VIEW_SERVICE_CARERS;

        Wizard::handleClientSecurity($view);

        $client = Client::loadModel(Yii::app()->user->id);

        Wizard::setStepActive($view);

        $errorMessage = '';
        $firstTime = false;

        //handle navigation 
        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            $carerIds = array();

            //get selected carers
            $posts = $_POST;
            foreach ($posts as $key => $value) {

                if (Util::startsWith($key, 'selected_carers_')) {
                    $carerIds[] = Util::lastCharactersAfter($key, 'selected_carers_');
                }
            }

            //store them
            Session::setSelectedCarers($carerIds);

            if (count($carerIds) >= BusinessRules::getCarerSelectionMinimumSelected()) {

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
                $currentStepIndex = Wizard::getCompletedStepIndex($view);
                $wizardCompleted = $client->wizard_completed;

                if ($currentStepIndex > $wizardCompleted) {
                    $client->wizard_completed = $currentStepIndex;
                    $client->save(false);
                }

                //redirect to next screen
                $this->redirect(array($nextStep));
            } else {

                //ignore error when back
                if (isset($_GET['toView']) || $_GET['navigation'] == 'back') {

                    if (isset($_GET['toView'])) {
                        $nextStep = $_GET['toView'];
                    } else {
                        $nextStep = Wizard::getPreviousStep($view);
                    }
                    $this->redirect(array($nextStep));
                } else {
                    $errorMessage = Yii::t('texts', 'ERROR_YOU_MUST_SELECT_X_CARER_MINIMUM');

                    Yii::app()->user->setFlash('error', $errorMessage);

                    $showMale = $posts['showMalePost'];
                    $showFemale = $posts['showFemalePost'];
                    $nationality = 'all';
                    $maxDisplayCarers = Session::getSelectCarersMaxDisplay();
                }
            }
        } else {
            //INITIALISATION

            $firstTime = true;
        }

        //Init default filters
        if ($firstTime) {
            $showMale = true;
            $showFemale = true;
            $nationality = 'all';

            if (Session::getSelectCarersMaxDisplay() == null) {
                $maxDisplayCarers = BusinessRules::getCarerSelectionShowMoreCarersNumber();
                Session::setSelectCarersMaxDisplay($maxDisplayCarers);
            } else {
                $maxDisplayCarers = Session::getSelectCarersMaxDisplay();
            }
        }

        //Fetch carers that correspond to service users of quote
        $quote = Session::getQuote();
        $serviceUserIds = Session::getSelectedServiceUsers();

        $carers = $client->getClientCarersSelection($quote, $serviceUserIds, $showMale, $showFemale, $nationality, $maxDisplayCarers + 1);
        $carersNotWanted = $client->getClientCarersNotWanted($quote, $serviceUserIds, $showMale, $showFemale, $nationality);

        if ($firstTime) {
            //first time, select max 3 carers
            $selectedCarers = Session::getSelectedCarers();
            if (!isset($selectedCarers)) {
                $a = 0;
                $selectedCarersIds = array();
                foreach ($carers as $carer) {
                    $selectedCarersIds[] = $carer->id;
                    $a++;
                    if ($a == BusinessRules::getCarerSelectionMinimumSelected()) {
                        break;
                    }
                }
                Session::setSelectedCarers($selectedCarersIds);
            }
        }

        $nationalities = DBServices::getCarersNationalities(true);

        $this->render('/clientRegistration/serviceCarers', array('carers' => $carers, 'clientId' => $client->id, 'showMale' => $showMale,
            'showFemale' => $showFemale, 'nationality' => $nationality, 'nationalities' => $nationalities, 'carersNotWanted' => $carersNotWanted,
            'formId' => 'choose-carers-form', 'view' => Constants::CARER_PROFILE_VIEW_SELECT_TEAM, 'errorMessage' => $errorMessage,
            'maxDisplayCarers' => $maxDisplayCarers));
    }

}
