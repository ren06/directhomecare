<?php
$this->pageTitle = 'Direct Homecare - Get a Care Job';
$this->keyWords = 'home care jobs, carer job, carer position, carer vacancies, carer work, high wages, work direct homecare';
$this->description = 'I used to work for an Agency. Now I find clients with Direct Homecare and they sort all the paperwork for me.';
?>

<section id="homepage-form">
    <div class="row">
        <div class="columns small-12 medium-6">
            <h1>Carer jobs</h1>
            <br>
            <h3>Complete the form below to register for home care positions.</h3>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="columns large-6 medium-6 small-12">
                <div class="panel callout radius centered-text">
                    <h5><i class="fi-lock"></i> No-spam guarantee</h5>
                    <p>We always keep your email private.</p>
                </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array('id' => 'carer_details-form',
                'enableAjaxValidation' => false,
                'focus' => array($carer, 'emailAddress'),
                'stateful' => true,
            ));

            if (Yii::app()->params['test']['showPopulateData'] == true) {
               echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populateSignUpCarer'));
            }

            $htmlOptions = array('maxlength' => 60, 'placeholder' => 'Enter your email address');
            echo $form->textField($carer, 'email_address', $htmlOptions);
            echo $form->error($carer, 'email_address');
            $htmlOptions = array('maxlength' => 60, 'placeholder' => 'Password', 'autocomplete' => 'off');
            echo $form->passwordField($carer, 'password', $htmlOptions);
            echo $form->error($carer, 'password');
            $htmlOptions = array('maxlength' => 60, 'placeholder' => 'Repeat password', 'autocomplete' => 'off');
            echo $form->passwordField($carer, 'repeat_password', $htmlOptions);
            echo $form->error($carer, 'repeat_password');

            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_GET_A_JOB_NOW'), array('submit' => array('site/carerJobs'), 'class' => 'large button expand'));
            $this->renderPartial('/common/_ajaxLoader');
            $this->endWidget();
            ?>
        </div>
    </div>
</section>
<section class="homepage-stripe">
    <div class="row centered-text">
        <div class="small-12 columns">
            <h2>Why should you work with Direct Homecare ?</h2>
            <h4 class="subheader">It's now easy and secure to work as a home carer.</h4>
        </div>
    </div>
    <div class="row centered-text">
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-pound.svg">
            <h3>High wage</h3>
            <p>The website takes tiny admin fees compared to agencies so you get more.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-lock.svg">
            <h3>Trust</h3>
            <p>Wages are handled by the website, no needs to claim or negociate with clients.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-clipboard-pencil.svg">
            <h3>Flexibility</h3>
            <p>Our system allows you to choose the dates and time you work.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-clock.svg">
            <h3>Easy</h3>
            <p>Fast and easy. Admin, scheduling and payments are sorted for you.</p>
        </div>
    </div>
    <br>
    <div class="row centered-text">
        <div class="small-12 columns">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/carer-jobs" class="small-12 medium-6 large-4 button radius">HOME CARE JOBS</a>
        </div>
    </div>
</section>
<section class="homepage-quote">
    <div class="row">
        <div class="small-12 medium-12 large-12 columns">
            <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-left.svg"> Simply register and complete your profile, apply to the jobs you want and be paid 7 days after the job is completed. <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-right.svg"></p>
            Emily George, carer advisor
        </div>
    </div>
</section>
<section class="homepage-stripe">
    <div class="row centered-text">
        <div class="small-12 columns">
            <h2>Some of our home care jobs</h2>
            <h4 class="subheader">Well paid, in your area and available now.</h4>
        </div>
    </div>
    <div class="row centered-text">
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-check.svg">
            <h3>Female, 65y<br>London NW3</h3>
            <p>
                Physically able<br>
                Slight memory loss<br>
                Every Tuesday and Sunday<br>
                10:00 until 12:00<br>
                £18.00 per day
            </p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-check.svg">
            <h3>Male, 18y<br>Uxbridge UB8</h3>
            <p>
                Disabled (e.g. paraplegic)<br>
                Mentally able<br>
                Every weekday<br>
                09:00 until 12:00<br>
                £27.00 per day
            </p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-check.svg">
            <h3>Female, 78y<br>London W10</h3>
            <p>
                Walking difficulties<br>
                Slight memory loss<br>
                Every Monday and Thursday<br>
                16:00 until 18:00<br>
                £18.00 per day
            </p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-check.svg">
            <h3>Male, 71y<br>St-Albans AL1</h3>
            <p>
                Walking difficulties<br>
                Slight memory loss<br>
                Every Monday and Friday<br>
                08:00 until 12:00<br>
                £36.00 per day
            </p>
        </div>
    </div>
    <br>
    <div class="row centered-text">
        <div class="small-12 columns">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/carer-jobs" class="small-12 medium-6 large-4 button radius">BECOME A CARER</a>
        </div>
    </div>
</section>
<section class="homepage-quote">
    <div class="row">
        <div class="small-12 medium-12 large-12 columns">
            <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-left.svg"> I used to work for an Agency. Now I find clients with Direct Homecare and they sort all the paperwork for me. <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-right.svg"></p>
            Emma English, carer for 3 years
        </div>
    </div>
</section>
<section class="homepage-stripe">
    <div class="row centered-text">
        <div class="small-12 columns">
            <h2>Get a job in 4 simple steps</h2>
            <h4 class="subheader">It's fast, convenient and totally secured.</h4>
        </div>
    </div>
    <div class="row centered-text">
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-torso-female.svg">
            <h3>1. Profile</h3>
            <p>Complete your profile and upload your documents on the site.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-arrows-in.svg">
            <h3>2. Apply to jobs</h3>
            <p>Review the job offers and apply if you are available and interested.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-home.svg">
            <h3>3. Work</h3>
            <p>Simply do your work, clean the client's home.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-pound.svg">
            <h3>4. Get paid</h3>
            <p>You receive your payment to  your bank account 7 days after the work is completed.</p>
        </div>
    </div>
    <br>
    <div class="row centered-text">
        <div class="small-12 columns">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/carer-jobs" class="small-12 medium-6 large-4 button radius">FIND CARE JOBS</a>
        </div>
    </div>
</section>
<section>
    <div class="homepage-quote centered-text">
        <div class="row">
            <div class="small-6 medium-3 large-3 columns border">
                <i class="fi-torsos-all-female" style="color:#fff;font-size:37px"></i>
                <p>1,950 registered carers</p>
            </div>
            <div class="small-6 medium-3 large-3 columns border">
                <i class="fi-shopping-cart" style="color:#fff;font-size:37px"></i>
                <p>700 happy clients in the UK</p>
            </div>
            <div class="small-6 medium-3 large-3 columns border">
                <i class="fi-like" style="color:#fff;font-size:37px"></i>
                <p>97% customer satisfaction rate</p>
            </div>
            <div class="small-6 medium-3 large-3 columns">
                <i class="fi-trees" style="color:#fff;font-size:37px"></i>
                <p>Founded in 2010 and still growing !</p>
            </div>
        </div>
    </div>
</section>