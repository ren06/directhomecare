<?php
$this->pageTitle = 'Site Map of Directhomecare';
$this->pageSubTitle = 'Everything you need to find on our site !';
$this->keyWords = 'cleaning, find carer, cleaner london, carer uk';
$this->description = 'In order to facilitate your navigation and enhanced your user experience, we have built this site map. Please find a list of all the relevant pages on our website.';
?>

<div class="row">
    <div class="large-3 columns ">
        <div class="panel">
            <div class="section-container vertical-nav" data-section data-options="deep_linking: false; one_up: true">
                <section class="section">
                    <h5 class="title"><a href="#general">General pages</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#services">Other services</a></h5>
                </section>
                <section class="section">
                    <h5 class="title"><a href="#glossary">Glossary</a></h5>
                </section>
            </div>
        </div>
    </div>
    <div class="large-9 columns">
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-home.svg"></div>
            <div id="general" class="large-10 columns">
                <h4>General pages</h4>
                <p>
                    <?php echo CHtml::link('Home page', array('site/index')); ?><br>   
                    <?php echo CHtml::link('About', array('site/page/view/about')); ?><br>
                    <?php echo CHtml::link('Contact', array('site/contact')); ?><br>
                    <?php echo CHtml::link('Jobs for carers', array('/carer-jobs')); ?><br>
                    <?php echo CHtml::link('Questions on our service', array('site/page/view/questions')); ?><br>
                    <?php echo CHtml::link('Glossary', array('site/page/view/glossary')); ?><br>
                    <?php echo CHtml::link('Site map', array('site/page/site-map')); ?><br>
                    <?php echo CHtml::link('Privacy policy', array('site/page/view/privacy')); ?>
                </p>
            </div>
        </div>
<!--        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-torso-female.svg"></div>
            <div id="services" class="large-10 columns">
                <h4>Other services</h4>
                <p>
                    <a class="rc-link" href="https://directcleaner.com">Direct Cleaner</a>
                </p>
            </div>
        </div>-->
        <hr/>
        <div class="row">
            <div class="large-2 columns small-3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-info.svg"></div>
            <div id="glossary" class="large-10 columns">
                <h4>Glossary</h4>
                <p>
                    <?php echo CHtml::link('24 hour care', array('site/page/view/24-hour-care')); ?><br>
                    <?php echo CHtml::link('Home care', array('site/page/view/home-care')); ?><br>
                    <?php echo CHtml::link('Elderly care', array('site/page/view/elderly-care')); ?><br>
                    <?php echo CHtml::link('Disabled home care', array('site/page/view/disabled-home-care')); ?><br>
                    <?php echo CHtml::link('Home care agency', array('site/page/view/home-care-agency')); ?><br>
                    <?php echo CHtml::link('Respite care', array('site/page/view/respite-care')); ?><br>
                    <?php echo CHtml::link('Care for the elderly', array('site/page/view/care-for-the-elderly')); ?><br>
                    <?php echo CHtml::link('Care in home', array('site/page/view/care-in-home')); ?><br>
                    <?php echo CHtml::link('Care UK', array('site/page/view/care-uk')); ?><br>
                    <?php echo CHtml::link('24 hour live-in care', array('site/page/view/24-hour-live-in-care')); ?><br>
                    <?php echo CHtml::link('Care home', array('site/page/view/care-home')); ?><br>
                    <?php echo CHtml::link('Companion care', array('site/page/view/companion-care')); ?><br>
                    <?php echo CHtml::link('Domiciliary care', array('site/page/view/domiciliary-care')); ?><br>
                    <?php echo CHtml::link('Domiciliary help', array('site/page/view/domiciliary-help')); ?><br>
                </p>
            </div>
        </div>
    </div>
</div>