<?php ?>

<h1>Manage Clients</h1>

<div class="search-form">
    <?php
    $this->renderPartial('/client/_search', array(
        'model' => $model,
    ));
    ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'client-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'first_name',
        'last_name',
        'email_address',
        'created',
        'wizard_completed',
        array(
            'class' => 'ButtonColumn', // can be omitted, default
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:144px'),
            'template' => '{select}',
            'buttons' => array(
                'select' => array(
                    'label' => 'Select',
                    'url' => 'Yii::app()->createUrl("/admin/clientAdmin/viewClient", array("id"=>$data->id))',
                    'options' => array('class' => 'applyButton rc-linkbutton-small'),
                ),
            ),
        ),
    ),
));
?>
