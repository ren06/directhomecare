
<h1>Clients Bookings</h1>
<br>
<?php

foreach($clients as $client){

    if($client->id != 1){
    
    echo $this->renderPartial('/booking/_clientBookings', array('client' => $client));
    
    }
     
}
?>

