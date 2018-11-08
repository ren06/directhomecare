<h1>Direct Homecare - Test Module</h1>

TOTO2
<h2>Cron jobs testing</h2>
<ul>
    <li>
        For every booking, create the next shifts and make the payment
        <?php echo CHtml::link("Execute", array('/test/unitTest/cronJobPayments')); ?>
    </li>

    <li>
        Credit carers for shifts they have completed with no complaints or closed complaints
        <?php echo CHtml::link("Execute", array('/test/unitTest/cronCreditCarers')); ?>
    </li>

    <li>
        Cancel shifts that have no carers <?php echo BusinessRules::getCancelByAdminHoursBeforeMissionStart() ?> hours before the start of the shift
        <?php echo CHtml::link("Execute", array('/test/unitTest/cronCanceMissionsByAdmin')); ?>
    </li>  


</ul>

<h2>Booking and Mission testing</h2>    

<ul>
    <li>
        Card encryption test
        <?php echo CHtml::link("Execute", array('unitTest/cardEncryptionTest')); ?>
    </li>

    <li>
        Clean all bookings and mission data !
        <?php //echo CHtml::link("Execute", array('unitTest/cleanBookingsMissions'), array('onClick' => 'return alert("Are you sure");')); ?>
    </li>

    <li>
        Create a fake client with a fake mission
        <?php echo CHtml::link("Execute", array('unitTest/createFakeClientAndMission')); ?>
    </li>

    <li>
        Create a <b>Hourly Booking </b> with its missions
        <?php echo CHtml::link("Execute", array('unitTest/createHourlyBookingAndMissions')); ?>
    </li>

    <li>
        Create a <b>Live-In Booking </b> with its mission
        <?php echo CHtml::link("Execute", array('unitTest/createLiveInBookingAndMission')); ?>
    </li>    

    <li>
        Create n Live In Bookings randomly with given client and date range
        <?php echo CHtml::link("Execute", array('unitTest/createTestBookings/clientId/357/dateRangeLow/2013-04-15/dateRangeHigh/2013-06-15/numberDaysRangeLow/3/numberDaysRangeHigh/100/number/3')); ?>
    </li>    

    <li>
        Cancel a mission like it was cancelled by an admin
        <?php echo CHtml::link("Execute", array('unitTest/cancelByAdmin')); ?>
    </li>
    <li>
        Test Borgun payment
        <?php echo CHtml::link("Execute", array('unitTest/testDoDirectPaymentBorgun')); ?>
    </li>
    <li>
        Test Borgun 
        <?php echo CHtml::link("Go to", array('unitTest/testBorgun')); ?>
    </li>
</ul>

<h2>Carers</h2>  

<ul>
    <li>
        Create 100 random carers
        <?php echo CHtml::link("Create carers", array('test/createRandomCarer/number/100')); ?>
    </li>
    
        <li>
       Delete carers (adjust fromId/0/toId/0) to specific keys)
        <?php echo CHtml::link("Delete carers", array('test/deleteCarers/fromId/0/toId/0')); ?>
    </li>
       
</ul>


