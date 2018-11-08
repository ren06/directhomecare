<?php
foreach ($carers as $carer) {
    ?>
    <table width="100%" style="padding-top:32px">
        <tr>
            <td style="width:25%" valign="top">
                <?php
                //CARER DETAILS
                $photo = $carer->getPhotoForClient();
                if (isset($photo)) {
                    try {
                        echo $photo->showImageForGuest('', 'width:80px;height:100px;border:1px solid #666;', $consoleCommand);
                    } catch (CException $e) {
                        echo '<img style="width:80px;height:100px;border:1px solid #666;" src="' . Yii::app()->params['server']['fullUrl'] . '/images/profile-blank.jpg"/>';
                    }
                } else {
                    // echo '<img style="width:80px;height:100px;border:1px solid #666;" src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/profile-blank.jpg"/>';

                    echo '<img style="width:80px;height:100px;border:1px solid #666;" src="' . Yii::app()->params['server']['fullUrl'] . '/images/profile-blank.jpg"/>';
                }
                ?>
            </td>
            <td style="width:75%" valign="top">
                <span style="display:block;font-weight:bold;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                    <span style="color:#C36">
                        <?php echo $carer->first_name; // . ' - ' . $carerId;   ?>
                    </span>
                    <?php
                    if (isset($carer->address)) {
                        echo ' - ' . $carer->address->city;
                    }
                    ?>
                </span>
                <?php echo Yii::t('texts', 'LABEL_CUSTOMER_RATING') . ': '; ?>
                <span style="color:#C36;">
                    <?php
                    $starRating = '&#9733;';
                    $starRatingEmpty = '&#9734;';
                    $numberStars = $carer->getOverallRating();
                    for ($i = 0; $i < $numberStars; $i++) {
                        echo $starRating;
                    }
                    for ($i = 0; $i < (5 - $numberStars); $i++) {
                        echo $starRatingEmpty;
                    }
                    ?>
                </span>
                <br />
                <span style="color:#C36;font-weight:bold;">
                    <?php echo Prices::getPriceDisplay(Constants::USER_CLIENT, Prices::HOURLY_PRICE, NULL); ?>
                </span>/ hour
                <div style="padding-top:8px">
                    <?php echo CHtml::link(Yii::t('texts', 'BUTTON_BOOK'), $buttonBookUrl, array('style' => 'background-color:#66c;color:#fff;font-family:arial;font-size:16px;font-weight:bold;line-height:32px;padding:6px 9px;text-align:center;text-decoration:none')); ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
    if ($carer->isIdentityVerified()) {
        echo '<span style="color:#C36;">&#10004;</span>' . Yii::t('texts', 'LABEL_IDENTITY_VERIFIED') . '<br />';
    }
    if ($carer->isBackgroundChecked()) {
        echo '<span style="color:#C36;">&#10004;</span>' . Yii::t('texts', 'LABEL_NO_CRIMINAL_RECORDS') . '<br />';
    }

    $documentsTexts = $carer->profileDisplayAllDocuments();
    if (count($documentsTexts) > 0) {
        ?>
        <div style="padding-top:16px">
            <b>Certificates:</b><br />
            <?php
            foreach ($documentsTexts as $documentsText) {
                echo $documentsText . '<br />';
            }
            ?>
        </div>
    <?php } ?>
    <div style="padding-top:16px">
        <b>Why I care:</b><br />
        <?php echo $carer->getMotivationTextForClient(); ?>
    </div>
    <div style="padding-top:16px">
        <b>What I like:</b><br />
        <?php echo $carer->getPersonalityTextForClient(); ?>
    </div>
    <?php
}
?>