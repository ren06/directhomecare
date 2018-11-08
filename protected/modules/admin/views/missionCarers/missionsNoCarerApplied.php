<h1>Shifts where no carer has applied</h1>

<?php
if (empty($missions)) {

    echo "There are currently no such shift";
} else {

    foreach ($missions as $mission) {

        echo 'Id: ' . $mission->id . '<br />';
        echo 'Booking: ' . $mission->id_booking . '<br />';    
        echo $mission->displayMissionAdmin();
        echo '<br />';
        echo '<hr>';
    }
}
?>