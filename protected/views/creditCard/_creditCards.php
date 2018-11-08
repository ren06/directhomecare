<?php
//displays all existing credit cards and generate new credit card

//used in:
// _changeBookingCards
// _maintainPaymentDetails

?>
<div id="creditCards">
    <?php
    $i = 0;
    foreach ($creditCards as $creditCard) {
        $this->renderPartial('/creditCard/_maintainCreditCard', array('client' => $client, 'creditCard' => $creditCard, 'index' => $i,
            'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));
        $i++;
    }
    ?>
</div>
<!-- New Credit card -->
<div id="creditCard_newContainer" style="display:none">
    <?php
    $this->renderPartial('/creditCard/_maintainCreditCard', array('client' => $client, 'creditCard' => new CreditCard(), 'index' => 'new',
        'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton));
    ?>
</div>