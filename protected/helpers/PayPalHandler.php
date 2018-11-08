<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PayPalHandler
 *
 * @author Renaud
 */
class PayPalHandler implements IPaymentHandler {
    //put your code here

    const USER_NAME = 'dh_uk_api1.directhomecare.com';
    const PASSWORD = '1367161081';
    const SIGNATURE = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AaxrUMNlFYwwOMpZ7YHrkkvaON3S';
    const ENVIRONMENT = 'sandbox';

    private $response = array();
    private static $_instance = null;

    /**
     * ACK Success
     * TRANSACTIONID Unique transaction ID of the payment.If the PaymentAction of the request was Authorization, the value of TransactionID is your AuthorizationID for use with the Authorization and Capture APIs.
     * AMT This value is the amount of the payment as specified by you on DoDirectPaymentRequest for reference transactions with direct payments.
     * AVSCODE Address Verification System response code. X Exact match
     * CVV2MATCH Result of the CVV2 check by PayPal.M Match, N No match, P Not processed, S Service not supported, U Service not available, X No response
     * CORRELATIONID Contains information about the PayPal application that processed the request. Use the value of this element if you need to troubleshoot a problem with one of your requests

     */
    private function __construct() {
        
    }

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new PayPalHandler();
        }
        return self::$_instance;
    }

    /**
     * @param type $client Client
     * @param type $creditCard CreditCard
     * @param type $billingAddress Address
     * @param type $price Price
     * @return boolean true if successful, false if not
     */
    public function doDirectPayment($client, $creditCard, $billingAddress, $price) {

        //Sale 
        //Authorization        
        $paymentType = urlencode('Sale');    // or 'Sale'       
        $firstName = urlencode($client->first_name);
        $lastName = urlencode($client->last_name);
        $creditCardType = urlencode($creditCard->getPayPalCardTypeLabel());
        $creditCardNumber = urlencode($creditCard->card_number);
        $expiryDate = $creditCard->expiry_date;

        $expDateYear = urlencode(substr($expiryDate, 0, 4));
        $expDateMonth = urlencode(substr($expiryDate, 5, 2));

        $cvv2Number = urlencode($creditCard->last_three_digits);
        $address1 = urlencode($billingAddress->address_line_1);
        $address2 = urlencode($billingAddress->address_line_2);
        $city = urlencode($billingAddress->city);
        $state = urlencode('');
        $zip = urlencode($billingAddress->post_code);
        $country = urlencode('GB');    // US or other valid country code
        $amount = urlencode($price->amount);
        $currencyID = urlencode($price->currency);       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        // Add request-specific fields to the request string.
        $nvpStr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber" .
                "&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName" .
                "&STREET=$address1&STREET2=$address2&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

        // Execute the API operation; see the PPHttpPost function above.
        $this->response = $this->payPalHttpPost('DoDirectPayment', $nvpStr);

        $result = $this->response["ACK"];

        if ($result == 'Success') {
            return true;
        } else {
            return false;
        }
    }

    public function refundTransaction($transactionId, $price, $memo) {

        // Set request-specific fields.
        $transactionID = urlencode($transactionId);
        $refundType = urlencode('Partial');      // or 'Partial'
        $amount = $price->amount;            // required if Partial.
        $memo = $memo;             // required if Partial.
        $currencyID = urlencode($price->currency);       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        // Add request-specific fields to the request string.
        $nvpStr = "&TRANSACTIONID=$transactionID&REFUNDTYPE=$refundType&CURRENCYCODE=$currencyID";

        if (isset($memo)) {
            $nvpStr .= "&NOTE=$memo";
        }

        if (strcasecmp($refundType, 'Partial') == 0) {
            if (!isset($amount)) {
                exit('Partial Refund Amount is not specified.');
            } else {
                $nvpStr = $nvpStr . "&AMT=$amount";
            }

            if (!isset($memo)) {
                exit('Partial Refund Memo is not specified.');
            }
        }

        $this->response = $this->payPalHttpPost('RefundTransaction', $nvpStr);

        $result = $this->response["ACK"];

        if ($result == 'Success') {
            return true;
        } else {
            return false;
        }
    }

    public function doNonReferencedCredit($client, $creditCard, $billingAddress, $price) {

        $firstName = urlencode($client->first_name);
        $lastName = urlencode($client->last_name);
        $creditCardType = urlencode($creditCard->getPayPalCardTypeLabel());
        $creditCardNumber = urlencode($creditCard->card_number);
        $expiryDate = $creditCard->expiry_date;

        $expDateYear = urlencode(substr($expiryDate, 0, 4));
        $expDateMonth = urlencode(substr($expiryDate, 5, 2));

        $cvv2Number = urlencode($creditCard->last_three_digits);
        $address1 = urlencode($billingAddress->address_line_1);
        $address2 = urlencode($billingAddress->address_line_2);
        $city = urlencode($billingAddress->city);
        $state = urlencode('');
        $zip = urlencode($billingAddress->post_code);
        $country = urlencode('GB');    // US or other valid country code
        $amount = urlencode($price->amount);
        $currencyID = urlencode($price->currency);       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        // Add request-specific fields to the request string.
        $nvpStr = "&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber" .
                "&EXPDATE=$expDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName" .
                "&STREET=$address1&STREET2=$address2&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

        // Execute the API operation; see the PPHttpPost function above.
        $this->response = $this->payPalHttpPost('DoNonReferencedCredit', $nvpStr);

        $result = $this->response["ACK"];

        if ($result == 'Success') {
            return true;
        } else {
            return false;
        }
    }

    public function getLongErrorMessage() {

        if (isset($this->response)) {
            return urldecode($this->response['L_LONGMESSAGE0']);
        } else {
            'No execution';
        }
    }

    public function getResponse() {

        return $this->response;
    }

    public function getTransactionId() {

        if (isset($this->response)) {
            return urldecode($this->response['TRANSACTIONID']);
        } else {
            'No execution';
        }
    }

    public function payPalHttpPost($methodName_, $nvpStr_) {

        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode(self::USER_NAME);
        $API_Password = urlencode(self::PASSWORD);
        $API_Signature = urlencode(self::SIGNATURE);
        $API_Endpoint = "https://api-3t.paypal.com/nvp";

        if ("sandbox" === self::ENVIRONMENT || "beta-sandbox" === self::ENVIRONMENT) {
            $API_Endpoint = "https://api-3t." . self::ENVIRONMENT . ".paypal.com/nvp";
        }
        $version = urlencode('51.0');

        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

        // Get response from the server.
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            //exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
            return false;
        } else {

            // Extract the response details.
            $httpResponseAr = explode("&", $httpResponse);

            $httpParsedResponseAr = array();
            foreach ($httpResponseAr as $i => $value) {
                $tmpAr = explode("=", $value);
                if (sizeof($tmpAr) > 1) {
                    $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
                }
            }

            if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
                exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
            }

            return $httpParsedResponseAr;
        }
    }

}

?>
