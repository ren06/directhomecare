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
class BorgunHandler implements IPaymentHandler {
    //put your code here

    const TYPE_AUTHORIZATION = 'authorization';
    const TYPE_TRANSACTION_LIST = 'transactionList';
    const USERNAME_LIVE = 'directhomecare';
    const USERNAME_TEST = 'directhomecare';
    const PASSWORD_LIVE = 'kiBu.541kH';
    const PASSWORD_TEST = 'Hioc-345';
    const PROCESSOR_LIVE = '95';
    const PROCESSOR_TEST = '95';
    const MERCHANT_ID_LIVE = '3301';
    const MERCHANT_ID_TEST = '95';
    const URL_TEST = 'https://gatewaytest.borgun.is/ws/Heimir.pub.ws:Authorization';
    const URL_LIVE = 'https://gateway01.borgun.is/ws/Heimir.pub.ws:Authorization';
    const URL_LIVE_2 = 'https://gateway02.borgun.is/ws/Heimir.pub.ws:Authorization';

    private $url;
    private $username;
    private $password;
    private $processor;
    private $merchantId;
    private $response = array();
    private static $_instance = null;
    private $transactionRef;
    private $transactionDate;
    private $longErrorMessage;
    private $xmlRequest;
    private $environment;

    private function __construct($system) {

        $this->environment = $system;

        if ($system == self::ENVIRONMENT_TEST) {

            $this->url = self::URL_TEST;
            $this->username = self::USERNAME_TEST;
            $this->password = self::PASSWORD_TEST;
            $this->processor = self::PROCESSOR_TEST;
            $this->merchantId = self::MERCHANT_ID_TEST;
        } elseif ($system == self::ENVIRONMENT_LIVE) {

            $this->url = self::URL_LIVE;
            $this->username = self::USERNAME_LIVE;
            $this->password = self::PASSWORD_LIVE;
            $this->processor = self::PROCESSOR_LIVE;
            $this->merchantId = self::MERCHANT_ID_LIVE;
        } else {
            assert(false);
        }
    }

    /**
     * Create payment handler for Borgun
     * 
     * @param type $environment ENVIRONMENT_LIVE or ENVIRONMENT_TEST
     * @return type BorgunHandler
     */
    public static function getInstance($environment = null) {
        if (is_null(self::$_instance)) {

            if (!isset($environment)) {
                if (Yii::app()->params['test']['paymentTest'] == false) {
                    $environment = IPaymentHandler::ENVIRONMENT_LIVE;
                } else {
                    $environment = IPaymentHandler::ENVIRONMENT_TEST;
                }
            }

            self::$_instance = new BorgunHandler($environment);
        }
        return self::$_instance;
    }

    private function execute($payload, $type) {

        // echo print_r($payload, true);
        //var_dump($payload);

        $url = $this->url;

        // starta curl - athugaðu að curl þarf að vera enabled í php stillingum til að þetta virki
        $ch = curl_init();

        // stilli á basic auth
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // (username:password) fyrir aðgang
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);

        //set header á false
        curl_setopt($ch, CURLOPT_HEADER, false);

        // stilli á http post
        curl_setopt($ch, CURLOPT_POST, true);

        // includa skeytið í sendinguna
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Fá svar til baka á true
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Force SSLv3
        //curl_setopt($ch, CURLOPT_SSLVERSION, 3);

        if ($this->environment == IPaymentHandler::ENVIRONMENT_LIVE) {
            // If you receive errors when connecting to the production environment then try and fetch Mozilla CA 
            // Cert bundle from http://curl.haxx.se/docs/caextract.html or http://search.cpan.org/~abh/Mozilla-CA-20120823/lib/Mozilla/CA.pm.
            // and set CURLOPT_CAINFO as the path to cacert.pem.
            $certPath = Yii::getPathOfAlias('webroot.protected.certificates') . '/cacert.pem';
            curl_setopt($ch, CURLOPT_CAINFO, $certPath);
        } else {

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }

        // urlið hjá Borgun sem við sendum til - athugaðu þetta er test umhverfis urlið, raunumhverfis url-ið kemur frá Borgun
        curl_setopt($ch, CURLOPT_URL, $url);

        //in production this value must be set
        $ipAddress = Yii::app()->params['server']['IPAddress'];
        if ($ipAddress != '') {
            curl_setopt($ch, CURLOPT_INTERFACE, $ipAddress);
        }

