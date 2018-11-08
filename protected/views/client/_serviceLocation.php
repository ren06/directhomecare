<?php
echo $form->hiddenField($serviceLocation, "[$index]id");

$radioId = 'radio_location' . $index;
$serviceLocationId = $serviceLocation->id;
if ($count > 1) {

    $checked = Session::serviceLocationIsSelected($serviceLocationId);

    echo CHtml::radioButton('radio_location', $checked, array('class' => 'radio-location', 'value' => $serviceLocationId, 'id' => $radioId));
}

// echo Chtml::label(Yii::t('texts', 'HEADER_LOCATION'), $radioId);

echo $form->textField($serviceLocation, "[$index]address_line_1", array('maxlength' => 50, 'placeholder' => 'Address line 1'));
echo $form->error($serviceLocation, "address_line_1", array('class' => 'rc-error'));

echo $form->textField($serviceLocation, "[$index]address_line_2", array('maxlength' => 50, 'placeholder' => 'Address line 2'));
echo $form->error($serviceLocation, "address_line_2", array('class' => 'rc-error'));

echo $form->textField($serviceLocation, "[$index]city", array('maxlength' => 50, 'placeholder' => 'City'));
echo $form->error($serviceLocation, 'city', array('class' => 'rc-error'));

echo $form->textField($serviceLocation, "[$index]post_code", array('maxlength' => 50, 'placeholder' => 'Post code'));
echo $form->error($serviceLocation, 'post_code', array('class' => 'rc-error'));
?>

<h4><?php echo Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_LOCATION'); ?></h4>
<p><?php echo Yii::t('texts', 'NOTE_OPTIONAL_ADDITIONAL_INFORATION_MAX_255'); ?></p>
<?php
echo $form->textArea($serviceLocation, "[$index]explanation", array('style' => 'min-height:7em'));
// echo $form->error($serviceLocation, "[$index]explanation");
?>
