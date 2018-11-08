<style type="text/css">
    #mymissions{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_MISSION_DETAILS');
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
    <h3><?php echo Yii::t('texts', 'HEADER_MISSION_REFERENCE') . '&#58;&#160;'; ?><?php echo BusinessLogic::getReference($mission); ?></h3>
    <div class="rc-container-60">
        <div class="rc-container-30-float-left">
            <div class="rc-module-bar">
                <div class="rc-module-name">
                    <?php echo Yii::t('texts', 'HEADER_DATES_AND_WAGE'); ?>
                </div>
            </div>
            <div class="rc-module-inside">
                <p>
                    <?php echo $mission->getTypeLabel() . '&#160;&#160;&#45;&#160;&#160;' . Calendar::calculate_Duration_DisplayAll($mission->start_date_time, $mission->end_date_time) . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . $mission->booking->getUnitPriceLabel(Constants::USER_CARER); ?>
                </p>
                <p>         
                    <?php echo Yii::t('texts', 'LABEL_WAGE') . '&#160;&#058;&#160;' . $mission->getOriginalTotalPrice(Constants::USER_CARER)->text; ?>
                </p>
                <p>
                    <?php echo Calendar::convert_DBDateTime_DisplayDateTimeText($mission->start_date_time, true, '&#160;', true) . '&#160;' . Yii::t('texts', 'LABEL_UNTIL') . '&#160;' . Calendar::convert_DBDateTime_DisplayDateTimeText($mission->end_date_time, true, '&#160;', true); ?>
                </p>
                <?php
                echo $mission->displayAbortedSlots(Yii::app()->user->roles, false);
                echo $mission->displayCancel(Constants::USER_CARER);
                ?>
            </div>
            <div class="rc-module-bar">
                <div class="rc-module-name">
                    <?php echo Yii::t('texts', 'HEADER_CLIENT'); ?>
                </div>
            </div>
            <div class="rc-module-inside">
                <p>
                    <?php
                    $client = $mission->booking->client;
                    echo $client->fullName;
                    ?>
                </p>
                <?php
                $mobilePhone = trim($client->mobile_phone);

                if (isset($mobilePhone) && $mobilePhone != '') {
                    $text = $mobilePhone . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_DO_NOT_HESITATE_TO_CALL_THE_CLIENT'));
                } else {
                    $text = Yii::t('texts', 'LABEL_NO_NUMBER');
                }
                ?>
                <p>
                    <?php echo Yii::t('texts', 'LABEL_PHONE_NUMBER') . '&#58;&#32;' . $text; ?>
                </p>
            </div>
            <div class="rc-module-bar">
                <div class="rc-module-name">
                    <?php echo Yii::t('texts', 'HEADER_USERS'); ?>
                </div>
            </div>
            <div class="rc-module-inside">
                <?php echo $mission->displayServiceUsersNameConditionsHTML(); ?>
            </div>
            <div class="rc-module-bar">
                <div class="rc-module-name">
                    <?php echo Yii::t('texts', 'HEADER_LOCATION'); ?>
                </div>
            </div>
            <div class="rc-module-inside">
                <p>
                    <?php echo $mission->serviceLocation->display(); ?>
                    <?php echo '<br />' . Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_LOCATION') . '&#58;&#32;'; ?>
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
        <div class="rc-container-29-float-right">
            <div class="rc-module-bar">
                <div class="rc-module-name">
                    <?php echo Yii::t('texts', 'HEADER_MAP'); ?>
                </div>
            </div>
            <?php UIServices::showMissionMap($mission); ?>
            <div class="rc-module-bar">
                <div class="rc-module-name">
                    <?php echo Yii::t('texts', 'HEADER_NOTES'); ?>
                </div>
            </div>
            <div class="rc-module-inside">
                <p>
                    <?php echo Yii::t('texts', 'NOTE_THIS_MISSION_HAS_BEEN_CONFIRMED_IT_IS_HIGHLY_IMPORTANT'); ?>
                </p>
            </div>
        </div>
        <div style="clear:both"></div>
    </div>
</div>