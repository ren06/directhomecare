<?php

class Emails {

    private static $API_KEY = 'a7QUxQoWhNUbmXsRORmZsA';

    private static function getAdminEmail() {

        return Yii::app()->params['emails']['adminEmail'];
    }

    private static function getAdminEmailBcc() {

        return Yii::app()->params['emails']['adminEmailBcc'];
    }

    private static function getShiftAdminEmails() {

        return Yii::app()->params['emails']['shiftAdminEmail'];
    }

//    private static function getAdminName() {
//        return Yii::app()->params['emails']['adminName'];
//    }

    /**
     * 
     * @param type $html
     * @param type $subject
     * @param type $to
     * @param type $from
     * @param type $bcc
     * @param boolean $useCron
     * 
     * @return boolean return false if error, an array if successful
     */
    public static function sendMail($html, $subject, $to, $from = null, $bcc = null, $useCron = false) {

        if (is_array($to)) { //send to lots of users
            $recipient = $to;
            $preserve = false;
        } else {
            $recipient = array(array("email" => $to));
            $preserve = true;
        }

        $sendMail = Yii::app()->params['emails']['emailsOn'];

        //config overwrites the logic
        if (Yii::app()->params['emails']['emailCron'] == false) {
            $useCron = false;
        }

        // $useCron = true;
        //$testMode = false;

        if ($sendMail) {

            $testMode = Yii::app()->params['emails']['emailTestOn'] == true;

            if ($useCron) {

                //Test mode
                if ($testMode) {

                    $recipient = Yii::app()->params['emails']['emailTestReceiver'];
                } else {

                    if (is_array($to)) {
                        $recipient = $to[0]['email'];
                    } else {
                        $recipient = $to;
                    }
                }
                //$recipient = 'rt@directhomecare.com';
                //tracing what's being sent
                self::logEmail($sendMail, $testMode, $subject, $to, $recipient, $from, $useCron);
                return self::storeEmail($html, $subject, $recipient, $from, $bcc);
            } else {
                //Test mode
                if ($testMode) {
                    if (is_array($to)) {
                        foreach ($to as &$t) {
                            $t['email'] = Yii::app()->params['emails']['emailTestReceiver'];
                        }
                        $recipient = $to;
                    } else {
                        $recipient = array(array("email" => Yii::app()->params['emails']['emailTestReceiver']));
                    }
                }

                $args = array(
                    'key' => self::$API_KEY,
                    'message' => array(
                        "html" => $html,
                        "text" => null,
                        "from_email" => "admin@directhomecare.com",
                        "from_name" => "Direct Homecare",
                        "subject" => $subject,
                        "to" => $recipient,
                        "track_opens" => true,
                        "track_clicks" => true,
                        "preserve_recipients" => $preserve,
                        "auto_text" => true
                    )
                );

                if ($from != null) {

                    $args['message']['headers'] = array('Reply-To' => $from);
                }

                if ($bcc != null) {

                    $args['message']['bcc_address'] = $bcc;
                }

                // Open a curl session for making the call
                $curl = curl_init('https://mandrillapp.com/api/1.0/messages/send.json');

                // Tell curl to use HTTP POST
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                // Tell curl not to return headers, but do return the response
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $args2 = json_encode($args);

                // Set the POST arguments to pass on
                curl_setopt($curl, CURLOPT_POSTFIELDS, $args2);

                // Make the REST call, returning the result
                $response = curl_exec($curl);
                $responseDecoded = json_decode($response);
                if (isset($responseDecoded)) {

                    if (!empty($responseDecoded)) {//happens for some reason
                        //make sure the email was successfuly sent
                        if (($responseDecoded[0]->status != 'sent') && ($responseDecoded[0]->status != 'queued')) {

                            Yii::log('Email not sent: ' . print_r($responseDecoded, true), CLogger::LEVEL_ERROR, 'email');
                            return false;
                        } else {
                            //tracing what's being sent
                            self::logEmail($sendMail, $testMode, $subject, $to, $recipient, $from, $useCron);
                            return $responseDecoded;
                        }
                    } else {
                        Yii::log('Response decoded set but emtpy: ', CLogger::LEVEL_WARNING, 'email');
                        return false;
                    }
                } else {
                    Yii::log('Response decoded not set', CLogger::LEVEL_WARNING, 'email');
                    return false;
                }
            }
        }

        //tracing what's being sent
        self::logEmail($sendMail, $testMode, $subject, $to, $recipient, $from, $useCron);
    }

