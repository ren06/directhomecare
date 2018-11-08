<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClientTesting
 *
 * @author Renaud
 */
class ClientTesting {

    //put your code here

    public static function createClient() {


        $dbTransaction = Yii::app()->db->beginTransaction();

        try {

            $client = new Client(Client::SCENARIO_CREATE_CLIENT);

            $client->first_name = Random::getRandomClientFirstName();
            $client->last_name = Random::getRandomClientLastName();


            $client->email_address = Random::getRandomEmail($client->first_name, $client->last_name);
            $client->password = 'test';
            $client->repeat_password = 'test';
            $client->test = true;
            $client->wizard_completed = Wizard::CLIENT_LAST_STEP_INDEX;

            $client->terms_conditions = '1';

            $client->save();

            $servicLocation = new Address();

            $servicLocation->address_line_1 = Random::getRandomAddressLine1();
            $servicLocation->address_line_2 = Random::getRandomAddressLine2();
            $servicLocation->city = 'London';
            $servicLocation->post_code = Random::getRandomUKPostCode();
            $servicLocation->data_type = Address::TYPE_SERVICE_LOCATION_MASTER_DATA;

            if (!$servicLocation->save()) {
                $servicLocation->validate();
                $errors = $servicLocation->getErrors();
                $postCode = $servicLocation->post_code;
                throw new CException('error saving location: ' . $errors);
            }

            //Create association
            $clientLocationAddress = new ClientLocationAddress();
            $clientLocationAddress->id_client = $client->id;
            $clientLocationAddress->id_address = $servicLocation->id;

            $clientLocationAddress->save(false);

            //service users
            $serviceUser = new ServiceUser();

            $serviceUser->gender = rand(Constants::GENDER_FEMALE, Constants::GENDER_MALE);

            $serviceUser->first_name = Random::getRandomServiceUserFirstName($serviceUser->gender);
            $serviceUser->last_name = Random::getRandomServiceUserLastName($serviceUser->gender);

            $dateBirth = Random::getRandomDateBirth(1900, 1940);

            $serviceUser->date_birth = $dateBirth;
            $objectCondition = new ServiceUserCondition();
            $objectCondition->id_service_user = $serviceUser->id;
            $objectCondition->id_condition = 18;

            $conditions[18] = $objectCondition;

            $objectCondition = new ServiceUserCondition();
            $objectCondition->id_service_user = $serviceUser->id;
            $objectCondition->id_condition = 17;

            $conditions[17] = $objectCondition;

            $objectCondition = new ServiceUserCondition();
            $objectCondition->id_service_user = $serviceUser->id;
            $objectCondition->id_condition = 21;

            $conditions[21] = $objectCondition;

            $objectCondition = new ServiceUserCondition();
            $objectCondition->id_service_user = $serviceUser->id;
            $objectCondition->id_condition = 20;

            $conditions[20] = $objectCondition;

            //$serviceUser->serviceUserConditions = $conditions;

            if (!$serviceUser->save()) {
                $serviceUser->validate();
                $errors = $serviceUser->getErrors();

                throw new CException('error saving service user: ' . $errors);
            }

            $serviceUserClient = new ClientServiceUser();
            $serviceUserClient->id_client = $client->id;
            $serviceUserClient->id_service_user = $serviceUser->id;
            $serviceUserClient->save();

            Condition::saveServiceUserConditions($serviceUser, $conditions);


            //credit card

            $creditCard = new CreditCard();
            $creditCard->card_number = '4044335369371653'; //Random::getRandomNumber(16);
            $creditCard->card_type = CreditCard::TYPE_MASTERCARD_CREDIT;
            $creditCard->last_three_digits = Random::getRandomNumber(3);
            $creditCard->expiry_date = '2015-12-31';
            $creditCard->name_on_card = $client->fullName;
            $creditCard->id_client = $client->id;
            $creditCard->id_address = $servicLocation->id;
            $creditCard->save();

            $dbTransaction->commit();

            return $client;
        } catch (CException $e) {

            $dbTransaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    //randomly create n booking live in
    public static function createLiveInBookingCreate($clientId, $dateRangeLow, $dateRangeHigh, $numberDaysRangeLow, $numberDaysRangeHigh) {

        $booking = new BookingLiveIn();

        $booking->recurring = 0;

        $date1 = Random::getRamdomDate($dateRangeLow, $dateRangeHigh);

        $numberDays = mt_rand($numberDaysRangeLow, $numberDaysRangeHigh); //booking length

        $date2 = Calendar::addDays($date1, $numberDays, Calendar::FORMAT_DBDATETIME);

        $booking->start_date_time = $date1;
        $booking->end_date_time = $date2;

        $client = Client::loadModel($clientId);

        $serviceUserIds = $client->getServiceUsersIds();

        $serviceLocationIds = $client->getLocationsIds();
        $serviceLocationId = $serviceLocationIds[0];

        $creditCards = $client->creditCards;
        $creditCard = $creditCards[0];
        $billingAddress = Address::loadModel($creditCard->id_address);

        BookingHandler::handleBooking($booking, $clientId, $serviceUserIds, $serviceLocationId, $billingAddress, $creditCard, null, null); //Paypal tranaction id

        return $booking;
    }

    public static function createHourlyQuote() {

        //create form data
        $startDate = '2013-11-15';
        $endDate = '2013-11-31'; //put = null for recurring

       // echo "Entered Booking dates: $startDate to $endDate <br>";

        $daysForms = array();

        for ($i = 0; $i < 7; $i++) {

            $dayForm = new DayForm();
            $date = Calendar::addDays($startDate, $i, Calendar::FORMAT_DBDATE);
            $dayForm->date = $date;
            $dayForm->startHour = '08';
            $dayForm->startMinute = Random::getRamdomMinutes();
            $dayForm->endHour = '14';
            $dayForm->endMinute = Random::getRamdomMinutes();
            $dayForm->selected = Random::getRandomBoolean();
            $dayForm->dayWeek = Calendar::getDayOfWeekNumber($date);

            $daysForms[] = $dayForm;
        }

        //create booking
        $booking = new BookingHourly();
        $booking->start_date_time = $startDate;
        $booking->end_date_time = $endDate;

        if ($endDate == null) {
            $booking->recurring = 1;
        }

        $booking->setDayForms($daysForms);

        return $booking;
    }

    public static function createHourlyBookingAndMissions($clientId, $startDate, $endDate) {

           
        $result = "Entered Booking dates: $startDate to $endDate <br>";

        $daysForms = array();
        
        $maxDays = Calendar::numberDays_DBDate($startDate, $endDate);

        for ($i = 0; $i < $maxDays; $i++) {

            $dayForm = new DayForm();
            $date = Calendar::addDays($startDate, $i, Calendar::FORMAT_DBDATE);
            $dayForm->date = $date;
            $dayForm->startHour = '08';
            $dayForm->startMinute = Random::getRamdomMinutes();
            $dayForm->endHour = '14';
            $dayForm->endMinute = Random::getRamdomMinutes();
            $dayForm->selected = Random::getRandomBoolean();
            $dayForm->dayWeek = Calendar::getDayOfWeekNumber($date);

            $daysForms[] = $dayForm;
        }

        //create booking
        $booking = new BookingHourly();
        $booking->start_date_time = $startDate;
        $booking->end_date_time = $endDate;

        if ($endDate == null) {
            $booking->recurring = 1;
        }

        $booking->setDayForms($daysForms);

        //client data
        $client = Client::loadModel($clientId);

        $serviceUserIds = $client->getServiceUsersIds();

        $serviceLocationIds = $client->getLocationsIds();
        $serviceLocationId = $serviceLocationIds[0];

        $creditCards = $client->creditCards;
        $creditCard = $creditCards[0];
        $billingAddress = Address::loadModel($creditCard->id_address);

        $bookingId = BookingHandler::handleBooking($booking, $clientId, $serviceUserIds, $serviceLocationId, $billingAddress, $creditCard, null, null);

        $bookingCreated = Booking::loadModel($bookingId);

        $result .= "Booking Created with dates: $bookingCreated->start_date to $bookingCreated->end_date <br>";
        $result .= 'Missions Created: <br>';

        $missionPayments = $bookingCreated->missionPayments;

        $missionPayment = $missionPayments[0];

        $missions = $missionPayment->missions;

        foreach ($missions as $mission) {
            $result .= $mission->displayMissionShort() . '<br>';
        }

        $result .= 'Price: ' . $missionPayment->getTotalPrice(Constants::USER_CLIENT)->text;
        
        return $result;
    }

    public static function createLiveInBookingAndMission($clientId = 432) {

        $booking = new BookingLiveIn();

        $booking->recurring = 0;

        $dateRangeLow = '2013-08-14';
        $dateRangeHigh = '2013-08-19';

        $date1 = Random::getRamdomDate($dateRangeLow, $dateRangeHigh);

        $numberDays = mt_rand(10, 30); //booking length

        $date2 = Calendar::addDays($date1, $numberDays, Calendar::FORMAT_DBDATETIME);

        $booking->start_date_time = $date1;
        $booking->end_date_time = $date2;

        $client = Client::loadModel($clientId);

        $serviceUserIds = $client->getServiceUsersIds();

        $serviceLocationIds = $client->getLocationsIds();
        $serviceLocationId = $serviceLocationIds[0];

        $creditCards = $client->creditCards;
        $creditCard = $creditCards[0];
        $billingAddress = Address::loadModel($creditCard->id_address);

        BookingHandler::handleBooking($booking, $clientId, $serviceUserIds, $serviceLocationId, $billingAddress, $creditCard, null, null); //Paypal tranaction id

        echo 'Booking created from ' . $date1 . ' to ' . $date2;

        return $booking;
    }

    public static function doDirectPaymentBorgun() {

        $client = Client::loadModel(407);

        $creditCards = $client->creditCards;
        $creditCard = $creditCards[0];

        $price = new Price(200, 'GBP');
        
        $paymentHandler = BorgunHandler::getInstance();
        $success = $paymentHandler->doDirectPayment(null, $creditCard, null, $price);
        echo $success . '<br>';
        echo 'transaction id: ' . $paymentHandler->getTransactionId();

        $response = $paymentHandler->getResponse();
        echo '<br>Response: ';
        echo print_r($response);
        $paymentSuccessful = $paymentHandler->isPaymentSuccessful();
        echo '<br>Payment ' . ($paymentSuccessful == true ? 'Successful' : 'Error');

        if (!$paymentSuccessful) {
            echo '<br>Code' . $paymentHandler->getTransactionCode();
            echo '<br>Message' . $paymentHandler->getPaymentMessage();
        }
    }

}

?>
