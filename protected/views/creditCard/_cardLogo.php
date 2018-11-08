<h4><?php echo Yii::t('texts', 'HEADER_CARDS_ACCEPTED'); ?></h4>
<p>
    <img alt="Visa" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_VISA'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-visa.png'; ?>"/>
    <img alt="Visa dedit" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_VISA_DEBIT'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-visaelectron.png'; ?>"/>
    <img alt="MasterCard" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_MASTERCARD'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-mastercard.png'; ?>"/>
    <img alt="Maestro" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_MAESTRO'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-maestro.png'; ?>"/>
</p>
<br>
<h4><?php echo Yii::t('texts', 'HEADER_SECURED_WEBSITE'); ?></h4>
<p style="height:3em">
    <a href="javascript:void(0)" onClick="verifySealTrustWave()" style="text-decoration:none">
        <img alt="TrustWave" title="<?php echo Yii::t('texts', 'ALT_CLICK_TO_VERIFY'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-trustwave.png'; ?>"/>
    </a>
    <a href="javascript:void(0)" onClick="verifySealGoDaddy()" style="text-decoration:none">
        <img alt="GoDaddy" title="<?php echo Yii::t('texts', 'ALT_CLICK_TO_VERIFY'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-godaddy.gif'; ?>"/>
    </a>
</p>
<script type="text/javascript">
        function verifySealTrustWave() {
            var bgHeight = "550";
            var bgWidth = "620";
            var url = "https://sealserver.trustwave.com/cert.php?customerId=972adbecec804efd870ece55be5eb860&size=105x54&style=";
            window.open(url, 'c_TW', 'location=no, toolbar=no, resizable=no, scrollbars=no, directories=no, status=no, width=' + bgWidth + ',height=' + bgHeight);
        }
        function verifySealGoDaddy() {
            var bgHeight = "466";
            var bgWidth = "600";
            var url = "https://seal.godaddy.com/verifySeal?sealID=DOmy4xTS4vJt0Pc4U1t3hXQIdAmiLxXWKOmZh2bYrnK5Ju9kv2UHPX6Qn";
            window.open(url, 'SealVerfication', 'menubar=no,toolbar=no,personalbar=no,location=yes,status=no,resizable=yes,fullscreen=no,scrollbars=no,width=' + bgWidth + ',height=' + bgHeight);
        }
</script>