<?php
$this->pageTitle = 'Direct Homecare - Book a Carer online';
$this->keyWords = 'carer worker, find carer, find care worker, trusted carer, personal care assistants, elderly care, find a carer';
$this->description = 'Find care worker online in London and all over the UK. Hire certified and trusted carers with confidence. Choose an affordable solution. Fast and easy.';
?>
<section id="homepage-form">
    <div class="row">
        <div class="columns large-7 medium-8 small-12">
            <h1>direct <i class="fi-heart" style="color:#ce0069"></i> homecare</h1>
            <br>
            <br>
            <br>
            <h3>Find highly rated carers in the UK.</h3>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="row">
        <div class="columns large-6 medium-8 small-12">
            <div class="row collapse">
                <div class="columns large-6 medium-6 small-12">
                    <?php
                    $postCode = '';
                    echo CHtml::textField('postCode', $postCode, array('id' => 'post_code', 'placeholder' => 'Enter your full UK postcode', 'style' => 'height:61px;font-size:16px;', 'onkeyup' => 'js:if (event.keyCode === 13) {checkPostCode();}'));
                    ?>
                </div>
                <div class="columns large-6 medium-6 small-12">
                    <?php
                    echo CHtml::button('FIND CARERS >', array('onClick' => 'js:checkPostCode();', 'class' => 'large button expand'));
                    ?>
                </div>
                <?php
                echo '<small class="error" id="errorMessageArea" style="display:none"><span id ="errorMessage" class="rc-error"></span></small>';
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <br>
        <br>
        <br>
        <br>
        <div class="columns large-3 medium-6 small-12">        
            <a href="#" data-reveal-id="videoModal"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/videoplay2.png"></a>
            <div id="videoModal" class="reveal-modal large" data-reveal="" style="display:block;opacity:1;">
                <h2>Book a professional carer online</h2>
                <div class="flex-video widescreen" style="display:block;">
                    <iframe src="//www.youtube-nocookie.com/embed/Fl6UDc_bq1s?rel=0" frameborder="0"></iframe>
                </div>
                <a class="close-reveal-modal">&#215;</a>
            </div>
        </div>
    </div>
</section>
<section class="homepage-stripe">
    <div class="row centered-text">
        <div class="small-12 columns">
            <h2>Why should you use Direct Homecare ?</h2>
            <h4 class="subheader">It's now really easy and secure to book home care.</h4>
        </div>
    </div>
    <div class="row centered-text">
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-pound.svg">
            <h3>Value</h3>
            <p>Affordable price yet great service. Costs are reduced with our automated system.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-lock.svg">
            <h3>Trust</h3>
            <p>You get a trusted carer. We verify the identity of carers and their certificates.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-star.svg">
            <h3>Quality</h3>
            <p>Highly rated only. Carers are rated by customers and we keep the best ones.</p>
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
            <a href="<?php echo Yii::app()->request->baseUrl; ?>" class="small-12 medium-6 large-4 button radius">FIND HOME CARE</a>
        </div>
    </div>
</section>
<section class="homepage-quote">
    <div class="row">
        <div class="small-12 medium-12 large-12 columns">
            <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-left.svg"> My carer is amazing, the service is highly professional. I would recommend Direct Homecare to anyone looking for efficiency. <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-right.svg"></p>
            Peter King, Westminster
        </div>
    </div>
</section>
<section class="homepage-stripe">
    <div class="row centered-text">
        <div class="small-12 columns">
            <h2>Some of our professional carers</h2>
            <h4 class="subheader">Highly motivated, experienced and ready to serve.</h4>
        </div>
    </div>
    <div class="row centered-text">
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cleaner-hammersmith.png">
            <h3>Leslie<br>Hammersmith</h3>
            <p>Experience: 8 years<br>
                Rating: <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i>
            </p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cleaner-islington.png">
            <h3>Emily<br>Islington</h3>
            <p>Experience: 7 years<br>
                Rating: <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i>
            </p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cleaner-kensington.png">
            <h3>Jane<br>Kensington</h3>
            <p>Experience: 5 years<br>
                Rating: <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"> </i><i class="fi-star"></i>
            </p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/cleaner-clapham.png">
            <h3>Clare<br>Clapham</h3>
            <p>Experience: 11 years<br>
                Rating: <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i> <i class="fi-star"></i>
            </p>
        </div>
    </div>
    <br>
    <div class="row centered-text">
        <div class="small-12 columns">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>" class="small-12 medium-6 large-4 button radius">BOOK A CARER</a>
        </div>
    </div>
