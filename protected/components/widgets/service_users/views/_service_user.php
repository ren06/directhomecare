<?php
//WARNING: THIS VIEW MAY BE RENDERED FROM THE CURRENT CONTROLLER AND NOT THE WIDGET
//WHEN RENDERPARTIAL IS CALLED FROM THE CACTION DON'T USE WIDGETS PROPERTIES
?>
<div id="service_user<?php echo $index ?>">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'serviceUser-form' . $index,
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
    ?>

    <?php
    $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field');
    ?>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php
            //Display First Name - Last Name only if existing user
            if (!$newServiceUser) {
                //display the checkbox only in the new mission scenario
                if ($scenario == ServiceUsersWidget::MISSION_SCENARIO) {

                    $value = Session::serviceUserIsSelected($serviceUser->id);

                    echo CHtml::checkBox('check_box' . $index, $value, array('class' => 'main_checkbox', 'id' => 'check_box' . $index));
                }
                echo $serviceUser->fullName;
            } else {
                echo Yii::t('texts', 'HEADER_SERVICE_USER');
            }
            ?>
        </div>
        <div class="rc-module-container-button">
            <?php
            echo CHtml::button(Yii::t('texts', 'BUTTON_EDIT'), array('class' => 'rc-button-small', 'id' => 'edit' . $index));
            echo '<span id="edit' . $index . 'disabled" class="rc-linkbutton-small-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_EDIT') . '</span>';

            if (!$serviceUser->isUsedBooking()) {
                echo CHtml::button(Yii::t('texts', 'BUTTON_DELETE_USER'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'remove' . $index));
                echo '<span id="remove' . $index . 'disabled" class="rc-linkbutton-small-disabled rc-button-small-space-left" style="display:none">' . Yii::t('texts', 'BUTTON_DELETE_USER') . '</span>';
                echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'rc-button-small', 'id' => 'save' . $index));
            } else {
                echo '<span id="used' . $index . '" title="' . Yii::t('texts', 'ALT_USED_IN_ANOTHER_BOOKING_CANNOT_DELETE') . '" class="rc-linkbutton-small-disabled rc-button-small-space-left">' . Yii::t('texts', 'BUTTON_DELETE_USER') . '</span>';
                echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'rc-button-small', 'id' => 'save' . $index));
            }
            if ($newServiceUser) {

                echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'cancelNew'));
            } else {

                echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'cancel' . $index));
            }
            ?>
        </div>
    </div>
    <?php
    if ($newServiceUser) {
        ?>
        <div id="details<?php echo $index ?>" class="rc-module-inside">
            <?php
        } else {

//            if($numberUsers > 1){
            $style = 'display:none';
//            }
//            else{
//                $style = 'display:visible';
//            }
            ?>
            <div id="details<?php echo $index ?>" style="<?php echo $style ?>" class="rc-module-inside">
                <?php
            }
            ?>
            <table>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($serviceUser, "[$index]first_name"); //$form->labelEx($serviceUser, "[$index]first_name");  ?></td>
                    <td class="rc-cell2"><?php echo $form->textField($serviceUser, "[$index]first_name", $htmlOptions); //$form->textField($serviceUser, "[$index]first_name", $htmlOptions);  ?></td>
                    <td class="rc-cell3"><?php echo $form->error($serviceUser, "[$index]first_name", array('class' => 'rc-error')); //$form->error($serviceUser, "first_name", array('class' => 'rc-error'));  ?></td>
                </tr>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($serviceUser, "[$index]last_name"); ?></td>
                    <td class="rc-cell2"><?php echo $form->textField($serviceUser, "[$index]last_name", $htmlOptions); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($serviceUser, "[$index]last_name", array('class' => 'rc-error')); ?></td>
                </tr>
                <tr>
                    <td class="rc-cell1"><?php echo $form->labelEx($serviceUser, "[$index]date_birth"); ?></td>
                    <td class="rc-cell2"><?php $this->widget('DropDownDatePickerWidget', array('index' => $index, 'myLocale' => Yii::app()->params['datePickerLocation'], 'date' => $serviceUser->date_birth, 'hideDay' => true, 'hideMonth' => true, 'htmlOptions' => array('class' => 'rc-drop'))); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($serviceUser, "[$index]date_birth", array('class' => 'rc-error')); ?></td>
                </tr>
                <tr> 
                    <td class="rc-cell1"><?php echo $form->labelEx($serviceUser, "[$index]gender"); ?></td>
                    <td class="rc-cell2"><?php echo $form->radioButtonList($serviceUser, "[$index]gender", array(Constants::GENDER_FEMALE => Yii::t('texts', 'LABEL_FEMALE'), Constants::GENDER_MALE => Yii::t('texts', 'LABEL_MALE')), array('class' => '', 'separator' => Yii::app()->params['radioButtonSeparator'])); ?></td>
                    <td class="rc-cell3"><?php echo $form->error($serviceUser, "[$index]gender", array('class' => 'rc-error')); ?></td>
                </tr>
            </table>
            <?php Yii::app()->controller->renderPartial('/condition/newConditions', array('model' => $serviceUser, 'errorMessages' => $errorMessages, 'index' => $index, 'isClient' => true)); ?>
            <br />
            <br />
            <br />
            <div class="rc-module-bar-bottom" style="display:none" id="bottom_bar_<?php echo $index ?>">
                <div class="rc-module-container-button">
                    <?php
                    echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'rc-button-small', 'id' => 'save_bottom' . $index));
                    if ($newServiceUser) {

                        echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'cancelNew_bottom'));
                    } else {

                        echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'cancel_bottom' . $index));
                    }
                    ?>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div><!-- /first div (php comfuses Netbeans) -->

    <script type="text/javascript">

