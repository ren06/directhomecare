<?php
//Yii::app()->clientScript->registerCoreScript("jquery");

$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'jobDialog',
    'options' => array(
        'title' => 'Create Job',
        'autoOpen' => true,
        'modal' => 'true',
        'width' => 'auto',
        'height' => 'auto',
    ),
));

$form = $this->beginWidget('CActiveForm', array(
            'id' => 'job-form',
                // 'enableAjaxValidation' => true,
        ));


$diplomas = Document::getDocuments(Document::TYPE_DIPLOMA);
$diplomas['0'] = 'Select diploma:';
ksort($diplomas);

$years = Calendar::getDropDownDiplomaYears();
//array_unshift($years , 'Year Earned:');
$years[0] = 'Year earned:';


echo CHtml::activeDropDownList($model, "id_document", $diplomas, array('class' => 'rc-drop'));
//echo CHtml::error($carerDocument, "id_document", array('class' => 'rc-error'));
echo CHtml::activeDropDownList($model, "year_obtained", $years, array('class' => 'rc-drop'));



//    echo $this->renderPartial('_formDialog', array('model' => $model));
?>

<div class="row buttons">
    <?php
//        echo CHtml::ajaxSubmitButton('Create Job', CHtml::normalizeUrl(array('test/addnew', 'render' => false)), array('success' => 'js: function(data) {
//                        $("#Person_jid").append(data);
//                        $("#jobDialog").dialog("close");
//                    }'), array('id' => 'closeJobDialog'));
    
        echo CHtml::button('Add New Diploma', array('class' => 'rc-button', 'id' => 'addNewDiploma', 'name' => 'addNewDiplomaName'
        , 'onclick' => "submitNewDiploma();"));
        
        echo CHtml::button('Close', array('class' => 'rc-button', 'id' => 'closeNewDiploma', 'name' => 'closeNewDiplomaName'
        , 'onclick' => "$('#jobDialog').dialog('close');"));
    
    ?>
</div>

<?php $this->endWidget(); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<script type="text/javascript">
    function submitNewDiploma(){

        $.ajax({
            success: function(html){
                
                $("#jobDialog").dialog("close");
                $("#CarerDocument_id_document").append(html);
                
            },
            type: 'post',
            url: '<?php echo $this->createUrl('addNew') ?>',
            data: {
                CarerDocument: decodeURIComponent($("#newdiploma-form").serialize())
            },
            cache: false,
            dataType: 'html'
        });
    };
</script>