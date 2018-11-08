<?php

?>

<h2>Carer: <?php echo $carer->fullName?> (ID: <?php echo $carer->id?>)</h2>
<hr>
<h4>
    <?php echo $carer->mobile_phone ?> <br><br>
    <?php echo $carer->email_address ?> <br>

</h4>  <hr>
<h4>
    Balance: <?php echo $carer->getCreditBalance()->text ?>
</h4>
<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif ?>
<hr>
<?php echo CHtml::link("View carer profile", array('carerAdmin/viewCarerProfile/id/' . $carer->id)); ?>
</br>
<?php echo CHtml::link("See uploaded documents", array('carerAdmin/approveCarerDocuments/carerId/' . $carer->id)); ?>
</br>
<?php echo CHtml::link("Update details", array('carerAdmin/update/id/' . $carer->id)); ?>
</br>
<?php //echo CHtml::link("View shifts", array('carerAdmin/viewCarerMissions/id/' . $carer->id)); ?>
</br>
<?php echo CHtml::link("Create Compensation", array('carerAdmin/createCompensation/carerId/' . $carer->id)); ?>
</br>
</br>

<?php if (Yii::app()->user->checkAccess('role_carerAdmin')) { ?>
<?php echo CHtml::button(
        "Delete Carer", 
        array('submit' => array('carerAdmin/delete/id/' . $carer->id),
              'confirm' => 'Are you sure?')); ?>
</br>
<?php } ?>
