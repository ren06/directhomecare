<?php
//quote specific logic
if ($quote instanceof BookingHourlyOneDayForm) {

    //One Day
    $quote = $quote->convertBookingHourly();
} elseif ($quote instanceof BookingHourly) {

    //Fourteen 
    $quote->subtype = Booking::SUBTYPE_TWO_FOURTEEN;
} elseif ($quote instanceof BookingHourlyRegularlyForm) {

    //Regulary
    $quote = $quote->convertBookingHourly();
}


$hourlyPrice = Prices::getPrice(Constants::USER_CLIENT, Prices::HOURLY_PRICE)->text;
$maxDays = BusinessRules::getHourlyBookingPaymentMaximumDays();

$quotePrice = $quote->getQuoteTotalPrice();

$unitPrice = Prices::getPrice(Constants::USER_CLIENT, Prices::HOURLY_PRICE);
echo  $unitPrice->text . ' x ' . Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true);

echo '&#58;&#160;<b>' . $quotePrice->totalPrice->text . '</b>';