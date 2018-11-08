<?php

/**
 * Booking Live in slot
 *
 * @author Renaud Theuillon
 */
class BookingLiveInSlot {

    public $startDateTime; //string
    public $endDateTime; //string
    public $toPay; //Price
    public $duration; //string (duration text)
    public $missionPayment; //total payment for all missions for this slot (1 for live in, n for hourly)
    public $hourlyMissions; //hourly missions

    public function __construct($startDateTime, $endDateTime, $duration, $toPay = null, $missionPayment = null) {

        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->toPay = $toPay;
        $this->duration = $duration;
        $this->missionPayment = $missionPayment;
    }

    public function getPaymentBreakdown($clientId) {

        $price = Prices::calculateLiveInMissionPrice(Constants::USER_CLIENT, $this->startDateTime, $this->endDateTime);

        //return array with breakdown of cash/credit
        $payment = Prices::calculatePaymentBreakdown($clientId, $price);

        return $payment;
    }
    
    /**
     * 
     * Return price of the current slot for given client
     * 
     * @param type $clientId used to see if client has voucher, taken into account in calculation
     * @return type Price
     */
    public function getPaymentBreakdownHourly($clientId) {

        $totalPrice = new Price();

        foreach ($this->hourlyMissions as $mission) {

            $price = Prices::calculateHourlyMissionPrice(Constants::USER_CLIENT, $mission->start_date_time, $mission->end_date_time);
            $totalPrice = $totalPrice->add($price);
        }
        //return array with breakdown of cash/credit
        $payment = Prices::calculatePaymentBreakdown($clientId, $totalPrice);

        return $payment;
    }

}

?>
