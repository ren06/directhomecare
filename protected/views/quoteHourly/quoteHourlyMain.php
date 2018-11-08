<?php
//selected tab depends on quote subtype
//$hourlyQuoteType = Session::getSelectedHourlyType();

switch (Session::getSelectedTab()) {
    case Constants::TAB_HOURLY_ONE:
        $selectedTab = 0;
        break;

    case Constants::TAB_HOURLY_FOURTEEN:
        $selectedTab = 1;
        break;

    case Constants::TAB_HOURLY_REGULARLY:
        $selectedTab = 2;
        break;
}
$this->widget('application.components.redefinitions.JuiTabs', array(
    'tabs' => array(
        Yii::t('texts', 'LABEL_ONE_DAY') => array(
            'id' => 'tab_' . Constants::TAB_HOURLY_ONE,
            'content' => $this->renderPartial('/quoteHourly/quoteHourlyTabOneDay', array('quote' => $quoteHourlyOneDay), true, false),
            // 'tooltip' => Yii::t('texts', 'ALT_FOR_ONE_OFF_VISITS'),
        ),
        Yii::t('texts', 'LABEL_2_TO_14_DAYS') => array(
            'id' => 'tab_' . Constants::TAB_HOURLY_FOURTEEN,
            'content' => $this->renderPartial('/quoteHourly/quoteHourlyTabFourteenDays', array('quote' => $quoteHourlyFourteenDays), true, false),
            // 'tooltip' => Yii::t('texts', 'ALT_FOR_UP_TO_14'),
        ),
        Yii::t('texts', 'LABEL_REGULARLY_SCHEDULED') => array(
            'id' => 'tab_' . Constants::TAB_HOURLY_REGULARLY,
            'content' => $this->renderPartial('/quoteHourly/quoteHourlyTabRegularly', array('quote' => $quoteHourlyRegularly), true, false),
            // 'tooltip' => Yii::t('texts', 'ALT_FOR_REGULARLY_SCHEDULED'),
        ),
    ),
    'id' => 'HourlyQuoteTabs',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'headerTemplate' => '<li><a href="{url}">{title}</a></li>',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    // additional javascript options for the tabs plugin
    'options' => array(
        'collapsible' => false,
    ),
//    'events' => array(
//        'activate' => 'function( event, ui ) {alert(ui);}',
//    ),
));
?>

<!--<div id ="oneDayResult">
    <?php
//    $quote = Session::getQuote();
//    if ($quote->showResult) {
//        $this->renderPartial('/quoteHourly/quoteHourlyResultMain', array('quote' => $quote));
//    }
    ?>
</div>-->

<script>
    $(document).ready(function()
    {
        $('#HourlyQuoteTabs').tabs({active: <?php echo $selectedTab ?>});


        $("#HourlyQuoteTabs").tabs({
            activate: function(event, ui) {

                var activeTab = $("#HourlyQuoteTabs").tabs("option", "active");

                var url = "<?php echo $this->createUrl("clientRegistration/selectTab") ?>";

                $.ajax({
                    type: "get",
                    url: url,
                    data: {selectedTabIndex: activeTab},
                    //success: function(html) { },
                    cache: false,
                    dataType: "html"
                });
            }
        });
    });
</script>