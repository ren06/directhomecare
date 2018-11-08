<?php
//Get author
$author = $message->author;
if ($author == $viewer) {
    $authorText = 'You';
} elseif ($author == Constants::USER_ADMIN) {
    $authorText = 'Direct Homecare';
} else {
    if ($viewer == Constants::USER_CARER) {
        $authorText = $client->first_name;
    } elseif ($viewer == Constants::USER_CLIENT) {
        $authorText = $carer->first_name;
    }
}

if ($message->visible_by == $viewer || $message->visible_by == 4) {
    ?>
    <div id="message_<?php echo $message->id ?>">
        <?php
        echo '<b>' . $authorText . '</b>';
        echo ' - ';
        echo $message->getDateTime();
        echo $message->isNew($viewer);

        //Display Buttons to accept or decline the booking
        if ($message->type == Message::TYPE_BOOKING && $message->booking->type == Booking::TYPE_HOURLY_TEMP && $viewer == Constants::USER_CARER) {
            $link = CHtml::link('here', array('carer/myMissions'));
            $textAccept = Yii::t('texts', 'NOTE_BOOKING_CONFIRMATION_LINK') . $link;
            $textDecline = Yii::t('texts', 'NOTE_BOOKING_DECLINED_CARER');
            $bookingId = $message->id_booking;
            $group = 'button_group_' . $bookingId;
            echo "<div id='$group'>";
            echo CHtml::ajaxButton('Accept', $this->createUrl('carer/acceptBooking'), array(
                'success' => "function(html) {                         
                             $('#$group').hide(); 
                             $('.flash-success').html('$textAccept');
                             $('.flash-success').show();    
                             $('#conversation').prepend(html);
                        }",
                'type' => 'POST',
                'data' => array('bookingId' => $bookingId, 'messageId' => $message->id),
                'error' => 'function(data) {
                        }',
                    ), array('class' => 'button tiny', 'id' => 'accept_' . $bookingId, 'name' => 'accept' . $bookingId)
            );
            echo ' ';
            echo CHtml::ajaxButton('Decline', $this->createUrl('carer/declineBooking'), array(
                'success' => "function(html) {    
                             $('#$group').hide(); 
                             $('.flash-notice').html('$textDecline');
                             $('.flash-notice').show();     
                             $('#conversation').prepend(html);
                        }",
                'type' => 'POST',
                'data' => array('bookingId' => $bookingId, 'messageId' => $message->id),
                'error' => 'function(data) {
                        }',
                    ), array('class' => 'button tiny', 'id' => 'decline_' . $bookingId, 'name' => 'decline' . $bookingId)
            );
            echo '</div>';
        }
        ?>
        <div class="alert-box info radius">
            <p>
                <?php
                echo $message->message;
                // JOB MESSAGE
                if ($message->type == Message::TYPE_JOB_POSTING) {
                    $job = $message->job;
                    echo '<br>';
                    echo '<u>Job details</u>';
                    echo '<br>';
                    echo $job->getAgeGroupText() . ' ' . $job->getGenderUserText();
                    echo '<br>';
                    echo $job->getMentalHealthText() . ', ' . $job->getPhysicalHealthText();
                    echo '<br>';
                    echo 'Activities: ' . $job->displayActivities();
                    echo '<br>';
                    $result = Maps::getPostCodeData($job->post_code);
                    $city = $result['city'];
                    echo "Location:  $city " . substr($job->post_code, 0, 3);
                    //$job->showMap();
                }
                //BOOKING MESSAGE
                if ($message->type == Message::TYPE_BOOKING) {
                    
                    $booking = $message->booking;
                    
                    echo '<br>';
                    echo "<u>Booking #$booking->id</u>";
                    echo '<br>';
                    
                    foreach ($booking->missions as $mission) {
                        echo $mission->displayDateTimes() . '<br>';
                        if ($viewer == Constants::USER_CARER) {
                            $unitPrice = Prices::getPrice(Constants::USER_CARER, Prices::HOURLY_PRICE);

                            echo Yii::t('texts', 'LABEL_WAGE') . '&#160;&#058;&#160;';
                            echo $unitPrice->text . ' x ' . $mission->getNumberDaysHoursMinutes(true);
                            echo ' = ';
                            echo $mission->getOriginalTotalPrice(Constants::USER_CARER)->text;
                        }
                        echo '<br>';
                    }
                    echo '<u>Address</u><br>';
                    echo $mission->address->display();
                    echo '<br>';
                    echo 'Client phone: ' . $client->mobile_phone;
                }
                ?>
            </p>
        </div>
    </div>
    <?php
}
?>