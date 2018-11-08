
<h3>Quick Booking</h3>
<br />

<div id="oneDay">
    <div id ="oneDayCriteria">
        <?php
        $this->renderPartial('/quoteHourly/_qbQuoteHourlyCriteriaOneDay', array('quote' => $quote));
        ?>
        <div class="rc-container-button">
            <span class="buttons">

                <?php
//                echo CHtml::ajaxButton('CALCULATE QUOTE', $url, array('type' => 'POST',
//                    'success' => 'function(html){
//                               $("#oneDay").html(html); 
//                              }',
//                    'data' => 'js:$("#hourly-one-quote-form").serialize()',
//                        ), array('class' => 'rc-button ajaxLoaderButton', 'name' => 'showHourlyQuoteOneDay' . uniqid()));
                ?>
            </span>
            <?php $this->renderPartial('/common/_ajaxLoader', array('html' => true, 'javascript' => true)); ?>
        </div>
    </div>
    <div id ="oneDayResult">
        <?php
        $this->renderPartial('/quoteHourly/_qbQuoteHourlyResult', array('quote' => $quote));
        ?>
        <div class="rc-container-button">
            <span class="buttons">
                <?php
///                $action = 'clientNewBooking/quickBooking';
//echo CHtml::link(Yii::t('texts', 'BUTTON_SEE_AVAILABLE_CARERS'), array($action, 'navigation' => 'next', 'quoteType' => $quote->type), array('class' => 'rc-linkbutton ajaxLoaderButton'));
                ?>
            </span>
                <?php //$this->renderPartial('/common/_ajaxLoader', array('html' => true, 'javascript' => false));  ?>
        </div>
            <?php // }  ?>
    </div>
</div>

<?php
//$this->widget('ServiceLocationsWidget', array('client' => $client, 'serviceLocations' => $serviceLocations, 'scenario' => ServiceUsersWidget::MISSION_SCENARIO));
?>

<?php
//$this->widget('ServiceUsersWidget', array('client' => $client, 'serviceUsers' => $serviceUsers, 'scenario' => ServiceUsersWidget::MISSION_SCENARIO));
?>

<?php
$url = $this->createUrl('clientNewBooking/qbChangeHours');
?>
<script>
    $(document).ready(function()
    {
        $(".qb-change-hours").change(function() { //NO

            $.ajax({
                success: function(html) {

                    $("#oneDayResult").html(html);

                },
                type: 'post',
                url: '<?php echo $url ?>',
                data: {
                    formData: decodeURIComponent($("#qb-one-day-form").serialize())
                },
                cache: false,
                dataType: 'html'

            });
        });
    });
    
     function handleDateSelect(dateText, updateElementId) { //startDate or endDate

        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        //20/11/2013
        var day = dateText.substr(0, 2);
        var month = dateText.substr(3, 2);
        month = month - 1;
        var year = dateText.substr(6, 4);

        var date = new Date(year, month, day);
        var index = date.getDay();

        var result = days[index];

        $("#" + updateElementId).html(result);

    }

</script>





