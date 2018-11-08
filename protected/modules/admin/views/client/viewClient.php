<?php ?>

<h2>Client: <?php echo $client->fullName ?> (ID: <?php echo $client->id ?>)</h2>
<hr>
<h4>
    <?php echo $client->mobile_phone ?> <br><br>
    <?php echo $client->email_address ?> <br>

</h4>  <hr>
<h4>
    Voucher Balance: <?php echo $client->getVoucherBalance() ?>
</h4>
<hr>
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


<?php echo CHtml::link("View/Update details", array('clientAdmin/update/id/' . $client->id)); ?>
</br>
<?php echo CHtml::link("View bookings (Client view)", array('clientAdmin/viewBookings/id/' . $client->id)); ?>
</br>
<?php echo CHtml::link("Manage bookings", array('clientAdmin/viewBookings2/id/' . $client->id)); ?>
</br>
<?php echo CHtml::link("Manage all shifts", array('clientAdmin/viewClientMissions/id/' . $client->id)); ?>
</br>
<?php echo CHtml::link("View service users", array('clientAdmin/viewServiceUsers/id/' . $client->id)); ?>
</br>
<?php echo CHtml::link("View service locations", array('clientAdmin/viewServiceLocation/id/' . $client->id)); ?>
</br>
</br>

<?php echo CHtml::link("Make a booking for client", array('clientAdmin/bookingForClient/id/' . $client->id)); ?>
</br>
</br>
<?php echo CHtml::link("View banking transactions", array('clientAdmin/viewBankingTransactions/id/' . $client->id)); ?>
<br>
<?php echo CHtml::link("Give free voucher", array('clientAdmin/freeVoucher/id/' . $client->id)); ?>
<br>
<?php echo CHtml::link("Create Message for Client", array('clientAdmin/createMessageForClient/id/' . $client->id)); ?>


<br>
<br>

<?php if (Yii::app()->user->checkAccess('role_superadmin')) { ?>
    <?php
    echo CHtml::button(
            "Delete Client", array('submit' => array('clientAdmin/delete/id/' . $client->id),
        'confirm' => 'Are you sure?'));
    ?>
    </br>
<?php } ?>
