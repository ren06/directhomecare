<?php

/**
 * Store total price for a quote, its duration in seconds ans its daily breakdown
 *
 * @author Renaud Theuillon
 */
class BookingHourlyQuotePrice {

    public $totalPrice; //Price
    public $daysBreakdown; //array of BookingHoulryDayPrice
    public $seconds; //integer

    public function __construct($totalPrice, $daysBreakdown, $seconds) {

        $this->totalPrice = $totalPrice;
        $this->daysBreakdown = $daysBreakdown;
        $this->duration = $seconds;
    }

}

?>
