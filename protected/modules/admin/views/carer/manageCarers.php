

<h1>Manage Carers</h1>

<div class="search-form">
    <?php
    $this->renderPartial('/carer/_search', array(
        'model' => $model,
    ));
    ?>
</div>

<?php
$this->renderPartial('/carer/_carersTable', array(
    'dataProvider' => $model->search(),
    'model' => $model,
));
?>


