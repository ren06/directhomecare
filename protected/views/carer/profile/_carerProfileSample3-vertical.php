<?php
$carerProfileType = 'short';
$view = Constants::CARER_PROFILE_VIEW_GUEST;
?>
<div class="rc-fb-container-sample-vertical">
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'HEDEAR_TO_SEE_CARERS_REGISTER'); ?>
        </div>
    </div>
    <table>
        <?php
       $carers = Carer::getRandomActiveCarers($numberProfiles);
       foreach($carers as $carer){
           ?>
            <tr>
                <td class="rc-fb-cell" style="border-width:0!important">
                    <?php $this->renderPartial('application.views.carer.profile._carerProfileDetails', array('carerProfileType' => $carerProfileType, 'carer' => $carer, 'view' => $view)); ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>