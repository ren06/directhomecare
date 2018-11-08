
<?php
//test clients and their service users/condition
echo CHtml::form();
echo CHtml::dropDownList('clientId', $clientId, $clients, array('submit' => 'carersSelectionClient'));
echo CHtml::endForm();
?>
<br><br>

<?php echo CHtml::beginForm('', 'POST', array('id' => $formId)); ?>
<div id="carerProfiles">

    <?php
    $this->renderPartial('application.views.carer.profile._carerProfilesMain', array('carers' => $carers, 'clientId' => $clientId, 'showMale' => $showMale, 'showFemale' => $showFemale,
        'nationality' => $nationality, 'nationalities' => $nationalities, 'carersNotWanted' => $carersNotWanted, 'formId' => $formId, 'view' => $view, 'maxDisplayCarers' => 100,
        'showGenderSelection' => true));
    ?>
</div>

<?php
echo CHtml::submitButton("CONTINUE >>", array('submit' => array('/clientNewBooking/selectCarers')));

echo CHtml::endForm();
?>

