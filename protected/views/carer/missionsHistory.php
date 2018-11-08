<style type="text/css">
    #mymissions{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php if (Yii::app()->params['test']['showSpec'] == true) { ?>
    <b>Shifts history Table</b><br />
    Display shifts if: shift is CREDITED<br />
    <b>Status</b><br />
    if shift is CANCELLED_BY_CLIENT: "Cancelled by client"<br />
    elseif shift is CANCELLED_BY_ADMIN: "Cancelled by admin"<br />
    elseif shift is SOLVED_COMPLAINT: "Completed" + Complaint-status<br />
    else (no complaint ever opened): "Completed"<br />
    <b>Buttons</b><br />
    if shift is CANCELLED_BY_CLIENT: "Details" | "Invoice"<br />
    elseif shift is CANCELLED_BY_ADMIN: "Details" | "Invoice"<br />
    elseif shift is SOLVED_COMPLAINT: "Details" | "Invoice" | "Complaint"<br />
    else (no complaint ever opened): "Details" | "Invoice"<br />
<?php } ?>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_MISSIONS_HISTORY');
$this->pageSubTitle = 'List of shifts completed';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <?php $this->renderPartial('_myMissionsMenu', array('selectedMenu' => 'historyMissions')); ?>
    </div>
</div>
<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <?php
        if (count($dataProvider->getData()) > 0) {
            $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider' => $dataProvider,
                'id' => 'transactions',
                'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
                'template' => '{items}{pager}',
                'columns' => Tables::getCarerHistoryMissionsColumnConfig($scenario),
                    )
            );
        } else {
            echo '<p><b>' . Yii::t('texts', 'TABLES_NO_MISSIONS') . '</b></p>';
        }
        ?>
    </div>
</div>