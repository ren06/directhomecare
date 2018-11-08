
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'gold-value-form',
            'enableAjaxValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true),
        ));
?>


<p class="rc-note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($serviceUser); ?>

<div class="row">
    <?php echo $form->labelEx($serviceUser, 'first_name'); ?>
    <?php echo $form->textField($serviceUser, 'first_name', array('size' => 45, 'maxlength' => 45)); ?>
    <?php echo $form->error($serviceUser, 'first_name'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($serviceUser, 'last_name'); ?>
    <?php echo $form->textField($serviceUser, 'last_name', array('size' => 45, 'maxlength' => 45)); ?>
    <?php echo $form->error($serviceUser, 'last_name'); ?>
</div>

<?php
$this->widget('application.extensions.ajaxvalidationmessages.EAjaxValidationMessages', array(
    'errorCssClass' => 'clsError',
    'errorMessageCssClass' => 'clsErrorMessage',
    'errorSummaryCssClass' => 'clsErrorSummary'));
?>

<div class="row buttons">
    <?php
    echo CHtml::ajaxSubmitButton('Create User', CHtml::normalizeUrl(array('test/submitAjax'), array(
                'beforeSend' => 'function() {
                                    $(".createUser").attr("disabled","disabled");
                                }',
                'error' => "function(data) {
                   alert('error);
                }",
                'success' => "function(html) {
    if (html.indexOf('{')==0) {
       jQuery('#gold-value-form').ajaxvalidationmessages('show', html);
    }
    else {
       jQuery('#gold-value-form').ajaxvalidationmessages('hide');
    }
 }",
                'error' => "function(html) {
    jQuery('#gold-value-form').ajaxvalidationmessages('hide');
 }",
                    ), array('class' => 'createUser', 'id' => '')
            ));
    ?>

</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div class="ajaxMessage"></div>