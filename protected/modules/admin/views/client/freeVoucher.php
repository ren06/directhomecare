
<h2>Give free voucher to Client: <?php echo $client->fullName ?> (ID: <?php echo $client->id ?>)</h2>
Voucher Balance: <?php echo $client->getVoucherBalance() ?>
<br><br>

<?php
echo CHtml::beginForm();

echo CHtml::label('Number of hours', '');
echo ' ';
echo CHtml::dropDownList('hours', '', array(1 => 1, 2 => 2, 3 => 3, 4 => 4));
echo ' x ';
echo Prices::getPrice(Constants::USER_CLIENT, 'hourly_price')->text;

echo '<br><br>';
echo CHtml::submitButton();

echo CHtml::endForm();
?>