</section>
<section class="homepage-quote">
    <div class="row">
        <div class="small-12 medium-12 large-12 columns">
            <p><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-left.svg"> Mum feels better at home now that her carer visits every week, I wonder why it took me so long to decide. A must use service. <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/quote-right.svg"></p>
            Jane Waller, Chelsea
        </div>
    </div>
</section>
<section class="homepage-stripe">
    <div class="row centered-text">
        <div class="small-12 columns">
            <h2>Book a carer in 4 simple steps</h2>
            <h4 class="subheader">It's fast, convenient and totally secured.</h4>
        </div>
    </div>
    <div class="row centered-text">
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-calendar.svg">
            <h3>1. Date</h3>
            <p>Select the day and time which suits you best, we have many carers.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-home.svg">
            <h3>2. Service</h3>
            <p>Select the services you require and write any special instruction.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-map.svg">
            <h3>3. Address</h3>
            <p>Enter your exact address and explain how you will let the carer in.</p>
        </div>
        <div class="small-12 medium-3 large-3 columns">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/svgs/fi-credit-card.svg">
            <h3>4. Payment</h3>
            <p>Secured online payment. Money back guaranty, no questions asked.</p>
        </div>
    </div>
    <br>
    <div class="row centered-text">
        <div class="small-12 columns">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>" class="small-12 medium-6 large-4 button radius">HIRE A CARER</a>
        </div>
    </div>
</section>
<section>
    <div class="homepage-quote centered-text">
        <div class="row">
            <div class="small-6 medium-3 large-3 columns border">
                <i class="fi-torsos-all-female" style="color:#fff;font-size:37px"></i>
                <p>1,500 registered carers</p>
            </div>
            <div class="small-6 medium-3 large-3 columns border">
                <i class="fi-like" style="color:#fff;font-size:37px"></i>
                <p>97% customer satisfaction rate</p>
            </div>
            <div class="small-6 medium-3 large-3 columns border">
                <i class="fi-trees" style="color:#fff;font-size:37px"></i>
                <p>Founded in 2012 and still growing !</p>       
            </div>
            <div class="small-6 medium-3 large-3 columns">

            </div>
        </div>
    </div>
</section>
<!-- check postcode validity -->
<script type="text/javascript">

    //When page loads set focus on Postcode field
    $(document).ready(function() {

        //$('#post_code').focus();
    });

    function checkPostCode() {

<?php
echo CHtml::ajax(array(
    'url' => array('site/checkPostCode'),
    'type' => 'get',
    'dataType' => 'json',
    'data' => 'js:{"postCode": $("#post_code").val()}',
    'beforeSend' => "function() {
                

             }",
    'success' => "function(data, status){
        
                    $('#errorMessageArea').hide();
                    $('.rc-fb-small-loader').show();
                    $('#post_code').removeClass('error');

                        if (data.success === 'true') {
                            //alert(data.redirectUrl);
                            //window.location.assign(data.redirectUrl);
                            //    $('body').load(data.redirectUrl);
                            $(location).attr('href', data.redirectUrl);
                        }
                        else {
                            $('.rc-fb-small-loader').hide();
                            $('#errorMessageArea').show();
                             $('#post_code').addClass('error');
                            
                            $('#errorMessage').html(data.errorMessage);

                            //$(object).closest('.rc-container-tickbox').find('.rc-fb-small-loader').hide();

                        }
                    }"
));
?>

    }

</script>