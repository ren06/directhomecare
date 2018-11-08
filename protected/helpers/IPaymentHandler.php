<?php

/**
 * Description of IPaymentHandler
 *
 * @author Renaud Theuillon
 */
interface IPaymentHandler {
    //put your code here
    const ENVIRONMENT_LIVE = 'live';
    const ENVIRONMENT_TEST = 'test';
    
    public function doDirectPayment($client, $creditCard, $billingAddress, $price);
    public function doRefund($transactionRef, $price, $creditCardNumber, $transactionDate);
    public function isTransactionSuccessful();
    public function getTransactionCode();
    public function getTransactionRef();
    public function getTransactionDate();
    public function getLongErrorMessage();
}

?>
