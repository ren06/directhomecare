<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_VISIT_DETAILS');
$this->pageSubTitle = 'All you need to know';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        $this->renderPartial('/common/_backTo', array('scenario' => $scenario));
        echo Yii::t('texts', 'SEPARATOR_BUTTON');
        echo CHtml::button(Yii::t('texts', 'BUTTON_PRINT'), array('onClick' => 'window.print()', 'class' => 'rc-button'));
        //echo CHtml::link(Yii::t('texts', 'BUTTON_SAVE_AS_PDF'), $this->createAbsoluteUrl('index'), array('class' => 'rc-linkbutton'));
        ?>        
    </div>
</div>
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <div class="row">
            <div class="large-6 medium-6 small-12 columns">
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <h4><?php echo Yii::t('texts', 'HEADER_VISIT_REFERENCE'); ?></h4>
                        <p>
                            <?php echo BusinessLogic::getReference($mission); ?>
                            - 
                            <?php echo $mission->getTypeLabel(); ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <h4><?php echo Yii::t('texts', 'HEADER_DATES'); ?></h4>
                        <p>
                            <?php echo Calendar::calculate_Duration_DisplayAll($mission->start_date_time, $mission->end_date_time); ?>
                            <?php echo Calendar::convert_DBDateTime_DisplayDateTimeText($mission->start_date_time, true, '&#160;', true) . '&#160;' . Yii::t('texts', 'LABEL_UNTIL') . '&#160;' . Calendar::convert_DBDateTime_DisplayDateTimeText($mission->end_date_time, true, '&#160;', true); ?>
                            <?php
                            echo $mission->displayAbortedSlots(Yii::app()->user->roles, false);
                            echo $mission->displayCancel(Constants::USER_CLIENT);
                            ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 medium-6 small-12 columns">
                        <h4><?php echo Yii::t('texts', 'HEADER_CARER'); ?></h4>
                        <?php
                        $carer = $mission->getAssignedCarer();
                        if (isset($carer)) {
                            $carerProfileType = 'long';
                            $params = array('carer' => $carer, 'carerProfileType' => $carerProfileType, 'view' => Constants::CARER_PROFILE_VIEW_SELECT_TEAM);
                            if (isset($clientId)) {
                                $params['clientId'] = $clientId;
                            }
                            echo $this->renderPartial('application.views.carer.profile._carerProfileDetails', $params);
                        } else {
                            echo '<img alt="Photo" class="rc-image-profile" src="' . Yii::app()->request->baseUrl . '/images/profile-blank.jpg"/>';
                            echo '<div class="rc-fb-nexttoimage">' . Yii::t('texts', 'STATUS_CARER_NOT_YET_ASSIGNED') . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_IT_TAKES_UP_TO_X_DAYS_TO_ASSIGN_CARER', array('{days}' => BusinessRules::getDelayToFindCarerInDays()))) . '</div>';
                        }
                        ?>  
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <h4><?php echo Yii::t('texts', 'HEADER_USERS'); ?></h4>
                        <p>
                            <?php echo $mission->displayServiceUsersNameConditionsHTML(true); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="large-6 medium-6 small-12 columns">
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <h4><?php echo Yii::t('texts', 'HEADER_MAP'); ?></h4>
                        <?php UIServices::showMissionMap($mission); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <h4><?php echo Yii::t('texts', 'HEADER_LOCATION'); ?></h4>
                        <p>
                            <?php echo $mission->serviceLocation->display(); ?>
                            <?php echo '<br /><span class="rc-note">' . Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_LOCATION') . '&#58;</span><br />'; ?>
                            <?php
                            if (isset($mission->serviceLocation->explanation) && $mission->serviceLocation->explanation != '') {
                                echo $mission->serviceLocation->explanation;
                            } else {
                                echo Yii::t('texts', 'LABEL_NO_NOTES');
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <h4><?php echo Yii::t('texts', 'HEADER_NOTES'); ?></h4>
        <p>
            <?php echo Yii::t('texts', 'CLIENT_SERVICE_DETAILS_CONTENT', array('{daysToGiveFeedback}' => BusinessRules::getDelayToGiveFeedbackInDays())); ?>
        </p>
    </div>
</div>