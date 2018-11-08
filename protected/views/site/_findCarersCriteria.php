<?php
if (count($carers) != 0) {
    ?>
    <div class="rc-container-button" style="margin-top:1em;">
        <div class="buttons">
            <?php
            //echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('class' => 'rc-button', 'submit' => array('findCarers')));
            ?>
        </div>
        <?php //$this->renderPartial('/common/_ajaxLoader'); ?> 
    </div>
<?php } ?>
<div class="rc-module-bar">
    <div class="rc-module-name">
        I need a carer:
    </div>
</div>
<div class="rc-container-fb2-criteria">
    <?php
    //INIT
    $refreshAjaxUrl = Yii::app()->createUrl('/site/findCarersRefresh');
    $onChangeJs = "js: refreshList(this, false);";
    $loaderImageUrl = Yii::app()->request->baseUrl . '/images/ajax-loader.gif';
    $loaderHtml = "<img class='rc-fb-small-loader' style='display:none;padding-left:4px;' alt='Loader' src='$loaderImageUrl'/>";
    ?>
    <div class="rc-container-fb2-criteria-line-even">

        <div class="rc-container-tickbox">
            <b>Postcode </b>
            <?php
            //Location
            // echo Yii::t('texts', 'LABEL_POST_CODE') . '&#160;&#160;';
            $attributeName = 'postCode';
            echo CHtml::textField($attributeName, $criteria['postCode'], array('class' => 'rc-field-medium')) . '&#160;&#160;';
            echo CHtml::button('UPDATE', array('onClick' => $onChangeJs, 'class' => 'rc-button-white-small'));
            echo $loaderHtml;
            echo '<br /><span class="rc-error" id="postCodeError" style="display:none">Invalid postcode.</span>';
            ?>
        </div>
    </div>
    <div class="rc-container-fb2-criteria-line-odd">
        <b>Gender</b>
        <div class="rc-container-tickbox">
            <?php
            //GENDER
            //echo Yii::t('texts', 'LABEL_GENDER_OF_THE_CARER') . ':&#160;&#160;&#160;';
            $attributeNameShowMale = 'showMale';
            $checkBoxId = $attributeNameShowMale;
            echo CHtml::CheckBox($attributeNameShowMale, $criteria['showMale'], array('onChange' => $onChangeJs));
            echo CHtml::label('Male', $checkBoxId);
            echo $loaderHtml;
            ?>
        </div>
        <div class="rc-container-tickbox">
            <?php
            //echo Yii::app()->params['radioButtonSeparator'];
            $attributeNameShowFemale = 'showFemale';
            $checkBoxId = $attributeNameShowFemale;
            echo CHtml::CheckBox($attributeNameShowFemale, $criteria['showFemale'], array('onChange' => $onChangeJs));
            echo CHtml::label('Female', $checkBoxId);
            echo $loaderHtml;
            ?>
        </div>

    </div>
    <div class="rc-container-fb2-criteria-line-even">
        <b>Who can provide</b>
        <?php
        //ACTIVITIES
        $activities = Condition::getConditions(Condition::TYPE_ACTIVITY);

        foreach ($criteria as $criter => $value) {
            if (Util::startsWith($criter, 'activities_condition_')) {
                $activitiesValues[] = $value;
            }
        }

        foreach ($activities as &$activity) {
            ?>
            <div class="rc-container-tickbox">
                <?php
                $attributeName = 'activities_condition_';
                $activityId = $activity->id;
                $attributeName = $attributeName . $activityId;

                if (in_array($activityId, $activitiesValues)) {
                    $value = true;
                } else {
                    $value = false;
                }

                echo CHtml::checkBox($attributeName, $value, array('id' => $attributeName, 'value' => $activityId, 'onClick' => $onChangeJs));

                $text = Condition::getText($activity->name);
                $tooltip = Condition::getTextTooltip($activity->name);
                $value = UIServices::renderTooltip($text, $tooltip, '', true);
                echo CHtml::label($value, $attributeName);

                echo $loaderHtml;
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            For a client who is:
        </div>
    </div>
    <div class="rc-container-fb2-criteria-line-odd">
        <!--        <b>For a person who is</b>-->
        <div class="rc-container-tickbox">
            <?php
            // echo Yii::t('texts', 'LABEL_GENDER_OF_THE_CARER') . ':&#160;&#160;&#160;';
            $attributeNamePrefix = 'person_gender';
            $attributeName = $attributeNamePrefix . '_' . Constants::GENDER_MALE;
            echo CHtml::radioButton($attributeNamePrefix, (Constants::GENDER_MALE == $criteria[$attributeNamePrefix]), array('id' => $attributeName, 'value' => Constants::GENDER_MALE, 'onClick' => $onChangeJs));
            echo CHtml::label('Male', $attributeName);
            ?>
            <!--        </div>
                    <div class="rc-container-tickbox">-->
            <?php
            echo Yii::app()->params['radioButtonSeparator'];
            $attributeName = $attributeNamePrefix . '_' . Constants::GENDER_FEMALE;
            echo CHtml::radioButton($attributeNamePrefix, (Constants::GENDER_FEMALE == $criteria[$attributeNamePrefix]), array('id' => $attributeName, 'value' => Constants::GENDER_FEMALE, 'onClick' => $onChangeJs));
            echo CHtml::label('Female', $attributeName);
            echo $loaderHtml
            ?>
        </div>
    </div>
    <div class="rc-container-fb2-criteria-line-even">
        <!--        <b>Of age</b>-->
        <?php
        $ageGroups = AgeGroup::getAgeGroupsLabels();
        $ageGroupsIds = array_keys($ageGroups);
        $value = 'age_group_3';
        foreach ($ageGroupsIds as $ageGroupsId) {
            $attributeNamePrefix = 'age_group';
            $attributeName = $attributeNamePrefix . '_' . $ageGroupsId;
            ?>
            <div class="rc-container-tickbox">
                <?php
                echo CHtml::radioButton($attributeNamePrefix, ($ageGroupsId == $criteria['age_group']), array('id' => $attributeName, 'value' => $ageGroupsId, 'onClick' => $onChangeJs));
                echo CHtml::label($ageGroups[$ageGroupsId], $attributeName);
                echo $loaderHtml;
                ?>
            </div>
            <?php
        }
        // PHYSICAL CONDITIONS
        ?>
    </div>
    <div class="rc-container-fb2-criteria-line-odd">
        <!--        <b>Who is</b>-->
        <?php
        $physicalConditions = Condition::getConditions(Condition::TYPE_PHYSICAL);
        $value = 'physical_condition_46';
        foreach ($physicalConditions as $physicalCondition) {
            ?>
            <div class="rc-container-tickbox">
                <?php
                $physicalId = $physicalCondition->id;
                $attributeNamePrefix = 'physical_condition';
                $attributeName = $attributeNamePrefix . '_' . $physicalId;
                echo CHtml::radioButton($attributeNamePrefix, ($physicalId == $criteria['physical_condition']), array('id' => $attributeName, 'value' => $physicalId, 'onClick' => $onChangeJs));
                echo CHtml::label(Condition::getText($physicalCondition->name), $attributeName);
                echo $loaderHtml;
                ?>
            </div>
            <?php
        }
        // MENTAL CONDITIONS
        ?>
    </div>
    <div class="rc-container-fb2-criteria-line-even">
        <!--        <b>And mentally</b>-->
        <?php
        $mentalConditions = Condition::getConditions(Condition::TYPE_MENTAL);
        $value = 'mental_condition_50';
        foreach ($mentalConditions as &$mentalCondition) {
            ?>
            <div class="rc-container-tickbox">
                <?php
                $mentalId = $mentalCondition->id;

                $attributeNamePrefix = 'mental_condition';
                $attributeName = $attributeNamePrefix . '_' . $mentalId;

                echo CHtml::radioButton($attributeNamePrefix, ($mentalId == $criteria['mental_condition']), array('id' => $attributeName, 'value' => $mentalId, 'onClick' => $onChangeJs));
                echo CHtml::label(Condition::getText($mentalCondition->name), $attributeName);
                echo $loaderHtml;
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            Type of care:
        </div>
    </div>
    <div class="rc-container-fb2-criteria-line-even">
        <div class="rc-container-tickbox">
            <?php
            // echo Yii::t('texts', 'LABEL_GENDER_OF_THE_CARER') . ':&#160;&#160;&#160;';
            $attributeNamePrefix = 'type_care';
            $valueHourly = Constants::HOURLY;
            $attributeName = $attributeNamePrefix . '_' . $valueHourly;
            echo CHtml::radioButton($attributeNamePrefix, ($valueHourly == $criteria[$attributeNamePrefix]), array('id' => $attributeName, 'value' => $valueHourly, 'onClick' => $onChangeJs));
            echo CHtml::label('By the hour', $attributeName);
            ?>

            <?php
            echo Yii::app()->params['radioButtonSeparator'];
            $valueLiveIn = Constants::LIVE_IN;
            $attributeName = $attributeNamePrefix . '_' . $valueLiveIn;
            echo CHtml::radioButton($attributeNamePrefix, ($valueLiveIn == $criteria[$attributeNamePrefix]), array('id' => $attributeName, 'value' => $valueLiveIn, 'onClick' => $onChangeJs));
            echo CHtml::label('24 hour live-in', $attributeName);
            echo $loaderHtml
            ?>
        </div>
    </div>
</div>

<?php
$sendMessageAjaxUrl = Yii::app()->createUrl('/site/sendMessageCarer');
?>

<script type="text/javascript">

    function selectCarer(carerId) {

        //set hidden input field value to selected carer
        $("input[id=selectedCarer]").val(carerId);

        //submit form
        $("#find-carers-form").submit();

    }

    function showCarerMessage(buttonId) {

        var area = $(buttonId).closest(".rc-fb2-profile");
        area.find(".message_area").show();
    }

    function sendMessage(buttonId, carerId) {

        var area = $(buttonId).closest(".rc-fb2-profile");

        var email = area.find('#email').val();
        var text = area.find('#text').val();

        var errorDiv = area.find('#error');
        var successDiv = area.find('#success');

        var messageArea = area.find('.message_area');

        $.ajax({
            beforeSend: function() {

                successDiv.hide();
                errorDiv.hide();
            },
            success: function(data) {

                if (data.success === 'false') {

                    errorDiv.show();
                    errorDiv.html(data.message);

//                    $('#postCodeError').show();
//
//                    //reverse selection criteria if error
//                    if ($(object).is(':radio') || $(object).is(':checkbox')) {
//                        var isChecked = $(object).is(":checked") ? 1 : 0;
//                        $(object).attr('checked', !isChecked);
//                    }
                }
                else {

                    errorDiv.hide();
                    successDiv.show();
                    successDiv.html(data.message);
                    messageArea.hide();
                }

            },
            type: 'post',
            url: '<?php echo $sendMessageAjaxUrl ?>',
            data: {
                email: email, text: text, carerId: carerId,
            },
            cache: false,
            dataType: 'json'
        });
    }

    /**
     * Refresh the carer list according to current criteria
     * 
     * @param {type} object button, checkbox or radio button that triggered the function
     * @param {type} showMore true if the 'Show More' button was clicked - logic slightly different
     * @returns {undefined}
     */
    function refreshList(object, showMore) {

        $.ajax({
            beforeSend: function() {

                if (showMore) {
                    $('#showMoreLoader').show();
                }
                else {
                    $(object).closest('.rc-container-tickbox').find('.rc-fb-small-loader').show();
                    $('#postCodeError').hide();
                }
            },
            success: function(html) {

                if (html === 'error') {
                    $('#postCodeError').show();

                    //reverse selection criteria if error
                    if ($(object).is(':radio') || $(object).is(':checkbox')) {
                        var isChecked = $(object).is(":checked") ? 1 : 0;
                        $(object).attr('checked', !isChecked);
                    }
                }
                else {

                    $('#carers_results').html(html);
                }
                if (showMore) {
                    $('#showMoreLoader').hide();
                }
                else {
                    $(object).closest('.rc-container-tickbox').find('.rc-fb-small-loader').hide();
                }

            },
            type: 'get',
            url: '<?php echo $refreshAjaxUrl ?>',
            data: {
                filters: $('form').serialize(), showMore: showMore
            },
            cache: false,
            dataType: 'html'
        });
    }

</script>
