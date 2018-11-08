<?php
//MENU
$carerId = $carer->id;
$selectedCarers = Session::getSelectedCarers();
if (!isset($selectedCarers) || count($selectedCarers) == 0) {
    $checked = false;
} else {
    $checked = in_array($carerId, $selectedCarers);
}

if ($isNewsletter) {
    //use inline CSS

    $cssFb2Profile = " style='background-color:#EEE;margin:1em 0 0 0;padding:1em 0 1em 0;' ";
    //rc-fb2-profile
    //background-color:#EEE;margin:1em 0 0 0;padding:1em 1em 1em 1em;

    $cssImageProfileSmall = " style='height:auto !important;border:1px solid #666;width:5em;' ";
    //rc-image-profile-small
    //height:auto !important;border:1px solid #666;width:5em;

    $cssNameAndCity = " style='display:block;font-family:Liberationsansbold, 'Arial Bold', sans-serif;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;max-width:10.5em;' ";
    //rc-fb2-nameandcity
    //display:block;font-family:Liberationsansbold, 'Arial Bold', sans-serif;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;max-width:10.5em;

    $cssFbIcon = "style='padding:4px 4px 0 0;height:15px;' ";
    //rc-fb-icon
    //padding:4px 4px 0 0;height:15px;

    $cssFbIconStar = " style='padding:1px 0 0 2px;height:18px;' ";
    //rc-fb-icon-star
    //padding:1px 0 0 2px;height:18px;

    $cssButton = "background-color:#66c;color:#fff;font-family:arial;font-size:16px;font-weight:bold;line-height:32px;margin:0px 4px;padding:6px 9px;text-align:center;text-decoration:none";
    //rc-button
    //background-color:#66c;color:#fff;font-family:arial;font-size:16px;font-weight:bold;line-height:32px;margin:0px 4px;padding:6px 9px;text-align:center;text-decoration:none
} else {
    //use main.css
    if ($checked) {
        $cssFb2Profile = " class='rc-fb2-profile selected' ";
    } else {
        $cssFb2Profile = " class='rc-fb2-profile' ";
    }
    $cssImageProfileSmall = " class='rc-image-profile-small' ";
    $cssNameAndCity = " class='rc-fb2-nameandcity' ";
    $cssFbIcon = " class='rc-fb-icon' ";
    $cssFbIconStar = " class='rc-fb-icon-star' ";
    $cssButton = "rc-button";
}
//$attributeName = 'selected_carers_' . $carerId;
?>
<div <?php echo $cssFb2Profile; ?>>
    <table width="100%">
        <tr>
            <td  style="width:6em">
                <?php
                //CARER DETAILS
                $photo = $carer->getPhotoForClient();
                if (isset($photo)) {
                    if ($isNewsletter) {
                        echo $photo->showImageForGuest('', 'height:auto !important;border:1px solid #666;width:5em;');
                    } else {
                        echo $photo->showImageForGuest('rc-image-profile-small');
                    }
                } else {
                    echo '<img ' . $cssImageProfileSmall . ' src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/profile-blank.jpg"/>';
                }
                ?>
            </td>
            <td>
                <p style="margin:0;">
                    <span <?php echo $cssNameAndCity ?>>
                        <span style="color:#C36">
                            <?php echo $carer->first_name; // . ' - ' . $carerId;    ?>
                        </span>
                        <?php
                        if (isset($carer->address)) {
                            echo ' - ' . $carer->address->city;
                        }
                        ?>
                    </span>
                    <?php
                    // $string = $carer->nationalityLabel;
                    // $trimedNationality = (strlen($string) > 11) ? substr($string, 0, 9) . '...' : $string;
                    // echo $trimedNationality . ', ' . $carer->ageLabel;
                    echo Yii::t('texts', 'LABEL_CUSTOMER_RATING') . ': ';
                    $starRating = '<img alt="Star rating"' . $cssFbIconStar . 'src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/icon-star.png"/>';
                    $numberStars = $carer->getOverallRating();
                    for ($i = 0; $i < $numberStars; $i++) {
                        echo $starRating;
                    }
                    echo '<br />';
                    if ($carer->isIdentityVerified()) {
                        if (!$isNewsletter) {
                            echo '<img alt="Check" ' . $cssFbIcon . ' title="' . Yii::t('texts', 'ALT_VERIFIED') . '" src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/icon-tick.png"/>' . Yii::t('texts', 'LABEL_IDENTITY_VERIFIED') . '<br />';
                        } else {
                            echo '<img alt="Check" style="padding:4px 4px 0 0" height="15px" title="' . Yii::t('texts', 'ALT_VERIFIED') . '" src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/icon-tick.png"/>' . Yii::t('texts', 'LABEL_IDENTITY_VERIFIED') . '<br />';
                        }
                    }
                    if ($carer->isBackgroundChecked()) {

                        if (!$isNewsletter) {
                            echo '<img alt="Check" ' . $cssFbIcon . ' title="' . Yii::t('texts', 'ALT_VERIFIED') . '" src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/icon-tick.png"/>' . Yii::t('texts', 'LABEL_NO_CRIMINAL_RECORDS') . '<br />';
                        } else {
                            echo '<img alt="Check" style="padding:4px 4px 0 0" height="15px" title="' . Yii::t('texts', 'ALT_VERIFIED') . '" src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/icon-tick.png"/>' . Yii::t('texts', 'LABEL_NO_CRIMINAL_RECORDS') . '<br />';
                        }
                    }
                    ?>
                </p>
            </td>
            <td style="text-align:right;">
                <?php if ($showButton) {
                    ?>
                    <span style="color:#C36;font-weight:bold;">
                        <?php echo Prices::getPriceDisplay(Constants::USER_CLIENT, Prices::HOURLY_PRICE, NULL); ?> 
                    </span>/ hour
                    <div style="padding-top:1em;">
                        <?php
                        //echo CHtml::checkBox($attributeName, $checked, array('class' => 'selectCarerChecxbox'));
                        //echo CHtml::label(Yii::t('texts', 'LINK_SELECTED'), $attributeName, array('class' => 'rc-link'));
                        if ($isNewsletter) {
                            echo CHtml::link(Yii::t('texts', 'BUTTON_BOOK'), $this->createAbsoluteUrl('/site/home'), array('style' => $cssButton));
                        } else {
                            echo CHtml::button(Yii::t('texts', 'BUTTON_BOOK'), array('class' => $cssButton, 'onClick' => "js:selectCarer($carerId)"));
                        }
                        ?>
                    </div>
                    <div style="padding-top:1em;">
                        <?php
                        if ($isNewsletter) {
                            // echo CHtml::link(Yii::t('texts', 'BUTTON_CONTACT'), $this->createAbsoluteUrl('/site/home'), array('style' => $cssButton));
                        } else {
                            echo CHtml::button(Yii::t('texts', 'BUTTON_CONTACT'), array('class' => 'rc-button-white-border', 'onClick' => "js:showCarerMessage(this)"));
                        }
                        ?>
                    </div>
                <?php } ?>
            </td>
        </tr>
    </table>
    <table>
        <?php
        $documentsTexts = $carer->profileDisplayAllDocuments();
        if (count($documentsTexts) > 0) {
            ?>
            <tr>
                <td style="width:6em">
                    <p>
                        <?php echo '<b>Certificates:</b><br />'; ?>
                    </p>
                </td>
                <td>
                    <p>
                        <?php
                        foreach ($documentsTexts as $documentsText) {
                            // echo '<img class="rc-fb-icon-certificate" title="' . Yii::t('texts', 'ALT_CERTIFICATE_VERIFIED') . '" src="' . Yii::app()->request->baseUrl . '/images/icon-certificate.png"/>';
                            echo $documentsText . '<br />';
                        }
                        ?>
                    </p>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td style="width:6em">
                <p>
                    <b>Why I care:</b>
                </p>
            </td>
            <td>
                <p>
                    <?php echo $carer->getMotivationTextForClient(); ?>
                </p>
            </td>
        </tr>
        <tr>
            <td style="width:6em">
                <p>
                    <b>What I like:</b>
                </p>
            </td>
            <td>
                <p>
                    <?php echo $carer->getPersonalityTextForClient(); ?>
                </p>
            </td>
        </tr>
    </table>
    <?php
    if (!$isNewsletter) {
        
        //use may have entered their email before
        $email = Session::getMessageMeEmail();
        if(!isset($email)){
            $email = '';
        }
        ?>  

        <div id="error" class="flash-error" style="display:none"></div>
        <div id="success" style="display:none" class="flash-success"></div>
        <div  style="display:none" class="message_area">
            <table>
                <tr>
                    <td class="rc-cell1"><?php echo CHtml::label('Your email ', ''); ?></td>
                    <td class="rc-cell2"><?php echo CHtml::textField('email', $email, array('class' => 'rc-field')); ?></td>
                </tr>
                <tr>
                    <td class="rc-cell1"><?php echo CHtml::label('Your message ', ''); ?></td>
                    <td class="rc-cell2"><?php echo CHtml::textArea('text', '', array('class' => 'rc-textarea-notes')); ?></td>
                </tr>
            </table>
            <div class="rc-container-button">
                <div class="buttons">
                    <?php echo CHtml::button('SEND', array('onClick' => "js:sendMessage(this, $carerId)", 'class' => 'rc-button', 'style' => 'width:90px', 'name' => 'send_' . $carerId)); ?>
                    <?php echo CHtml::button('CANCEL', array('onClick' => 'js:$(this).closest(".message_area").hide()', 'class' => 'rc-button', 'style' => 'width:90px', 'name' => 'cancel_' . $carerId)); ?>
                </div>  
                <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
            </div>
        </div>
        <?php
    }
    ?>
</div>