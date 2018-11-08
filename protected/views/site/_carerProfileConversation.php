<?php
//MENU
$cssImageProfileSmall = " class='rc-image-profile-small' ";
?>
<div class="row">
    <div class="large-3 medium-4 small-6 columns">
        <?php
        //CARER DETAILS
        $photo = $carer->getPhotoForClient();
        if (isset($photo)) {
            echo $photo->showImageForGuest('rc-image-profile-small');
        } else {
            echo '<img ' . $cssImageProfileSmall . ' src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/profile-blank.jpg"/>';
        }
        ?>
    </div>
    <div class="large-9 medium-8 small-6 columns">
        <p>
            <span style="color:#C36">
                <?php echo $carer->first_name; ?>
            </span>
            <?php
            if (isset($carer->address)) {
                echo ' - ' . $carer->address->city;
            }
            ?>
            <br>
            <?php
            echo Yii::t('texts', 'LABEL_CUSTOMER_RATING') . ': ';
            $starRating = '<i class="fi-star" style="color:#ce0069"></i>';
            $numberStars = $carer->getOverallRating();
            for ($i = 0; $i < $numberStars; $i++) {
                echo $starRating;
            }
            echo '<br>';
            if ($carer->isIdentityVerified()) {
                echo '<i class="fi-check" style="color:#ce0069"></i> ' . Yii::t('texts', 'LABEL_IDENTITY_VERIFIED') . '<br>';
            }
            if ($carer->isBackgroundChecked()) {
                echo '<i class="fi-check" style="color:#ce0069"></i> ' . Yii::t('texts', 'LABEL_NO_CRIMINAL_RECORDS') . '<br>';
            }
            ?>
        </p>
    </div>
</div>
<br>
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        $documentsTexts = $carer->profileDisplayAllDocuments();
        if (count($documentsTexts) > 0) {
            ?>
            <p>
                <?php
                echo '<b>Certificates:</b><br>';
                foreach ($documentsTexts as $documentsText) {
                    // echo '<img class="rc-fb-icon-certificate" title="' . Yii::t('texts', 'ALT_CERTIFICATE_VERIFIED') . '" src="' . Yii::app()->request->baseUrl . '/images/icon-certificate.png"/>';
                    echo $documentsText . '<br>';
                }
                ?>
            </p>
        <?php } ?>
        <p>
            <b>Why I care:</b><br>
            <?php echo $carer->getMotivationTextForClient(); ?>
        </p>
        <p>
            <b>What I like:</b><br>
            <?php echo $carer->getPersonalityTextForClient(); ?>
        </p>
        <?php
        if ($viewer == Constants::USER_CLIENT) {
            echo CHtml::button(Yii::t('texts', 'BUTTON_BOOK'), array('submit' => array('client/bookCarer/id/' . $carer->id), 'class' => 'button tiny'));
            ?>
            <span style="color:#C36;font-weight:bold;">
                <?php echo ' ' . Prices::getPriceDisplay(Constants::USER_CLIENT, Prices::HOURLY_PRICE, NULL); ?> 
            </span>/ hour
            <?php
        }
        ?>
    </div>
</div>
