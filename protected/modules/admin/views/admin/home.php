<h2>Direct Homecare - Administration</h2>
<?php
if (Yii::app()->user->checkAccess('role_cronjobAdmin')) {
    ?>
    <h3>Cron-jobs testing</h3>
    For every booking, create the next shifts and make the payment <?php echo CHtml::link("Execute", array('/test/unitTest/cronJobPayments')) . '<br />'; ?>
    Credit carers for shifts they have completed with no complaints or closed complaints <?php echo CHtml::link("Execute", array('/test/unitTest/cronCreditCarers')) . '<br />'; ?>
    Cancel shifts that have no carers <?php echo BusinessRules::getCancelByAdminHoursBeforeMissionStart() ?> hours before the start of the shift <?php echo CHtml::link("Execute", array('/test/unitTest/cronCanceMissionsByAdmin')) . '<br />'; ?>
    Create a withdrawal for all Carers  <?php echo CHtml::link("Execute", array('/test/unitTest/cronCreateCarersWithdrawals')) . '<br />'; ?>
    <?php
}
?>
<?php
if (Yii::app()->user->checkAccess('role_carerGeneral')) {
    ?>
    <br />
    <br />
    <h3>Carers</h3>    
    <?php echo CHtml::link("Search and manage", array('carerAdmin/manageCarers')) . '<br />'; ?>
    <?php echo CHtml::link("Search by post code", array('carerAdmin/searchByPostCode')) . '<br />'; ?>
    <?php echo CHtml::link("Show carer with at least one diploma", array('carerAdmin/searchByQualification')) . '<br />'; ?>
    <?php if (Yii::app()->user->checkAccess('role_carerAdmin')) { ?>
        <?php echo CHtml::link("See login history", array('carerAdmin/loginHistory')) . '<br />'; ?>
    <?php } ?>
    <?php echo CHtml::link("View carers on a map", array('carerAdmin/showCarersMap')) . '<br />'; ?>
    <?php echo CHtml::link("Select Homepage carers", array('carerAdmin/selectHomePageCarers')) . '<br />'; ?>
    <br />
    <?php echo CHtml::link("Unapproved documents", array('carerAdmin/approveDocumentsCarers/all/0')) . '<br />'; ?>
    <?php echo CHtml::link("All documents", array('carerAdmin/approveDocumentsCarers/all/1')) . '<br />'; ?>
    <?php echo CHtml::link("Last created carers", array('carerAdmin/index')) . '<br />'; ?>
    <br/>
    <?php echo CHtml::link("Withdrawals", array('carerAdmin/carerWithdrawals')) . '<br />'; ?>

    <?php
}
?>
<?php
if (Yii::app()->user->checkAccess('role_clientAdmin')) {
    ?>
    <br />
    <br />
    <h3>Clients</h3>
    <?php // echo CHtml::link("All Client registered never made a booking", array('clientAdmin/clients/wzd/0')) . '<br />'; ?>
    <?php echo CHtml::link("Search and manage", array('clientAdmin/manageClients')) . '<br />'; ?>

    <?php
    if (Yii::app()->user->checkAccess('role_superadmin')) {
        echo CHtml::link("See prospects", array('admin/clientProspectsAdmin')) . '<br />';
    }
    ?>
    <?php
    if (Yii::app()->user->checkAccess('role_superadmin')) {
        echo CHtml::link("Create fake client and shift", array('/unitTest/createFakeClientAndMission'), array('confirm' => 'Are you sure you want to create a fake client with a fake shift ?')) . '<br />';
    }
    ?>
    <?php echo CHtml::link("Last clients who made a booking", array('clientAdmin/clients/wzd/' . Wizard2::CLIENT_LAST_STEP_INDEX)) . '<br />'; ?>
    <br />
    <br />
    <h3>Bookings</h3>
    <?php echo CHtml::link("All Client Bookings", array('bookingAdmin/admin')) . '<br />'; ?>
    <?php echo CHtml::link("All Bookings per Client", array('bookingAdmin/clientsBookings')) . '<br />'; ?>

    <br />
    <br />
    <h3>Visits/Shifts</h3>
    <?php echo CHtml::link("See carers who have applied - <b>ADMIN ACTION -> ASSIGN CARER</b>", array('missionCarers/missionsNoCarerSelected')) . '<br />'; ?>
    <?php
    if (Yii::app()->user->checkAccess('role_superadmin')) {
        echo CHtml::link("Search and manage (DEBUGGING ONLY)", array('missionCarers/manageMissionsCarers')) . '<br />';
    }
    ?>
    <?php echo CHtml::link("No carers have applied - REPORT", array('missionCarers/missionsNoCarerApplied')) . '<br />'; ?>

    <?php echo CHtml::link("No carers confirmed - Can change selected carer", array('missionCarers/missionsCarerNotConfirmed')) . '<br />'; ?>
    <?php echo CHtml::link("Carer is assigned - REPORT", array('missionCarers/missionsCarerAssigned')) . '<br />'; ?>
    <?php echo CHtml::link("Assign a different carer - ACTION", array('missionCarers/missionsCarerAssignedNotStarted')) . '<br />'; ?>
    <br />
    <br />

    <?php if (Yii::app()->user->checkAccess('role_superadmin')) { ?>
        <h3>Cancel missions</h3>
        <?php
        echo CHtml::link("Refund client booking", array('clientAdmin/refundMissionsByBooking')) . '<br />';
    }
    ?>
    <br />
    <?php
    if (Yii::app()->user->checkAccess('role_superadmin')) {
        echo CHtml::link("Cancel all missions of Client booking (voucher)", array('clientAdmin/cancelMissionsByBooking')) . '<br />';
        ?>
        <br />    
        <br />
    <?php } ?>
    <h3>Profile of Carers</h3>
    <?php echo CHtml::link("All carer profiles", array('carerAdmin/allCarersProfiles')) . '<br />'; ?>
    <?php echo CHtml::link("Carers selection by Client BETA", array('carerAdmin/carersSelectionClient')) . '<br />'; ?>

    <br />
    <br />
    <h3>Complaints</h3>
    <?php echo CHtml::link("Search and manage", array('complaint/admin')) . '<br />'; ?>
    <br />
    <br />
    <?php if (Yii::app()->user->checkAccess('role_superadmin')) { ?>
        <h3>Borgun</h3>
        <?php echo CHtml::link("See transaction history", array('borgun/transactionHistory')) . '<br />'; ?>
        <?php echo CHtml::link("Payment test", array('borgun/testBorgunPayment')) . '<br />'; ?>
        <?php echo CHtml::link("Payment refund", array('borgun/testBorgunRefund')) . '<br />'; ?>
        <br />
        <br />

    <?php } ?>

    <?php if (Yii::app()->user->checkAccess('role_superadmin')) { ?>
        <h3>Newsletters</h3>
        Now run with commands and cron jobs
        <br />
        <?php echo CHtml::link("Send newsletter to Clients", array('newsletter/sendClientsNewsletter')) . '<br />'; ?>
        <br />
        <?php echo CHtml::link("Send newsletter to Carers", array('newsletter/sendCarersNewsletter')) . '<br />'; ?>

        <br />
        <br />

    <?php } ?>


    <?php if (Yii::app()->user->checkAccess('role_superadmin')) { ?>     
        <h3>Database</h3>
        <?php echo CHtml::link("Clear DB cache", array('admin/clearDBCache')) . '<br />'; ?>
        <br />
        <br />

    <?php } ?>

    <?php if (Yii::app()->user->checkAccess('role_superadmin')) { ?>
        <h3>Other</h3>
        <?php echo CHtml::link("Update post codes coordinates", array('admin/updateAddressPostCodeCoordinates')) . '<br />'; ?>
        <?php echo CHtml::link("Adjust Carer addresses", array('admin/updateCarerAddresses')) . '<br />'; ?>

        <?php
    }
}
?>