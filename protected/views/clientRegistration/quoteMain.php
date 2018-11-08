<!-- clientRegistration/quote -->
<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_QUOTE') ?>

<?php echo Wizard::generateWizard(); ?>
<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_SELECT_THE_DURATION'); ?></h2>
<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success') ?>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error') ?>
    </div>
<?php endif ?>
<p class="rc-note">
    <?php echo Yii::t('texts', 'FIELDS_REQUIRED'); ?>
</p>
<div style="float:left;">
    <div class="rc-container-40">
        <?php
        if (Session::getSelectedTab() == Constants::TAB_LIVEIN) {

            $this->renderPartial('/quoteLiveIn/quoteLiveInMain', array('quoteLiveIn' => $quoteLiveIn));
        } else {

            $this->renderPartial('/quoteHourly/quoteHourlyMain', array('quoteHourlyOneDay' => $quoteHourlyOneDay,
                'quoteHourlyFourteenDays' => $quoteHourlyFourteenDays, 'quoteHourlyRegularly' => $quoteHourlyRegularly,
                    ), false, false);
        }
        ?>
    </div>
</div>
<?php
//Carers samples
// $numberProfiles = 3;
// $this->renderPartial('application.views.carer.profile._carerProfileSample3-vertical', array('numberProfiles' => $numberProfiles));
?>
<div style="clear:both"></div>

<script type="text/javascript">
    function handleEndDateDefaultLogic(serviceType, dateText) {
        var endDateValue = $('#' + serviceType + 'EndDate').val();
        if (endDateValue === '') {
            $('#' + serviceType + 'EndDate').datepicker('option', 'defaultDate', dateText);
        }
        else if (endDateValue !== '') {

            var startDate = convertDate(dateText);
            var endDate = convertDate(endDateValue);

            if (startDate.getTime() > endDate.getTime()) {

                $('#' + serviceType + 'EndDate').datepicker('setDate', dateText);
            }
        }
    }

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

//        $.ajax({
//            type: "POST",
//            url: url,
//            data: {date: dateText},
//            success: function(html) {
//
//                var content = html;
//                $("#" + updateElementId).html(content);
//              
//            },
//            cache: false,
//            dataType: "html"
//        });

    }

</script>

