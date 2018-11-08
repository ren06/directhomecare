<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = 'Your bookings';
$this->pageSubTitle = 'Visits you have paid for';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myBookingsMenu', array('selectedMenu' => 'currentBookings')); ?>
    </div>
</div>
<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        $bookingIndexes = $client->getBookingIndexes();
        foreach ($bookings as $booking) {
        //Yii::beginProfile('myBookings');
        $index = $bookingIndexes[$booking->id];
        $this->renderPartial('_booking', array('client' => $client, 'booking' => $booking, 'index' => $index, 'scenario' => $scenario));
        echo '<br><br>';
        //Yii::endProfile('myBookings');
        }
        ?>
    </div>
</div>