<?php
/* @var $this AdminController */
/* @var $data Carer */
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
        "idSelector" => 'email_address_' . $data->id, //Id of HTML object.
    ));
    ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('date_birth')); ?>:</b>
    <?php echo CHtml::encode($data->date_birth); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('wizard_completed')); ?>:</b>
    <?php
    echo CHtml::encode($data->wizard_completed);
    if ($data->isActive()) {
        echo ' - <b>Active</b>';
    } else {
        echo ' - Not active';
    }
    ?>
    <br />

    <b><?php echo $data->getAttributeLabel('gender'); ?>:</b>
    <?php echo CHtml::encode($data->getGenderLabel()) ?>
    <br />

    <b><?php echo $data->getAttributeLabel('created'); ?>:</b>
    <?php echo Calendar::convert_DBDateTime_DisplayDateTimeText($data->created, false, ' ', false) ?>
    <br />

    <b><?php echo 'Last login date' ?>:</b>
    <?php echo Calendar::convert_DBDateTime_DisplayDateTimeText(DBServices::getLastLogin($data->id, Constants::USER_CARER), false, ' ', false) ?>
    <br />



    <?php /*
      <b><?php echo CHtml::encode($data->getAttributeLabel('hourly_work')); ?>:</b>
      <?php echo CHtml::encode($data->hourly_work); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('nationality')); ?>:</b>
      <?php echo CHtml::encode($data->nationality); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('country_birth')); ?>:</b>
      <?php echo CHtml::encode($data->country_birth); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('mobile_phone')); ?>:</b>
      <?php echo CHtml::encode($data->mobile_phone); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('live_in')); ?>:</b>
      <?php echo CHtml::encode($data->live_in); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('live_in_work_radius')); ?>:</b>
      <?php echo CHtml::encode($data->live_in_work_radius); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('hourly_work_radius')); ?>:</b>
      <?php echo CHtml::encode($data->hourly_work_radius); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('work_with_male')); ?>:</b>
      <?php echo CHtml::encode($data->work_with_male); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('work_with_female')); ?>:</b>
      <?php echo CHtml::encode($data->work_with_female); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('car_owner')); ?>:</b>
      <?php echo CHtml::encode($data->car_owner); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('motivation_text')); ?>:</b>
      <?php echo CHtml::encode($data->motivation_text); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('personal_text')); ?>:</b>
      <?php echo CHtml::encode($data->personal_text); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('dh_rating')); ?>:</b>
      <?php echo CHtml::encode($data->dh_rating); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('sort_code')); ?>:</b>
      <?php echo CHtml::encode($data->sort_code); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('account_number')); ?>:</b>
      <?php echo CHtml::encode($data->account_number); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('terms_conditions')); ?>:</b>
      <?php echo CHtml::encode($data->terms_conditions); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('motivation_text_status')); ?>:</b>
      <?php echo CHtml::encode($data->motivation_text_status); ?>
      <br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('personal_text_status')); ?>:</b>
      <?php echo CHtml::encode($data->personal_text_status); ?>
      <br />

     */ ?>

</div>