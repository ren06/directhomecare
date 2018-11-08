<?php Yii::app()->clientScript->registerCoreScript("jquery"); ?>
<table>
    <tr>
        <th>Id</th>
        <th>Date</th>
        <th>Cancel By Client</th>
    </tr>
    <?php
    foreach ($bookings as $booking) {

        $bookingId = $booking->id;

        $missions = $booking->missions;

        $missionNotStarted = true;
        ?>
        <tr>
            <td>
                <?php echo $bookingId; ?>
            </td>
            <td>
                <?php echo $booking->displayDates(); ?>
            </td>
            <td>
                <?php
                foreach ($missions as $mission) {

                    $missionNotStart = $mission->isNotStarted();

                    if (!$missionNotStart) {

                        $missionNotStarted = false;
                        break;
                    }
                }

                if ($booking->isAllCancelledByClient()) {

                    echo 'All cancelled by client';
                } else {


                    if (!$missionNotStarted) {
                        echo 'Cannot cancel, mission started ';
                    } else {
                        $url = $this->createUrl('clientAdmin/cancelByClient');
                        echo CHtml::ajaxLink('Cancel (give voucher)', $url, array('type' => 'POST',
                            'success' => 'function(html){
                               $("#result").html(html); 
                              }',
                            'data' => array('bookingId' => $bookingId),
                                )
                                , array('class' => 'rc-button', 'name' => 'refundBooking_' . $bookingId . '_' . uniqid()));
                    }
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<script>
    $(document).ready(function()
    {
        //  $('.rc-button').html('toto');
    });
</script>



