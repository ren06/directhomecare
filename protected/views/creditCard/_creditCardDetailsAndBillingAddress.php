<div id="card">
    <?php
    $form = $this->beginWidget('CActiveForm', array('id' => "card-form-$index",
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
            ));

    $this->renderPartial('/creditCard/_creditCardDetails', array('form' => $form, 'creditCard' => $creditCard, 'index' => $index));

    $this->endWidget();
    ?>

</div>
<div id="address">
    <?php
    $form = $this->beginWidget('CActiveForm', array('id' => "billing-address-form-$index",
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
        'stateful' => true,
            ));


    if (isset($creditCard->address)) {

        $addressId = $creditCard->address->id;
        $selectedBillingAddressRadioButton = 'radio_button_billing_address_' . $addressId . '-' . $index;
    } else {
        $selectedBillingAddressRadioButton = '';
    }

    $address = new Address();


    // echo '<br />';
//    $this->renderPartial('/creditCard/_billingAddress', array('form' => $form, 'billingAddress' => $address,
//        'client' => $client, 'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton, 'index' => $index));

    $this->endWidget();
    ?>
</div>