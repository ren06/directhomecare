<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialog',
    'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
    'theme' => 'green',
    'cssFile' => 'jquery-ui-1.9.0.custom.css',
    'options' => array(
        'autoOpen' => true,
        'title' => Yii::t('texts', 'Update details'),
        'resizable' => false,
        'modal' => true,
        'draggable' => false,
        'width' => '480',
    // 'height' => '260',
    ),
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'complain-form',
    'enableAjaxValidation' => true,
        ));

$uniqueId = uniqid();
?>
<table class="rc-container-upload-table">
    <tr class="rc-upload-tablecell">
        <td class="rc-upload-tablecell">
            <p id="errorMessage_<?php echo $uniqueId ?>" class="rc-error" style="display:none">
                <?php echo Yii::t('texts', 'Please enter a reference and a transaction date'); ?>
            </p> 
        </td>
    </tr>
    <tr class="rc-upload-tablecell">
        <td class="rc-upload-tablecell">
            <?php
            $completedStatus = CarerWithdrawal::STATUS_COMPLETED;
            
            //echo CHtml::radioButtonList('success', $completedStatus, array($completedStatus => 'Success', CarerWithdrawal::STATUS_FAILED => 'Failed'), array('labelOptions' => array('style' => 'display:inline'), 'separator' => Yii::app()->params['radioButtonSeparator'],));
            //hack because too lazy to replace compeltely old logic, radio button hidden, always set to success
            echo CHtml::hiddenField('success', $completedStatus);

            $minDate = Calendar::today(Calendar::FORMAT_DISPLAYDATE);

            echo '<br>';
            echo CHtml::label('Bank reference', 'reference');
            echo CHtml::textField('reference', '', array('id' => 'reference' . $uniqueId, 'size' => 40, 'maxlength' => 40, 'class' => 'rc-field-medium'));
            echo '<br>';
            echo CHtml::label('Date', 'transaction_date');
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'themeUrl' => Yii::app()->request->baseUrl . '/css/jqueryui',
                'theme' => 'green',
                'cssFile' => 'jquery-ui-1.9.0.custom.css',
                'name' => 'transaction_date',
                //'value' => $quote->getStartDate(false),
                'options' => array('dateFormat' => 'dd/mm/yy',
                    'firstDay' => '1', //Monday
                    'minDate' => $minDate,
                    'maxDate' => '+730',
                    'changeYear' => 'true',
                    'changeMonth' => 'true',
                )
                ,
                'htmlOptions' => array('id' => 'transaction_date' . $uniqueId, 'class' => 'rc-field-medium'),));

            echo CHtml::hiddenField('withdrawalId', $withdrawalId);
            ?>
        </td>
    </tr>
</table>
<div class="rc-container-button">
    <?php
    $url = $this->createUrl('/admin/carerAdmin/carerWithdrawals');
    echo CHtml::ajaxButton(Yii::t('texts', Yii::t('texts', 'OK')), $this->createUrl('/admin/carerAdmin/updateWithdrawalDetails'), array(
        'beforeSend' => "function(xhr, opts) {
            
            //remove error message
            document.getElementById('errorMessage_$uniqueId').style.display = 'none';
                
            var success = $('input:radio[name=success]:checked').val();
                        
            //if(success == $completedStatus){
            
                var reference = $.trim($('#reference$uniqueId').val());
                var transaction_date = $.trim($('#transaction_date$uniqueId').val());

                if( reference == '' || transaction_date == '' ) {
                    document.getElementById('errorMessage_$uniqueId').style.display = 'block';
                    xhr.abort();
                }
            //}
        }",
        'success' => "function(html) {    
                            window.location.href='$url';
                            $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');                         
                        }",
        'type' => 'POST',
        
        'error' => 'function(data) {}',
            ), array('class' => 'rc-button', 'id' => uniqid())
    );

    echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'rc-button', 'id' => 'closeDialog', 'name' => 'closeDialogName'
        , 'onclick' => "$('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');"));
    ?>
</div>
<?php $this->endWidget(); //form widget?>


<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>