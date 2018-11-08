<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_CONFIRMATION');
$this->pageSubTitle = 'Your booking was processed';
?>


<div class="row" style="min-height:15em">
    <div class="large-6 medium-6 small-12 columns">
        <p>
            Thanks for booking your carer.
        </p>
        <p>
            The carer will now confirm that she/he will attend.<br>
            Alternatively you will be fully refunded.
        </p>
        <p>
            It can take up to 48 hours for the carer to confirm.
        </p>
    </div>
</div>


<?php
if (Yii::app()->piwik->siteID != '0') {
    ?>
    <script type="text/javascript">
        /*<![CDATA[*/
        var _paq = _paq || [];
        (function() {
            _paq.push(['setSiteId', '1']);
            _paq.push(['setTrackerUrl', 'https://directhomecare.com/stats_piwik/piwik.php']);
            _paq.push(['addEcommerceItem',
                "<?php
    if ($quote->type == Constants::LIVE_IN) {
        echo '2';
    } else {
        echo '1';
    }
    ?>", // "1" for Hourly or "2" for Live-in.
                "<?php
    if ($quote->type == Constants::LIVE_IN) {
        echo 'Home Care Live-in';
    } else {
        echo 'Home Care Hourly';
    }
    ?>", // "Home Care Hourly" or "Home Care Live-in".
                "Home Care", //product category
    <?php
    if ($quote->type == Constants::LIVE_IN) {
        $price = Prices::getPrice(Constants::USER_CLIENT, Prices::LIVE_IN_DAILY_PRICE);
    } else {
        $price = Prices::getPrice(Constants::USER_CLIENT, Prices::HOURLY_PRICE);
    } echo $price->amount;
    ?>, // Price per hour for Hourly or per day for Live-in. Integer.
    <?php
    if ($quote->type == Constants::LIVE_IN) {
        $slots = $quote->getLiveInMissionSlots();
        $slot = $slots[0];
        $dura = $slot->duration;
        $pos = strpos($dura, '&#160;');
        $duration = substr($dura, 0, $pos);
    } else {
        $quotePrice = $quote->getQuoteTotalPrice();
        $duration = $quotePrice->duration / 3600;
    }
    echo $duration;
    ?> // Number of hours for Hourly or number of days for Live-in. Integer.
            ]);
            _paq.push(['trackEcommerceOrder',
                "<?php echo $bookingId; ?>", // Transaction ID.
    <?php
    if ($quote->type == Constants::LIVE_IN) {
        echo $slots[0]->toPay->amount;
    } else {
        echo $quotePrice->totalPrice->amount;
    }
    ?>// Cart amount. Integer.
            ]);
            // Call the file
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.defer = true;
            g.async = true;
            g.src = 'https://directhomecare.com/stats_piwik/piwik.js';
            s.parentNode.insertBefore(g, s);
        })();
        /*]]>*/
    </script>
    <?php
}
?>
