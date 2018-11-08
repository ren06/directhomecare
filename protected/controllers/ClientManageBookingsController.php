<?php

class ClientManageBookingsController extends Controller {

    const SCENARIO_REGISTRATION_BOOKING = 0;
    const SCENARIO_NEW_BOOKING = 1;

    public $layout = '//layouts/layoutClient';
    private $widgetScenario = ServiceUsersWidget::MISSION_SCENARIO;

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function actions() {

        return array(
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
            AddComplaintDialog::NAME => array(
                'class' => 'AddComplaintDialog',
            ),
            AddComplaint::NAME => array(
                'class' => 'AddComplaint',
            ),
        );
    }

    public function accessRules() {

        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'details', 'cancelServicePage', 'abortServicePage', 'invoice', 'feedbackPage',
                    'showCancelServiceDialog', 'cancelService', 'carerVisits', 'index', 'detailsHistory', 'discardMission', 'feedback',
                    'feedbackDialog', 'complain', AddComplaintDialog::NAME, AddComplaint::NAME, 'myBookings', 'discardBooking',
                    'changeBookingLocation', 'selectBookingLocation', 'getImageForClient',
                    'serviceUsers.saveUser', 'serviceUsers.addUser', 'serviceUsers.cancelEditUser', 'serviceUsers.cancelNewUser', 'serviceUsers.deleteUser',
                    'serviceUsers.selectServiceUser', 'showCreateHolidayGapDialog', 'createHolidayGap',
                    'serviceLocations.saveLocation', 'serviceLocations.addLocation', 'serviceLocations.cancelEditLocation', 'serviceLocations.cancelNewLocation',
                    'serviceLocations.deleteLocation', 'showChangeEndDateDialog', 'changeEndDate', 'changeEndDateCancel',
                    'transactionsHistory', 'carerVisitsComplaint', 'changeBookingUsers', 'changeBookingCard', 'serviceLocations.selectLocation',
                    'selectBookingServiceUsers', 'cancelVisitConfirmation', 'selectBookingCreditCard', 'newBooking', 'stopPayments',
                    'adjustShiftTimes', 'adjustShift', 'adjustShiftTimesConfirmation'
                ),
                'expression' => "UserIdentityUser::isClient()", //'expression'=>'isset($user->role) && ($user->role==="editor")'
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /*
     * INIT METHOD
     */

    public function actionIndex() {

        $this->redirect('myBookings');
    }

    public function actionDetails($id, $scenario) {

        Mission::authorizeClient($id);

        $mission = Mission::loadModel($id);

        //$this->render('/carer/missionDetails', array('mission' => $mission, 'scenario' => self::BOOKED_SERVICES));
        $this->render('/clientManageBookings/serviceDetails', array('mission' => $mission, 'scenario' => $scenario));
    }

    public function actionMyBookings() {

        $bookings = Booking::getClientBookings(Yii::app()->user->id);

        $client = Client::loadModel(Yii::app()->user->id);

        $this->render('myBookings', array('client' => $client, 'bookings' => $bookings, 'scenario' => NavigationScenario::CLIENT_BACK_TO_BOOKINGS));
    }

    public function actionDetailsHistory($id) {

        $request = RequestOld::loadChild($id);

        $this->render('serviceDetails', array('request' => $request, 'scenario' => self::SERVICES_HISTORY));
    }

    public function actionCancelServicePage($id, $scenario) {

        Mission::authorizeClient($id);
        $mission = Mission::loadModel($id);

        //double check it was not entered in the URL and that the mission is cancellable
        if (!($mission->isCancelButtonVisible() && $mission->isActive())) {
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        }

        $this->render('cancelService', array('mission' => $mission, 'buttonsColumnVisible' => false, 'scenario' => $scenario));
    }

//    public function actionAbortServicePage($id) {
//
//        $request = RequestOld::loadChild($id);
//        $dataProvider = RequestOld::getBookedService($id);
//
//        $this->render('abortService', array('request' => $request, 'dataProvider' => $dataProvider, 'buttonsColumnVisible' => false));
//    }

