<div id="service_location<?php echo $index ?>">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'servicelocation-form' . $index,
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
    ));
    ?>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php
            //Display checkbox
            if (!$newServiceLocation) {
                //display the checkbox only in the new mission scenario
                if ($scenario == ServiceLocationsWidget::MISSION_SCENARIO) {

                    //select service if only one or the one just created
                    if ($size == 1 || isset($newServiceSelected)) {
                        $value = true;
                    } else {
                        $value = Session::serviceLocationIsSelected($serviceLocation->id);
                    }

                    echo CHtml::radioButton('radio_button' . $index, $value, array('id' => 'radio_button' . $index));
                }
//                 elseif ($scenario == ServiceLocationsWidget::CHANGE_SCENARIO) {
//                    $target = CHtml::normalizeUrl(array('clientManageBookings/selectBookingLocation'));
//                    $return = CHtml::normalizeUrl(array('clientManageBookings/myBookings'));
//                    echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_SELECT'), $target, array(
//                        'type' => 'POST',
//                        'success' => "function(data){
//                       $('body').load('$return');}",
//                        'data' => array('locationId' => $serviceLocation->id, 'bookingId' => $booking->id)
//                            ), array(//htmlOptions
//                        'href' => $target,
//                        'class' => 'rc-linkbutton-small'
//                            )
//                    );
//                }
                echo $serviceLocation->address_line_1 . '&#160;&#160;&#45;&#160;&#160;' . $serviceLocation->city . '&#160;&#160;&#45;&#160;&#160;' . $serviceLocation->post_code;
            } else {
                echo Yii::t('texts', 'HEADER_LOCATION');
            }
            ?>
        </div>
        <div class="rc-module-container-button">
            <?php
            //if location is used by a booking not possible to modify it

            echo CHtml::button(Yii::t('texts', 'BUTTON_EDIT'), array('class' => 'rc-button-small', 'id' => "edit{$index}"));
            echo '<span id="edit' . $index . 'disabled" class="rc-linkbutton-small-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_EDIT') . '</span>';

            if (!$serviceLocation->isUsedBooking() && !$serviceLocation->isUsedCreditCard()) {
                echo CHtml::button(Yii::t('texts', 'BUTTON_DELETE_LOCATION'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'remove' . $index));
                echo '<span id="remove' . $index . 'disabled" class="rc-linkbutton-small-disabled rc-button-small-space-left" style="display:none">' . Yii::t('texts', 'BUTTON_DELETE_LOCATION') . '</span>';
            } else {
                echo '<span id="used' . $index . '" title="' . Yii::t('texts', 'ALT_USED_IN_ANOTHER_BOOKING_CANNOT_DELETE') . '" class="rc-linkbutton-small-disabled rc-button-small-space-left">' . Yii::t('texts', 'BUTTON_DELETE_LOCATION') . '</span>';
            }
            echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'rc-button-small', 'id' => 'save' . $index));
            if ($newServiceLocation) {
                echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'cancelNew'));
            } else {
                echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button-small rc-button-small-space-left', 'id' => 'cancel' . $index));
            }
            ?>
        </div>
    </div>
    <div class="rc-module-inside">
        <table>
            <?php
            $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field toggable');
            ?>
            <tr>
                <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]address_line_1"); ?></td>
                <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]address_line_1", $htmlOptions); ?></td>
                <td class="rc-cell3"><?php echo $form->error($serviceLocation, "[$index]address_line_1", array('class' => 'rc-error')); ?></td>
            </tr>
            <tr>
                <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]address_line_2"); ?></td>
                <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]address_line_2", $htmlOptions); ?></td>
                <td class="rc-cell3"><?php echo $form->error($serviceLocation, "[$index]address_line_2", array('class' => 'rc-error')); ?></td>
            </tr>
            <tr>
                <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]city"); ?></td>
                <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]city", $htmlOptions); ?></td>
                <td class="rc-cell3"><?php echo $form->error($serviceLocation, "[$index]city", array('class' => 'rc-error')); ?></td>
            </tr>
            <tr>
                <td class="rc-cell1"><?php echo $form->labelEx($serviceLocation, "[$index]post_code"); ?></td>
                <td class="rc-cell2"><?php echo $form->textField($serviceLocation, "[$index]post_code", $htmlOptions); ?></td>
                <td class="rc-cell3"><?php echo $form->error($serviceLocation, "[$index]post_code", array('class' => 'rc-error')); ?></td>
            </tr>
        </table>
            <h3><?php echo Yii::t('texts', 'HEADER_NOTES_ABOUT_THE_LOCATION'); ?><?php // byRC echo $form->labelEx($serviceLocation, "[$index]explanation");      ?></h3>
            <p class="rc-note"><?php echo Yii::t('texts', 'NOTE_OPTIONAL_ADDITIONAL_INFORATION_MAX_255'); ?></p>
            <div id="<?php //byRC echo $note_errors        ?>" class="rc-error">
                <?php echo $form->error($serviceLocation, "[$index]explanation"); ?>
            </div>
            <?php echo $form->textArea($serviceLocation, "[$index]explanation", array('class' => 'rc-textarea-notes')); ?>
        <?php $this->endWidget(); ?>
        <script type="text/javascript">
            <?php
            $php_array = array_keys(Yii::app()->session['UserIndexes']);
            $js_array = json_encode($php_array);
            echo "var locationIndexes = " . $js_array . ";\n";
            ?>
            $("#save<?php echo $index ?>").click(function() {

                $.ajax({
                    success: function(html) {

                        var index = <?php echo $index ?>;

                        if (html.indexOf("{") == 0) {

                            var json = $.parseJSON(html);

                            $.each(json, function(key, value) {
                                $("#" + key).addClass("clsError");
                                $("#" + key + "_em_").show().html(value.toString());
                                $("label[for=" + key + "]").addClass("clsError");
                            });

                        }
                        else {
                            //unselect previous radio button
                            $(':radio').attr('checked', false);
                            //add new location
                            $("#service_location" + index).replaceWith(html);
                            displayMode(index);
                            enableAllButtons(locationIndexes);
                        }

                    },
                    type: 'post',
                    url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'saveLocation' . '/index/' . $index)) ?>',
                    data: {
                        ServiceLocation: decodeURIComponent(jQuery(this).parents("form").serialize())
                    },
                    cache: false,
                    dataType: 'html'
                });

            });

            $("#remove<?php echo $index ?>").click(function() {
                if (confirm('<?php echo Yii::t('texts', 'WIDGETS_VIEWS_SERVICE_LOCATION_POPUP1'); ?>')) {
                    $.ajax({
                        success: function(html) {

                            if (html != "") {

                                $('#errorMessage').html(html);
                            }
                            else {
                                $("#service_location<?php echo $index ?>").remove();
                                //if only one radio button select it (align with backend)
                                var radioButtons = $('#serviceLocationsWidget').find('input[type=radio]');

                                if (radioButtons.length == 1) {
                                    radioButtons.attr('checked', true);
                                }
                            }
                        },
                        type: 'get',
                        url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'deleteLocation')) ?>',
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
                        disableAllButtons(currentIndex, locationIndexes);
                        editMode(currentIndex);

                    },
                    cache: false

                });

            });

            $("#cancel<?php echo $index ?>").click(function() {

                $.ajax({
                    success: function(html) {

                        var currentIndex = <?php echo $index ?>;

                        $("#service_location<?php echo $index ?>").replaceWith(html);

                        displayMode(currentIndex);
                        enableAllButtons(locationIndexes);

                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'cancelEditLocation')) ?>',
                    data: {
                        index: <?php echo $index ?>
                    },
                    cache: false,
                    dataType: 'html'
                });

            });

            $("#cancelNew").click(function() {

                $.ajax({
                    success: function(html) {

                        var currentIndex = <?php echo $index ?>;

                        $("#service_location<?php echo $index ?>").remove(html);

                        displayMode(currentIndex);
                        enableAllButtons(locationIndexes);

                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'cancelNewLocation')) ?>',
                    data: {
                        index: <?php echo $index ?>
                    },
                    cache: false,
                    dataType: 'html'
                });

            });

            $("#radio_button<?php echo $index ?>").click(function() {

                //var isSelected = $("#radio_button<?php echo $index ?>").is(":checked") ? 1:0; 

                $('#serviceLocationsWidget').find('input[type=radio]').attr('checked', false);
                $(this).attr('checked', true);

                $.ajax({
                    url: '<?php echo CHtml::normalizeUrl(array($actionPath . 'selectLocation')) ?>',
                    type: 'get',
                    data: {index: <?php echo $index ?>}
                });
            });

            $(function() {

                var currentIndex = <?php echo $index ?>;

                if (<?php echo (int) $newServiceLocation ?> > 0) { //$newServicelocation is a boolean

                    disableAllButtons(currentIndex, locationIndexes);
                    editMode(currentIndex);
                }
                else {

                    displayMode(currentIndex);
                }
            });
        </script>
    </div>
</div>