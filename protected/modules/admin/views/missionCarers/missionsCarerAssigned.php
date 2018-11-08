
<h1>Shifts where carers are assigned</h1>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif ?>

<?php
if (empty($missions)) {

    echo "There are currently no such shift";
} else {

    foreach ($missions as $mission) {

        echo "Mission Id $mission->id <br>";
        echo $mission->displayMissionAdmin();

        echo '<br><br><b>Assigned Carer: </b></br>';
        $assignedCarer = $mission->getAssignedCarer();
        echo $assignedCarer->displayAdmin();
        echo CHtml::encode(' ');
        echo CHtml::link('View', $this->createUrl('/admin/carerAdmin/viewCarer/id/' . $assignedCarer->id));

        echo '<br><br><b>Not selected Carer: </b></br>';
        //show not selected carers
        $notSelectedCarers = $mission->getAssociatedCarers(MissionCarers::NOT_SELECTED);
        if (isset($notSelectedCarers)) {
            foreach ($notSelectedCarers as $notSelectedCarer) {

                echo $notSelectedCarer->displayAdmin();
                echo CHtml::encode(' ');
                echo CHtml::link('View', $this->createUrl('/admin/carerAdmin/viewCarer/id/' . $notSelectedCarer->id));
                echo CHtml::encode(' | ');
                echo CHtml::link('Assign', $this->createUrl('/admin/carerAdmin/assignOtherCarer/carerId/' . $notSelectedCarer->id . '/missionId/' . $mission->id), array('confirm' => 'Are you sure? It will send an email to both carers.'));
                echo '<br>';
            }
        }

        echo '<br><hr>';
    }
}
?>