    public function actionInvoice($id, $scenario) {

        $missionPayment = MissionPayment::loadModel($id);

        $missions = $missionPayment->missions;

        $this->render('/invoice/invoice', array('missions' => $missions, 'user' => Constants::USER_CLIENT, 'scenario' => $scenario));
    }

    public function actionFeedbackPage($id, $scenario) {

        Mission::authorizeClient($id);
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

        $this->render('visitComplaintPage', array('mission' => $mission, 'complaintCarer' => $complaintCarer,
            'complaintClient' => $complaintClient, 'scenario' => $scenario));
    }

    public function actionShowCancelServiceDialog() {

        if (Yii::app()->request->isAjaxRequest) {

            $id = $_POST['id'];
            $scenario = $_POST['scenario'];

            Mission::authorizeClient($id);
            $mission = Mission::loadModel($id);

            $resultAmounts = $mission->calculateCancelMoneyAmountsClient();

            $voucherAmountText = $resultAmounts['voucher']->text;

            $total = $mission->getTotalLivePrice(Constants::USER_CLIENT);
            $text = Yii::t('texts', 'NOTE_CANCEL_VISIT', array('{totalPaid}' => $total->text, '{totalVoucher}' => $voucherAmountText));
            $url = CHtml::normalizeUrl(array('clientManageBookings/cancelService'));

            $returnURL = CHtml::normalizeUrl(array('clientManageBookings/cancelVisitConfirmation/scenario/' . $scenario));

            echo UIServices::showMissionDialog($url, $id, $text, Yii::t('texts', 'BUTTON_YES_CANCEL_THIS_VISIT'), Yii::t('texts', 'BUTTON_CLOSE'), Yii::t('texts', 'HEADER_DO_YOU_WANT_TO_CANCEL_THIS_VISIT'), $returnURL, 600, 200);
        }
    }

    public function actionAbortServiceDialog() {

        if (Yii::app()->request->isAjaxRequest) {

            //first validate entered dates
            $abortDate = $_POST['abortDate'];
            $id = $_POST['id'];
            $type = $_POST['type'];

            $request = RequestOld::loadChild($id);
            $endDate = $RequestLiveIn->getEndDate();

            if (Calendar::isBetween($abortDate, $RequestLiveIn->getMinimumAbortDate(), $endDate, $format = Calendar::FORMAT_DISPLAYDATE, true)) {

                echo UIServices::showMissionDialog('abortService', $id, $type, Yii::t('texts', 'POPUP_NOTE_SURE_TO_ABORT'), Yii::t('texts', 'POPUP_BUTTON_YES_I_WANT_ABORT'), Yii::t('texts', 'CLOSE'), Yii::t('texts', 'POPUP_TITLE_ABORT_SERVICE'));
            } else {
                echo 'error';
            }
        }
    }

    public function actionCancelService() {

        if (Yii::app()->request->isAjaxRequest) {

            $id = $_POST['id'];

            Mission::authorizeClient($id);
            $mission = Mission::loadModel($id);

            $mission->cancelByClient();
        }
    }

    public function actionAbortService() {

        if (Yii::app()->request->isAjaxRequest) {

            $id = $_POST['id'];
            $type = $_POST['type'];
            $abortDate = $_POST['abortDate'];

            RequestLiveInOld::abortByClient(Yii::app()->user->id, $id, $abortDate);
        }
    }

    //DISCARD cancelled by client mission
    public function actionDiscardMission($id) {

        if (Yii::app()->request->isAjaxRequest) {

            Mission::authorizeClient($id);
            $mission = Mission::loadModel($id);
            $mission->discardByClient();
        }
    }

    public function actionDiscardBooking($id) {

        if (Yii::app()->request->isAjaxRequest) {

            $booking = Booking::loadModel($id);
            $booking->discardByClient();
        }
    }

