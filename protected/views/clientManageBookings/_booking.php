<!-- sub view to display a client booking in "My Bookings" menu is called by view myBookings -->

<p>
    <?php
    echo '<h4>' . Yii::t('texts', 'LABEL_BOOKING') . '&#160;' . $index . '</h4>';

    echo '<br>';

    echo $booking->displayServiceUsersText('&#160;&#160;&#45;&#160;&#160;');

    echo '&#160;&#160;&#45;&#160;&#160;';

    echo $booking->getTypeLabel();

    echo '&#160;&#160;&#45;&#160;&#160;';

    echo $booking->location->display('&#160;&#160;&#45;&#160;&#160;');

    $url = Yii::app()->createUrl("clientManageBookings/myBookings");
//    if ($booking->isDiscardButtonVisible()) {
//        echo ' | ';
//        echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_DISCARD'), Yii::app()->createUrl("clientManageBookings/discardBooking", array("id" => $booking->id)), array('type' => 'POST',
//            'success' => "function(data){                                          
//                      $('body').load('$url');}",
//            'data' => array('id' => $booking->id)), array(
//            //htmlOptions
//            'name' => 'discard_' . $booking->id,
//            'href' => Yii::app()->createUrl("clientManageBookings/discardBooking"),
//            'class' => 'rc-linkbutton-small',
//                )
//        );
//    }
    ?>
</p>
<?php
$this->renderPartial('application.views.clientManageBookings._bookingPayments', array('booking' => $booking, 'scenario' => $scenario));
?>

<script type="text/javascript">
<?php
$showText = Yii::t('texts', 'LABEL_SHOW_VISITS');
$hideText = Yii::t('texts', 'LABEL_HIDE_VISITS');
?>
    function showHideUpcomingMissions(element, link) {

        var selector = '#' + element;

        if ($(selector).is(":visible")) {

            link.innerHTML = '<?php echo $showText ?>';
            $(selector).hide('blind');

        }
        else {

            link.innerHTML = '<?php echo $hideText ?>';
            $(selector).show('blind');
        }

    }
</script>