    public static function logEmail($sendMail, $testMode, $subject, $to, $recipient, $from, $useCron) {

        if (is_array($to)) {
            $to = print_r($to, true);
        }
        if (is_array($recipient)) {
            if (is_array($recipient[0])) {
                $recipient = $recipient[0]['email'];
            }
        }

        if ($from == null) {
            $from = 'None';
        }

        ($sendMail == 1 ? $sendMail = 'true' : $sendMail = 'false');
        ($testMode == 1 ? $testMode = 'true' : $testMode = 'false');
        ($useCron == 1 ? $useCron = 'true' : $useCron = 'false');

        Yii::log("[Sent] $sendMail [Test] $testMode [Subject] $subject [To] $to [ActualRecipient] $recipient [From] $from [CronUsed] $useCron ", CLogger::LEVEL_INFO, 'email');
    }

    public static function storeEmail($html, $subject, $to, $from = null, $bcc = null) {

        $created = Calendar::today(Calendar::FORMAT_DBDATETIME);
//        $html = 'test';
//
//        if ($from == null) {
//            $from = 'NULL';
//        } else {
//            $from = "'" . $from . "'";
//        }
//
//
//        if ($bcc == null) {
//            $bcc = 'NULL';
//        } else {
//            $bcc = "'" . $bcc . "'";
//        }
        //$html = mysql_real_escape_string($html);

        $html = mysql_escape_string($html);

        //$html = Yii::app()->db->quoteValue($html);

        $sql = "INSERT INTO tbl_emails (recipient, subject, body, sender, bcc, created) VALUES ('$to', '$subject', '$html', '$from', '$bcc', '$created'); ";

        return Yii::app()->db->createCommand($sql)->execute();
    }

    // ******* EMAILS TO CLIENT *******
    // -1- Send email to Client when sign up. -- USED BY NEW DH
    public static function sendToClient_RegistrationConfirmation($client) {
        $firstName = $client->first_name; //Yii::t('emails', 'EMAIL_CLIENT'); //$client->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_WELCOME_TO_DIRECT_HOMECARE');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_YOU_HAVE_MADE_THE_RIGHT_CHOICE');

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('site', 'home', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_MAKE_A_NEW_BOOKING');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to, null, null, true);
    }

    //-- USED BY NEW DH
    public static function sendToClient_JobPostingConfirmation($job) {

        $client = $job->client;

        $firstName = $client->first_name; //Yii::t('emails', 'EMAIL_CLIENT'); //$client->first_name;
        $emailTitle = Yii::t('emails', 'Job Posting Confirmation');
        $emailContent = Yii::t('emails', 'You have posted a new Job. The Carers who are interested will get back to you soon.');

        $emailContent .= '<br><br>';
        $emailContent .= $job->displayJobEmail();

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('client', 'myCarers', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'VIEW CARERS REPLIES');

        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to, null, null, true);
    }

