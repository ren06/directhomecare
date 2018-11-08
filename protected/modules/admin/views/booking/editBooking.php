

<?php

$bookingId = $booking->id;
//$missions = $booking->missions;
echo "<h1>Missions of Booking $bookingId </h1>";

$modelMission = new Mission('search');
$modelMission->id_booking = $bookingId;

$this->renderPartial('/mission/_missionsTable', array('modelMission' => $modelMission));

?>
<br>
<h2>Add mission to booking</h2>
<br>

