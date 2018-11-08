<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CHANGE_LOCATION') ?>

<div class="rc-container-40">
    <div class="flash-error" style="display:none">
        <?php echo Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_LOCATION'); ?>
    </div>
    <h2 class="rc-h2red"><?php echo $this->pageTitle; ?></h2>
    <p class="rc-note">
        <?php echo Yii::t('texts', 'NOTE_CHANGES_WILL_AFFECT_FUTURE_PAYMENTS'); ?>
    </p>
    <?php
    $this->widget('ServiceLocationsWidget', array('booking' => $booking, 'client' => $client, 'serviceLocations' => $locations, 'scenario' => ServiceLocationsWidget::MISSION_SCENARIO));
    ?>
</div>

<div class="rc-container-button">
    <?php
    $target = CHtml::normalizeUrl(array('clientManageBookings/selectBookingLocation'));
    $return = CHtml::normalizeUrl(array('clientManageBookings/myBookings'));
    echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_SELECT'), $target, array(
        'type' => 'POST',
        'success' => "function(data){                                          
                        if(data === 'error'){
                           $('.flash-error').show();
                        }
                        else{
                            //$('body').load('$return'); //only change the html
                            window.location.href = '$return';
                        }
            }",
        'data' => array(/* 'locationId' => $serviceLocation->id, */ 'bookingId' => $booking->id)
            ), array(//htmlOptions
        'href' => $target,
        'class' => 'rc-linkbutton'
            )
    );
    ?>

    <?php echo CHtml::link(Yii::t('texts', 'BUTTON_CANCEL'), array('clientManageBookings/myBookings'), array('class' => 'rc-linkbutton')); ?>
</div>