<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_SELECT_THE_DURATION');
$this->pageSubTitle = 'Select the date and time you need a carer for';
?>

<div class="row">
    <div class="large-8 medium-8 small-12 columns">
        <?php
        echo CHtml::beginForm('', 'get', array('id' => 'select-dates-form'));
//        $form = $this->beginWidget('CActiveForm', array('id' => 'select-dates-form',
//            //'enableAjaxValidation' => true,
//            'method' => 'get',
//            'focus' => 'date', //''array($quote, 'date'),
//            'stateful' => true,
//            'htmlOptions' => array('enctype' => 'multipart/form-data'),
//        ));
        ?>
        <div id="oneDay">
            <div id ="oneDayCriteria">
                <?php
                $this->renderPartial('/selectDates/_hourlyOneDayCriteria', array('quote' => $quote));
                ?>
            </div>
        </div>
        <div class="buttons">
            <div class="row">
                <div class="large-6 medium-6 small-6 columns">
                    <?php
                    //back button, either client/myCarers or client/conversation/id/242
                    $url = Session::getBackUrl();
                    if (isset($url)) {
                        echo CHtml::link(Yii::t('texts', 'BUTTON_BACK'), array($url), array('class' => 'button expand'));
                        // echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('submit' => array('selectDates', 'nav' => 'next'), 'class' => 'button'));
                    }
                    ?>        
                </div>
                <div class="large-6 medium-6 small-6 columns">
                    <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('submit' => array('selectDates', 'nav' => 'next'), 'class' => 'button alert expand')); ?>
                </div>
            </div>
            <?php $this->renderPartial('/common/_ajaxLoader'); ?>
        </div>
        <div class="large-4 medium-4 small-12 columns">
            <?php
            //Side Cart
            // $client = Session::getClient();
            // $this->renderPartial('/client/_sideCart', array('client' => $client, 'hideDates' => true));
            ?>
        </div>
    </div>
    <?php // $this->renderPartial('/selectDates/_hourlyRegularCriteria', array('quote' => $quote, 'form' => $form));  ?>
    <?php // echo Calendar::draw_calendar(2, 2014); ?>
</div>
<?php
echo CHtml::endForm();
//$this->endWidget(); 
$url = $this->createUrl('site/changeDateTime');
$buttonHtml = CHtml::button(Yii::t('texts', 'SELECT'), array('class' => 'button'));
?>
<script type="text/javascript">
    //when user presses Eneter key, triggers the submit Button
    $(document).on("keydown", function() {
        if (event.keyCode === 13) {
            var submitButon = $('#yt0');
            var url = '/site/selectDates/nav/next';
            if (location.port == "8888") {
                url = '/directhomecare' + url;
            }

            jQuery.yii.submitForm(submitButon, url, {});
            return false;
        }
    });

    $(document).on("click", ".button-select", function() {

        var button = $(this);

        $.ajax({
            success: function(html) {

                button.closest('span').html(html);

            },
            type: 'post',
            url: '<?php echo $this->createUrl('site/calendarSelect') ?>',
            cache: false,
            dataType: 'html'
        });
    });

    $(document).on("click", ".button-delete", function() {

        var html = '<?php echo $buttonHtml ?>';

        var button = $(this);
        button.closest('span').html(html);
    });


    $(document).on('click', ".dayForm-remove", (function()
    {
        var container = $(this).closest('.dayFormContainer');
        var index = container.attr('id').split('_')[1];

        container.remove();

        $.ajax({
            success: function(result, status) {

                var htmlPrice = decodeURIComponent(escape(result.htmlPrice));

                $('#oneDayResult').html(htmlPrice);
            },
            type: 'get',
            url: '<?php echo $this->createUrl('site/removeDay'); ?>' + '/index/' + index,
            data: {
                formData: decodeURIComponent($("#select-dates-form").serialize()),
                index: $(".dayForms").length
            },
            cache: false,
            dataType: 'json'
        });
    }));

    $(document).on('change', ".change-hours", (function()
    {
        var index = $(this).closest('.dayFormContainer').attr('id').split('_')[1];

        $('.flash-error').hide();

        var noValidate = $(this).hasClass('no-validate');

        $.ajax({
            success: function(result, status) {

                var html = decodeURIComponent(escape(result.html));
                $("#oneDayResult").html(html);

                var flashErrorId = '#flash-error-' + index;

                if (result.success === 'false') {

                    $(flashErrorId).show();
                    $(flashErrorId).html(result.errorMessage);
                }
                else {
                    $(flashErrorId).hide();
                }
            },
            type: 'get',
            url: '<?php echo $url ?>',
            data: {
                formData: decodeURIComponent($("#select-dates-form").serialize()),
                index: index,
                noValidate: noValidate
            },
            cache: false,
            dataType: 'json'
        });
    }));

    function handleDateSelect(dateText, updateElementId, object) { //startDate or endDate


        $('.flash-error').hide();

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

        var index = $(object).closest('.dayFormContainer').attr('id').split('_')[1];

        $(object).closest('.dayFormContainer').find('.error').removeClass('error');

        $.ajax({
            success: function(result, status) {

                var html = decodeURIComponent(escape(result.html));
                $("#oneDayResult").html(html);

                var flashErrorId = '#flash-error-' + index;

                if (result.success === 'false') {

                    $(flashErrorId).show();
                    $(flashErrorId).html(result.errorMessage);
                }
                else {
                    $(flashErrorId).hide();
                }
            },
            type: 'get',
            url: '<?php echo $url ?>',
            data: {
                formData: decodeURIComponent($("#select-dates-form").serialize()),
                index: index,
                noValidate: false
            },
            cache: false,
            dataType: 'json'
        });

    }
</script>