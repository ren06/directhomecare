<div class="footer-top centered-text">
    <div class="row">
        <div class="small-12 medium-6 large-6 columns">
            <h3>Direct Homecare</h3>
            <p>An online platform to connect customers to carers is made in London. We aim to provide the best home care service to households thanks to a unique technology and high standard recruitment process.</p>
        </div>
        <div class="small-6 medium-3 large-3 columns border">
            <i class="fi-plus" style="color:#fff;font-size:37px"></i>
            <h4>Want more?</h4>
            <p>
                <?php echo CHtml::link(Yii::t('texts', 'About'), array('site/page/view/about'), array('class' => '', 'id' => 'about')); ?><br>
                <!--<?php // echo CHtml::link(Yii::t('texts', 'Area covered'), array('site/area'), array('class' => 'rc-link', 'id' => 'area')); ?><br>-->
                <?php echo CHtml::link(Yii::t('texts', 'Carer jobs'), array('/carer-jobs'), array('class' => '', 'id' => 'jobs')); ?><br>
                <?php echo CHtml::link('Glossary', array('site/page/view/glossary')); ?><br>             
            </p>
        </div>
        <div class="small-6 medium-3 large-3 columns">
            <i class="fi-megaphone" style="color:#fff;font-size:37px"></i>
            <h4>Talk to us</h4>
            <p>
                <?php echo CHtml::link(Yii::t('texts', 'Questions'), array('site/page/view/questions'), array('class' => '', 'id' => 'questions')); ?><br>
                <?php echo CHtml::link(Yii::t('texts', 'Contact'), array('site/contact'), array('class' => 'rc-link', 'id' => 'contact')); ?>
            </p>
        </div>
    </div>
</div>
<div class="footer-bottom">
    <div class="row text-center">
        <div class="small-12 medium-6 large-3 columns">
            <h4><a href="<?php echo Yii::app()->request->baseUrl . '/'; ?>">direct <i class="fi-heart" style="color:#ce0069"></i> homecare</a></h4>
        </div>
        <div class="small-12 medium-6 large-6 columns">
            <br>
            <?php
            echo CHtml::link(Yii::t('texts', 'LINK_PRIVACY'), array('site/page/view/privacy'), array('class' => '', 'id' => 'privacy'));
            echo CHtml::link(Yii::t('texts', 'LINK_SITEMAP'), array('/site-map'), array('class' => '', 'id' => 'sitemap'));
            if (Yii::app()->user->isGuest == false) {
                echo CHtml::link(Yii::t('texts', 'LINK_TERMS'), array('site/page/view/terms'), array('class' => '', 'id' => 'terms'));
            }
            ?>
        </div>
        <div class="small-12 medium-12 large-3 columns">
            <br>
            <a href="https://www.facebook.com/DirectHomecare"><i class="fi-social-facebook" style="font-size:37px"></i></a>
            <a href="https://plus.google.com/+DirectHomecarelondon"><i class="fi-social-google-plus" style="font-size:37px"></i></a>
        </div>
        <div class="small-12 medium-12 large-12 columns">
            <br>
            <?php echo Yii::t('texts', 'NOTE_COPYRIGHT'); ?>
        </div>
    </div>
</div>