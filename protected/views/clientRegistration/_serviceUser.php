<div id="service_user<?php echo $index ?>">
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'HEADER_SERVICE_USER'); ?>
        </div>
        <div class="rc-module-container-button">
            <?php echo CHtml::button(Yii::t('texts', 'BUTTON_DELETE_USER'), array('id' => 'service_users-remove' . $index, 'class' => 'rc-button-small')) ?>
        </div>
    </div>
    <div class="rc-module-inside">
        <table>       
            <?php
            $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field');
            ?>
            <tr>
                <td class="rc-cell1"><?php echo CHtml::activeLabelEx($serviceUser, "[$index]first_name"); ?></td>
                <td class="rc-cell2"><?php echo CHtml::activeTextField($serviceUser, "[$index]first_name", $htmlOptions); ?></td>
                <td class="rc-cell3"><?php echo CHtml::error($serviceUser, "first_name", array('class' => 'rc-error')); ?></td>
            </tr>
            <tr>
                <td class="rc-cell1"><?php echo CHtml::activeLabelEx($serviceUser, "[$index]last_name"); ?></td>
                <td class="rc-cell2"><?php echo CHtml::activeTextField($serviceUser, "[$index]last_name", $htmlOptions); ?></td>
                <td class="rc-cell3"><?php echo CHtml::error($serviceUser, "last_name", array('class' => 'rc-error')); ?></td>
            </tr>
            <tr>
                <td class="rc-cell1"><?php echo CHtml::activeLabelEx($serviceUser, "[$index]date_birth"); ?></td>
                <td class="rc-cell2"><?php $this->widget('DropDownDatePickerWidget', array('index' => $index, 'myLocale' => 'en_gb', 'date' => $serviceUser->date_birth, 'hideDay' => true, 'hideMonth' => true, 'htmlOptions' => array('class' => 'rc-drop'))); ?></td>
                <td class="rc-cell3"><?php echo CHtml::error($serviceUser, "date_birth", array('class' => 'rc-error')); ?></td>
            </tr>
            <tr> 
                <td class="rc-cell1"><?php echo CHtml::activeLabelEx($serviceUser, "[$index]gender"); ?></td>
                <td class="rc-cell2"><?php echo CHtml::activeRadioButtonList($serviceUser, "[$index]gender", array(1 => Yii::t('texts', 'LABEL_FEMALE'), 2 => Yii::t('texts', 'LABEL_MALE')), array('separator' => Yii::app()->params['radioButtonSeparator'])); ?></td>
                <td class="rc-cell3"><?php echo CHtml::error($serviceUser, "gender", array('class' => 'rc-error')); ?></td>
            </tr>
        </table>
        <?php
        //render service users conditions
        $this->renderPartial('application.views.condition.newConditions', array('model' => $serviceUser, 'errors' => $conditionErrors, 'index' => $index, 'isClient' => true));
        ?>
        <?php Yii::app()->clientScript->registerCoreScript("jquery") ?>
        <br />
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
</div>