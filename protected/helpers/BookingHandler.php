<?php

class BookingHandler {

    /**
     * 
     * 
     * 
     * @param type $quoteBooking
     * @param type $clientId
     * @param type $serviceUserIds MASTER DATA 
     * @param type $serviceLocationId MASTER DATA 
     * @param type $carerIds
     * @param type $billingAddress will be used to create bookingAddress
     * @param type $creditCard
     * @param type $transactionRef
     * @param type $transactionDate
     * @return type
     * @throws Exception
     */
    public static function handleBooking($quoteBooking, $clientId, $serviceUserIds, $serviceLocationId, $carerId, $billingAddressId, $creditCard, $transactionRef, $transactionDate, $sendMail = true) {

        $transaction = Yii::app()->db->beginTransaction();

        try {

            $totalPrice = $quoteBooking->getQuoteTotalPrice()->totalPrice;

            //only store the credit card when it's new (otherwise problem saving with encrypted value)

            if ($creditCard->isNewRecord) {
                $creditCard = CreditCard::handleCreditCard($creditCard, $clientId, $billingAddressId);
            }

            //create booking address from service location
            $bookingAddress = Address::copy($serviceLocationId, Address::TYPE_SERVICE_LOCATION_BOOKING);

            //use a copy of the quote, useful if errors
            $booking = clone $quoteBooking;
            unset($booking->id); //could be one if transaction failed
            $booking->id_client = $clientId;
            $booking->id_address = $bookingAddress->id;

            if ($booking->recurring == 1) {
                unset($booking->end_date_time);
            }

            $val = $booking->validate();
            $errors = $booking->errors;

            if (count($errors) > 0) {
                $text = print_r($errors, true);
                throw new CException($text);
            }

            if ($booking->type == Constants::HOURLY) {
                $booking->adjustEndDate();
                $booking->type = 3;
            }

            $val = $booking->save();

            $bookingId = $booking->id;

            //store selected carers
            $bookingCarers = new BookingCarers();
            $bookingCarers->id_carer = $carerId;
            $bookingCarers->id_booking = $bookingId;
            $bookingCarers->relation = 0; //not used yet
            $bookingCarers->save();

            //create mission
            if ($booking->type == Constants::LIVE_IN) {

                //LIVE IN
                $slot = $booking->getNextMissionSlot();

                $missionStartDateTime = $slot->startDateTime;
                $missionEndDateTime = $slot->endDateTime;

                //Create Mission Payment 1::1 for Live in. Dates are the same as slot
                $missionPayment = new MissionPayment();
                $missionPayment->id_credit_card = $creditCard->id;
                $missionPayment->id_booking = $booking->id;
                $missionPayment->start_date_time = $missionStartDateTime;
                $missionPayment->end_date_time = $missionEndDateTime;
                $missionPayment->transaction_id = $transactionRef;
                $missionPayment->transaction_date = $transactionDate;
                $missionPayment->save();

                $err = $missionPayment->getErrors();

                $mission = MissionLiveIn::createMissionLiveIn($booking->id, $missionPayment->id, $serviceLocationId, $serviceUserIds, $missionStartDateTime, $missionEndDateTime);

                //$totalPrice = $missionPayment->getTotalPrice(Constants::USER_CLIENT);

                $payment = Prices::calculatePaymentBreakdown($clientId, $totalPrice);

                ClientTransaction::createPayment($clientId, $missionPayment->id, $payment['paidCash'], $payment['paidCredit'], $payment['remainingCreditBalance']);
            } else {

                $dayForms = $booking->dayForms;
                //HOURLY
                //store to database

                $booking->saveBookingHourlyDays(); // STILL USED??
                //$slot = $booking->getNextMissionSlot();
                //does not work, should return max 14 days for endDateTimeSlot
                $missionRangeStartDate = $booking->start_date_time;
                $missionRangeEndDate = $booking->getSlotEndDate($missionRangeStartDate);

                //Create Mission Payment 1::1 for Live in. Dates are the same as slot
                $missionPayment = new MissionPayment();
                $missionPayment->id_credit_card = $creditCard->id;
                $missionPayment->id_booking = $booking->id;
                $missionPayment->start_date_time = $missionRangeStartDate;
                $missionPayment->end_date_time = $missionRangeEndDate;
                $missionPayment->transaction_id = $transactionRef;
                $missionPayment->transaction_date = $transactionDate;
                $missionPayment->save();

                //create transactional copies
                $missionAddress = Address::copy($serviceLocationId, Address::TYPE_SERVICE_LOCATION_MISSION);

                //copy service users for mission and booking
                $newServiceUserIds = array();
                foreach ($serviceUserIds as $serviceUserId) {
                    $newServiceUser = ServiceUser::copy($serviceUserId, ServiceUser::TYPE_TRANSACTIONAL_DATA);
                    $newServiceUserIds[] = $newServiceUser->id;
                }

                //associate service users to booking (creates entries in tbl_booking_service_user)
                ServiceUser::assignToBooking($newServiceUserIds, $bookingId);

                //create missions using copies
                $booking->createMissions($missionPayment->id, $missionAddress->id, $newServiceUserIds);


                $payment = Prices::calculatePaymentBreakdown($clientId, $totalPrice);

                //store only the last digits of the card, then the card can be deleted by the user
                $lastDigits = $creditCard->getLastNumbers();

                ClientTransaction::createPayment($clientId, $missionPayment->id, $payment['paidCash'], $payment['paidCredit'], $payment['remainingCreditBalance'], ClientTransaction::PAYMENT, $totalPrice, $transactionRef, $lastDigits, $booking->id);
            }
            
            //Selected caerer
            $carer = Carer::loadModel($carerId);

            //Send email confirmation to Client
            if ($sendMail) {
                $client = Client::loadModel($clientId);
                Emails::sendToClient_BookingConfirmation($client);                
                Emails::sendToShiftAdmin_NewBooking($client, $booking, $carer);
            }

            //Send generic email to carers except for seleced ones (array of carer id)
            //Generic
            //$ok = Emails::sendToCarer_All_NewJob($carerIds);

            if ($sendMail) {
                $postCode = $booking->location->post_code;
                $city = $booking->location->city;
                //$potentialCarers = Carer::getPotentialCarersForMission($postCode, $booking, $carerIds);
                //$emailSent = Emails::sendToCarers_Potential_NewJob($potentialCarers, $postCode, $city);
            }
 

            if ($sendMail) {
                $emailSent = Emails::sendToCarer_Shortlisted_NewJob($carer, $postCode, $city, $client);
            }

            //set a carer relation to selected (adjust score)
            $relation = $carer->getClientRelation($clientId);

            if (!isset($relation)) {
                ClientCarerRelation::setCarerRelation($clientId, $carer->id, ClientCarerRelation::RELATION_SELECTED);
            } else {
                $rel = $relation->relation;
            }

            //PRE-SELECT CARER
            $missions = $booking->missions;
            foreach ($missions as $mission) {
                MissionCarers::automaticSelected($mission->id, $carerId);
            }

            //Commit
            $transaction->commit();

            //Returns newly created booking
            return $booking;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Used by cron job only
     * Make the PayPal payment
     * 
     * @param type $bookingId
     * @return mixed true if payment successful, string with error message if not
     * @throws Exception
     */
    public static function handleNextMission($bookingId) {

        $transaction = Yii::app()->db->beginTransaction();

        try {
            //get required info
            $booking = Booking::loadModel($bookingId);
            $client = $booking->client;

            $usedCreditCard = $booking->creditCard;
            $slot = $booking->getNextMissionSlot();

            //make payment TODO CHANGE TO BORGUN HANDLER
            $payPalHandler = PayPalHandler::getInstance();
            $payment = $slot->getPaymentBreakdown($client->id);

            $toPay = $payment['paidCash'];

            if ($toPay->amount > 0) {

                $paymentSuccessful = $payPalHandler->doDirectPayment($client, $usedCreditCard, $usedCreditCard->address, $toPay);
                $transactionRef = $payPalHandler->getTransactionRef();
                $transactionDate = $payPalHandler->getTransactionDate();
            } else {
                $paymentSuccessful = true; //everything paid in voucher
                $transactionRef = null;
                $transactionDate = null;
            }

            if ($paymentSuccessful) {

                //Create Booking
                $missionStartDateTime = $slot->startDateTime;
                $missionEndDateTime = $slot->endDateTime;

                //Create Mission Payment 1::1 for Live in. Dates are the same as slot
                $missionPayment = new MissionPayment();
                $missionPayment->id_credit_card = $usedCreditCard->id;
                $missionPayment->id_booking = $booking->id;
                $missionPayment->start_date_time = $missionStartDateTime;
                $missionPayment->end_date_time = $missionEndDateTime;
                $missionPayment->transaction_id = $transactionRef;
                $missionPayment->transaction_date = $transactionDate;
                $missionPayment->save();

                $serviceLocationId = $booking->serviceLocation->id;

                $serviceUsers = $booking->serviceUsers;

                $serviceUserIds = array();

                foreach ($serviceUsers as $servieUser) {
                    $serviceUserIds[] = $servieUser->id;
                }

                //create mission
                if ($booking->type == Constants::LIVE_IN) {
                    $mission = MissionLiveIn::createMissionLiveIn($booking->id, $missionPayment->id, $serviceLocationId, $serviceUserIds, $slot->startDateTime, $slot->endDateTime);
                    //Create payment
                    self::createPaymentLiveIn($slot, $booking->id_client, $missionPayment->id, $slot->startDateTime, $slot->endDateTime);
                } else {
                    $mission = MissionHourly::create();
                }

                //Notify the client a new payment was made
                Emails::sendToClient_NewPayment($booking->client);
            } else {
                Emails::sendToClient_NewPayment_Error($client, $payPalHandler->getLongErrorMessage());
            }



            if ($paymentSuccessful) {
                //Commit
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return $payPalHandler->getLongErrorMessage();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

}

?>
