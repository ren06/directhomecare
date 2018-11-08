<div id="assignedAndSelectedMissions">

    <?php
    $assignedCount = count($assignedMissionsDataProvider->getData());
    $selectedCount = count($selectedMissionsDataProvider->getData());

    if ($assignedCount > 0) {
        ?>
        <h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_CONFIRMED'); ?></h2>
        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_THESE_MISSIONS_ARE_BOOKED'); ?>
        </p>
        <div id="assignedMissions">
            <?php
            $columns = Tables::getCarerAssignedMissionsColumnConfig($scenario);

            $this->renderPartial('_missionsTable', array('id' => Tables::ASSIGNED_MISSIONS_GRID, 'dataProvider' => $assignedMissionsDataProvider, 'columns' => $columns));
            ?>
        </div>
        <?php
    }

    if ($selectedCount > 0) {
        ?>
        <h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_WAITING_FOR_YOUR_CONFIRMATION'); ?></h2>
        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_YOU_HAVE_BEEN_SELECTED'); ?>
        </p>
        <div id="selectedMissions">
            <?php
            $this->renderPartial('_missionsTable', array('id' => Tables::SELECTED_MISSIONS_GRID, 'dataProvider' => $selectedMissionsDataProvider, 'columns' => Tables::getCarerSelectedMissionsColumnConfig($scenario)));
            ?>
        </div>
        <?php
    }
    ?>
</div>