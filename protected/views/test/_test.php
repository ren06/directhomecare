
<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'focus' => array($serviceUser, 'first_name'),
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


<div class="row buttons">
    <?php
    echo CHtml::ajaxSubmitButton('Create User', CHtml::normalizeUrl(array('test/submitAjax'), array(
                'beforeSend' => 'function() {
                                    $(".createUser").attr("disabled","disabled");
                                }',
                'success' => 'function(data) {
                                alert(data);
                                if( data == "sent" ) {
                                    $(".ajaxMessage").text("User has been successfully created");
                                } else {
                                    $(".ajaxMessage").text("Oops! It looks there is some error in your form");
                                }
                                $(".createUser").removeAttr("disabled");
                            }'
                    ), array('class' => 'createUser')
            ));
    ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div class="ajaxMessage"></div>