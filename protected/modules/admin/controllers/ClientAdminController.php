<?php

class ClientAdminController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '/layouts/column2';
    public $defaultAction = 'index';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            // 'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index', 'view', 'admin'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionClients($wzd) {

        if ($wzd == Wizard2::CLIENT_LAST_STEP_INDEX) {
            $condition = 'wizard_completed=' . $wzd;
        } else {
            $condition = 'wizard_completed < ' . Wizard2::CLIENT_LAST_STEP_INDEX;
        }

        $dataProvider = new CActiveDataProvider('Client', array(
            'criteria' => array(
                'condition' => $condition,
                'order' => 'id DESC',
            //'with' => array('author'),
            ),))
        ;

        $this->render('/client/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('/client/view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Client;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Client'])) {
            $model->attributes = $_POST['Client'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('/client/create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Client'])) {
            $model->attributes = $_POST['Client'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('/client/update', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Client');
        $this->render('/client/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Client('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Client']))
            $model->attributes = $_GET['Client'];

        $this->render('/client/admin', array(
            'model' => $model,
        ));
    }

    public function actionManageClients() {

        $model = new Client('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Client']))
            $model->attributes = $_GET['Client'];

        $this->render('/client/manageClients', array(
            'model' => $model,
        ));
    }

    public function actionViewClient($id) {

        $client = Client::loadModel($id);

        $this->render('/client/viewClient', array(
            'client' => $client,
        ));
    }

    //All missions
    public function actionViewClientMissions($id) {

        $client = Client::loadModel($id);

        $this->render('/client/viewClientMissions', array('client' => $client));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Client::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'client-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionDelete($id) {

        $model = Client::loadModelAdmin($id);
        $model->delete();

        $this->redirect(array('manageClients'));
    }

    public function actionCancelMissionsByBooking() {

        //search all clients who made a booking
        $clientsBooking = Client::getClientsMadeBooking();

        $clients = array();
        foreach ($clientsBooking as $client) {

            $clients[$client['id']] = $client['id'] . ' ' . $client['first_name'] . ' ' . $client['last_name'];
        }

        $this->render('/client/cancelMissionsByBooking', array(
            'clients' => $clients,
        ));
    }

    public function actionCancelMissionsByBookingClientSelection() {

        $clientId = $_POST['clientId'];

        $client = Client::loadModel($clientId);

        $bookings = $client->bookings;

        $result = $this->renderPartial('/client/cancelMissionsByBookingTable', array('bookings' => $bookings), true, true);

        echo $result;
    }

    public function actionRefundMissionsByBooking() {

        //search all clients who made a booking
        $clientsBooking = Client::getClientsMadeBooking();

        $clients = array();
        foreach ($clientsBooking as $client) {

            $clients[$client['id']] = $client['id'] . ' ' . $client['first_name'] . ' ' . $client['last_name'];
        }

        $this->render('/client/refundClientByBooking', array(
            'clients' => $clients,
        ));
    }

    public function actionRefundClientByBookingClientSelection() {

        $clientId = $_POST['clientId'];
        //UIServices::unregisterAllScripts();

        $client = Client::loadModel($clientId);

        $bookings = $client->bookings;

        $result = $this->renderPartial('/client/refundClientByBookingTable', array('bookings' => $bookings), true, true);

        echo $result;
    }

    public function actionRefundClient() {

        $bookingId = $_POST['bookingId'];

        $booking = Booking::loadModel($bookingId);

        $resultArray = $booking->refund(true); //$byPassMissionsNotStarted

        echo $resultArray['message'];
    }

    public function actionCancelByClient() {

        $bookingId = $_POST['bookingId'];

        $booking = Booking::loadModel($bookingId);

        $resultArray = $booking->cancelByClient(true); //$byPassMissionsNotStarted

        echo $resultArray['message'];
    }

    public function actionViewServiceLocation($id) {

        $client = Client::loadModel($id);

        $form = $this->beginWidget('CActiveForm', array());

        $clientLocations = $client->clientLocations;
        $output = '';
        for ($i = 0; $i < count($clientLocations); $i++):

            $location = $clientLocations[$i];
            $output .= "Id: $location->id <br><br>";
            $output .= $this->renderPartial('application.views.clientRegistration._serviceLocation', array(
                'serviceLocation' => $location,
                'conditionErrors' => array(),
                'index' => $i,
                'form' => $form,
                    ), true);
        endfor;

        // if ($this->beforeRender($view)) {
        $layoutFile = $this->getLayoutFile($this->layout);
        $output = $this->renderFile($layoutFile, array('content' => $output), true);

        //$this->afterRender($view, $output);

        $output = $this->processOutput($output);

        echo $output;
    }

    public function actionViewServiceUsers($id) {

        $client = Client::loadModel($id);

        $serviceUsers = $client->serviceUsers;

        $output = '';

        for ($i = 0; $i < count($serviceUsers); $i++):

            $serviceUser = $serviceUsers[$i];
            $output .= "Id: $serviceUser->id <br><br>";
            $output .= $this->renderPartial('application.views.clientRegistration._serviceUser', array(
                'serviceUser' => $serviceUser,
                'conditionErrors' => array(),
                'index' => $i,
                    ), true);
        endfor;

        // if ($this->beforeRender($view)) {
        $layoutFile = $this->getLayoutFile($this->layout);
        $output = $this->renderFile($layoutFile, array('content' => $output), true);

        //$this->afterRender($view, $output);

        $output = $this->processOutput($output);

        echo $output;


        //  $this->render('application.views.clientMaintain.serviceUsers', array('client' => $client, 'serviceUsers' => $serviceUsers));
    }

    public function actionBookingForClient($id) {

        $clientId = $id;
        $client = Client::loadModel($id);

        $model = new BookingForClientForm();

        $action = isset($_GET['action']) ? $_GET['action'] : '';

        if (isset($_POST['BookingForClientForm'])) {

            $model->attributes = $_POST['BookingForClientForm'];
            $model->hourlyQuoteSimpleForm = new HourlyQuoteSimpleForm();

            foreach ($_POST['HourlyQuoteDayForm'] as $dayFormData) {

                $dayForm = new HourlyQuoteDayForm(HourlyQuoteDayForm::ADMIN_SCENARIO);
                $dayForm->attributes = $dayFormData;

                $model->hourlyQuoteSimpleForm->dayForms[] = $dayForm;
            }

            if ($action == 'Add') {
                $dayForm = new HourlyQuoteDayForm(HourlyQuoteDayForm::ADMIN_SCENARIO);
                $dayForm->initFirstTime();
                $model->hourlyQuoteSimpleForm->dayForms[] = $dayForm;
            } else {
                if ($model->validate() && $model->hourlyQuoteSimpleForm->validate()) {

                    $booking = $model->hourlyQuoteSimpleForm->convertBookingHourly();
                    $booking->scenario = Booking::SCENARIO_ADMIN_CREATE;

                    //CREDIT CARD AND BILLING OK - CONTINUE
                    //Calculate payment
                    $payment = $booking->calculatePayment(Constants::USER_CLIENT, $clientId);

                    $creditCardEncrypted = CreditCard::loadModel($model->creditCardId);
                    $creditCard = $creditCardEncrypted->getDecryptedTemporaryInstance();
                    $billingAddress = Address::loadModel($model->addressId);

                    //total price
                    $totalPrice = $payment['toPay'];

                    $price = $payment['paidCash'];
                    $voucher = $payment['paidCredit'];

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

                            $bookingId = BookingHandler::handleBooking($booking, $clientId, array($model->serviceUserId), $model->addressId, array($model->carerId), $billingAddress, $creditCard, $transactionRef, $transactionDate, $model->sendEMail);

                            $booking = Booking::loadModel($bookingId);
                            $missionCount = count($booking->missions);

                            Yii::app()->user->setFlash('success', "Booking $bookingId created with $missionCount mission(s). Total amount: " . $totalPrice->text . ' (paid by card ' . $price->text . ' paid by voucher ' . $voucher->text . ')');
                            $this->redirect(array('clientAdmin/viewClient/id/' . $clientId));
                        } else {
                            Yii::app()->user->setFlash('error', $paymentHandler->getPaymentMessage() . ' ' . $paymentHandler->getTransactionCode());
                        }
                    }
                } else {
                    Yii::app()->user->setFlash('error', 'Some dates are overlapping or some shifts are too short');
                }
            }
        } else {

            $hourlyQuoteSimpleForm = new HourlyQuoteSimpleForm();
            $hourlyQuoteSimpleForm->initFirstTime();

            $model->hourlyQuoteSimpleForm = $hourlyQuoteSimpleForm;
        }

        $this->render('/client/bookingForClient', array('client' => $client, 'model' => $model));
    }

    public function actionBookingForClientRemoveShift($index) {
        
    }

    public function actionBookingForClientAddShift() {
        
    }

    public function actionViewBookings($id) {

        $client = Client::loadModel($id);

        $bookings = $client->bookings;

        $this->render('/client/viewBookings', array('bookings' => $bookings, 'client' => $client));
    }

    public function actionViewBookings2($id) {

        //$client = Client::loadModel($id);

        $model = new Booking('search');
        $model->id_client = $id;
        $model->discarded_by_client = null;
        $model->recurring = null;
        $model->type = null;
        $model->subtype = null;

        $this->render('/booking/admin', array(
            'model' => $model,
        ));
    }

    public function actionEditBooking($id) {

        $booking = Booking::loadModel($id);

        $this->render('/client/editBooking', array('booking' => $booking));
    }

    public function actionViewBankingTransactions($id, $environment = null) {

        $client = Client::loadModel($id);

        $bookings = $client->bookings;
        $allPayments = array();
        foreach ($bookings as $booking) {

            $payments = $booking->missionPayments; //1 to 1 most of the time
            $allPayments = array_merge($allPayments, $payments);
        }


        if (!isset($environment)) {
            if (Yii::app()->params['test']['paymentTest'] == false) {
                $environment = IPaymentHandler::ENVIRONMENT_LIVE;
            } else {
                $environment = IPaymentHandler::ENVIRONMENT_TEST;
            }
        }

        $paymentHandler = BorgunHandler::getInstance($environment);

        $success = $paymentHandler->getTransactionHistory();

        //get payment ref for givent customer and filter them from transaction history

        if ($success) {

            $transactionList = $paymentHandler->getTransactionList();

            $transactions = $transactionList->transactions;

//            foreach ($transactions as $transaction) {
//
//                $transactionType = BorgunHandler::getTransactionTypeText($transaction->transactionType) . '(' . $transaction->transactionType . ')';
//                //$transactionDate = Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate);
//                $transactionDate = $transaction->transactionDate;
//
//                if ($transaction->trCurrency == '826') {
//                    $currency = 'GBP';
//                } else {
//                    $currency = 'XXX';
//                }
//
//                $amountPrice = new Price($transaction->trAmount / 100, $currency);
//                $actionCode = $transaction->actionCode . ' (' . BorgunHandler::getCodeText($transaction->actionCode) . ')';
//
//                $row = array(
//                    'TransactionType' => $transactionType,
//                    'TransactionNumber' => $transaction->transactionNumber,
//                    'BatchNumber' => $transaction->batchNumber,
//                    'FormattedTransactionDate' => Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate),
//                    'TransactionDate' => $transactionDate,
//                    'PAN' => $transaction->PAN,
//                    'RRN' => $transaction->RRN,
//                    'ActionCode' => $actionCode,
//                    'AuthorizationCode' => $transaction->authorizationCode,
//                    'Amount' => $amountPrice->text,
//                    'Voided' => $transaction->voided,
//                    'Status' => $transaction->status,
//                );
//
//                $rawData[] = $row;
//            }



            $rawData = array();

            foreach ($allPayments as $payment) {

                $reference = $payment->transaction_id;

                foreach ($transactions as $transaction) {

                    if ($reference == $transaction->RRN) {

                        $transactionType = BorgunHandler::getTransactionTypeText($transaction->transactionType) . '(' . $transaction->transactionType . ')';
                        //$transactionDate = Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate);
                        $transactionDate = $transaction->transactionDate;

                        if ($transaction->trCurrency == '826') {
                            $currency = 'GBP';
                        } else {
                            $currency = 'XXX';
                        }

                        $amountPrice = new Price($transaction->trAmount / 100, $currency);
                        $actionCode = $transaction->actionCode . ' (' . BorgunHandler::getCodeText($transaction->actionCode) . ')';

                        $booking = $payment->booking;
                        $missions = $booking->missions;
                        $missionIds = array();
                        foreach ($missions as $mission) {

                            $missionIds[] = $mission->id;
                        }

                        $row = array(
                            'BookingId' => $booking->id,
                            'MissionIds' => $missionIds,
                            'TransactionType' => $transactionType,
                            'TransactionNumber' => $transaction->transactionNumber,
                            'BatchNumber' => $transaction->batchNumber,
                            'FormattedTransactionDate' => Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate),
                            'TransactionDate' => $transactionDate,
                            'PAN' => $transaction->PAN,
                            'RRN' => $transaction->RRN,
                            'ActionCode' => $actionCode,
                            'AuthorizationCode' => $transaction->authorizationCode,
                            'Amount' => $amountPrice->text,
                            'Voided' => $transaction->voided,
                            'Status' => $transaction->status,
                        );


                        $rawData[] = $row;
                    }
                }
            }

            $clientTransactions = $client->clientTransactions;

            foreach ($clientTransactions as $clientTransaction) {

                $reference = $clientTransaction->bank_reference;

                foreach ($transactions as $transaction) {

                    if (isset($clientTransaction->id_mission)) {

                        if ($reference == $transaction->RRN) {

                            $transactionType = BorgunHandler::getTransactionTypeText($transaction->transactionType) . '(' . $transaction->transactionType . ')';
                            //$transactionDate = Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate);
                            $transactionDate = $transaction->transactionDate;

                            if ($transaction->trCurrency == '826') {
                                $currency = 'GBP';
                            } else {
                                $currency = 'XXX';
                            }

                            $amountPrice = new Price($transaction->trAmount / 100, $currency);
                            $actionCode = $transaction->actionCode . ' (' . BorgunHandler::getCodeText($transaction->actionCode) . ')';

                            //$booking = $payment->booking;
                            $missionId = $clientTransaction->id_mission;
                            $missionIds = array($missionId);

                            $row = array(
                                'BookingId' => '',
                                'MissionIds' => $missionIds,
                                'TransactionType' => $transactionType,
                                'TransactionNumber' => $transaction->transactionNumber,
                                'BatchNumber' => $transaction->batchNumber,
                                'FormattedTransactionDate' => Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate),
                                'TransactionDate' => $transactionDate,
                                'PAN' => $transaction->PAN,
                                'RRN' => $transaction->RRN,
                                'ActionCode' => $actionCode,
                                'AuthorizationCode' => $transaction->authorizationCode,
                                'Amount' => $amountPrice->text,
                                'Voided' => $transaction->voided,
                                'Status' => $transaction->status,
                            );


                            $rawData[] = $row;
                        }
                    }
                }
            }

            //we want the latest transaction first

            $rawData = array_reverse($rawData);

            $dataProvider = new CArrayDataProvider($rawData, array(
                'keyField' => 'TransactionNumber',
                'totalItemCount' => count($rawData),
                'pagination' => array('pageSize' => 100,),
            ));
        }

        $this->render('/client/bankingTransactions', array('dataProvider' => $dataProvider, 'client' => $client));
    }

    public function actionFreeVoucher($id) {

        $client = Client::loadModel($id);

        if (!empty($_POST)) {

            $hours = $_POST['hours'];

            $unitPrice = Prices::getPrice(Constants::USER_CLIENT, 'hourly_price');

            $amount = $unitPrice->multiply($hours);

            ClientTransaction::createFreeVoucher($id, $amount);

            Yii::app()->user->setFlash('success', "Voucher credited: " . $amount->text);

            $this->redirect(array('viewClient', 'id' => $id));
        }

        $this->render('/client/freeVoucher', array(
            'client' => $client,
        ));
    }
    
    public function actionCreateMessageForClient($id){
        
        $client = Client::loadModel($id);
        
         $this->render('/client/clientMessage', array(
            'client' => $client,
        ));
        
    }

}
