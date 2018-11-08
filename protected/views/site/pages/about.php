<?php
$this->pageTitle = 'About Direct Homecare';
$this->pageSubTitle = 'Find our corporate information below.';
$this->keyWords = 'About Direct Homecare, who is direct homecare, direct homecare corporate information, concept direct homecare';
$this->description = 'Direct Homecare is a platform connecting Carers and Clients. We make your life easier by dealing with the administrative side of hiring a carer.';
?>

<div class="row">
    <div class="large-3 columns ">
        <div class="panel">
            <div class="section-container vertical-nav" data-section data-options="deep_linking: false; one_up: true">
                <section class="section">
                    <h5 class="title"><a href="#concept">Concept</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#security">Security</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#cards"><?php echo Yii::t('texts', 'HEADER_CARDS_ACCEPTED'); ?></a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#team">Team</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#corporate">Corporate information</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#homecare">Home care</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#contact">Contact</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#reviews">Reviews</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#social">Social media</a></h5>
                </section>
            </div>
        </div>
    </div>
    <div class="large-9 columns">
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-info.svg"></div>
            <div id="concept" class="large-10 columns">
                <h4>Concept</h4>
                <p>Direct Homecare is a platform connecting carers and clients. While we are not a homecare agency, we make your life easier by dealing with the administrative side of hiring a carer. Clients can find the perfect carer for their needs and carers can find the best possible match for their skills and availability. Their relationship (schedule, fees, feedback...) is fully administered by the Direct Homecare website.</p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-lock.svg"></div>
            <div id="security" class="large-10 columns">
                <h4>Security</h4>
                <p>
                    The website is hosted in the UK by a leading web provider. Your data is safe as we adhere to the strictest privacy standards - we never share your details with any third parties. The website is fully encrypted (see the lock-pad next to the address bar of your browser).<br />
                    Furthermore we are PCI Compliant, the card industry security standard.
                </p>
                <p style="height:3em">
                    <a href="javascript:void(0)" onClick="verifySealTrustWave()" style="text-decoration:none">
                        <img alt="TrustWave" title="<?php echo Yii::t('texts', 'ALT_CLICK_TO_VERIFY'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-trustwave.png'; ?>"/>
                    </a>
                    &#160;&#160;
                    <a href="javascript:void(0)" onClick="verifySealGoDaddy()" style="text-decoration:none">
                        <img alt="GoDaddy" title="<?php echo Yii::t('texts', 'ALT_CLICK_TO_VERIFY'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-godaddy.gif'; ?>"/>
                    </a>
                </p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-credit-card.svg"></div>
            <div id="cards" class="large-10 columns">
                <h4><?php echo Yii::t('texts', 'HEADER_CARDS_ACCEPTED'); ?></h4>
                <p>We are approved to accept these type of credit and debit cards:</p>
                <p>
                    <img alt="Visa" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_VISA'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-visa.png'; ?>"/>
                    <img alt="Visa dedit" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_VISA_DEBIT'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-visaelectron.png'; ?>"/>
                    <img alt="MasterCard" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_MASTERCARD'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-mastercard.png'; ?>"/>
                    <img alt="Maestro" title="<?php echo Yii::t('texts', 'ALT_WE_ACCEPT_MAESTRO'); ?>" src="<?php echo Yii::app()->request->baseUrl . '/images/card-logo-maestro.png'; ?>"/>
                </p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-torsos.svg"></div>
            <div id="team" class="large-10 columns">
                <h4>Team</h4>
                <p>
                    The company is a team of seasoned I.T. and business professionals who have extensive experience in the home care field as well as developing complex websites and robust data systems.
                </p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-check.svg"></div>
            <div id="corporate" class="large-10 columns">
                <h4>Corporate information</h4>
                <p>
                    Direct Homecare is a website operated by Direct Homecare Ltd. We are a limited company set up in 2010 in the United Kingdom and based in London.<br />
                    Company number: 08123047.
                </p>
            </div>
        </div>
<!--    <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-home.svg"></div>
            <div id="homecare" class="large-10 columns">
                <h4>Cleaning services for your home</h4>
                <p>
                    We also help families finding a cleaner to look after their home. So if you cleaning services you can visit <a class="rc-link" href="https://directcleaner.com">Direct Cleaner</a> on the website :<br>
                    <a href="https://directcleaner.com">https://directcleaner.com</a>
                </p>
            </div>
        </div>-->
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-telephone.svg"></div>
            <div id="contact" class="large-10 columns">
                <h4>Contact</h4>
                <p>
                    To contact us and access to all our contact details, please visit the <a href="<?php echo Yii::app()->request->baseUrl; ?>/contact">contact page</a>.
                </p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-bookmark.svg"></div>
            <div id="reviews" class="large-10 columns">
                <h4>Customers reviews</h4>
                <p>
                    Our customers are reviewing our website and providing their feedback on <a class="rc-link" target="_blank" href="http://www.yell.com/biz/direct-homecare-london-7742472">Yell</a>, <a class="rc-link" target="_blank" href="http://www.reviewcentre.com/Carers-and-Care-Homes/Direct-Homecare-directhomecare-com-reviews_2331210">ReviewCentre</a>, <a class="rc-link" target="_blank" href="http://www.yelp.co.uk/biz/direct-homecare-london">Yelp</a>, <a class="rc-link" target="_blank" href="http://uk.local.yahoo.com/Greater_London/London/direct_homecare/2045308041-e-44418.html">Yahoo! Local</a>, <a class="rc-link" target="_blank" href="http://www.bing.com/local/details.aspx?lid=YN1029x100381331">Bing Local</a> and <a class="rc-link" target="_blank" href="http://www.rateitall.com/i-6615136.aspx">RateItAll</a>.
                </p>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-social-facebook.svg"></div>
            <div id="social" class="large-10 columns">
                <h4>Social media</h4>
                <p>
                    Find us on <a class="rc-link" target="_blank" href="https://plus.google.com/+DirecthomecareLondon" rel="publisher">Google+ Local</a>, <a class="rc-link" target="_blank" href="https://www.facebook.com/DirectHomecare">Facebook</a>, <a class="rc-link" target="_blank" href="http://www.pinterest.com/directhomecare/home-care/">Pinterest</a>, <a class="rc-link" target="_blank" href="https://plus.google.com/+Directhomecare">Google+</a> and <a class="rc-link" target="_blank" href="https://twitter.com/DirectHomecare">Twitter</a>.<br>
                    <?php include Yii::app()->basePath . '/views/site/pages/_socialButtons.php'; ?>
                </p>
            </div>
        </div>
    </div>
</div>
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
        var url = "https://seal.godaddy.com/verifySeal?sealID=8XcbmCv2Z46ULXvjLie3zleV1rG4w8iJEDchaYVI4vLeleg8bC";
        window.open(url, 'SealVerfication', 'menubar=no,toolbar=no,personalbar=no,location=yes,status=no,resizable=yes,fullscreen=no,scrollbars=no,width=' + bgWidth + ',height=' + bgHeight);
    }
</script>