    public function actionChangeBookingLocation($bookingId) {

        $booking = Booking::loadModel($bookingId);
        $location = $booking->location;
        $client = Client::loadModel($booking->id_client);
        $locations = $client->clientLocations;

        //build original user indexes
        $userIndexes = array();

        for ($i = 0; $i < count($locations); $i++) {

            $id = $locations[$i]->id;

            $userIndexes[$i] = $id;
        }

        Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;

        //store booking        
        Session::setSelectedValidQuote($booking);
        //select the current location
        Session::setSelectedServiceLocation($location->id);


        $this->render('changeBookingLocation', array('client' => $client, 'locations' => $locations, 'booking' => $booking));
    }

    public function actionChangeBookingUsers($bookingId) {

        $booking = Booking::loadModel($bookingId);
        $bookingUsers = $booking->serviceUsers;
        $client = Client::loadModel($booking->id_client);
        $clientUsers = $client->serviceUsers;

        //build original user indexes
        $userIndexes = array();

        for ($i = 0; $i < count($clientUsers); $i++) {

            $id = $clientUsers[$i]->id;

            $userIndexes[$i] = $id;
        }


        Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;

        //store booking
        Session::setSelectedValidQuote($booking);

        //set selected service user ids
        foreach ($bookingUsers as $bookingUser) {
            Session::setSelectedServiceUser($bookingUser->id);
        }

        $this->render('changeBookingUsers', array('client' => $client, 'clientUsers' => $clientUsers, 'booking' => $booking));
    }

