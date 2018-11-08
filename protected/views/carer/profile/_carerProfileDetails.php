<?php
//byRC: this renderPatial has 4 types defined by $carerProfileType: admin, long, medium, short
$carerId = $carer->id;

if ($view == Constants::CARER_PROFILE_VIEW_ADMIN) {

    //ADMIN VIEW
    if (isset($clientId)) {
        $relation = $carer->getClientRelation($clientId);

        if (isset($relation)) {
            if ($relation->relation == ClientCarerRelation::RELATION_FAVOURITE) {
                echo CHtml::hiddenField('carer_favourite_' . $carerId, $carerId);
            } elseif ($relation->relation == ClientCarerRelation::RELATION_SELECTED) {
                echo CHtml::hiddenField('carer_selected_' . $carerId, $carerId);
            }
            $relationText = $relation->getLabel();
        } else {
            $relationText = 'No relation';
        }
    } else {
        $relationText = null;
    }

    $ageGroups = $carer->ageGroups;
    $ageGroupAll = '';

    foreach ($ageGroups as $ageGroup) {
        $ageGroupAll .= $ageGroup->age_group . ', ';
    }

    $conditions = $carer->carerConditions;
    $conditionsAll = '';
    foreach ($conditions as $condition) {
        $conditionsAll = $conditionsAll . $condition->id_condition . ', ';
    }

    $photo = $carer->getPhotoForClient();
    if (isset($photo)) {
        if ($view == Constants::CARER_PROFILE_VIEW_GUEST) {
            echo $photo->showImageForGuest('rc-image-profile');
        } else {
            echo $photo->showImageForClient('rc-image-profile');
        }
    } else {
        echo '<img src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/svgs/fi-torso.svg">';
    }
    echo '<br>';
    echo $carer->fullName . '<br>';
    echo 'ID: ' . $carerId . '<br>';
    if (isset($relationText)) {
        echo $relationText . '<br>';
    }
    echo 'Score: ' . $carer->overall_score . '<br>';
    echo $carer->getGenderLabel() . '<br>';
    echo 'Age group: ' . $ageGroupAll . '<br>';
    echo 'Conditions: ' . $conditionsAll . '<br>';
} else {
    ?>
    <div class="row">
        <div class="large-4 medium-4 small-4 columns">
            <?php
            //CLIENT VIEW
            //Photo
            $photo = $carer->getPhotoForClient();
            if (isset($photo)) {
                if ($view == Constants::CARER_PROFILE_VIEW_GUEST) {

                    echo $photo->showImageForGuest('rc-image-profile-small');
                } else {

                    echo $photo->showImageForClient('rc-image-profile-small');
                }
            } else {
                echo '<img src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/svgs/fi-torso.svg">';
            }
            ?>
        </div>
        <div class="large-8 medium-8 small-8 columns">
            <p style="margin:0">
                <b>
                    <?php
                    echo '<span style="color:#C36">' . $carer->first_name;
                    if ($view == Constants::CARER_PROFILE_VIEW_ADMIN || $carerProfileType == 'long') {
                        echo ' ' . $carer->last_name . '</span>';
                    } else {
                        echo '</span> - ' . (isset($carer->address->city) ? $carer->address->city : "");
                    }
                    ?>
                </b>
                <br>
                <?php
                //  if (isset($clientId)) {
                //      if ($carer->isUsedBefore($clientId)) {
                //          echo '<img class="rc-fb-icon" title="' . Yii::t('texts', 'ALT_CARER_USED_BEFORE') . '" src="' .  Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/icon-relation.png"/>';
                //      }
                //  }
                ?>
                <?php
                echo Yii::t('texts', 'LABEL_CUSTOMER_RATING') . ': ';
                $starRating = '<i class="fi-star" style="color:#ce0069"></i>';
                $numberStars = $carer->getOverallRating();
                for ($i = 0; $i < $numberStars; $i++) {
                    echo $starRating;
                }
                echo '<br>';
                // $string = $carer->nationalityLabel;
                // $trimedNationality = (strlen($string) > 10) ? substr($string, 0, 8) . '...' : $string;
                // . $trimedNationality . ', ' . $carer->ageLabel . '<br>';
                if ($carer->isIdentityVerified()) {
                    echo '<i class="fi-check" style="color:#ce0069"></i> ' . Yii::t('texts', 'LABEL_IDENTITY_VERIFIED');
                }

                // if ($carer->isBackgroundChecked()) {
                //    echo '<img alt="Check" class="rc-fb-icon" title="' . Yii::t('texts', 'ALT_VERIFIED') . '" src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/icon-tick.png"/>' . Yii::t('texts', 'LABEL_NO_CRIMINAL_RECORDS');
                // }
                ?>
            </p>

            <?php
            //Phone number
            if ($carerProfileType == 'long') {
                echo '<p><b>' . Yii::t('texts', 'LABEL_PHONE_NUMBER') . '&#58;&#32;' . $carer->mobile_phone . '</b>' . '</p>';
            }

            if ($carerProfileType == 'long' || $carerProfileType == 'medium') {
                echo '<p>';
                $documentsTexts = $carer->profileDisplayAllDocuments();
                foreach ($documentsTexts as $documentsText) {
                    echo '<img class="rc-fb-icon-certificate" title="' . Yii::t('texts', 'ALT_CERTIFICATE_VERIFIED') . '" src="' . Yii::app()->request->baseUrl . '/images/icon-certificate.png"/>' . $documentsText . '<br>';
                }
                echo '</p>';

                echo '<p class="rc-fb-motivation-text">' . $carer->getPersonalityTextForClient() . '</p>';
                echo '<p class="rc-fb-motivation-text">' . $carer->getMotivationTextForClient() . '</p>';
            }
        }
        ?>
    </div>
</div>