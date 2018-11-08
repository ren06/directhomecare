
<h1>Shifts where carers have applied but nobody is Assigned</h1>

<?php
if (empty($missions)) {

    echo "There are currentyl no such shift";
} else {

    foreach ($missions as $mission) {

        echo 'Id: ' . $mission->id . '<br />';
        echo 'Booking: ' . $mission->booking->id . '<br /><br />';
        echo $mission->displayMissionAdmin();
        echo '<br /><br />';
        echo CHtml::link('See all Carers who applied for this Shift', array('missionCarers/missionCarersApplied', 'id' => $mission->id,
            'scenario' => MissionCarersController::SCENARIO_CARER_SELECTION));
        echo '<br />';echo '<br />';
        echo '<hr>';
    }
}
?>