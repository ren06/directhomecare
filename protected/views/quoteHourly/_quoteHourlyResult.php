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

$daysBreakdown = $quotePrice->daysBreakdown;

if (count($daysBreakdown) > 0) {
    $firstBookingHourlyDayPrice = $quotePrice->daysBreakdown[0];
    $firstDay = Calendar::getDayOfWeekNumber($firstBookingHourlyDayPrice->date);
}

if ($quote->recurring == true) {

    $new = array();

    foreach ($daysBreakdown as $k => $v) {
        $new[$k] = clone $v;
    }

    $daysBreakdown = $new;
}

$numberDays = count($daysBreakdown);

if ($numberDays > 14) {
    $numberDays = 14;
}
?>

<!--<table>-->
<div class="rc-container-fb2-criteria-line-even">
    <?php
    for ($i = 0; $i < $numberDays; $i++) {
        $dayBreakdown = $daysBreakdown[$i];
//        if ($i != 0 && $firstDay == Calendar::getDayOfWeekNumber($dayBreakdown->date)) {
//            break;
//        }
        ?>
                    <!--        <tr>
                                <td style="width:20%">-->
        <?php
        echo Calendar::getDayOfWeekText($dayBreakdown->date, Calendar::FORMAT_DBDATE, true);
        ?>
        <!--                </p>
                    </td>
                    <td style="width:80%">
                        <p>-->
        <?php
        echo Calendar::convert_DBDateTime_DisplayDateText($dayBreakdown->date) . '&#160;&#160;' . $dayBreakdown->startTime . ' - ' . $dayBreakdown->endTime;
        ?>
        <!--            </td>
                </tr>-->
        <?php
    }
    ?>
    <!--</table>-->
    <!--<hr style="margin-top:0.5em" />-->
</div>

<div class="rc-container-fb2-criteria-line-odd">
<!--    <img alt="Calendar" width="32" height="32" src="<?php // echo Yii::app()->request->baseUrl . '/images/bigicon-calendar-green.png';      ?>" />-->
    <?php
    echo Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true) . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::HOURLY_PRICE) . '&#58;&#160;<b><span style="color:#66C">' . $quotePrice->totalPrice->text . '</span></b>';
    // echo
    // '<b>' . Yii::t('texts', 'LABEL_SERVICE') . '&#58;&#160;</b>' . 
    // Yii::t('texts', 'LABEL_HOURLY_CARE');
    ?>
    <?php
    if ($quote->getDays() > $maxDays) {
        echo '<p><b>' . Yii::t('texts', 'LABEL_FIRST_2_WEEKS') . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_THE_SCHEDULE_IS_DONE_2_WEEKS')) . '</p>';
    }
    ?>
</div>
<?php
if ($quote->getDays() > $maxDays) {
    if ($quote->recurring) {
        echo '<br /><p><b>' . Yii::t('texts', 'RECURRING_UNTIL_YOU_STOP') . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_UPCOMING_PAYMENTS_CAN_BE_STOPPED_AT_ANY_TIME')) . '</p>';
    } else {
        echo '<br /><p><b>' . Yii::t('texts', 'RECURRING_UNTIL') . Calendar::convert_DBDateTime_DisplayDateText($quote->end_date_time) . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_UPCOMING_PAYMENTS_CAN_BE_STOPPED_AT_ANY_TIME')) . '</p>';
    }
}
?>