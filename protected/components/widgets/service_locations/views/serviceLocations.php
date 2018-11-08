<div id="serviceLocationsWidget">
    <div id="service_locations">
        <?php for ($i = 0; $i < count($this->serviceLocations); $i++): ?>

            <?php
            $serviceLocation = $this->serviceLocations[$i];
            $new = $serviceLocation->isNewRecord;
            $size = sizeof($this->serviceLocations);
            $booking = $this->booking;

            $this->render('_service_location', array(
                'serviceLocation' => $serviceLocation,
                'index' => $i,
                'scenario' => $scenario,
                'actionPath' => $this->getController()->id . '/serviceLocations.',
                'newServiceLocation' => $new,
                'booking' => $booking,
                'size' => $size,
            ));
            ?>

        <?php endfor ?>

    </div>
</div>
<br />
<?php
echo CHtml::ajaxButton(Yii::t('texts', 'BUTTON_ADD_ANOTHER_LOCATION'), $this->controller->createUrl('serviceLocations.addLocation'), array(
    'beforeSend' => 'function() {   }',
    'success' => 'function(html) {
                  
                 $("#service_locations").append(html);    
                        
     }',
    'error' => 'function(data) {
         }',
        ), array('class' => 'rc-button-white', 'id' => 'add_service_location')
);

echo '<span id="add_service_location_disabled" class="rc-linkbutton-white-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_ADD_ANOTHER_LOCATION') . '</span>';
?>
<script type="text/javascript">

    function editMode(index) {

        $('#service_location' + index + ' :input').removeAttr('disabled');

        $('#service_location' + index).find('.toggable').addClass('rc-field');
        $('#service_location' + index).find('.toggable').removeClass('rc-field-disabled');

        $('#add_service_location').attr('disabled', true);

        $('#service_location' + index).find('.required span').show();

        $('#save' + index).show();
        $('#cancel' + index).show();

        $('#edit' + index).hide();
        $('#remove' + index).hide();
        $('#used' + index).hide();

        $('.buttons_input').hide();
        $('.buttons_span').show();

    }

    function displayMode(index) {

        $('#service_location' + index + ' :input').attr('disabled', 'disabled');

        $('#service_location' + index).find('.toggable').addClass('rc-field-disabled');
        $('#service_location' + index).find('.toggable').removeClass('rc-field');

        $('.required span').hide();


        $("#add_service_location").removeAttr("disabled");
        $("#radio_button" + index).removeAttr("disabled");

        $('#save' + index).hide();
        $('#cancel' + index).hide();
        $('#used' + index).show();

        $('#edit' + index).show();
        $('#edit' + index).removeAttr('disabled');

        $('#remove' + index).show();
        $('#remove' + index).removeAttr('disabled');

        $('.buttons_input').show();
        $('.buttons_span').hide();
    }

    function disableAllButtons(currentIndex, locationIndexes) {

        $("#add_service_location").hide();
        $("#add_service_location_disabled").show();

        //disable all butons
        for (var i = 0; i < locationIndexes.length; i++) {
            {
                var index = locationIndexes[i];
                if (i != currentIndex) {

                    $('#remove' + index).hide();
                    $('#edit' + index).hide();
                    $('#edit' + index + 'disabled').show();
                    $('#remove' + index + 'disabled').show();
                }
            }

        }
    }

    function enableAllButtons(locationIndexes) {

        $("#add_service_location").show();
        $("#add_service_location_disabled").hide();
        //enable all butons
        for (var i = 0; i < locationIndexes.length; i++)
        {
            var index = locationIndexes[i];
            $('#edit' + index).show();
            $('#remove' + index).show();
            $('#edit' + index + 'disabled').hide();
            $('#remove' + index + 'disabled').hide();

        }
    }

</script>