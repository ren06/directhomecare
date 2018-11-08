<?php

$valid = $quote->validate();

//if (!$valid) {
////see if errors global or specific to a form
//    if (!empty($quote->errors)) {
//        $errors = $quote->errors;
//    } else {
//        $errors = $quote->dayForms[$index]->errors;
//    }
//
//    $errorMessage = reset($errors);
//    $errorMessage = $errorMessage[0];
//}
//quote specific logic
if ($quote instanceof HourlyQuoteSimpleForm/* BookingHourlyOneDayForm */) {

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

echo $unitPrice->text . ' x ' . Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true);


if ($valid) {
    $color = 'style="color:#66C"';
} else {
    $color = 'style="color:#C36"';
}

echo "&#58;&#160;<b $color>" . $quotePrice->totalPrice->text . '</b>';




