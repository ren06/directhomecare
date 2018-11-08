
<h1>Bookings</h1>
<br>
<br>
<?php 

    $bookingIndexes = $client->getBookingIndexes();
    
    foreach ($bookings as $booking) {

        $index = $bookingIndexes[$booking->id];
         
        $this->renderPartial('application.views.clientManageBookings._booking', array('client' => $client, 'booking' => $booking, 'index' => $index, 'scenario' => NavigationScenario::CLIENT_BACK_TO_NONE));
        echo '<br /><br />' ;
        
    }
?>
