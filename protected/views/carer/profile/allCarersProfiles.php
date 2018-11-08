<?php
echo CHtml::beginForm();

echo CHtml::button(Yii::t('texts', 'Select All'), array('onclick' => '$( "input:checkbox" ).attr("checked", true)'));
echo CHtml::button(Yii::t('texts', 'Deselct All'), array('onclick' => '$( "input:checkbox" ).attr("checked", false)'));
?>
<hr />
<div class="rc-container-conditions-tickbox">
    <?php
    $attributeName = 'active';
    echo CHtml::CheckBox($attributeName, $searchValues[$attributeName]);
    echo CHtml::label('Active', $attributeName);
    ?>
</div>
<hr>
<div class="rc-container-conditions-tickbox">
    <?php
    $attributeName = 'nationality';
    echo CHtml::label('Nationality', $attributeName);
    echo CHtml::dropDownList($attributeName, $nationality, $nationalities);
    ?>
</div>
<hr>
<div class="rc-container-conditions-tickbox">
    <?php
    $attributeName = 'hourly';
    echo CHtml::CheckBox($attributeName, $searchValues[$attributeName]);
    echo CHtml::label('Hourly', $attributeName);
    ?>
</div>

<div class="rc-container-conditions-tickbox">
    <?php
    $attributeName = 'liveIn';
    echo CHtml::CheckBox($attributeName, $searchValues[$attributeName]);
    echo CHtml::label('Live in', $attributeName);
    ?>
</div>
<hr>
<?php
$physicalConditions = Condition::getConditions(Condition::TYPE_PHYSICAL);
$mentalConditions = Condition::getConditions(Condition::TYPE_MENTAL);
$activities = Condition::getConditions(Condition::TYPE_ACTIVITY);

$value = $searchValues['physical_condition'];

foreach ($physicalConditions as $physicalCondition) {
    ?>
    <div class="rc-container-conditions-tickbox">
        <?php
        $id = $physicalCondition->id;

        $attributeNamePrefix = 'physical_condition';
        $attributeName =  $attributeNamePrefix . '_' . $physicalCondition->id;
        
        echo CHtml::radioButton($attributeNamePrefix, ($value == $attributeName), array('value' => $attributeName)) . "\n";
        echo CHtml::label(Condition::getText($physicalCondition->name . ' (' . $physicalCondition->id . ')'), $attributeName);
        ?>
    </div>
    <?php
}
?>
<hr>
<?php
$value = $searchValues['mental_condition'];
foreach ($mentalConditions as &$mentalCondition) {
    ?>
    <div class="rc-container-conditions-tickbox">
        <?php
        $attributeNamePrefix = 'mental_condition';
        $attributeName = $attributeNamePrefix . '_' . $mentalCondition->id;

        echo CHtml::radioButton($attributeNamePrefix, ($value == $attributeName), array('value' => $attributeName)) . "\n";
        echo CHtml::label(Condition::getText($mentalCondition->name) . ' (' . $mentalCondition->id . ')', $attributeName);
        ?>
    </div>
    <?php
}
?>
<hr>
<?php
foreach ($activities as &$activity) {
    ?>
    <div class="rc-container-conditions-tickbox">
        <?php
        $attributeName = 'activities_condition_';
        $attributeName = $attributeName . $activity->id;

        $value = $searchValues[$attributeName];

        echo CHtml::checkBox($attributeName, $value, array('class' => '', 'id' => $attributeName)) . "\n";
        echo CHtml::label(Condition::getText($activity->name . ' (' . $activity->id . ')'), $attributeName);
        ?>
    </div>
    <?php
}
?>
<hr>
<?php
$carer = new Carer();
$labels = $carer->attributeLabels();
?>
<div class="rc-container-conditions">    
    <div class="rc-container-conditions-tickbox">
        <?php echo CHtml::checkBox('work_with_male', $value = $searchValues['work_with_male']); ?>      
        <?php echo CHtml::label($labels['work_with_male'], 'work_with_male') ?>
    </div>
    <div class="rc-container-conditions-tickbox">
        <?php echo CHtml::checkBox('work_with_female', $searchValues['work_with_female']); ?>                    
        <?php echo CHtml::label($labels['work_with_female'], 'work_with_female'); ?>
    </div>
</div>
<hr>
<!-- Age group -->
<div class="rc-container-conditions">
    <?php
    $ageGroups = AgeGroup::getAgeGroups();
    $ageGroupsKeys = array_keys($ageGroups);
    foreach ($ageGroupsKeys as $ageGroupsKey) {

        $attributeName = 'ageGroup_' . $ageGroupsKey;
        $value = $searchValues[$attributeName];
        ?>
        <div class="rc-container-conditions-tickbox">
            <?php
            echo CHtml::CheckBox($attributeName, $value) . "\n";
            echo CHtml::label($ageGroups[$ageGroupsKey], $attributeName);
            ?>
        </div>        
        <?php
    }
    ?>
</div>
<hr>
<?php
echo CHtml::submitButton('Show', array('submit' => Yii::app()->createUrl("admin/carerAdmin/allCarersProfiles")));

echo CHtml::endForm();
?>
<hr>

<?php echo 'Results: ' . count($carers) . ' (showing max. 1000)'; ?>
<table>
    <?php
    $lineBreak = 4;
    $i = 0;
    echo '<tr>';
    foreach ($carers as $carer) {

        if ($i % $lineBreak === 0) {
            echo '</tr>';
            echo '<tr>';
        }
        
        if($i == 1000){
            break;
        }

        echo '<td>';
        $this->renderPartial('application.views.carer.profile._carerProfileDetails', array('carer' => $carer, 
            'view' => $view, 'carerProfileType' => 'short'));
        echo '</td>';

        $i++;
    }

    echo '<tr>';
    ?>
</table>
