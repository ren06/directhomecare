<?php

/**
 * Contains all logic that can be called by a cron Job
 * 
 * Each function here should have one Command class (to be called by Linux cron)
 * and possibly called by UnitTest actions
 * 
 */
class CronJob {

    /**
     * Take money from client and create missions
     * 
     * @return array containing raw data of processed missions
     */
    public static function createPaymentsAndMissions() {

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

        //not taken
        echo 'Not taken <p><p>';

        foreach ($notTaken as $not) {

            echo $not->id . ' - ' . $not->displayDates();
            echo '<p>';
        }

        return $rawData;
    }

    /**
     * Credit the carers
     */
    public static function creditCarers() {

        Mission::creditCarers();
    }

    /**
     * 
     * Cancel missions that have no carers n hours before the start of the mission
     * 
     * @return type
     */
    public static function cancelMissionsByAdmin() {

        $missions = Mission::getMissionsCancellableByAdmin();

        foreach ($missions as $mission) {

            $mission->cancelByAdmin();
        }

        return $missions;
    }

    public static function processNewsletter() {
        
    }

    /**
     * 
     * Send emails according to the Email table
     * 
     * @return int
     */
    public static function processEmails() {

        $sql = "SELECT * FROM tbl_emails WHERE status = 0 LIMIT 1 ";

        $results = Yii::app()->db->createCommand($sql)->queryAll();

        $success = 0;

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        while (count($results) == 1) {

            $result = $results[0];

            $id = $result['id'];
            $html = $result['body'];
            $to = $result['recipient'];
            $subject = $result['subject'];
            $from = $result['sender'];
            $bcc = $result['bcc'];

            $response = Emails::sendMail($html, $subject, $to, $from, $bcc, false);

            if ($response[0]->status != 'sent') {
                $status = 2; //error
                Yii::log('Email status: ' . print_r($response, true), CLogger::LEVEL_ERROR, 'error_email');
            } else {
                $status = 1; //success
            }

            $transaction = Yii::app()->db->beginTransaction();
            $sql = "UPDATE tbl_emails SET status=$status, sent='$today' WHERE id=$id";
            Yii::app()->db->createCommand($sql)->execute();
            $transaction->commit();

            if ($status == 1) {
                echo "Email sent to $to </br>";
            } else {
                echo "Error with mail $to </br>";
            }

            $sql = "SELECT * FROM tbl_emails WHERE status = 0 LIMIT 1 ";

            $results = Yii::app()->db->createCommand($sql)->queryAll();


            $success++;
        }

        return $success;
    }

    public static function processCarerNewsletter($consoleCommand) {

        //get all carers who are not active
        $carers = Carer::model()->findAll('email_address IS NOT NULL AND no_newsletter = 0 AND deactivated = 0 AND active = 0');
        $response = "Carers: <br /><br />";

        foreach ($carers as $carer) {

            $emailAddress = $carer->email_address;

            $missingDocuments = $carer->getMissingDocuments();

            //$emailAddress = 'renaud.theuillon@googlemail.com';
            //check whitch document is missing
            EmailsNewsletters::carer_CompleteYourProfile($emailAddress, $carer->first_name, $missingDocuments, $consoleCommand);

            $response .= $emailAddress . ', missing documents: ' . print_r($missingDocuments, true) . ' <br />';
        }

        $response .= "<br />Finished. " . count($carers) . " emails sent.";
        echo $response;
    }