<?php
$php_array = array_keys(Yii::app()->session['UserIndexes']);
$js_array = json_encode($php_array);
echo "var userIndexes = " . $js_array . ";\n";
?>
        $("#save<?php echo $index ?>, #save_bottom<?php echo $index ?>").click(function() {

            $.ajax({
                success: function(html) {

                    var index = <?php echo $index ?>;

                    //clear errors (class rc-error)
                    $(".rc-error").hide();

                    if (html.indexOf("{") === 0) {

                        var json = $.parseJSON(html);

                        var formErros = json.formErrors;
                        var conditionErrors = json.conditionErrors;

                        $.each(formErros, function(key, value) {

                            $("#" + key).addClass("clsError");
                            $("#" + key + "_em_").show().html(value.toString());
                            $("label[for=" + key + "]").addClass("clsError");
                        });

                        $.each(conditionErrors, function(key, value) {
                            $("#" + key).html(value);
                            $("#" + key).show();
                        });
                    }
                    else {

                        $("#service_user" + index).replaceWith(html);
                        displayMode(index);
                        enableAllButtons(userIndexes);
                    }

                },
                type: 'post',
                url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'saveUser' . '/index/' . $index)) ?>',
                data: {
                    ServiceUser: decodeURIComponent(jQuery(this).parents("form").serialize())
                },
                cache: false,
                dataType: 'html'
            });
        });


        $("#remove<?php echo $index ?>").click(function() {
            if (confirm('<?php echo Yii::t('texts', 'WIDGETS_VIEWS_SERVICE_USER_POPUP1') ?>')) {
                $.ajax({
                    success: function(html) {

                        if (html != "") {

                            $('#errorMessage').html(html);
                        }
                        else {
                            $("#service_user<?php echo $index ?>").remove();
                        }
                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'deleteUser')) ?>',
                    data: {
                        index: <?php echo $index ?>
                    },
                    cache: false,
                    dataType: 'html'
                });
            }
        });

        $("#edit<?php echo $index ?>").click(function() {
            $.ajax({
                success: function(html) {
                    var currentIndex = <?php echo $index ?>;
                    disableAllButtons(currentIndex, userIndexes);
                    editMode(currentIndex);
                    $("#details" + currentIndex).show("blind", "slow");
                },
                cache: false
            });
        });

        $("#cancel<?php echo $index ?>, #cancel_bottom<?php echo $index ?>").click(function() {

            $.ajax({
                success: function(html) {

                    var currentIndex = <?php echo $index ?>;

                    $("#service_user<?php echo $index ?>").replaceWith(html);

                    displayMode(currentIndex);
                    enableAllButtons(userIndexes);

                },
                type: 'get',
                url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'cancelEditUser')) ?>',
                data: {
                    index: <?php echo $index ?>
                },
                cache: false,
                dataType: 'html'
            });

        });

        $("#cancelNew, #cancelNew_bottom").click(function() {

            $.ajax({
                success: function(html) {

                    var currentIndex = <?php echo $index ?>;

                    $("#service_user<?php echo $index ?>").remove(html);

                    displayMode(currentIndex);
                    enableAllButtons(userIndexes);

                },
                type: 'get',
                url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'cancelNewUser')) ?>',
                data: {
                    index: <?php echo $index ?>
                },
                cache: false,
                dataType: 'html'
            });

        });

        $("#check_box<?php echo $index ?>").click(function() {
            var isChecked = $("#check_box<?php echo $index ?>").is(":checked") ? 1 : 0;
            $.ajax({
                url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'selectServiceUser')) ?>',
                type: 'get',
                data: {checkBoxState: isChecked, index: <?php echo $index ?>}
            });
        });

        $(function() {

            var currentIndex = <?php echo $index ?>;

            if (<?php echo (int) $newServiceUser ?> > 0) { //$newServiceUser is a boolean

                disableAllButtons(currentIndex, userIndexes);
                editMode(currentIndex);
            }
            else {

                displayMode(currentIndex);
            }
        });

    </script>