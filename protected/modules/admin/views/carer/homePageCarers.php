<?php

echo CHtml::beginForm();

$pageBreak = 5;
$i = 0;

echo '<table>';

foreach ($carers as $carer) {

    $photo = $carer->getPhotoForClient();

    if (isset($photo)) {

        if ($i == 0) {
            echo '<tr>';
            $open = true;
        }

        $checkBoxId = 'checkBoxId' . $carer->id;
        $homepage = $carer->show_homepage;
        $checked = ($homepage == 1);
        echo '<td>';
        echo CHtml::checkBox('select_carer_' . $carer->id, $checked, array('value' => $carer->id, 'id' => $checkBoxId));
        echo CHtml::label($carer->fullName, $checkBoxId);
        echo '<br>';
        echo $photo->showImageForClient('rc-image-profile');
        echo '</td>';
        if ($i == $pageBreak - 1) {
            $i = 0;
            echo '</tr>';
            $open = false;
        }

        $i++;
    }
}

if ($open) {
    echo '</tr>';
}
echo '</table>';
echo '<br><br>';

echo CHtml::submitButton('SAVE');

echo CHtml::endForm();
?>
