<?php

class UnitTestController extends Controller {

    public $layout = '/layouts/column1';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('cancelByAdmin, createFakeClientAndMission, createTestBookings, cronCancelMissionsByAdmin,
                    cronCreditCarers, cronJobPayments, DBConnections, doNonReferencedCredit, splitBookingLiveIn'),
                'users' => array('admin'),
            ),
//            array('allow', // allow all users to perform 'index' and 'view' actions
//                'actions' => array('error', 'adminLogin'),
//                'users' => array('*'),
//            ),
        );
    }

    /**
     * generate split for booking
     */
    public function actionSplitBookingLiveIn() {

        $sets[] = array('startDate' => '2013-03-25', 'endDate' => '2013-04-12');
        $sets[] = array('startDate' => '2013-03-03', 'endDate' => '2013-04-05');
        $sets[] = array('startDate' => '2013-03-09', 'endDate' => '2013-03-31');
        $sets[] = array('startDate' => '2013-03-01', 'endDate' => '2013-04-30');
        $sets[] = array('startDate' => '2013-03-03', 'endDate' => '2013-03-08');
        $sets[] = array('startDate' => '2013-03-03', 'endDate' => '2013-03-20');
        $sets[] = array('startDate' => '2013-03-03', 'endDate' => '2013-03-23');
        $sets[] = array('startDate' => '2013-03-03', 'endDate' => '2013-03-24');
        $sets[] = array('startDate' => '2013-03-03', 'endDate' => '2013-03-25');
        $sets[] = array('startDate' => '2013-03-03', 'endDate' => '2013-03-26');


        foreach ($sets as $set) {

            $this->render('splitBookingLiveIn', array('startDate' => $set['startDate'], 'endDate' => $set['endDate']));
        }
    }

    /**
     * Get the bookings that need payment and create the missions
     */
    public function actionCronJobPayments() {

        $bookings = Booking::getPayableBookings();

        $allBookings = Booking::model()->findAll();

        $notTaken = array();
        $rawData = array();

        foreach ($allBookings as $bk) {

            $found = false;

            foreach ($bookings as $booking) {

                if ($bk->id == $booking->id) {
                    $found = true;
                    break;
                }
            }

            if ($found) {

                $row = array();

                $row['id'] = $booking->id;
                $row['dates'] = $booking->displayDates(true);

                $mission = Booking::getLastMission($booking->id);

                $row['lastMissionDates'] = $mission->displayDates2(true);

                $nextSlot = $booking->getNextMissionSlot();

                if ($nextSlot == null) {
                    $row['nextSlot'] = 'No slot - finished';
                } else {

                    //check next slot is creatable
                    $payInAdvanceHours = BusinessRules::getCronPaymentInAdvanceInHours();

                    //overall rule: create mission if next slot's start date is between 7 and 14 days from now
                    //if now and next slot start date < 14 days: ok to create, otherwise too soon
                    if (Calendar::hoursBetween_DBDateTime(Calendar::today(Calendar::FORMAT_DBDATETIME), $nextSlot->startDateTime) <= $payInAdvanceHours) {

                        $delayHours = BusinessRules::getNewBookingDelayLiveInHours();

                        //if now and next slot start date < 7 days
                        if (Calendar::hoursBetween_DBDateTime(Calendar::today(Calendar::FORMAT_DBDATETIME), $nextSlot->startDateTime) <= $delayHours) {

                            $row['nextSlot'] = 'Too late to create, start date in less than ' . BusinessRules::getNewBookingDelayLiveInDays() . ' days. Start date: ' . Calendar::convert_DBDateTime_DisplayDateTimeText($nextSlot->startDateTime) . ' Today is ' . Calendar::convert_DBDateTime_DisplayDateText(Calendar::today(Calendar::FORMAT_DBDATE));
                        } else {
                            $result = Calendar::convert_DBDateTime_DisplayDateTimeText($nextSlot->startDateTime);
                            $result .= ' - ';
                            $result .= Calendar::convert_DBDateTime_DisplayDateTimeText($nextSlot->endDateTime);

                            //check credit card expiry date
                            $id = $booking->id;
                            $creditCard = $booking->creditCard;

                            $creditCard->handleExpiry();

                            if ($creditCard->hasExpired()) {

                                $result .= ' - Credit Card expired';
                            } else {

                                //cron job only
                                $paymentResult = BookingHandler::handleNextMission($booking->id);

                                if ($paymentResult === true) {
                                    $result .= ' - Payment Successful';
                                } else {
                                    $result .= ' - Payment error' . $paymentResult;
                                }
                            }

                            $row['nextSlot'] = $result;
                        }
                    } else {
                        $row['nextSlot'] = 'Too soon to create Mission. It starts in more than ' . BusinessRules::getCronPaymentInAdvanceInDays() . ' days. Start date: ' . Calendar::convert_DBDateTime_DisplayDateTimeText($nextSlot->startDateTime) . ' Today is ' . Calendar::convert_DBDateTime_DisplayDateText(Calendar::today(Calendar::FORMAT_DBDATE));
                    }

                    $rawData[] = $row;
                }


                //create mission
            } else {

                $notTaken[] = $bk;
            }
        }

        $dataProvider = new CArrayDataProvider($rawData, array(
            'id' => 'user',
//                    'sort' => array(
//                        'attributes' => array(
//                            'id', 'username', 'email',
//                        ),
//                    ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));



        $this->render('cronJob', array('dataProvider' => $dataProvider, 'notTaken' => $notTaken));
    }

    public function actionCronEmails() {

        $success = CronJob::processEmails();

        echo 'Processed emails: ' . $success;
    }

    /**
     * Generate bookings for an existing client. start date is randomly created between given range, end date is 
     * 
     * @param type $clientId id of client
     * @param type $dateRangeLow date range low for booking start date
     * @param type $dateRangeHigh date range high for booking start date
     * @param type $numberDaysRangeLow number of days for the booking, low range
     * @param type $numberDaysRangeHigh number of days for the booking, high range
     * @param type $number number of booking to create
     */
    public function actionCreateTestBookings($clientId, $dateRangeLow, $dateRangeHigh, $numberDaysRangeLow, $numberDaysRangeHigh, $number) {

        for ($i = 0; $i < $number; $i++) {

            ClientTesting::createLiveInBookingCreate($clientId, $dateRangeLow, $dateRangeHigh, $numberDaysRangeLow, $numberDaysRangeHigh);
        }
    }

    /**
     *  Run the cron job to cancel missions that have no carers n hours before start of the mission    
     */
    public function actionCronCanceMissionsByAdmin() {

        $hours = BusinessRules::getCancelByAdminHoursBeforeMissionStart();
        echo "Missions involved are active, have no carers assigned and start in less than $hours hours <p>";
        //$compareDate = Calendar::addHours(Calendar::today(), $hours, $format);

        echo 'Today is ' . Calendar::convert_DBDateTime_DisplayDateText(Calendar::today());

        echo '<p>';

        $missions = CronJob::cancelMissionsByAdmin();

        foreach ($missions as $mission) {

            echo $mission->displayMissionShort();
            $diff = Calendar::hoursBetween_DBDateTime(Calendar::today(), $mission->start_date_time);

            $error = $mission->error_text;
            if ($error != '') {
                echo "| Error: " . $error;
            } else {
                echo " | Successful cancel";
            }

            echo " | Is mission status now CANCEL BY ADMIN? " . ($mission->status == Mission::CANCELLED_BY_ADMIN ? 'Yes' : 'No' );
            echo " | Time difference is $diff. Under $hours ? " . ($diff < $hours ? 'Yes' : 'No');
            echo " | Is a carer assigned ? " . ($mission->getAssignedCarer() == null ? 'No' : 'Yes');
            echo '<br>';
        }
    }

    public function actionCronCreditCarers() {

        Mission::creditCarers();
    }

    public function actionCancelByAdmin() {

        $mission = Mission::loadModel(248);

        $result = $mission->cancelByAdmin();

        var_dump($result);
    }

    public function actionDoNonReferencedCredit() {

        $creditCard = CreditCard::loadModel(84);
        $price = new Price(100, 'GBP');

        $payPalHandler = PayPalHandler::getInstance();
        $result = $payPalHandler->doDirectPayment($creditCard->client, $creditCard, $creditCard->address, $price);

        var_dump($result);
    }

    public function actionDBConnections() {

        Yii::app()->db;
        Yii::app()->db_photo;
        Yii::app()->db_diploma;
        Yii::app()->db_driving_licence;
        Yii::app()->db_identification;

        echo 'All databases are up';
    }

    public function actionCreateFakeClientAndMission() {

        $client = ClientTesting::createClient();

        echo 'User successfully created <br>';
        echo 'Name: ' . $client->fullName . '<br>';
        echo 'email: ' . $client->email_address . '<br>';

        $booking = ClientTesting::createLiveInBookingCreate($client->id, '2013-06-15', '2013-06-15', 12, 12);
        echo 'Booking Created with date ' . $booking->displayDates(true) . '<br>';
    }

    public function actionGetDocuments() {

        $diplomas = Document::getDocumentList(Document::TYPE_DIPLOMA);

        echo 'Diploma <br>';

        $diplomas = Util::array_put_to_position($diplomas, Yii::t('texts', 'DROPDOWN_SELECT_DOCUMENT'), 0);
        echo CHtml::dropDownList('diplomas', null, $diplomas, array('class' => 'rc-drop'));

        echo '<br>';

        $crb = Document::getDocumentList(Document::TYPE_CRIMINAL);
        $crb = Util::array_put_to_position($crb, Yii::t('texts', 'DROPDOWN_SELECT_DOCUMENT'), 0);

        echo 'CRB <br>';
        echo CHtml::dropDownList('crb', null, $crb, array('class' => 'rc-drop'));
    }

    public function actionCardEncryptionTest() {

        $card = new CreditCard();

        $card->card_number = '4044335369371653';
        $card->last_three_digits = '053';
        $card->card_type = CreditCard::TYPE_MASTER_CARD;
        $card->expiry_date = '2015-12-31';
        $card->name_on_card = 'Test User';
        $card->id_client = 407;
        $card->id_address = 320;

        //card validated
        $valid = $card->validate();

        if ($valid) {
            echo 'Card Valid <br>';
            $card->save();
            $id = $card->id;
        } else {
            echo 'Card Inalid <br>';
            die();
        }

        $storedCard = CreditCard::loadModel($id);

        $tempCard = $storedCard->getDecryptedTemporaryInstance();

        echo 'Decrypted <br>';
        echo $tempCard->card_number . '<br>';
        echo $tempCard->last_three_digits . '<br>';
        echo $tempCard->expiry_date . '<br>';
    }

    public function actionEncryptTest() {
//        $key = 'password to (en/de)crypt';
        $string = '4044335369371653';
//
//        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
//        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

        $encrypted = Encryption::encrypt($string);

        $decrypted = Encryption::decrypt($encrypted);

        var_dump($encrypted);
        var_dump($decrypted);
    }

    public function actionCreateHourlyBookingAndMissions() {

        ini_set('max_execution_time', 300);

        ClientTesting::createHourlyBookingAndMissions();
    }

    public function actionCleanBookingsMissions() {

        $obj = new ExecuteSQLScript();

        $filePath = Yii::getPathOfAlias('webroot.db_scripts.maintenance') . '/cleanBookingsMissions.sql';
        echo $obj->executeFile($filePath);
    }

    public function actionTestSplitHourly() {

        $results = Calendar::splitBookingHourly('2013-06-01', '2013-07-30');

        foreach ($results as $result) {

            echo $result['startDay'] . ' to ' . $result['endDay'] . '<br>';
        }
    }

    public function actionTestNextHourlySlot() {

        $booking = Booking::loadModel(272);

        $slot = $booking->getNextMissionSlot();

//        for ($i = 0; $i < count($slot); $i++) {
//            $slt = $slot[$i];
//            echo $slt->startDateTime . ' ' . $slt->endDateTime . ' ' . $slt->duration . ' ' . $slt->toPay->text .'<br>';
//        }
        foreach($slot as $slt){
            echo $slt->startDateTime . ' ' . $slt->endDateTime . ' ' . $slt->duration . ' ' . $slt->toPay->text .'<br>';
        }
        
    }

}

