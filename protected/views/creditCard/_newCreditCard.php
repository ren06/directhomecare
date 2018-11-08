<?php
/*
 * Used in selectOrNewCreditCard which is used in payment wizard 2
 * 
 * Also used in maintain card
 */
?>

<!-- Credit card  -->
<?php
$this->renderPartial('/creditCard/_creditCardDetails', array('form' => $form, 'creditCard' => $creditCard, 'index' => 'new'));
?>
<!-- Billing address  -->
<?php
//$this->renderPartial('/creditCard/_billingAddress', array('form' => $form, 'billingAddress' => $billingAddress, 'client' => $client,
//    'billingAddress' => $billingAddress, 'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton,
//    'index' => 'new'));
?>