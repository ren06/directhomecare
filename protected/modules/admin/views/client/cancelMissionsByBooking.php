<?php
foreach ($clients as $key => $clientId)
    break;

echo CHtml::form('', '', $htmlOptions = array('id' => 'client-form'));
echo CHtml::dropDownList('clientId', $clientId, $clients);

echo CHtml::endForm();

$url = $this->createUrl('clientAdmin/cancelMissionsByBookingClientSelection');
echo CHtml::ajaxButton('Show bookings', $url, array('type' => 'POST',
    'success' => 'function(html){
                               $("#clientBookings").html(html); 
                              }',
    'data' => 'js:$("#client-form").serialize()',
        )
        , array('class' => 'rc-button', 'name' => 'showBookings' . uniqid()));
?>

<br>
<br>
<div id ="result">
</div>
<br>
<br>
<div id="clientBookings">
</div>
