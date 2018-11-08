
<?php
if ($all) {
    ?>
    <h2>Showing all Carers with at least one document</h2>
    <?php
} else {
    ?>
    <h2>Only showing Carers with at least one document waiting for approval</h2>
    <?php
}
?>

<?php
$dataProvider = new CActiveDataProvider(Carer::model()->resetScope());
$dataProvider->setData($carers);

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'text-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'fullName',
        array(
            'class' => 'CButtonColumn',
            'headerHtmlOptions' => array('style' => 'width:90px'),
            'template' => '{viewDetails}',
            'buttons' => array(
                'viewDetails' => array('label' => 'View',
                    'url' => 'Yii::app()->createUrl("admin/carerAdmin/approveCarerDocuments", array("carerId"=>$data->id))',
                ),
            ),
        ),
    )
        )
);
?>

<div id="hook" style="display:none">
    <div id="dialog"></div>
</div>