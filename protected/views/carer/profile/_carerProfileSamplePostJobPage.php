<?php
$carerProfileType = 'short';
$view = Constants::CARER_PROFILE_VIEW_GUEST;

$i = 1;
$carers = Carer::getRandomActiveCarers(6);
foreach ($carers as $carer) {
    ?>
    <div class="large-4 medium-4 small-12 columns">
        <?php $this->renderPartial('application.views.carer.profile._carerProfileDetails', array('carerProfileType' => $carerProfileType, 'carer' => $carer, 'view' => $view)); ?>
    </div>
    <?php
    $i = $i + 1;
}
?>