<?php
echo $form->hiddenField($serviceUser, "[$index]id");
?>

<div id="service_user<?php echo $index ?>">
    <?php
    $checkBoxId = 'checkbox_user' . $index;
    $serviceUserId = $serviceUser->id;
    if ($count > 1) {
        $checked = Session::serviceUserIsSelected($serviceUserId);

        // echo CHtml::checkBox('check_box', $checked, array('class' => 'checkbox-service-user', 'value' => $serviceUserId, 'id' => $checkBoxId));
    }
    // echo Chtml::label(Yii::t('texts', 'HEADER_SERVICE_USER'), $checkBoxId);
    ?>
    <?php
    echo CHtml::activeTextField($serviceUser, "[$index]first_name", array('maxlength' => 50, 'placeholder' => 'First name'));
    echo CHtml::error($serviceUser, "first_name", array('class' => 'rc-error'));

    echo CHtml::activeTextField($serviceUser, "[$index]last_name", array('maxlength' => 50, 'placeholder' => 'Last name'));
    echo CHtml::error($serviceUser, "last_name", array('class' => 'rc-error'));
    ?>

    <h4><?php echo Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_USER'); ?></h4>
    <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_OPTIONAL_ADDITIONAL_INFORATION_MAX_500'); ?></p>
    <?php
    echo CHtml::activeTextArea($serviceUser, "[$index]note", array('style' => 'min-height:7em'));
    // echo CHtml::error($serviceUser, "[$index]note");
    
    Yii::app()->clientScript->registerCoreScript("jquery");
    ?>
    <script type="text/javascript">
        $("#service_users-remove<?php echo $index ?>").click(function() {
            $.ajax({
                success: function(html) {
                    $("#service_user<?php echo $index ?>").remove();
                },
                type: 'get',
                url: '<?php echo $this->createUrl('removeServiceUser') ?>',
                data: {
                    index: <?php echo $index ?>
                },
                cache: false,
                dataType: 'html'
            });
        });
    </script>
</div>