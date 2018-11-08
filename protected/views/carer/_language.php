<tr class="language_tr">
    <td class="rc-cell1">
        <?php echo CHtml::activeDropDownList($carerLanguage, "[$index]language", $availableLanguages, array('class' => 'rc-drop')); ?> 
    </td>
    <td class="rc-cell2">
        <?php echo CHtml::activeDropDownList($carerLanguage, "[$index]level", $availableLanguagesLevels, array('class' => 'rc-drop')); ?>
    </td>
    <td class="rc-cell3">
        <?php echo CHtml::button(Yii::t('texts', 'BUTTON_DELETE'), array('class' => 'button small', 'onclick' => "$(this).closest('.language_tr').remove();")); ?>
    </td>
</tr>