
<h2>Search Carers by postcode</h2>

<h4>Display carers according to the post code and the hourly work radius they entered</h4>
<br />
<?php
echo CHtml::beginForm('', 'GET');

echo CHtml::textField('postCode', $postCode);

echo CHtml::submitButton('Search', array('submit' => Yii::app()->createUrl("admin/carerAdmin/searchByPostCode")));

echo CHtml::endForm();

if (isset($error)) {

    echo "<br>$error<br>";
}

if (count($carers) > 0) {


    $dataProvider = new CActiveDataProvider('Carer', array(
        'pagination' => array(
            'pageSize' => 200,
        ),
    ));

    $dataProvider->setData($carers);

    $this->renderPartial('/carer/_carersTable', array(
        'dataProvider' => $dataProvider,
        'model' => new Carer(),
    ));
} else {

    if ($postCode != "" && count($carers) == 0) {

        echo "<br>No Carers found<br><br>";
    }
}
?>
