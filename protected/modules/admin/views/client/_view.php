<?php
/* @var $this ClientAdminController */
/* @var $data Client */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('first_name')); ?>:</b>
    <?php echo CHtml::encode($data->first_name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('last_name')); ?>:</b>
    <?php echo CHtml::encode($data->last_name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('email_address')); ?>:</b>
    <?php
    echo CHtml::textField('email', $data->email_address, array('id' => 'email_address_' . $data->id, 'style' => 'width:15.42em;'));
    $this->widget('application.extensions.EZClip.EZClip', array(
        "type" => "input",
        "idSelector" =>  'email_address_' . $data->id, //Id of HTML object.
    ));
    ?>
    <br />

<!--	<b><?php echo CHtml::encode($data->getAttributeLabel('date_birth')); ?>:</b>
<?php echo CHtml::encode($data->date_birth); ?>
        <br />-->

    <b><?php echo CHtml::encode($data->getAttributeLabel('mobile_phone')); ?>:</b>
<?php echo CHtml::encode($data->mobile_phone); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('wizard_completed')); ?>:</b>
<?php echo CHtml::encode($data->wizard_completed); ?>
    <br />

</div>