    public static function processClientNewsletter($consoleCommand) {

        //get all clients with an email address

        $randomCarerNumber = 3;

        $clients = Client::model()->findAll('email_address IS NOT NULL AND no_newsletter = 0 ');
        $response = "Clients: <br /><br />";

        //do clients
        foreach ($clients as $client) {

            $postCode = "";
            $email = $client->email_address;
            $selectedCarers = array();

            $clientLocations = $client->clientLocations;

            if (isset($clientLocations) && count($clientLocations) > 0) {

                $address = $clientLocations[0];
                if (isset($address->post_code)) {
                    $postCode = $address->post_code;
                }
            } else {
                $criteria = ClientCarerSearchCriteria::get($client->id, null);
                if (isset($criteria['postCode'])) {
                    $postCode = $criteria['postCode'];
                }
            }

            if (isset($postCode) && Maps::isValidPostCode($postCode)) {

                $carers = Carer::getCarersFromPostCode($postCode, Constants::HOURLY, null);

                if (count($carers) == 0) {
                    $selectedCarers = Carer::getRandomActiveCarers($randomCarerNumber);
                    $postCodeUsed = false;
                } else {

                    shuffle($carers);

                    if (count($carers) < $randomCarerNumber) {
                        $randomCarerNumber = count($carers);
                    }

                    $i = 0;
                    for ($i = 0; $i < $randomCarerNumber; $i++) {

                        $selectedCarers[] = $carers[$i];
                    }

                    $postCodeUsed = true;
                }
            } else {
                $selectedCarers = Carer::getRandomActiveCarers($randomCarerNumber);
                $postCodeUsed = false;
            }

            //$email = 'renaud.theuillon@googlemail.com';

            EmailsNewsletters::client_SomeCarersNearYou($email, $selectedCarers, $consoleCommand);

            $response .= $client->email_address . ', carers close to postcode  ' . $postCodeUsed . ", post code $postCode <br />";
        }

        //do prospects, only pickup those who have no step 2 email address (those who are registered clients)
        $response .= "<br />Prospects: <br /><br />";
        $prospects = ClientProspect::model()->findAll('email_address_step2 IS NULL AND no_newsletter = 0 ');

        foreach ($prospects as $prospect) {

            $postCode = "";
            $selectedCarers = array();
            $email = $prospect->email_address_step1;

            $criteria = ClientCarerSearchCriteria::get(null, $prospect->sessionID);

            if (isset($criteria['postCode'])) {

                $postCode = $criteria['postCode'];
                $carers = Carer::getCarersFromPostCode($postCode, Constants::HOURLY, null);

                if (count($carers) == 0) {
                    $selectedCarers = Carer::getRandomActiveCarers($randomCarerNumber);
                    $postCodeUsed = false;
                } else {

                    shuffle($carers);

                    if (count($carers) < $randomCarerNumber) {
                        $selectedCarersNumber = count($carers);
                    } else {
                        $selectedCarersNumber = $randomCarerNumber;
                    }

                    for ($i = 0; $i < $selectedCarersNumber; $i++) {

                        $selectedCarers[] = $carers[$i];
                    }

                    $postCodeUsed = true;
                }
            } else {
                $selectedCarers = Carer::getRandomActiveCarers($randomCarerNumber);
                $postCodeUsed = false;
            }

            // $email = 'renaud.theuillon@googlemail.com';
            EmailsNewsletters::client_SomeCarersNearYou($email, $selectedCarers, $consoleCommand);
            $response .= $prospect->email_address_step1 . ', carers close to postcode  ' . $postCodeUsed . ", post code $postCode <br />";
        }

        $response .= "<br />Finished";
        echo $response;
    }

    public static function createCarersWithdrawals() {

        //get all carers who have a positive balance
        $sql = "SELECT * FROM tbl_carer_transaction WHERE id 
            IN( select MAX(id) FROM tbl_carer_transaction GROUP BY id_carer) 
            AND credit_balance > 0 ";

        $results = Yii::app()->db->createCommand($sql)->queryAll();

        $emailText = "";

        //for each found result create a withdrawal
        foreach ($results as $result) {

            $carerId = $result['id_carer'];
            $amount = $result['credit_balance'];

            CarerTransaction::createWithdraw($carerId, $amount);

            $carer = Carer::loadModel($carerId);

            $sortCode = $carer->sort_code;
            $accountNumber = $carer->account_number;

            if (!isset($sortCode) || $sortCode == "") {
                $sortCode = '<b>NO SORT CODE</b>';
            }
            if (!isset($accountNumber) || $accountNumber == "") {
                $accountNumber = '<b>NO ACCOUNT NUMBER</b>';
            }

            $emailText .= "Carer $carer->fullName ($carerId) must be paid for $amount GBP. Sort code: $sortCode Account no: $accountNumber Email: $carer->email_address<br/><br/>";
        }

        if ($emailText != "") {

            Emails::sendToAdmin_CarersWithdrawals($emailText);

            echo "Email sent to admin: <br/>" . $emailText;
        } else {
            echo "No carers are due payment.";
        }
    }

}

?>
