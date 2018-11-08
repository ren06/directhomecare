<div class="form" id="jobDialogForm">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'job-form',
               // 'enableAjaxValidation' => true,
            ));
//I have enableAjaxValidation set to true so i can validate on the fly the
    ?>

    <p class="rc-note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php
    $diplomas = Document::getDocuments(Document::TYPE_DIPLOMA);
    $diplomas['0'] = 'Select diploma:';
    ksort($diplomas);

    $years = Calendar::getDropDownDiplomaYears();
    //array_unshift($years , 'Year Earned:');
    $years[0] = 'Year earned:';


    echo CHtml::activeDropDownList($model, "id_document", $diplomas, array('class' => 'rc-drop'));
    //echo CHtml::error($carerDocument, "id_document", array('class' => 'rc-error'));
    echo CHtml::activeDropDownList($model, "year_obtained", $years, array('class' => 'rc-drop'));
    ?>


    <div class="row buttons">
<?php echo CHtml::ajaxSubmitButton(Yii::t('job', 'Create Job'), CHtml::normalizeUrl(array('test/addnew', 'render' => false)), array('success' => 'js: function(data) {
                        $("#Person_jid").append(data);
                        $("#jobDialog").dialog("close");
                    }'), array('id' => 'closeJobDialog')); ?>
    </div>

<?php $this->endWidget(); ?>

</div>

<script type="text/javascript">
    function submitNewDiploma(){
      
        $.ajax({
            success: function(html){
                
                $("#dialogDiploma").empty();
                $("#dialogDiplomaPopup").dialog("close");
                $("#diplomas").append(html);
                
                //alert(html);
                
            },
            type: 'post',
            url: '<?php echo $this->createUrl('diplomaAdd') ?>',
            data: {
                CarerDocument: decodeURIComponent($("#newdiploma-form").serialize())
            },
            cache: false,
            dataType: 'html'
        });
    };
</script>