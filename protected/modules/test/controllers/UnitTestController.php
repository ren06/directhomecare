<?php

class UnitTestController extends Controller {

    public function actionTestCreditCarers() {

        Mission::creditCarers();
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

        $rawData = CronJob::createPaymentsAndMissions();

        $dataProvider = new CArrayDataProvider($rawData, array(
            'id' => 'user',
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));


        $this->render('cronJob', array('dataProvider' => $dataProvider));
    }

    public function actionTestLiveInMissionSlots() {

        $startDate = $date = mktime(0, 0, 0, 12, 1, date("Y"));
        $startDate = date('d/m/Y', $startDate);
        $endDate = $date = mktime(0, 0, 0, 12, 29, date("Y"));
        $endDate = date('d/m/Y', $endDate);

        //$startDate = '2012-12-13';
        //$endDate = '2013-01-15';

        $startDate = '2012-12-13';
        $endDate = '2012-12-31';

        $result = Calendar::getLiveInMissionSlots($startDate, $endDate);


        echo $startDate . ' - ' . $endDate;
        echo '</br>';
        echo var_dump($result);
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

    /**
     * Simulate cancel by admin
     */
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

        $result = '';

        $card = new CreditCard();

        $card->card_number = '4044335369371653';
        $card->last_three_digits = '053';
        $card->card_type = CreditCard::TYPE_MASTERCARD_CREDIT;
        $card->expiry_date = '2015-12-31';
        $card->name_on_card = 'Test User';
        $card->id_client = 13;
        $card->id_address = 1;

        //card validated
        $valid = $card->validate();

        if ($valid) {
            $result .= 'Card Valid <br>';
            $card->save();
            $id = $card->id;
        } else {
            $result .= 'Card Inalid <br>';
            die();
        }

        $storedCard = CreditCard::loadModel($id);

        $tempCard = $storedCard->getDecryptedTemporaryInstance();

        $result .= 'Decrypted <br>';
        $result .= $tempCard->card_number . '<br>';
        $result .= $tempCard->last_three_digits . '<br>';
        $result .= $tempCard->expiry_date . '<br>';

        $this->renderText($result);
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

        $clientsIdName = Client::getClientsIdName();
        $clients = array();

        foreach ($clientsIdName as $client) {
            $clients[$client['id']] = $client['id'] . ' ' . $client['first_name'] . ' ' . $client['last_name'];
        }

        $clientId = $clientsIdName[0];

        $result = '';
        $startDate = '';
        $endDate = '';

        if (isset($_POST['clientId']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {

            $clientId = $_POST['clientId'];
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];

            $result = ClientTesting::createHourlyBookingAndMissions($clientId, Calendar::convert_DisplayDate_DBDate($startDate), Calendar::convert_DisplayDate_DBDate($endDate));
        }

        $this->render('createHourlyBooking', array('clients' => $clients, 'clientId' => $clientId, 'startDate' => $startDate, 'endDate' => $endDate, 'result' => $result));
    }

    public function actionCreateLiveInBookingAndMission() {

        ini_set('max_execution_time', 300);

        ClientTesting::createLiveInBookingAndMission();
    }

    public function actionCleanBookingsMissions() {

        if (false) {
            $obj = new ExecuteSQLScript();

            $filePath = Yii::getPathOfAlias('webroot.db_scripts.maintenance') . '/cleanBookingsMissions.sql';
            echo $obj->executeFile($filePath);
            echo '<br>Finished';
        }
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
        foreach ($slot as $slt) {
            echo $slt->startDateTime . ' ' . $slt->endDateTime . ' ' . $slt->duration . ' ' . $slt->toPay->text . '<br>';
        }
    }

    public function actionTestDoDirectPaymentBorgun() {

        ClientTesting::doDirectPaymentBorgun();
    }

    public function actionCurlIP() {

        $ch = curl_init('http://www.whatismyip.org/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $myIp = curl_exec($ch);
        echo $myIp;
    }

    public function actionTestPHPExtensions() {

        echo 'cURL: ';
        echo (function_exists('curl_version') ? 'Activated' : 'Not activated');
        echo '<br>';
    }

    public function actionTestPostCodesDistance() {

        $postCode1 = 'W10 5UB';
        //$postCode2 = 'W1B 5AG';
        //$postCode2 = 'SW6 4EA';
        //$postCode2 = 'W10 5SY';
        $postCode2 = 'TW14 8HD';

        $distance = Maps::getDistance($postCode1, $postCode2);

        echo "distance between $postCode1 and $postCode2 is $distance miles";
        //echo $result;
    }

    public function actionTestCarerPostCode() {

        $clientPostCode = 'W10 5UB';

        $result = Maps::getPostCodeData($clientPostCode);

        $sql = "SELECT * FROM tbl_carer WHERE active = 1"; //has an address by definition
        $carers = Carer::model()->findAllBySql($sql);

        $matchingCarerIds = array();
        foreach ($carers as $carer) {

            $address = $carer->address;
            $latitudeFrom = $address->longitude;
            $longitudeFrom = $address->latitude;

            $radius = $carer->hourly_work_radius;

            $distance = Maps::getGreatCircleDistance($latitudeFrom, $longitudeFrom, $result['latitude'], $result['longitude']);

            if ($distance <= $radius) {
                $matchingCarerIds [] = $carer->id;
            }
        }

        $carers = Carer::loadModels($matchingCarerIds);
    }

    public function actionTestCarerPostCodeFilter($postCode) {

        if (isset($postCode)) {

            //$postCode = 'W10 5UB';
            $carers = Carer::getCarersFromPostCode($postCode, Constants::HOURLY);
            $text = "Entered postcode $postCode <br> Carers found<br><br>";

            $result = Maps::getPostCodeData($postCode);

            foreach ($carers as $carer) {
                $address = $carer->address;
                $carerPostCode = $address->post_code;
                $radius = $carer->hourly_work_radius;
                //$distance = Maps::getDistance($postCode, $carerPostCode);

                $distance = Maps::getGreatCircleDistance($address->latitude, $address->longitude, $result['latitude'], $result['longitude']);

                $photo = $carer->getPhotoForClient();
                if (isset($photo)) {
                    $text .= $photo->showImageForClient('rc-image-profile-small');
                }

                $distanceRounded = round($distance, 2);
                $text .= '<b>' . $carer->fullName . '</b>';
                $valid = $distance <= $radius;
                $res = ($valid ? 'true' : 'false');
                $text .= ": post code <b> $carerPostCode </b> - work radius <b>$radius miles</b> - distance <b>$distanceRounded  miles </b> - valid: <b>$res</b><br>";
            }

            $this->renderText($text);
        }
    }

    public function actionTestAddressFromPostCode() {

        $postCodes = array('W10 5SY', 'W1B 5AG', 'W10 5UB', 'RG27 9JS', 'GU21 4YH', 'SW6 4EA',
            'TW14 8HD', 'M60 4EP', 'EC2R 8AH');

        foreach ($postCodes as $postCode) {
            $result = Maps::getPostCodeData($postCode);

            echo $postCode . ' ' . $result['city'] . '<br>';
        }
    }

    public function actionTestValidPostCode() {

        $postCodes = array('W10 5SY', 'W1B 5AG', 'W99 5UB', 'RG00 9JS', 'GU21 4YH', 'SZ6 4EA',
            'TW14 8HD', 'M60 4ZZ', 'EC2Z 8AH');

        foreach ($postCodes as $postCode) {
            $valid = Maps::isValidPostCode($postCode);

            echo $postCode . ' valid: ' . ($valid ? "true" : "false") . '<br>';
        }
    }

    public function actionTestPotentialCarersEmails($postCode) {

        $booking = new Booking();
        $booking->type = Constants::HOURLY;

        $res = Maps::getPostCodeData($postCode);
        $city = $res['city'];

        $carers = Carer::getPotentialCarersForMission($postCode, $booking, null);

        Emails::sendToCarers_Potential_NewJob($carers, $postCode, $city);

        echo 'finished';
    }

    public function actionTestGoogleMaps($postCode) {

        $this->render('testGoogleMaps', array('postCode' => $postCode));
    }
    
    public function actionCronCreateCarersWithdrawals(){
        
        CronJob::createCarersWithdrawals();
        
    }

}

