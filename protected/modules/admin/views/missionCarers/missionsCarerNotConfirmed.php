
<h1>Shifts where carers are selected but haven't confirmed yet</h1>

<?php
if (empty($missions)) {

    echo "There are currently no such shift";
} else {

    foreach ($missions as $mission) {

        echo 'Id: ' . $mission->id . '<br />';
        echo 'Booking: ' . $mission->booking->id . '<br />';
        echo $mission->displayMissionAdmin();
        echo '<br />';
        echo CHtml::link('See applicants', array('missionCarers/missionCarersApplied', 'id' => $mission->id,
            'scenario' => MissionCarersController::SCENARIO_CARER_NOT_CONFIRMED));
        echo '<br />';
        echo '<br />';
        echo '<hr>';
    }
}
?>