    public function actionChangeBookingCard($bookingId) {

        $client = Client::loadModel(Yii::app()->user->id);

        $booking = Booking::loadModel($bookingId);

        $creditCards = $client->creditCards; //CreditCard::getCreditCard($clientId, Constants::DATA_MASTER);

        $newCreditCard = new CreditCard();

        Session::setSelectedCreditCard($booking->creditCard->id);

        $selectedBillingAddressRadioButton = '';

        $this->render('changeBookingCard', array('client' => $client, 'booking' => $booking, 'creditCards' => $creditCards, 'newCreditCard' => $newCreditCard,
            'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));


        //$this->render('changeBookingCard', array('client' => $client, 'clientUsers' => $clientUsers, 'bookingUsers' => $bookingUsers, 'booking' => $booking));
    }

    public function actionSelectBookingLocation() {

        $locationId = Session::getSelectedServiceLocation();

        if ($locationId == null) {

            echo 'error';
        } else {

            //$locationId = $_POST['locationId'];
            $bookingId = $_POST['bookingId'];

            //set location id
            $booking = Booking::loadModel($bookingId);
            $booking->id_address = $locationId;
            $booking->save();
        }
    }

    public function actionSelectBookingServiceUsers() {

        $serviceUsersIds = Session::getSelectedServiceUsers();

        if (empty($serviceUsersIds)) {

            echo 'error';
        } else {

            $bookingId = $_POST['bookingId'];

            //set location id
            $booking = Booking::loadModel($bookingId);

            //delete previous associations, set new users and save
            $booking->changeServiceUsers($serviceUsersIds);
        }
    }

    public function actionSelectBookingCreditCard() {

        Session::removeSelectedCreditCard();

        $cardId = $_POST['radio_button_card'];
        $bookingId = $_POST['bookingId'];

        //set location id
        $booking = Booking::loadModel($bookingId);

        //delete previous associations, set new users and save
        $booking->id_credit_card = $cardId;
        $res = $booking->save(false);
        echo '';
    }

    public function actionShowCreateHolidayGapDialog() {

        if (!Yii::app()->user->checkAccess(Constants::USER_CLIENT)) {

            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }

        if (Yii::app()->request->isAjaxRequest) {

            UIServices::unregisterJQueryScripts();

            $bookingId = $_POST['bookingId'];

            $booking = Booking::loadModel($bookingId);

            $holidayGap = new BookingGap();

            $output = $this->renderPartial('_createHolidayGap', array('booking' => $booking, 'holidayGap' => $holidayGap), true, true);

            echo $output;
        }
    }

    public function actionCreateHolidayGap() {

        $model = new BookingGap();

        $start_date_time = ReadHttpRequest::readStartDateTime();
        $end_date_time = ReadHttpRequest::readEndDateTime();

        $model->start_date_time = $start_date_time;
        $model->end_date_time = $end_date_time;
        $model->id_booking = $_REQUEST['bookingId'];

        $this->performAjaxValidation($model);

        $successful = $model->save();

        //redirect done in ajax
    }

    public function actionShowChangeEndDateDialog() {

//        if (!Yii::app()->user->checkAccess(Constants::USER_CLIENT)) {
//
//            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
//        }

        if (Yii::app()->request->isAjaxRequest) {

            UIServices::unregisterAllScripts();

            $bookingId = $_POST['bookingId'];

            $booking = Booking::loadModel($bookingId);

            $output = $this->renderPartial('_changeEndDateDialog', array('booking' => $booking), true, true);

            echo $output;
        }
    }

    public function actionStopPayments() {

        if (isset($_REQUEST['setEndDate'])) {

            //start date of next mission to be paid + minimum duration
            //load booking
            $bookingId = $_REQUEST['bookingId'];
            $booking = Booking::loadModel($bookingId);


            $booking->end_date_time = $_REQUEST['setEndDate'];
            $booking->recurring = 0;

            $successful = $booking->save(false);
            UIServices::unregisterAllScripts();
        } else {
            echo 'No End Date';
        }
    }

    public function actionChangeEndDate() {

        if (isset($_POST['untilFurther'])) {

            $bookingId = $_REQUEST['bookingId'];
            $booking = Booking::loadModel($bookingId);
            $booking->recurring = true;
            $booking->end_date_time = null;

            $booking->validate();
            $err = $booking->errors;

            $successful = $booking->save(false);
        } elseif (isset($_POST['setEndDate'])) {

            //start date of next mission to be paid + minimum duration
            //load booking
            $bookingId = $_REQUEST['bookingId'];
            $booking = Booking::loadModel($bookingId);

            if ($booking->type == Constants::LIVE_IN) {

                $end_date_time = ReadHttpRequest::readEndDateTime();
            } else {
                $end_date_time = ReadHttpRequest::readEndDate();
            }

            $booking->end_date_time = $end_date_time;
            $booking->recurring = 0;
            $booking->scenario = Booking::SCENARIO_CHANGE_END_DATE;

            if ($booking->validate()) {

                $successful = $booking->save(false);
                UIServices::unregisterAllScripts();
            } else {
                $errors = $booking->errors;
                $error = $errors['end_date_time'];
                $message = $error[0];
                echo $message;
            }
        } else {
            echo 'abort';
        }
    }

    public function actionChangeEndDateCancel() {

        UIServices::unregisterAllScripts();
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

    public function actionGetImageForClient($documentId, $crop) {

        //TODO must add security, make sure this document id is related to carer assigned to current mission

        $carerDocument = CarerDocument::loadModelAdmin($documentId);

        if ($crop) {

            $fileContent = $carerDocument->getCropFile();
        } else {
            $fileContent = $carerDocument->getFile();
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-length: ' . $fileContent->size);
        header('Content-Type: ' . $fileContent->extension);
        // header('Content-Disposition: attachment; filename=' . 'name');

        echo $fileContent->content;
    }

    public function actionTransactionsHistory() {

        $dataProvider = ClientTransaction::getClientTransactions(Yii::app()->user->id);

        $this->render('transactionsHistory', array('dataProvider' => $dataProvider, 'scenario' => NavigationScenario::CLIENT_BACK_TO_PAYMENTS));
    }

    //Returns all visits for current client
    public function actionCarerVisits() {

        $clientId = Yii::app()->user->id;

        $dataProvider = Mission::getClientMissionsDataProvider($clientId);

        $this->render('carerVisits', array('dataProvider' => $dataProvider, 'scenario' => NavigationScenario::CLIENT_BACK_TO_VISITS));
    }

    public function actionCarerVisitsComplaint() {

        $clientId = Yii::app()->user->id;

        $dataProvider = Mission::getComplaintMissionsClientDataProvider($clientId);

        $this->render('carerVisitsComplaint', array('dataProvider' => $dataProvider, 'scenario' => NavigationScenario::CLIENT_BACK_TO_OPENED_COMPLAINTS));
    }

    public function actionCancelVisitConfirmation($scenario) {

        $this->render('cancelVisitConfirmation', array('scenario' => $scenario));
    }

    public function actionAdjustShift($id, $scenario) {

        if (isset($id) && isset($scenario)) {
            Yii::app()->user->setState('shift_id', $id);
            Yii::app()->user->setState('scenario', $scenario);
            $this->redirect(array('adjustShiftTimes'));
        } else {
            $this->redirect(array('myBookings'));
        }
    }

    public function actionAdjustShiftTimes() {

        $id = Yii::app()->user->getState('shift_id');
        $scenario = Yii::app()->user->getState('scenario');

        if (!(isset($id) && isset($scenario))) {
            $this->redirect(array('myBookings'));
        }

        Mission::authorizeClient($id);
        $mission = Mission::loadModel($id);
        $client = Client::loadModel(Yii::app()->user->id);
        $result = null;

        $clientId = Yii::app()->user->id;

        $creditCards = CreditCard::getCreditCard($clientId, Constants::DATA_MASTER);

        $selectedCreditCardRadioButton = $creditCards[0]->id;
        $creditCard = new CreditCard();

        //make a copy of the mission and store it in the Session
        $originalMission = Session::getTempMission($id);
        if (!isset($originalMission)) {
            $originalMission = clone $mission;
            Session::setTempMission($originalMission);
        }

        //double check it was not entered in the URL and that the mission is cancellable
        if (!($mission->isFinished() && $mission->isActive())) {
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        }

        //checked and get new entered time
        if (isset($_POST['calculate_button']) || isset($_POST['pay_button'])) { //calculate time button

            //set the date
            $hour = $_POST['MissionHourly']['StartHour'];
            str_pad($hour, 2, '0', STR_PAD_LEFT);
            $minute = $_POST['MissionHourly']['StartMinute'];
            $timeStart = $hour . ':' . $minute . ':00';
            $mission->start_date_time = $mission->getStart_Date() . ' ' . $timeStart;

            $hour = $_POST['MissionHourly']['EndHour'];
            str_pad($hour, 2, '0', STR_PAD_LEFT);
            $minute = $_POST['MissionHourly']['EndMinute'];
            $timeEnd = $hour . ':' . $minute . ':00';
            $mission->end_date_time = $mission->getStart_Date() . ' ' . $timeEnd;

            $newDuration = Calendar::calculate_Duration_Seconds($mission->start_date_time, $mission->end_date_time);
            $oldDuration = Calendar::calculate_Duration_Seconds($originalMission->start_date_time, $originalMission->end_date_time);

            if ($newDuration <= $oldDuration) {
                //error cannot be shorter
                Yii::app()->user->setFlash('error_times', Yii::t('texts', 'ERROR_YOU_CAN_ONLY_INCREASE_THE_NUMBER_OF_HOURS'));
            } else {

                //ok
                //figure out the payments
                $price = Prices::getTotalPrice($mission, Constants::USER_CLIENT);
                $oldPrice = Prices::getTotalPrice($originalMission, Constants::USER_CLIENT);

                $toPay = $price->substract($oldPrice);

                $payment = Prices::calculatePaymentBreakdown($client->id, $toPay);
                $payCard = $payment['paidCash'];
                $payVoucher = $payment['paidCredit'];
                $voucherBalance = $payment['remainingCreditBalance'];

                $timeDiff = $newDuration - $oldDuration;

                $result['toPay'] = $toPay;
                $result['toPayCard'] = $payCard;
                $result['toPayVoucher'] = $payVoucher;
                $result['extraTime'] = Calendar::convert_Seconds_DayHoursMinutesSeconds($timeDiff, true);

                //User clicks pay
                if (isset($_POST['pay_button'])) {

                    $amount = $_POST['amount'];
                    $currency = $_POST['currency'];

                    $price = new Price($amount, $currency);

                    if ($payCard->amount > 0) {

                        $cardValue = $_POST[UIConstants::RADIO_BUTTON_CREDIT_CARD2];
                        $selectedCreditCardRadioButton = $cardValue;


                        //if user enters a new card
                        if ($cardValue == UIConstants::RADIO_BUTTON_CREDIT_CARD_OTHER) {

                            $creditCard = new CreditCard();

                            $data = $_POST['CreditCard'];
                            $creditCard->attributes = $data;
                            $creditCard->id_client = $clientId;
                            $creditCard->id_address = $client->id_address;

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
                            //user selects existing card
                            $creditCardId = $cardValue;
                            CreditCard::authorizeClient($creditCardId);
                            $creditCardEncrypted = CreditCard::loadModel($creditCardId);
                            $creditCard = $creditCardEncrypted->getDecryptedTemporaryInstance();
                        }
                        
                        $validCard = $creditCard->validate();
                        $voucherOnly = false;
                    } else {
                        $validCard = true;
                        $voucherOnly = true;
                        $paymentSuccessful = true;
                    }

                    $bankReference = '';
                    $cardLastDigits = '';
                    
                    if ($validCard) {

                        if (!$voucherOnly) {

                            //PAY
                            $paymentHandler = BorgunHandler::getInstance();
                            $success = $paymentHandler->doDirectPayment(null, $creditCard, null, $payCard);

                            if ($success) {

                                $bankReference = $paymentHandler->getTransactionRef();

                                $response = $paymentHandler->getResponse();
                                $res = print_r($response, TRUE);

                                $paymentSuccessful = $paymentHandler->isTransactionSuccessful();

                                if (!$paymentSuccessful) {
                                    Yii::app()->user->setFlash('error_card', Yii::t('texts', 'Payment error'));
                                } else {

                                    //store if new credit card
                                    if ($creditCard->isNewRecord) {
                                        $creditCard->save();
                                    }
                                    
                                     $cardLastDigits = $creditCard->getLastNumbers();
                                }
                            } else {
                                Yii::app()->user->setFlash('error_card', Yii::t('texts', 'Payment error'));
                            }
                        }

                        if ($paymentSuccessful) {

                            //create client transaction
                            $id_mission_payment = $mission->id_mission_payment;
                                                   
                            ClientTransaction::createPayment($client->id, $id_mission_payment, $payCard, $payVoucher, $voucherBalance, ClientTransaction::ADJUSTMENT, $toPay, $bankReference, $cardLastDigits, null, $mission->id);

                            //update for good the mission
                            $succ = $mission->save(false);

                            //clear temp mission
                            Session::setTempMission(null);

                            $url = Yii::app()->createUrl("clientManageBookings/transactionsHistory");
                            Yii::app()->user->setFlash('success', "The shift was adjusted - You have paid " . $price->text . ". You can see the transaction <a href='$url'>here</a>.");

                            Yii::app()->user->setState('originalMission', $originalMission);
                            Yii::app()->user->setState('mission', $mission);
                            Yii::app()->user->setState('price', $price);
                            Yii::app()->user->setState('scenario', $scenario);

                            Yii::app()->user->setState('shift_id', null);

                            $this->redirect(array('adjustShiftTimesConfirmation'));
                        }
                    }
                } 
            }
        }

        $this->render('adjustShiftTimes', array('result' => $result,
            'client' => $client,
            'originalMission' => $originalMission,
            'mission' => $mission,
            'buttonsColumnVisible' => false,
            'creditCard' => $creditCard,
            'creditCards' => $creditCards,
            'selectedCreditCardRadioButton' => $selectedCreditCardRadioButton,
            'scenario' => $scenario));
    }

    public function actionAdjustShiftTimesConfirmation() {

        $originalMission = Yii::app()->user->getState('originalMission');
        $mission = Yii::app()->user->getState('mission');
        $price = Yii::app()->user->getState('price');
        $scenario = Yii::app()->user->getState('scenario');

        $this->render('adjustShiftTimesConfirmation', array('originalMission' => $originalMission,
            'mission' => $mission,
            'price' => $price,
            'scenario' => $scenario));
    }

}
?>
