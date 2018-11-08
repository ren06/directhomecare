<h2>Give Compensation to Carer: <?php echo $carer->fullName ?> (ID: <?php echo $carer->id ?>)</h2>
Balance: <?php echo $carer->getCreditBalance()->text ?>
<br><br>

<?php
echo CHtml::beginForm();

echo CHtml::label('Number of hours', '');
echo ' ';
echo CHtml::dropDownList('hours', '', array(1 => 1, 2 => 2, 3 => 3, 4 => 4));
echo ' x ';
echo Prices::getPrice(Constants::USER_CARER, 'hourly_price')->text;

echo '<br><br>';

echo 'For mission:';

$missions = Mission::getMissionsForLastMonths(2);
$options = array();
foreach ($missions as $mission) {
    $options[$mission->id] = $mission->displayForCarerCompensation();
}

echo CHtml::dropDownList('missionId', '', $options);
echo CHtml::submitButton();

echo CHtml::endForm();
?>
