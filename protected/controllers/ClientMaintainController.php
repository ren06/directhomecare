<?php

class ClientMaintainController extends Controller {

    public $layout = '//layouts/layoutClient';
    private $widgetScenario = ServiceUsersWidget::EDIT_SCENARIO;

    public function actions() {

        return array(
            ShowPriceAction::NAME => array(
                'class' => 'ShowPriceAction',
            ),
            'serviceUsers.' => array('class' => 'application.components.widgets.service_users.ServiceUsersWidget',
                'saveUser' => array('scenario' => $this->widgetScenario),
                'addUser' => array('scenario' => $this->widgetScenario),
                'cancelEditUser' => array('scenario' => $this->widgetScenario)
            ),
            'serviceLocations.' => array('class' => 'application.components.widgets.service_locations.ServiceLocationsWidget',
                'saveLocation' => array('scenario' => $this->widgetScenario),
                'addLocation' => array('scenario' => $this->widgetScenario),
                'cancelLocation' => array('scenario' => $this->widgetScenario)
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
                'actions' => array('index', 'maintainServiceLocations', 'maintainServiceUsers', 'maintainDetails',
                    'saveDetails', 'cancelDetails', 'savePassword', 'cancelPassword',
                    'serviceUsers.saveUser', 'serviceUsers.addUser', 'serviceUsers.cancelEditUser', 'serviceUsers.cancelNewUser', 'maintainPaymentDetails', 'serviceUsers.deleteUser',
                    'serviceLocations.saveLocation', 'serviceLocations.addLocation', 'serviceLocations.cancelEditLocation', 'serviceLocations.cancelNewLocation', 'serviceLocations.deleteLocation',
                    'cancelEditCard', 'saveCard', 'deleteCard', 'myCarers',
                ), 'expression' => "UserIdentityUser::isClient()", //'expression'=>'isset($user->role) && ($user->role==="editor")'
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {

        if (Yii::app()->user->wizard_completed != Wizard::CLIENT_LAST_STEP_INDEX) {
            $this->redirect('signIn');
        } else {
            $this->redirect('newService');
        }
    }

    public function actionMaintainPaymentDetails() {

        $client = Client::loadModel(Yii::app()->user->id);

        $creditCards = $client->creditCards; //CreditCard::getCreditCard($clientId, Constants::DATA_MASTER);

        $newCreditCard = new CreditCard();

        $selectedBillingAddressRadioButton = '';

        $this->render('maintainPaymentDetails', array('client' => $client, 'creditCards' => $creditCards, 'newCreditCard' => $newCreditCard,
            'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));
    }

    public function actionCancelEditCard($index) {

        UIServices::unregisterAllScripts();

        $client = Client::loadModel(Yii::app()->user->id);

        if ($index !== 'new') {

            $creditCards = $client->creditCards;

            $creditCard = $creditCards[$index];
            $creditCard->setScenario(CreditCard::VALIDATION_SCENARIO_EDIT);

            $params = array('creditCard' => $creditCard, 'client' => $client, 'selectedBillingAddressRadioButton' => '',
                'index' => $index);

            $output = $this->renderPartial('/creditCard/_creditCardDetailsAndBillingAddress', $params, true, true);

            echo $output;
        } else {

            $client = Client::loadModel(Yii::app()->user->id);

            $creditCards = $client->creditCards; //CreditCard::getCreditCard($clientId, Constants::DATA_MASTER);

            $newCreditCard = new CreditCard();

            $selectedBillingAddressRadioButton = '';

            $output = $this->renderPartial('maintainPaymentDetails', array('client' => $client, 'creditCards' => $creditCards, 'newCreditCard' => $newCreditCard,
                'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));

            echo $output;
        }
    }

    public function actionSaveCard() {

        //contains errors
        $errorMessagesFormArray = array();

        //read data
        $creditCardParams = array();
        parse_str($_POST['CreditCard'], $creditCardParams);

        $billingAddressParams = array();
        parse_str($_POST['BillingAddress'], $billingAddressParams);

        $month = $creditCardParams['Month'];
        $year = $creditCardParams['Year'];

        //get index and client
        $index = $_POST['index'];

        $client = Client::loadModel(Yii::app()->user->id);

        //Handle Address
        //save address

        $addressSuccessful = true;

        if (isset($billingAddressParams[UIConstants::RADIO_BUTTON_BILLING_ADDRESS_OTHER . '-' . $index])) {

            //NEW Address
            $billingAddress = new Address();
            //read address
            $billingAddress->address_line_1 = $billingAddressParams['Address']['address_line_1'];
            $billingAddress->address_line_2 = $billingAddressParams['Address']['address_line_2'];
            $billingAddress->city = $billingAddressParams['Address']['city'];
            $billingAddress->post_code = $billingAddressParams['Address']['post_code'];

            $billingAddress->data_type = Address::TYPE_BILLING_ADDRESS;

            $selectedBillingAddressRadioButton = UIConstants::RADIO_BUTTON_BILLING_ADDRESS_OTHER . $index;

            if (!$billingAddress->validate()) {

                $errorMessage = CActiveForm::validate($billingAddress, null, false);

                $className = get_class($billingAddress);
                $errorMessages = str_replace($className . '_', 'error_', $errorMessage);

                $errorMessagesFormArray = json_decode($errorMessages, true);

                $addressSuccessful = false;
            } else {

                $billingAddress->save();

                $addressId = $billingAddress->id;

                //create association
                $serviceLocationAddress = new ClientLocationAddress();
                $serviceLocationAddress->id_client = Yii::app()->user->id;
                $serviceLocationAddress->id_address = $addressId;

                $serviceLocationAddress->save(false);
            }
        } else {

            //get location ID
            $fieldNames = array_keys($billingAddressParams);

            foreach ($fieldNames as $fieldName) {

                if (Util::startsWith($fieldName, 'radio_button_billing_address_')) {

                    $addressId = substr($fieldName, strlen('radio_button_billing_address_'));
                    $billingAddress = Address::loadModel($addressId);

                    $selectedRadioButton = $fieldName;
                    break;
                }
            }
        }

        //get current mode

        if ($index !== 'new') {

            $creditCards = $client->creditCards;
            $creditCard = $creditCards[$index];
        } else {
            $creditCard = new CreditCard();
            $creditCard->name_on_card = $creditCardParams['CreditCard']['name_on_card'];
            $creditCard->card_type = $creditCardParams['CreditCard']['card_type'];
            $creditCard->card_number = $creditCardParams['CreditCard']['card_number'];
            $creditCard->last_three_digits = $creditCardParams['CreditCard']['last_three_digits'];
            $creditCard->id_client = Yii::app()->user->id;
        }

        //set id used or created above           
        if ($addressSuccessful) {
            $creditCard->id_address = $addressId;
        }

        //validate time
        if (is_numeric($month) && is_numeric($year) && checkdate($month, 1, $year)) {

            //add leading 0 if necessary
            $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $month = sprintf("%02d", $month);

            $date_value = "$year-$month-$day";

            if (!Calendar::dateIsBefore($date_value, Calendar::today(Calendar::FORMAT_DBDATE))) {
                $creditCard->expiry_date = $date_value;
            } else {

                unset($creditCard->expiry_date); //unset the value so that error is reported in the model
            }
        }

        if (!$creditCard->validate()) {

            //Form field errors

            $errorMessage = CActiveForm::validate($creditCard, null, false);

            //"{"ServiceUser_last_name":["Last name cannot be blank."]}"
            $className = get_class($creditCard);
            //$errorMessages = str_replace($className . '_', $className . '_', $errorMessage);
            $errorMessages = str_replace($className . '_', 'error_', $errorMessage);
            //'error_name_on_card_' . $index

            $errorMessagesFormArray = CMap::mergeArray(json_decode($errorMessages, true), $errorMessagesFormArray);
        } else {
            if ($addressSuccessful) {

                if ($index == 'new') {
                    $res = $creditCard->save(false);
                    Session::setSelectedCreditCard($creditCard->id);
                } else {
                    $res = $creditCard->save(false, array('expiry_date'));
                }
            }
        }

        if (empty($errorMessagesFormArray)) {

            if ($index == 'new') {

                $client = Client::loadModel(Yii::app()->user->id);

                $creditCards = $client->creditCards;

                $newCreditCard = new CreditCard();

                $selectedBillingAddressRadioButton = '';

                //NOT NEEDED ANYMORE AS THE WHOLE PAGE IS NOW REFRESHED AFTER SUCCESS
                //$output = $this->renderPartial('/creditCard/_creditCards', array('client' => $client, 'creditCards' => $creditCards, 'newCreditCard' => $newCreditCard,
                //    'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton)); 
                $output = '';
            } else {

                $visibleCard = CreditCard::loadModel($creditCard->id);
                $visibleCard->setScenario(CreditCard::VALIDATION_SCENARIO_EDIT);

                $params = array('creditCard' => $visibleCard, 'client' => $client, 'selectedBillingAddressRadioButton' => '',
                    'index' => $index);

                $output = $this->renderPartial('/creditCard/_creditCardDetailsAndBillingAddress', $params, true, true);
            }

            echo $output;
        } else {

            $jsonErrors = json_encode($errorMessagesFormArray);

            echo $jsonErrors;
        }
    }

    public function actionDeleteCard() {

        $client = Client::loadModel(Yii::app()->user->id);

        $creditCards = $client->creditCards;

        $index = $_POST['index'];

        $creditCard = $creditCards[$index];

        $creditCard->delete();
    }

    public function actionMaintainServiceUsers() {

        $clientId = Yii::app()->user->id;
        $client = Client::loadModel($clientId);
        $serviceUsers = $client->serviceUsers;

        if (empty($serviceUsers)) {
//
//            $serviceUser = new ServiceUser();
//            $serviceUsers[] = $serviceUser;

            $lastJob = $client->getLastJob();
            $serviceUser = new ServiceUser();

            if ($lastJob->who_for == 0) {
                $serviceUser->first_name = $lastJob->first_name_user;
                $serviceUser->last_name = $lastJob->last_name_user;
            } else {
                $serviceUser->first_name = $client->first_name;
                $serviceUser->last_name = $client->last_name;
            }

            $serviceUsers[] = $serviceUser;

            $userIndexes[0] = -1; //new unassigned id
            Yii::app()->session['UserIndexes'] = $userIndexes;
        } else {
            //build original user indexes
            $userIndexes = array();

            for ($i = 0; $i < count($serviceUsers); $i++) {

                $id = $serviceUsers[$i]->id;

                $userIndexes[$i] = $id;
            }

            Yii::app()->session['UserIndexes'] = $userIndexes;
        }

        $this->render('serviceUsers', array('client' => $client, 'serviceUsers' => $serviceUsers));
    }

    public function actionMaintainServiceLocations() {

        $clientId = Yii::app()->user->id;
        $client = Client::loadModel($clientId);
        $serviceLocations = $client->clientLocations;

        if (empty($serviceLocations)) {

            $serviceLocation = new Address();
            $serviceLocations[] = $serviceLocation;

            $userIndexes[0] = -1; //new unassigned id
            Yii::app()->session['UserIndexes'] = $userIndexes;
        } else {
            //build original user indexes
            $userIndexes = array();

            for ($i = 0; $i < count($serviceLocations); $i++) {

                $id = $serviceLocations[$i]->id;

                $userIndexes[$i] = $id;
            }

            Yii::app()->session['UserIndexes'] = $userIndexes;
        }

        $this->render('serviceLocations', array('client' => $client, 'serviceLocations' => $serviceLocations));
    }

    public function actionMaintainDetails() {

        $client = Client::loadModel(Yii::app()->user->id);

        $client->setScenario(Client::SCENARIO_UPDATE_CLIENT);

        $resetPassword = new ResetPasswordForm();

        $this->render('details', array('client' => $client, 'resetPassword' => $resetPassword));
    }

    public function actionSaveDetails() {

        if (Yii::app()->request->isAjaxRequest) {
            //update
            $request = Yii::app()->request;

            $clientParam = $request->getPost('Client');

            $invalidBirthDate = false;

            $valid = true;

            $client = Client::loadModel(Yii::app()->user->id);
            $client->setScenario(Client::SCENARIO_UPDATE_CLIENT);

            if (isset($clientParam)) {

                //POPULATE DATA FROM FORM
                $client->attributes = $clientParam; //$_POST['Client'];
//            //check date validity
//            $month = $_POST['Month'];
//            $day = $_POST['Day'];
//            $year = $_POST['Year'];
//
//            if (checkdate($month, $day, $year)) {
//
//                $day = sprintf("%02d", $day);
//                $month = sprintf("%02d", $month);
//
//                $date_value = "$year-$month-$day";
//                $client->date_birth = $date_value;
//            } else {
//
//                unset($client->date_birth); //unset the value so that error is reported in the model
//            }

                if (!$client->save()) {

                    $errorMessage = CActiveForm::validate(array($client), null, false);

                    //[{'id':'Address_0_address_line_1','inputID':'Address_0_address_line_1','
                    //$className = get_class($client);
                    //$errorMessage = str_replace($className . '_', $className . '_', $errorMessage);

                    echo $errorMessage;
                } else {

                    $firstName = $client->first_name;
                    Yii::app()->user->setState('full_name', $client->first_name . ' ' . $client->last_name);

                    $test = Yii::app()->user->getState('full_name');
                    $message = Yii::t('texts', 'FLASH_CHANGES_SAVED');
                    $client->setScenario(Client::SCENARIO_UPDATE_CLIENT); //to make asterisks consistent, scenario changed by save()

                    $this->renderPartial('_details', array('client' => $client, 'message' => $message), false, true);
                }
            }
        }
    }

    public function actionCancelDetails() {

        if (Yii::app()->request->isAjaxRequest) {

            $client = Client::loadModel(Yii::app()->user->id);

            $client->setScenario(Client::SCENARIO_UPDATE_CLIENT);

            $output = $this->renderPartial('_details', array('client' => $client, 'message' => ''), true, true);

            echo $output;
        }
    }

    public function actionCancelPassword() {

        if (Yii::app()->request->isAjaxRequest) {

            $resetPassword = new ResetPasswordForm();

            echo $this->renderPartial('_password', array('resetPassword' => $resetPassword), false, true);
        }
    }

    public function actionSavePassword() {

        if (Yii::app()->request->isAjaxRequest) {
            $client = Client::loadModel(Yii::app()->user->id);

            $request = Yii::app()->request;
            $passwordParam = $request->getPost('ResetPasswordForm');

            $resetPassword = new ResetPasswordForm();
            $valid = true;

            if (isset($passwordParam)) {

                $resetPassword->attributes = $_POST['ResetPasswordForm'];

                $valid = $resetPassword->validate();

                if ($valid) {
                    //check old password
                    $identity = new UserIdentityUser($client->email_address, $passwordParam['oldPassword']);

                    if ($identity->authenticate()) {
                        //check passwords are the same

                        if ($passwordParam['newPassword'] == $passwordParam['newPasswordRepeat']) {

                            //update password
                            $client->password = $passwordParam['newPassword'];

                            $client->savePassword();

                            $resetPassword = new ResetPasswordForm();
                        } else {
                            $valid = false;
                            $errors['PasswordError'] = Yii::t('texts', 'FLASH_TWO_PASSWORDS_DIFFERENT');
                            echo json_encode($errors);
                        }
                    } else {
                        $valid = false;
                        $errors['PasswordError'] = Yii::t('texts', 'FLASH_OLD_PASSWORD_WRONG');
                        echo json_encode($errors);
                    }
                } else {
                    $valid = false;
                    $errorMessage = CActiveForm::validate(array($resetPassword), null, false);
                    echo $errorMessage;
                }
            }

            if ($valid) {
                echo $this->renderPartial('_password', array('resetPassword' => $resetPassword), false, true);
            }
        }
    }

}