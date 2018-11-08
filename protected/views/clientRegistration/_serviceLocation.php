
<div class="rc-module-bar">
    <div class="rc-module-name">
        <?php echo Yii::t('texts', 'HEADER_LOCATION'); ?>
    </div>
</div>
<div class="rc-module-inside">
    <table  id="<?php echo $index ?>service_location">   
        <?php $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field'); ?>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]address_line_1"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]address_line_1", $htmlOptions); ?></td>
            <td class="rc-cell3"><?php echo $form->error($serviceLocation, "address_line_1", array('class' => 'rc-error')); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]address_line_2"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]address_line_2", $htmlOptions); ?></td>
            <td class="rc-cell3"><?php echo $form->error($serviceLocation, "address_line_2", array('class' => 'rc-error')); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]city"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]city", array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
            <td class="rc-cell3"><?php echo $form->error($serviceLocation, 'city', array('class' => 'rc-error')); ?></td>
        </tr>
        <tr>
            <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]post_code"); ?></td>
            <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]post_code", array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
            <td class="rc-cell3"><?php echo $form->error($serviceLocation, 'post_code', array('class' => 'rc-error')); ?></td>
        </tr>
    </table>
    <h3><?php echo Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_LOCATION'); ?><?php // byRC echo $form->labelEx($serviceLocation, "[$index]explanation");    ?></h3>
    <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_OPTIONAL_ADDITIONAL_INFORATION_MAX_255'); ?></p>
    <div id="<?php //byRC echo $note_errors      ?>" class="rc-error">
        <?php echo $form->error($serviceLocation, "[$index]explanation"); ?>
    </div>
    <?php echo $form->textArea($serviceLocation, "[$index]explanation", array('class' => 'rc-textarea-notes')); ?>
    <?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
</div>
<script type="text/javascript">
    $(".service_location-remove<?php echo $index; ?>").click(function() {
        $.ajax({
            success: function(html) {
                $("#<?php echo $index; ?>service_location").remove();
            },
            type: 'get',
            url: '<?php echo $this->createUrl('removeServiceLocation'); ?>',
            data: {
                index: <?php echo $index; ?>
            },
            cache: false,
            dataType: 'html'
        });
    });
</script>