    //-- USED BY NEW DH
    public static function sendToCarer_NewClientMessage($client, $carer, $message, $conversationId) {

        $firstName = $carer->first_name;
        $emailTitle = Yii::t('emails', 'New Message');
        $emailContent = "$client->first_name sent you a message: <br><br> $message";

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('carer', "conversation", $email, "id---$conversationId");

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'REPLY TO MESSAGE');

        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to, null, null, true);
    }

    //-- USED BY NEW DH
    public static function sendToClient_NewCarerMessage($client, $carer, $message, $conversationId) {

        $firstName = $client->first_name;
        $emailTitle = Yii::t('emails', 'New Message');
        $emailContent = "$carer->first_name sent you a message: <br><br> $message";

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('client', "conversation", $email, "id---$conversationId");

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'REPLY TO MESSAGE');

        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to, null, null, true);
    }

    // -2- Send email to Client when payment made (wzd1 or wz2). OKOKOK
    public static function sendToClient_BookingConfirmation($client) {
        $firstName = $client->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_ORDER_CONFIRMATION');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_YOUR_ORDER_HAS_BEEN_PLACED');

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('clientManageBookings', 'myBookings', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_VIEW_PAYMENT');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -3- Send email to Client when payment made (by cron-job). OKOK (cannot test email pass becaus ecron not working)
    public static function sendToClient_NewPayment($client) {
        $firstName = $client->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_ORDER_CONFIRMATION');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_YOUR_ORDER_HAS_BEEN_PLACED');

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('clientManageBookings', 'myBookings', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_VIEW_PAYMENT');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -4- Send email to Client when cron-job error. (Acquirer repplied problem on card) (cannot test email pass becaus ecron not working)
    public static function sendToClient_NewPayment_Error($client) {
        $firstName = $client->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_ERROR_WITH_YOUR_ORDER');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_YOUR_ORDER_COULD_NOT_BE_PROCESSED');

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('clientManageBookings', 'myBookings', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_CHECK_CARD_DETAILS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -5- Send email to Client when card will expire 2 weeks/1 week/3 days/1 day/followed by cron-job error). (cannot test email pass becaus ecron not working)
    public static function sendToClient_CreditCardExpiry($client) {
        $firstName = $client->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_YOUR_CARD_WILL_EXPIRE');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_THE_CARD_USED_TO_PAY_FOR_HOME_CARE_BOOKINGS_WILL_EXPIRE_SOON');

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('clientManageBookings', 'myBookings', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_UPDATE_CARD_DETAIS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -6- Send email to Client when a visit is cancelled by Admin. OKOK (cannot test email pass becaus ecron not working)
    public static function sendToClient_MissionCancelledAndReimbursed($client, $mission) {
        $firstName = $client->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_VISIT_CANCELLED');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_ONE_OF_THE_VISIT_YOU_ODERED_WAS_CANCELLED');

        $emailContent .= '<br><br> Visit:' . $mission->displayDateTimes();

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('clientManageBookings', 'myBookings', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_VIEW_VISITS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -7- Send email to Client when a Booking was refund
    public static function sendToClient_BookingCancelledAndReimbursed($client) {
        $firstName = $client->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_BOOKING_CANCELLED');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_BOOKING_CANCELLED');

        $email = urlencode($client->email_address);
        $urlParam = self::constructURL('clientManageBookings', 'myBookings', $email);

        $emailButtonLink = ''; //Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = ''; //Yii::t('emails', 'EMAIL_BUTTON_VIEW_VISITS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $client->email_address;
        return self::sendMail($html, $subject, $to);
    }

    //     ******* EMAILS TO CARER *******
    // -1- Send email to Carer when sign up. OKOKOK
    public static function sendToCarer_SignUp($carer) {
        $firstName = Yii::t('emails', 'EMAIL_CARER'); // byRC: no name of Carer $carer->first_name
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_WELCOME_TO_DIRECT_HOMECARE');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_CONGRATULATIONS_FOR_REGISTERING');

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'index', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;

        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_CHOOSE_MISSION');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $carer->email_address;

        return self::sendMail($html, $subject, $to, null, null, true);
    }

    // -2- Send email to Carer when Admin has selected a carer for a mission he had applied to. OKOKOK
    public static function sendToCarer_SelectedForMission($carer) {
        $firstName = $carer->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_YOU_WERE_SELECTED_FOR_A_MISSION');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_PLEASE_CONFIRM_THE_MISSION');

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'myMissions', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_GO_TO_CURRENT_MISSIONS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $carer->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -3- Send email to Carer when Carer has confirmed a mission (to sensibilise her). OKOKOK
    public static function sendToCarer_ConfirmedForMission($carer) {
        $firstName = $carer->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_MISSION_CONFIRMED');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_IT_IS_HIGHLY_IMPORTANT_THAT_YOU_ATTEND');

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'myMissions', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_GO_TO_MISSION_DETAILS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $carer->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -4- Send email to Carer 2 days before start of assigned-mission.
    public static function sendToCarer_MissionReminder($carer) {
        $firstName = $carer->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_DONT_FORGET_YOUR_MISSION');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_YOU_HAVE_COMITTED_TO_ATTEND_TO_A_MISSION');

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'myMissions', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_VIEW_MISSION_DETAILS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $carer->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -5 a- Send to ALL Carers when a new mission is available. (latter we will send to carers who match mission only or to pre-selected carers only) OKOK (No email passed with URL because same email for all; also we will update this functino with pre-selection and therefore will do unique emails with email passed)
    public static function sendToCarer_All_NewJob($exceptionList = null) {

        $criteria = new CDbCriteria();

        if (isset($exceptionList)) {

            $carerIds = implode(',', $exceptionList);

            $criteria->addCondition("id NOT IN ($carerIds)");
        }
        //$criteria->addCondition('no_job_alerts = 0'); //don't send emails to carer who set that flag to true
        $allCarers = Carer::model()->newJobAll()->findAll($criteria);
        //$emailEncoded = Encryption::encryptURLParam($person->email_address));

        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_NEW_MISSION');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_THERE_ARE_NEW_MISSIONS_FOR_YOU');

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/carer/chooseMission';
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_CHOOSE_MISSION');

        $unsubscribeJobAlertsLink = self::getUnsubscribeJobAlertsLink();

        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => 'Carer',
            'unsubscribeJobAlertsLink' => $unsubscribeJobAlertsLink,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;

        $toAll = array();
        $to = array();

        foreach ($allCarers as $carer) {

            //$firstName = $carer->first_name;

            $to['email'] = $carer->email_address;
            $toAll[] = $to;
        }

        return self::sendMail($html, $subject, $toAll);
    }

    // -5 b- Send to selected carers new mission is available.
    public static function sendToCarer_Shortlisted_NewJob($carer, $postCode, $city, $client) {

        $postCode3Letters = substr($postCode, 0, 3);

        $firstName = $carer->first_name;

        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_NEW_MISSION_BOOKED');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_NEW_MISSION_BOOKED');

        $conversation = Conversation::getConversation($client->id, $carer->id);
        $conversationId = $conversation->id;

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'conversation', $email, "id---$conversationId");

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_CONFIRM_JOB');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;

        $to = $carer->email_address;
        return self::sendMail($html, $subject, $to);
    }

    //5-c send to not selected carers but who could be interesed
    public static function sendToCarers_Potential_NewJob($carers, $postCode, $city) {

        $postCode3Letters = substr($postCode, 0, 3);

        $ok = true;

        foreach ($carers as $carer) {

            $firstName = $carer->first_name;

            $emailTitle = Yii::t('emails', 'EMAIL_TITLE_NEW_MISSION_PERSO', array('{postCode3Letters}' => $postCode3Letters, '{city}' => $city));
            $emailContent = Yii::t('emails', 'EMAIL_CONTENT_THERE_ARE_NEW_MISSIONS_FOR_YOU');
 
            $email = urlencode($carer->email_address);
            $urlParam = self::constructURL('carer', 'myMissions', $email);

            $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
            $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_CONFIRM_JOB');

            $unsubscribeJobAlertsLink = self::getUnsubscribeJobAlertsLink();

            $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
                'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
                'firstName' => $firstName,
                'unsubscribeJobAlertsLink' => $unsubscribeJobAlertsLink
            );
            $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
            $subject = $emailTitle;

            $to = $carer->email_address;

            //bulk email, use cron job
            $ok = $ok && self::sendMail($html, $subject, $to, null, null, true);
        }

        return $ok;
    }

    public static function sendToCarer_NewJob($job, $carer) {

//        $postCode3Letters = substr($postCode, 0, 3);
//
//        $firstName = $carer->first_name;
//
//        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_NEW_MISSION_PERSO', array('{postCode3Letters}' => $postCode3Letters, '{city}' => $city));
//        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_THERE_ARE_NEW_MISSIONS_FOR_YOU');
//
//        $email = urlencode($carer->email_address);
//        $urlParam = self::constructURL('carer', 'chooseMission', $email);
//
//        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
//        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_CHOOSE_MISSION');
//        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
//            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
//            'firstName' => $firstName
//        );
//        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
//        $subject = $emailTitle;
//
//        $to = $carer->email_address;
//        return self::sendMail($html, $subject, $to);
    }

    // -6- Send email to Carer when Client has cancelled the assigned-mission. OKOKOK
    public static function sendToCarer_AssignedMissionCancelledByClientOrAdmin($carer) {
        $firstName = $carer->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_MISSION_CANCELLED');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_ONE_OF_THE_CONFIRMED_MISSION_WAS_CANCELLED');

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'myMissions', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_VIEW_CURRENT_MISSIONS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $carer->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -7- Send email to Carer when Admin has selected a carer for a mission he had applied to. OKOKOK
    public static function sendToCarer_DeAssignedMission($carer) {
        $firstName = $carer->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_SOMEBODY_ELSE_ASSIGNED');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_SOMEBODY_ELSE_ASSIGNED');

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'myMissions', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_GO_TO_CURRENT_MISSIONS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $carer->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -8- Send email to Carer when Admin has selected a carer for a mission he had applied to. OKOKOK
    public static function sendToCarer_AssignedDirectlyMission($carer) {
        $firstName = $carer->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_YOU_WERE_ASSIGNED_FOR_A_SHIFT');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_YOU_WERE_ASSIGNED_FOR_A_SHIFT');

        $email = urlencode($carer->email_address);
        $urlParam = self::constructURL('carer', 'myMissions', $email);

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_GO_TO_CURRENT_MISSIONS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $carer->email_address;
        return self::sendMail($html, $subject, $to);
    }

    //     ******* OTHER EMAILS *******
    // -1- Send email to Carer or Client when they ask to reset pssword. OKOK (no email passed as this is not relevant for this function which passes a unique code)
    public static function sendPerson_ResetPasswordEmail($person, $newPassword) {

        Yii::log('Email debug: id user: ' . $person->id . ' firstname ' . $person->first_name . ' password: ' . $newPassword, CLogger::LEVEL_ERROR, 'error_email');

        $firstName = Yii::t('emails', 'EMAIL_USER'); // byRC: no name for just registered carers and clients.
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_NEW_PASSWORD');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_YOU_HAVE_REQUESTED_A_NEW_PASSWORD');

        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/site/changeTempPassword/id/' . Encryption::encryptURLParam($person->email_address . "-" . $newPassword);
        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_CHANGE_PASSWORD');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $person->email_address;
        return self::sendMail($html, $subject, $to);
    }

    // -2- Send email to Carer or Client when new message in complaint (complaint solved does NOT trigger any emails). OKOKOK
    public static function sendToClient_NewResponseToComplaint($person) {
        $firstName = $person->first_name;
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_PLEASE_CHECK_THE_COMPLAINT_MENU');
        $emailContent = Yii::t('emails', 'EMAIL_CONTENT_THERE_IS_NEW_INFORMATION_IN_THE_COMPLAINT_MENU');

        $email = urlencode($person->email_address);

        if ($person instanceof Client) {

            $urlParam = self::constructURL('clientManageBookings', 'carerVisitsComplaint', $email);
            $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        } else {

            $urlParam = self::constructURL('carer', 'missionsComplaint', $email);
            $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . $urlParam;
        }

        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_VIEW_OPENED_COMPLAINTS');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $person->email_address;
        return self::sendMail($html, $subject, $to);
    }

    //  ******* EMAILS TO ADMIN *******
    // -1- Contact form OKOK (no email passed)
    public static function sendToAdmin_ContactForm($fromName, $fromEmail, $fromSubject, $message) {
        $firstName = 'Admin';
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_CONTACT_FORM');
        $emailContent = 'Name: ' . $fromName . '<br /><br />Email: ' . $fromEmail . '<br /><br />Subject: ' . $fromSubject . '<br /><br />' . $message;
        $emailButtonLink = false;
        $emailButtonText = false;
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = self::getAdminEmail();
        $bcc = self::getAdminEmailBcc();
        return self::sendMail($html, $subject, $to, $fromEmail, $bcc);
    }

    // -2- Client emailing Carer (admin for now)
    public static function sendToAdmin_ClientMessage($carer, $fromEmail, $message) {
        $firstName = 'Admin';
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_CLIENT_MESSAGE');
        $emailContent = 'Message for carer ' . $carer->fullName . ' (' . $carer->id . ')' . '<br /><br />From Email: ' . $fromEmail . '<br /><br />Message:<br />' . $message;
        $emailButtonLink = false;
        $emailButtonText = false;
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = self::getAdminEmail();
        $bcc = self::getAdminEmailBcc();
        return self::sendMail($html, $subject, $to, $fromEmail, $bcc);
    }

    //3-withdrawal request from carer
    public static function sendToAdmin_CarerWithdrawal($carer, $amount) {
        $firstName = 'Admin';
        $emailTitle = Yii::t('emails', 'Withdrawal request from Carer');
        $emailContent = 'Withdrawal request from Carer ' . $carer->fullName . ' (' . $carer->id . ')' . '<br /><br />Amount: £' . $amount . '<br /><br />';
        $emailButtonLink = false;
        $emailButtonText = false;
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = self::getAdminEmail();
        $bcc = self::getAdminEmailBcc();
        return self::sendMail($html, $subject, $to, null, $bcc);
    }
    
        public static function sendToAdmin_CarersWithdrawals($text) {
        $firstName = 'Admin';
        $emailTitle = Yii::t('emails', 'Bi-weekly payment of All Carers');
        $emailContent = $text . '<br /><br />';
        $emailButtonLink = false;
        $emailButtonText = false;
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = self::getAdminEmail();
        $bcc = self::getAdminEmailBcc();
        return self::sendMail($html, $subject, $to, null, $bcc);
    }


    // Copy to client
    public static function sentToClient_CopyMessageMe($clientEmail, $message, $carer) {

        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_CLIENT_COPY_MESSAGE');
        $emailContent = 'You have sent this message to ' . $carer->first_name . ':<br /><br />' . $message;
        $emailContent .= '<br><br>The carer will get back to you as soon as possible. <br>';
        $emailButtonLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl;
        $emailButtonText = Yii::t('emails', 'Go to DirectHomecare');
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => 'Madam/Sir',
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $clientEmail;

        return self::sendMail($html, $subject, $to);
    }

    public static function sendToShiftAdmin_NewBooking($client, $booking, $carer) {

        $firstName = 'Admin';
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_ADMIN_NEW_BOOKING');
        $emailContent = 'Client ' . $client->fullName . ' has made a new booking: <br><br>';
        $emailContent .= $booking->displayForAdminEmail();

        $emailContent .= '<br><br>Selected carer:' . $carer->getFullName() . ' ' . $carer->id;

        $emailButtonLink = false;
        $emailButtonText = false;
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $emails = self::getShiftAdminEmails();

        $toAll = array();
        $to = array();
        foreach ($emails as $email) {

            $to['email'] = $email;
            $toAll[] = $to;
        }

        return self::sendMail($html, $subject, $toAll);
    }

    public static function sendToShiftAdmin_CarerCancelledAssignedMission($carerId, $missionId) {

        $carer = Carer::loadModel($carerId);
        $mission = Mission::loadModel($missionId);

        $firstName = 'Admin';
        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_ADMIN_CARER_CANCEL_ASSIGNED');
        $emailContent = 'Carer ' . $carer->fullName . ' has cancelled a shift <br><br>';
        $emailContent .= $mission->displayMissionAdmin();

        $emailButtonLink = false;
        $emailButtonText = false;
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $firstName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $emails = self::getShiftAdminEmails();

        $toAll = array();
        $to = array();
        foreach ($emails as $email) {

            $to['email'] = $email;
            $toAll[] = $to;
        }

        return self::sendMail($html, $subject, $toAll);
    }

    public static function sendToPerson_ContactFormConfirmation($fromName, $fromEmail) {

        $emailTitle = Yii::t('emails', 'EMAIL_TITLE_CONTACT_FORM_CONFIRMATION');
        $emailContent = 'Thanks for contacting us. We will respond to you within 12 hours.';
        $emailButtonLink = false;
        $emailButtonText = false;
        $params = array('emailTitle' => $emailTitle, 'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink, 'emailButtonText' => $emailButtonText,
            'firstName' => $fromName,
        );
        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);
        $subject = $emailTitle;
        $to = $fromEmail; // self::getAdminEmail();
        // $bcc = self::getAdminEmailBcc();
        return self::sendMail($html, $subject, $to);
    }

    /**
     * 
     * @param type $controller
     * @param type $action
     * @param type $emailAddress
     * @param type $parameters use '---' to separate name from value ie id---231
     * @return type
     */
    public static function constructURL($controller, $action, $emailAddress, $parameters = "") {

        $result = "/site/emailClick/controller/$controller/action/$action/emailAddress/$emailAddress/parameters/$parameters";

        return $result;
    }

    public static function getUnsubscribeJobAlertsLink() {
        $unsubscribeJobAlertsLink = Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/site/unsubscribeJobAlerts';
        return $unsubscribeJobAlertsLink;
    }

    //     ******* INVESTORS *******

    public static function sendInvestors_Email($email) {

        $subject = 'Help improving the Home Care industry';

        //get random carers
        $emailContent = '<br /><br />';

        $emailContent = "
            You can be part of a revolution in the Home Care industry. <br /><br />

            Great carers should be rewarded and service users must receive help from highly motivated and respectful individuals. <br /><br />

            We aim at improving the service  provided to the elderly with our unique rating process for carers. <br /><br />

            Direct Homecare is still a young company and we have many hurdles to overcome. Yet, people arround us are supportive and share our vision. <br /><br />

            The UK's leading crowd-funding platform CrowdCube has approved Direct Homecare and is helping us to raise investments. <br /><br />

            If you you want to be part of our venture, read our pitch here: <br />         
            <a href='http://www.crowdcube.com/investment/direct-homecare-14984'>http://www.crowdcube.com/investment/direct-homecare-14984</a><br /><br />

            Renaud Cohard-Beaumont & <br />
            Emilie Harper <br /><br />
            
            Email: info@directhomecare.com <br />
            Phone: 020 3519 1188 <br />
            Web: directhomecare.com <br />

            ";

        $params = array('emailTitle' => $subject,
            'firstName' => 'Sir or Madam',
            'emailContent' => $emailContent,
            'emailButtonText' => false,
        );



        $html = Yii::app()->controller->renderFile(Yii::app()->basePath . '/views/mail/emailTemplate.php', $params, true);

        $to = $email;

        return self::sendMail($html, $subject, $to);
    }

}

?>