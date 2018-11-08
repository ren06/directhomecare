<?php

/**
 * Store day price, date and duration in seconds
 *
 * @author Renaud Theuillon
 */
class BookingHourlyDayPrice {

    public $date; 
    public $price;
    public $seconds;
    public $startTime;
    public $endTime;
    

    public function __construct($date, $price, $seconds, $startTime, $endTime) {

        $this->date = $date;
        $this->price = $price;        
        $this->duration = $seconds;        
        $this->startTime = $startTime; 
        $this->endTime = $endTime; 
    }

}

?>
