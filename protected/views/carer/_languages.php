<?php
$availableLanguages = Languages::getLanguages();
$availableLanguagesLevels = Languages::getLanguageLevels();
?>

<table id="languages_table">
    <?php
    $i = 0;
    foreach ($carerLanguages as $carerLanguage) {

        $this->renderPartial('_language', array('carerLanguage' => $carerLanguage,
            'availableLanguages' => $availableLanguages,
            'availableLanguagesLevels' => $availableLanguagesLevels,
            'index' => $i,
        ));
        $i++;
    }
    ?>
</table>

<?php
 echo CHtml::ajaxLink(Yii::t('texts', 'LINK_ADD_LANGUAGE'), $this->createUrl('carer/addLanguage'), array(
        'beforeSend' => "function(xhr, opts) {}",
        'success' => "function(html) {
                            $('#languages_table').append(html);
                        }",
        'type' => 'POST',
        'data' => array('index' => "js:$('#languages_table tr').length"),
        'error' => 'function(data) {}',
            ), array('class' => 'rc-link')
    );
?>