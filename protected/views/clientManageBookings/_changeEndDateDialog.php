<?php
/* @var $form CActiveForm */

$earliestEndDate = $booking->getEarliestEndDate();


$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'autoOpen' => true,
        'title' => Yii::t('texts', 'HEADER_STOP_PAYMENTS'),
        'resizable' => false,
        'modal' => true,
        'draggable' => false,
        'width' => '480',
    // 'height' => '260',
    ),
));
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'booking-gap-form',
        'enableAjaxValidation' => true,
        //'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => false
        ),
        'focus' => '#start_hour', //bug if focus on CJuiDialog
        'action' => 'createHolidayGap'
    ));

    echo CHtml::hiddenField('bookingId', $booking->id); //used to pass booking id 
    echo Chtml::hiddenField('setEndDate', $earliestEndDate);
    ?>
    <p>
        <?php
        echo Yii::t('texts', 'NOTE_ALL_UPCOMING_PAYMENTS_WILL') . '<br />';
        echo Yii::t('texts', 'NOTE_THE_LAST_CARER_VISIT_WILL') . ' <b>' . Calendar::convert_DBDateTime_DisplayDateText($earliestEndDate) . '</b>.<br />';
        echo Yii::t('texts', 'NOTE_TO_RESUME_THE_SERVICE');
        //echo CHtml::link(Yii::t('texts', 'LABEL_CLICK_FOR_EARLIEST_END_DATE'), '', array('class' => 'rc-link', 'id' => 'earliestEndDate'));
        ?>
    </p>
    <div class="rc-container-button">
        <div class="buttons">
            <?php
            echo CHtml::ajaxSubmitButton(Yii::t('texts', 'BUTTON_STOP_PAYMENTS'), CHtml::normalizeUrl(array('stopPayments')), array(
                'beforeSend' => 'function() {}',
                'success' => 'function(data, status, xhr) {
                
                //clear any existing errors
                $("#errorMessage").html("");
               
                if(data === "abort"){
                                 
                    xhr.abort();
                }
                else{
                    
                    if (data != "") {
                        
                        $("#errorMessage").html(data);
                        //hide ajax loader
                        $(".loading").hide();
                        //show again buttons
                        $("#OK").closest(".rc-container-button").find(".buttons").show();
                    }
                    else {
                         window.location.href="' . CHtml::normalizeUrl(array('myBookings')) . '"; 
                    }   
                }
            }',
                'error' => 'function(data) {}',
                    ), array('class' => 'rc-button', 'id' => 'OK')
            );

            echo CHtml::ajaxSubmitButton(Yii::t('texts', 'BUTTON_CLOSE'), CHtml::normalizeUrl(array('changeEndDateCancel')), array(
                'beforeSend' => 'function() {}',
                'success' => 'function(data, status, xhr) {
                
                    $("div.ui-dialog:visible").find("div.ui-dialog-content").dialog("close");

            }',
                'error' => 'function(data) {}',
                    ), array('class' => 'rc-button', 'id' => 'Cancel')
            );
            ?>
        </div>
        <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form --> 

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
