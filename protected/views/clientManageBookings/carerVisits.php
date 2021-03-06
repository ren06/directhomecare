<style type="text/css">
    #mybookings{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = 'Your visits';
$this->pageSubTitle = 'The detail of all visists within your bookings';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myBookingsMenu', array('selectedMenu' => 'carerVisits')); ?>
    </div>
</div>
<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_carersVisits', array('dataProvider' => $dataProvider, 'scenario' => $scenario)); ?>
    </div>
</div>