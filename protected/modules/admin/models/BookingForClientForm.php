<?php

class BookingForClientForm extends CFormModel {

    public $hourlyQuoteSimpleForm;

    public $carerId;
    public $serviceUserId;
    public $addressId;
    public $creditCardId;
    public $sendEMail;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('carerId, serviceUserId, addressId, creditCardId, sendEMail', 'required'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'date' => 'Date',
            'startTime' => 'Start time',
        );
    }

}