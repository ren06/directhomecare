<?php
$carerProfileType = 'short';
$view = Constants::CARER_PROFILE_VIEW_GUEST;
?>
<table class="rc-fb-table">
    <tr>
        <?php
        $carers = Carer::getRandomActiveCarers(3);
        foreach ($carers as $carer) {
            ?>
            <td class="rc-fb-cell-sample"><?php $this->renderPartial('application.views.carer.profile._carerProfileDetails', array('carerProfileType' => $carerProfileType, 'carer' => $carer, 'view' => $view)); ?></td>
        <?php } ?>
    </tr>
</table>