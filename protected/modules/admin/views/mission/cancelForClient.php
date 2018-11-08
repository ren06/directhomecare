<?php

echo CHtml::beginForm('', 'POST');

echo CHtml::hiddenField('cancel', $mission->id);
echo '<h2>Cancel Mission ' . $mission->id . ' for client ' . $client->fullName . '</h2>';
echo '<br>';
echo $mission->displayMissionShort();
echo '<br><br><hr>';

echo $text;

echo '<hr>';

echo CHtml::submitButton('Cancel Mission');

echo CHtml::endForm();
?>
