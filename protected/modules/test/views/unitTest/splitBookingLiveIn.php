<?php
$result = Calendar::splitBookingLiveIn($startDate, $endDate);

//display results

echo '<b>';
echo Calendar::convert_DBDateTime_DisplayDate($startDate) . ' - ' . Calendar::convert_DBDateTime_DisplayDate($endDate);
$duration = Calendar::daysBetween_DBDate($startDate, $endDate);
echo ' - ' . $duration . ' days';
echo '</b>';
echo '</br>';
//echo var_dump($result);

$maxSlots = 10;

$i = 0;

foreach ($result as $slot) {
    ?>
    <p>
    <?php
    $timestamp = strtotime($slot['startDay']);
    $day = date('D', $timestamp);

    echo $day . ' ' . Calendar::convert_DBDateTime_DisplayDateText($slot['startDay']) . '<br />';

    $timestamp = strtotime($slot['endDay']);
    $day = date('D', $timestamp);

    echo $day . ' ' . Calendar::convert_DBDateTime_DisplayDateText($slot['endDay']) . '<br />';
    ?>
    </p>
        <?php
        $days = Calendar::daysBetween_DBDate($slot['startDay'], $slot['endDay']);
        echo $days;

        if ($duration > 21) {

            assert($days < 22 && $days > 7);
        }
        ?>

    <hr />
    <?php
}

assert($startDate == $result[0]['startDay']);
assert($endDate == $result[count($result) - 1]['endDay']);
?>
