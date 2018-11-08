<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CONFIRMATION'); ?>

<table class="rc-container-arrows">
    <tr>
        <td class="rc-cell-arrows" style="width:8.5em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-left.png'; ?>"/>
        </td>
        <td class="rc-cell-arrows" style="width:8.5em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-up.png'; ?>"/>
        </td>
        <td class="rc-cell-arrows" style="width:7em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-up.png'; ?>"/>
        </td>
        <td class="rc-cell-arrows" style="width:10em">
            <img alt="Arrow" src="<?php echo Yii::app()->request->baseUrl . '/images/tutorial-arrow-right.png'; ?>"/>
        </td>
    </tr>
    <tr>
        <td class="rc-cell-arrows">
            <?php echo Yii::t('texts', 'LABEL_MAKE_ANOTHER_BOOKING'); ?>
        </td>
        <td class="rc-cell-arrows">
            <?php echo Yii::t('texts', 'LABEL_SEE_YOUR_CARER_VISITS'); ?>
        </td>
        <td class="rc-cell-arrows">
            Access your carers profile
        </td>
        <td class="rc-cell-arrows">
            <?php echo Yii::t('texts', 'LABEL_EDIT_YOUR_INFORMATION'); ?>
        </td>
    </tr>
</table>
<div class="rc-container-30">
    <div class="rc-container-greyed" style="padding:2em!important">
        <h2 class="rc-h2red" style="padding:0!important"><?php echo Yii::t('texts', 'HEADER_YOUR_BOOKING_HAS_BEEN_PROCESSED'); ?></h2>
        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_CONFIRMATION_CLIENT'); ?>
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