        $result = curl_exec($ch);

        // error handling
        if (curl_errno($ch)) {
            $result1 = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
            $this->longErrorMessage = $result1;

            Yii::log($result1, CLogger::LEVEL_ERROR, 'payment');
            return false;
        } else {
            $returnCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            switch ($returnCode) {
                case 404:
                    $result1 = 'ERROR -> 404 Not Found';
                    Yii::log($result1, CLogger::LEVEL_ERROR, 'payment');
                    break;
                default:
                    break;
            }
        }

//        echo 'xml input: <br>';
//        echo print_r($payload, true);
//        echo '<br><br>';

        $xml = htmlspecialchars_decode($result);

        if ($type == self::TYPE_AUTHORIZATION) {

            $xmlTag = 'getAuthResXml';
        } elseif ($type == self::TYPE_TRANSACTION_LIST) {

            $xmlTag = 'transactionListXML';
        }


        $first = explode("<$xmlTag>", $xml);
        if (count($first) > 1) {
            $second = explode("</$xmlTag>", $first[1]);
        } else {
            var_dump($first);
            die();
        }
        $xml_object = new SimpleXMLElement($second[0]);

        $this->response = (array) $xml_object;

        return true;
    }

    /**
     * @param type $client Client
     * @param type $creditCard CreditCard
     * @param type $billingAddress Address
     * @param type $price Price
     * @return boolean true if successful, false if not
     */
    public function doDirectPayment($client, $creditCard, $billingAddress, $price) {

        //Common stuff
        $amnt = urlencode($price->amount);
        $amount = $amnt * 100;

        $currency = urlencode($price->currency);       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        if ($currency === 'GBP') {
            $currency = '826';
        }

        $dbDatetime = Calendar::today(Calendar::FORMAT_DBDATETIME);
        $dateTime = Calendar::convert_DBDateTime_BorgunDateTimeShortYear($dbDatetime);

        $processor = $this->processor;
        $merchantId = $this->merchantId;
        $terminalId = '1';

        //Sale Authorization        
        $transType = '1'; //normal transaction

        $creditCardNumber = urlencode($creditCard->card_number);
        $cardExpiryDate = $creditCard->expiry_date;
        $expiryDate = Calendar::convert_DBDateTime_Borgun_CardExpiryDate($cardExpiryDate);
        $cvv2Number = urlencode($creditCard->last_three_digits);

        $transactionRef = MissionPayment::getUniqueTransactioNumber(); //value followed by a sequence, for example ACME00012345. The last six letters must contain a numeric value.

        $this->transactionRef = $transactionRef;
        $this->transactionDate = $dateTime;

        //Payment payload
        $payload = '<?xml version="1.0" encoding="UTF-8"?>';
        $payload .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:aut="http://Borgun/Heimir/pub/ws/Authorization">';
        $payload .= '<soapenv:Header/>';
        $payload .= '<soapenv:Body>';
        $payload .= '<aut:getAuthorizationInput>';
        $payload .= '<getAuthReqXml>';
        $payload .= '&lt;?xml version="1.0" encoding="UTF-8"?&gt;';
        $payload .= '&lt;getAuthorization&gt;';
        $payload .= '&lt;Version&gt;1000&lt;/Version&gt;';
        $payload .= "&lt;Processor&gt;$processor&lt;/Processor&gt;";
        $payload .= "&lt;MerchantID&gt;$merchantId&lt;/MerchantID&gt;";
        $payload .= "&lt;TerminalID&gt;$terminalId&lt;/TerminalID&gt;";
        $payload .= "&lt;TransType&gt;$transType&lt;/TransType&gt;";
        $payload .= "&lt;TrAmount&gt;$amount&lt;/TrAmount&gt;";
        $payload .= "&lt;TrCurrency&gt;$currency&lt;/TrCurrency&gt;";
        $payload .= "&lt;DateAndTime&gt;$dateTime&lt;/DateAndTime&gt;";
        $payload .= "&lt;PAN&gt;$creditCardNumber&lt;/PAN&gt;";
        $payload .= "&lt;ExpDate&gt;$expiryDate&lt;/ExpDate&gt;";
        $payload .= "&lt;RRN&gt;$transactionRef&lt;/RRN&gt;";
        $payload .= "&lt;CVC2&gt;$cvv2Number&lt;/CVC2&gt;";
        $payload .= '&lt;/getAuthorization&gt;';
        $payload .= '</getAuthReqXml>';
        $payload .= '</aut:getAuthorizationInput>';
        $payload .= '</soapenv:Body>';
        $payload .= '</soapenv:Envelope>';

        $this->xmlRequest = $payload;

        //execute payload
        $technicalSuccess = $this->execute($payload, self::TYPE_AUTHORIZATION);

        if (!$technicalSuccess) {
            return false;
        }

        //logging if error
        if (!$this->isTransactionSuccessful()) {

            $msg = 'Payment Error ' . $this->getTransactionCode();
            if (isset($client)) {
                $msg .= ', Client ' . $client->id . ' ' . $client->fullName;
            }
            $msg .= ' Amount ' . $price->currency . ' ' . $price->amount;
            Yii::log($msg, CLogger::LEVEL_ERROR, 'payment');
        }

        return true;
    }

    /**
     * 
     * @param type $transaction Borgun transaction
     * @param type $price Price object
     * @param type $creditCardNumber card number
     * @param type $transactionDate format dbDateTime
     * 
     * @return boolean
     */
    public function doRefund($transactionRef, $price, $creditCardNumber, $transactionDate) {

        //convert to $transactionDate
        $transactionDate = Calendar::convert_DBDateTime_BorgunDateTimeShortYear($transactionDate);

        //Common stuff
        $amnt = urlencode($price->amount);
        $amount = $amnt * 100;

        $currency = urlencode($price->currency);       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        if ($currency === 'GBP') {
            $currency = '826';
        }

        $processor = $this->processor;
        $merchantId = $this->merchantId;
        $terminalId = '1';

        //Refund
        $transType = 3;

        $payload = '<?xml version="1.0" encoding="UTF-8"?>';
        $payload .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:aut="http://Borgun/Heimir/pub/ws/Authorization">';
        $payload .= '<soapenv:Header/>';
        $payload .= '<soapenv:Body>';
        $payload .= '<aut:getAuthorizationInput>';
        $payload .= '<getAuthReqXml>';

        $payload .= '&lt;?xml version="1.0" encoding="UTF-8"?&gt;';
        $payload .= '&lt;getAuthorization&gt;';
        $payload .= '&lt;Version&gt;1000&lt;/Version&gt;';
        $payload .= "&lt;Processor&gt;$processor&lt;/Processor&gt;";
        $payload .= "&lt;MerchantID&gt;$merchantId&lt;/MerchantID&gt;";
        $payload .= "&lt;TerminalID&gt;$terminalId&lt;/TerminalID&gt;";
        $payload .= "&lt;TransType&gt;$transType&lt;/TransType&gt;";
        $payload .= "&lt;TrAmount&gt;$amount&lt;/TrAmount&gt;";
        $payload .= "&lt;TrCurrency&gt;$currency&lt;/TrCurrency&gt;";
        $payload .= "&lt;DateAndTime&gt;$transactionDate&lt;/DateAndTime&gt;";
        $payload .= "&lt;PAN&gt;$creditCardNumber&lt;/PAN&gt;";
        $payload .= "&lt;RRN&gt;$transactionRef&lt;/RRN&gt;";
        $payload .= '&lt;/getAuthorization&gt;';

        $payload .= '</getAuthReqXml>';
        $payload .= '</aut:getAuthorizationInput>';
        $payload .= '</soapenv:Body>';
        $payload .= '</soapenv:Envelope>';

        $this->xmlRequest = $payload;

        // echo print_r($payload, true);

        $technicalSuccess = $this->execute($payload, self::TYPE_AUTHORIZATION);

        if (!$technicalSuccess) {
            return false;
        }

        //logging if error
        if (!$this->isTransactionSuccessful()) {

            $msg = 'Refund Error ' . $this->getTransactionCode();
            $msg .= ' Amount ' . $price->currency . ' ' . $price->amount;
            Yii::log($msg, CLogger::LEVEL_ERROR, 'payment');
        }

        return true;
    }

    public function doPartialReverse($transaction, $originalPrice, $refundPrice, $creditCardNumber) {

        //Common stuff
        $originalAmnt = urlencode($originalPrice->amount);
        $originalAmount = $originalAmnt * 100;

        $refundAmnt = urlencode($refundPrice->amount);
        $refundAmount = $refundAmnt * 100;

        $currency = urlencode($originalPrice->currency);       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        if ($currency === 'GBP') {
            $currency = '826';
        }

        $dbDatetime = Calendar::today(Calendar::FORMAT_DBDATETIME); //format YYMMDDhhmmss
        $dateTime = Calendar::convert_DBDateTime_BorgunDateTimeShortYear($dbDatetime);

        $processor = $this->processor;
        $merchantId = $this->merchantId;
        $terminalId = '1';

        //Partial Reverse
        $transType = 4;

        $payload = '<?xml version="1.0" encoding="UTF-8"?>';
        $payload .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:aut="http://Borgun/Heimir/pub/ws/Authorization">';
        $payload .= '<soapenv:Header/>';
        $payload .= '<soapenv:Body>';
        $payload .= '<aut:getAuthorizationInput>';
        $payload .= '<getAuthReqXml>';

        $payload .= '&lt;?xml version="1.0" encoding="UTF-8"?&gt;';
        $payload .= '&lt;getAuthorization&gt;';
        $payload .= '&lt;Version&gt;1000&lt;/Version&gt;';
        $payload .= "&lt;Processor&gt;$processor&lt;/Processor&gt;";
        $payload .= "&lt;MerchantID&gt;$merchantId&lt;/MerchantID&gt;";
        $payload .= "&lt;TerminalID&gt;$terminalId&lt;/TerminalID&gt;";
        $payload .= "&lt;TransType&gt;$transType&lt;/TransType&gt;";
        $payload .= "&lt;TrAmount&gt;$originalAmount&lt;/TrAmount&gt;";
        $payload .= "&lt;NewAmount&gt;$refundAmount&lt;/NewAmount&gt;";
        $payload .= "&lt;TrCurrency&gt;$currency&lt;/TrCurrency&gt;";
        $payload .= "&lt;DateAndTime&gt;$dateTime&lt;/DateAndTime&gt;";
        $payload .= "&lt;PAN&gt;$creditCardNumber&lt;/PAN&gt;";

        $payload .= "&lt;RRN&gt;$transaction&lt;/RRN&gt;";
        $payload .= '&lt;/getAuthorization&gt;';

        $payload .= '</getAuthReqXml>';
        $payload .= '</aut:getAuthorizationInput>';
        $payload .= '</soapenv:Body>';
        $payload .= '</soapenv:Envelope>';

        $this->xmlRequest = $payload;

        $technicalSuccess = $this->execute($payload, self::TYPE_AUTHORIZATION);

        if (!$technicalSuccess) {
            return false;
        }

        //logging if error
        if (!$this->isTransactionSuccessful()) {

            $msg = 'Refund Error ' . $this->getTransactionCode();
//            if (isset($client)) {
//                $msg .= ', Client ' . $client->id . ' ' . $client->fullName;
//            }
            $msg .= ' Amount Refunded ' . $refundPrice->currency . ' ' . $refundPrice->amount;
            Yii::log($msg, CLogger::LEVEL_ERROR, 'payment');
        }

        return true;
    }

    public function getTransactionHistory() {

        $fromDate = '20141201';

        $dbDatetime = Calendar::today(Calendar::FORMAT_DBDATETIME); //format YYMMDDhhmmss
        $toDate = Calendar::convert_DBDateTime_BorgunDate($dbDatetime);

        $processor = $this->processor;
        $merchantId = $this->merchantId;

        $payload = '<?xml version="1.0" encoding="UTF-8"?>';
        $payload .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:aut="http://Borgun/Heimir/pub/ws/Authorization">';
        $payload .= '<soapenv:Header/>';
        $payload .= '<soapenv:Body>';
        $payload .= '<aut:getTransactionList>';
        $payload .= '<transactionListReqXML>';


        $payload .= '&lt;?xml version="1.0" encoding="UTF-8"?&gt;';
        $payload .= '&lt;TransactionListRequest&gt;';
        $payload .= '&lt;Version&gt;1000&lt;/Version&gt;';
        $payload .= "&lt;Processor&gt;$processor&lt;/Processor&gt;";
        $payload .= "&lt;MerchantId&gt;$merchantId&lt;/MerchantId&gt;";
        //$payload .= "&lt;BatchNumber&gt;3&lt;/BatchNumber&gt;";
        //$payload .= "&lt;RRN&gt;$transaction&lt;/RRN&gt;";
        $payload .= "&lt;FromDate&gt;$fromDate&lt;/FromDate&gt;";
        $payload .= "&lt;ToDate&gt;$toDate&lt;/ToDate&gt;";
        $payload .= '&lt;/TransactionListRequest&gt;';

        $payload .= '</transactionListReqXML>';
        $payload .= '</aut:getTransactionList>';
        $payload .= '</soapenv:Body>';
        $payload .= '</soapenv:Envelope>';


        $technicalSuccess = $this->execute($payload, self::TYPE_TRANSACTION_LIST);

        if (!$technicalSuccess) {
            return false;
        }

        //logging if error
        if (!$this->isTransactionSuccessful()) {

            $msg = 'Error ' . $this->getTransactionCode();

            Yii::log($msg, CLogger::LEVEL_ERROR, 'payment');
        }

        return true;
    }

    /**
     * Technical error message
     * 
     * @return string
     */
    public function getLongErrorMessage() {

        if (isset($this->longErrorMessage)) {
            return $this->longErrorMessage;
        } else {
            return 'No execution';
        }
    }

    /**
     * Payment message
     * 
     * @return string
     */
    public function getPaymentMessage() {

        $response = $this->getResponse();

        if (isset($response['Message'])) {

            return $response['Message'];
        } else {

            $code = $this->getTransactionCode();

            return self::getCodeText($code);
        }
    }

    public static function getCodeText($code) {

        switch ($code) {

            case '000': return 'Accepted.';
            case '100': return 'Do not honor (Not accepted).';
            case '101': return 'Expired card.';
            case '102': return 'Suspected card forgery (fraud).';
            case '103': return 'Merchant call acquirer.';
            case '104': return 'Restricted card.';
            case '106': return 'Allowed PIN retries exceeded.';
            case '109': return 'Merchant not identified.';
            case '110': return 'Invalid amount.';
            case '111': return 'Invalid card number.';
            case '112': return 'PIN required.';
            case '116': return 'Not sufficient funds.';
            case '117': return 'Invalid PIN.';
            case '118': return 'Unknown card.';
            case '119': return 'Transaction not allowed to cardholder.';
            case '120': return 'Transaction not allowed to terminal.';
            case '121': return 'Exceeds limits to withdrawal.';
            case '125': return 'Card not valid.';
            case '126': return 'False PIN block.';
            case '129': return 'Suspected fraud.';
            case '130': return 'Invalid Track2.';
            case '131': return 'Invalid Expiration Date.';
            case '161': return 'DCC transaction not allowed to cardholder.';
            case '162': return 'DCC cardholder currency not supported.';
            case '163': return 'DCC exceeds time limit for withdrawal.';
            case '164': return 'DCC transaction not allowed to terminal.';
            case '165': return 'DCC not allowed to merchant.';
            case '166': return 'DCC unknown error.';
            case '201': return 'Card not valid.';
            case '904': return 'Form error.';
            default : return 'No  message, action code: ' . $code;
        }
    }

    public function getTransactionCode() {

        $response = $this->getResponse();
        return $response['ActionCode'];
    }

    /*
     * Payment or Refund successful
     */

    public function isTransactionSuccessful() {
        return $this->getTransactionCode() == '000';
    }

    public function getResponse() {

        return $this->response;
    }

    public function getTransactionRef() {

        if (isset($this->transactionRef)) {
            return $this->transactionRef;
        } else {
            'No execution';
        }
    }

    /**
     * 
     * @return type Borgun date format YYMMDDMMmmss
     */
    public function getTransactionDate() {

        if (isset($this->transactionDate)) {
            return $this->transactionDate;
        } else {
            'No execution';
        }
    }

    /**
     * 
     * Return proxy object
     * 
     * @return TransactionListResponse, contains property $transactions that has n TransactionListTransaction
     */
    public function getTransactionList() {

        $response = $this->getResponse();

        $transactionListResponse = new TransactionListResponse($response);

        return $transactionListResponse;
    }

    public static function getTransactionTypeText($transactionType) {

        switch ($transactionType) {

            case '1': return 'Purchase';
            case '3': return 'Refund';
            case '5': return 'Pre-auth.';
            default: return $transactionType;
        }
    }

    public function getXMLRequest() {

        return $this->xmlRequest;
    }

}

?>
