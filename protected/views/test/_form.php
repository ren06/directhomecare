<div class="row">


    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'test-form',
                    // 'enableAjaxValidation' => true,
            ));

     $model = new CarerDocument;
     
    echo $form->labelEx($model, 'id_document');
    ?>

    <div id="job">

        <?php
        echo CHtml::ajaxLink(Yii::t('job', 'Create job'), $this->createUrl('test/addnew'), array(
            'onclick' => '$("#jobDialog").dialog("open"); return false;',
            'update' => '#jobDialog'
                ), array('id' => 'showJobDialog'));
        ?>
        <div id="jobDialog"></div>
    </div>

</div>

<?php $this->endWidget(); ?>