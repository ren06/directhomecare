<?php
$short = false;
?>
<p>
    <?php echo '<b>' . Yii::t('texts', 'LABEL_SERVICE') . '&#58;&#160;</b>' . Yii::t('texts', 'LABEL_LIVE_IN_CARE'); ?>
</p>
<?php
$slots = $quoteLiveIn->getLiveInMissionSlots();
if ($short == false) {
    $maxSlots = 1;
} else {
    $maxSlots = 1;
}
$i = 0;

foreach ($slots as $slot) {
    if ($i == $maxSlots) {
        break;
    }
    $lastSunday = Calendar::getLastSunday(Calendar::convert_DBDateTime_DBDate($slot->startDateTime));
    $toBePaidOn = date('Y-m-d', strtotime('-7 days', strtotime($lastSunday)));
    ?>
    <?php
    if (count($slots) > 1) {
        echo '<p><b>' . Yii::t('texts', 'LABEL_FIRST_2_WEEKS') . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_THE_SCHEDULE_IS_DONE_2_WEEKS')) . '</p>';
    }
    ?>
    <p>
        <?php echo Calendar::convert_DBDateTime_DisplayDateTimeText($slot->startDateTime) . '&#160;&#160;&#160;&#160;' . Yii::t('texts', 'LABEL_UNTIL') . '&#160;&#160;&#160;&#160;' . Calendar::convert_DBDateTime_DisplayDateTimeText($slot->endDateTime); ?>
    </p>
    <p>
        <?php
        echo $slot->duration . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::LIVE_IN_DAILY_PRICE);
        echo '&#58;&#160;<b>' . $slot->toPay->text . '</b>';
        ?>
    </p>
    <?php
    if ($i == $maxSlots - 1) {
        $endDateTime = $quoteLiveIn->end_date_time;
        if ($quoteLiveIn->recurring) {
            echo '<br /><p><b>' . Yii::t('texts', 'RECURRING_UNTIL_YOU_STOP') . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_UPCOMING_PAYMENTS_CAN_BE_STOPPED_AT_ANY_TIME')) . '</p>';
        } else {
            if (count($slots) > 1) {
                echo '<br /><p><b>' . Yii::t('texts', 'RECURRING_UNTIL') . Calendar::convert_DBDateTime_DisplayDateText($endDateTime) . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_UPCOMING_PAYMENTS_CAN_BE_STOPPED_AT_ANY_TIME')) . '</p>';
            }
        }
    }
    $i++;
}
?>