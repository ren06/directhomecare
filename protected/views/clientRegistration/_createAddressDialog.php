<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'billingDialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'title' => 'Create new billing address', //RTRT
        'autoOpen' => true,
        'resizable' => false,
        'modal' => true,
        'draggable' => false,
        'width' => '610',
       // 'height' => '210',
    ),
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'new-address-form',
    'enableAjaxValidation' => true,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
?>

<table  class="rc-container-upload-table">   
    <?php
    $htmlOptions = array('maxlength' => 60, 'class' => 'rc-field');
    ?>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "address_line_1"); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($billingAddress, "address_line_1", $htmlOptions); ?></td>
        <td class="rc-cell3"><?php echo $form->error($billingAddress, "address_line_1", array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "address_line_2"); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($billingAddress, "address_line_2", $htmlOptions); ?></td>
        <td class="rc-cell3"><?php echo $form->error($billingAddress, "address_line_2", array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "city"); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($billingAddress, "city", array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($billingAddress, "city", array('class' => 'rc-error')); ?></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo $form->labelEx($billingAddress, "post_code"); ?></td>
        <td class="rc-cell2"><?php echo $form->textField($billingAddress, "post_code", array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
        <td class="rc-cell3"><?php echo $form->error($billingAddress, "post_code", array('class' => 'rc-error')); ?></td>
    </tr>
</table>

<div class="rc-container-button">
    <?php
    echo CHtml::button(Yii::t('texts', 'Create'/*RTRT*/), array('class' => 'rc-button', 'id' => 'createButton', 'name' => 'createButtonName'
        , 'onclick' => "submitForm();"));

    echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button', 'id' => 'cancelButton', 'name' => 'cancelButtonName'
        , 'onclick' => "$('#dialog').dialog('close');"));
    ?>
</div>


<?php $this->endWidget(); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

<script type="text/javascript">
    function submitForm(){

        var formId = 'new-address-form';

        $.ajax({
            success: function(data){
   
                if (data.status == 'failure'){                  
                    
                    var json = $.parseJSON(data.html);
                          
                    $.each(json, function(key, value) {
                        
                        //alert(value.toString());
                        
                        $("#" + key).addClass("clsError");
                        $("#" + key + "_em_").show().html(value.toString());
                        $("label[for=" + key + "]").addClass("clsError");
                    });
                }
                else if(data.status == 'saveFailure'){
                 
                   // $("#createDialogErrorMessage").html(data.html);
                 
                }
                else{
                    
                    $("#dialog").dialog("close");
                    $("#adresses").append(data.html);
                }
            },
            type: 'post',
            url: '<?php echo $this->createUrl('createBillingAddress') ?>',
            data: {
                CarerDocument: decodeURIComponent($(formId).serialize())
            },
            cache: false,
            dataType: 'json'
        });
    };
    </script>

