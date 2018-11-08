<?php

/**
 * Helper class to realise PayPal transactions
 *
 * @author Renaud Theuillon
 */
class PayPal {

    const USER_NAME = 'dh_uk_api1.directhomecare.com';
    const PASSWORD = '1367161081';
    const SIGNATURE = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AaxrUMNlFYwwOMpZ7YHrkkvaON3S';

    //const BUSINESS = 'ERFK23GWBRA2Q';

    public static function htmlPayment($amount, $currency, $client, $billingAddress) {
        ?>

        <div id="loading">Contacting PayPal please wait...</div>  <!-- RTRT  -->


        <iframe id ="payPalIFrame" name="hss_iframe" width="638px" height="550px" style="overflow-y: hidden"></iframe>

        <form style="display:none" target="hss_iframe" name="form_iframe"
              method="post"
              action="https://securepayments.sandbox.paypal.com/cgi-bin/acquiringweb">
            <input type="hidden" name="cmd" value="_hosted-payment">
            <input type="hidden" name="subtotal" value="<?php echo $amount ?>">
            <input type="hidden" name="business" value="<?php echo self::BUSINESS ?>">
            <input type="hidden" name="currency_code" value="<?php echo $currency ?>">
            <input type="hidden" name="paymentaction" value="sale">
            <input type="hidden" name="template" value="templateD">

            <input type="hidden" name="rm" value="2" /> 

            <input type="hidden" name="billing_address1" value="<?php echo $billingAddress->address_line_1 ?>">
            <?php
            if (isset($billingAddress->address_line_2)) {
                ?>
                <input type="hidden" name="billing_address2" value="<?php echo $billingAddress->address_line_2 ?>">
            <?php } ?>
            <input type="hidden" name="billing_city" value="<?php echo $billingAddress->city ?>">
            <input type="hidden" name="billing_country" value="<?php echo $billingAddress->country ?>">

            <input type="hidden" name="billing_first_name" value="<?php echo $client->first_name ?>">
            <input type="hidden" name="billing_last_name" value="<?php echo $client->last_name ?>">
            <input type="hidden" name="billing_zip" value="<?php echo $billingAddress->post_code ?>">

<!--            <input type="hidden" name="return" value="<?php echo Yii::app()->request->baseUrl; ?>/clientService/payPalReturn">-->
        </form>

        <script type="text/javascript">
            $("#loading").delay(5000).hide(0);
            document.form_iframe.submit();
        </script>

        <?php
    }

    public static function checkValidCard($creditCard) {

        return true;
    }

    public static function getTransactionDetail($tx) {

        // Set request-specific fields.
        $transactionID = urlencode($tx);


// Add request-specific fields to the request string.
        $nvpStr = "&TRANSACTIONID=$transactionID";

// Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = self::PPHttpPost('GetTransactionDetails', $nvpStr);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            return $httpParsedResponseAr;
        } else {
            return 'GetTransactionDetails failed: ' . var_dump($httpParsedResponseAr);
        }
    }

    public static function setExpressCheckout() {

        // Set request-specific fields.
        $paymentAmount = urlencode('example_payment_amuont');
        $currencyID = urlencode('USD');       // or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        $paymentType = urlencode('Authorization');    // or 'Sale' or 'Order'


        $returnURL = urlencode("my_return_url");
        $cancelURL = urlencode('my_cancel_url');

        // Add request-specific fields to the request string.
        $nvpStr = "&Amt=$paymentAmount&ReturnUrl=$returnURL&CANCELURL=$cancelURL&PAYMENTACTION=$paymentType&CURRENCYCODE=$currencyID";

        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = self::PPHttpPost('SetExpressCheckout', $nvpStr);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            // Redirect to paypal.com.
            $token = urldecode($httpParsedResponseAr["TOKEN"]);
            $payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token";
            if ("sandbox" === $environment || "beta-sandbox" === $environment) {
                $payPalURL = "https://www.$environment.paypal.com/webscr&cmd=_express-checkout&token=$token";
            }
            header("Location: $payPalURL");
            exit;
        } else {
            exit('SetExpressCheckout failed: ' . print_r($httpParsedResponseAr, true));
        }
    }

    public static function expressCheckout() {

        self::setExpressCheckout();

        /**
         * This example assumes that a token was obtained from the SetExpressCheckout API call.
         * This example also assumes that a payerID was obtained from the SetExpressCheckout API call
         * or from the GetExpressCheckoutDetails API call.
         */
        // Set request-specific fields.
        $payerID = urlencode("payer_id");
        $token = urlencode("token");

        $paymentType = urlencode("Authorization");   // or 'Sale' or 'Order'
        $paymentAmount = urlencode("payment_amount");
        $currencyID = urlencode("USD");      // or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
        // Add request-specific fields to the request string.
        $nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$paymentType&AMT=$paymentAmount&CURRENCYCODE=$currencyID";

        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = self::PPHttpPost('DoExpressCheckoutPayment', $nvpStr);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            exit('Express Checkout Payment Completed Successfully: ' . print_r($httpParsedResponseAr, true));
        } else {
            exit('DoExpressCheckoutPayment failed: ' . print_r($httpParsedResponseAr, true));
        }
    }

    public static function PPHttpPost($methodName_, $nvpStr_) {

        $environment = 'sandbox';

        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = urlencode(self::USER_NAME);
        $API_Password = urlencode(self::PASSWORD);
        $API_Signature = urlencode(self::SIGNATURE);
        //$API_Endpoint = "https://api-3t.paypal.com/nvp";

        $API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';

//        if ("sandbox" === $environment || "beta-sandbox" === $environment) {
//            $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
//        }
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
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }

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

    public static function getBalance() {

        $nvpStr = "";

        $httpParsedResponseAr = self::PPHttpPost('GetBalance', $nvpStr);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            exit('GetBalance Completed Successfully: ' . urldecode(print_r($httpParsedResponseAr, true)));
        } else {
            exit('GetBalance failed: ' . urldecode(print_r($httpParsedResponseAr, true)));
        }
    }

/**
 * 
 * @param type $client
 * @param type $creditCard
 * @param type $billingAddress
 * @param type $price
 * @return type array 
 * ACK Success
 * TRANSACTIONID Unique transaction ID of the payment.If the PaymentAction of the request was Authorization, the value of TransactionID is your AuthorizationID for use with the Authorization and Capture APIs.
 * AMT This value is the amount of the payment as specified by you on DoDirectPaymentRequest for reference transactions with direct payments.
 * AVSCODE Address Verification System response code. X Exact match
 * CVV2MATCH Result of the CVV2 check by PayPal.M Match, N No match, P Not processed, S Service not supported, U Service not available, X No response
 * CORRELATIONID Contains information about the PayPal application that processed the request. Use the value of this element if you need to troubleshoot a problem with one of your requests
 */
    public static function directPayment($client, $creditCard, $billingAddress, $price) {

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
        $httpParsedResponseAr = self::PPHttpPost('DoDirectPayment', $nvpStr);

        return $httpParsedResponseAr;
    }
    
    public static function isPaymnentSuccessful($httpParsedResponseAr){
        
        $result = $httpParsedResponseAr["ACK"];
        
        if($result == 'Success'){
            return true;
        }
        else{
            return false;
        }        
    }
    
    /**
     * Handle response from PayPal
     * @param type $httpParsedResponseAr
     */
    public static function handleDirectPaymentResponse($httpParsedResponseAr) {

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            exit('Direct Payment Completed Successfully: ' . print_r($httpParsedResponseAr, true));
        } else {
            exit('DoDirectPayment failed: ' . urldecode(print_r($httpParsedResponseAr, true)));
        }
    }

    public static function pay() {


        //value="PAY">Pay</option>
        //<option value="CREATE">Create</option>
        //<option value="PAY_PRIMARY">Pay Primary</option>

        $path = Yii::getPathOfAlias('webroot.protected.classes.paypal-sdk.lib.services.AdaptivePayments');

        $file = $path . '/AdaptivePayments.php';
        require_once $file;

        $file = $path . '/AdaptivePaymentsService.php';
        require_once $file;


        $logger = new PPLoggingManager('Pay');
        $i = 0;

        if (true) {
            $receiver = array();

            $receiver[$i] = new Receiver();
            $receiver[$i]->email = 'direct_1355843908_biz@directhomecare.com';
            $receiver[$i]->amount = '200';
            // $receiver[$i]->primary = true;
//            if ($_POST['invoiceId'][$i] != "") {
//                $receiver[$i]->invoiceId = $_POST['invoiceId'][$i];
//            }
            //if ($_POST['paymentType'][$i] != "" && $_POST['paymentType'][$i] != DEFAULT_SELECT) {
            $receiver[$i]->paymentType = 'SERVICE';
            //}
            //<option>- Select -</option>
            //<option>GOODS</option>
            //<option>SERVICE</option>
            //<option>PERSONAL</option>
            //<option>CASHADVANCE</option>
            //<option>DIGITALGOODS</option> 
            //   $receiver[$i]->paymentSubType = $_POST['paymentSubType'][$i];
            //$receiver[$i]->phone = new PhoneNumberType('44', '07893434223');
            //$receiver[$i]->phone->extension = '';
        }

        $receiverList = new ReceiverList($receiver);

        $serverName = $_SERVER['SERVER_NAME'];
        $serverPort = $_SERVER['SERVER_PORT'];
        $url = dirname('http://' . $serverName . ':' . $serverPort . $_SERVER['REQUEST_URI']);
        $returnUrl = $url . "/WebflowReturnPage.php";
        $cancelUrl = $url . "/Pay.php";


        $payRequest = new PayRequest(new RequestEnvelope("en_GB"), 'PAY', $cancelUrl, 'GBP', $receiverList, $returnUrl);

        // Add optional params
        //PRIMARYRECEIVER, EACHRECEIVER, SENDER, SECONDARYONLY
        //$payRequest->feesPayer = 'PRIMARYRECEIVER';

        $payRequest->preapprovalKey = '';


        $payRequest->ipnNotificationUrl = '';
        $payRequest->memo = '';
        $payRequest->pin = '';
        $payRequest->preapprovalKey = '';
        $payRequest->reverseAllParallelPaymentsOnError = '';
        $payRequest->senderEmail = 'platfo_1255077030_biz@gmail.com';
        $payRequest->trackingId = '';

        if (false) {
            $payRequest->fundingConstraint = new FundingConstraint();
            $payRequest->fundingConstraint->allowedFundingType = new FundingTypeList();
            $payRequest->fundingConstraint->allowedFundingType->fundingTypeInfo = array();
            $payRequest->fundingConstraint->allowedFundingType->fundingTypeInfo[] = new FundingTypeInfo('ECHECK');
        }


        //$payRequest->sender = new SenderIdentifier();
        //$payRequest->sender->email = 'senderEmail@hotmail.com';
//        $payRequest->sender->phone = new PhoneNumberType();
//
//        $payRequest->sender->phone->countryCode = '44';
//
//
//        $payRequest->sender->phone->phoneNumber = '0343434';
//
//        $payRequest->sender->phone->extension = '';
//
//        $payRequest->sender->useCredentials = '';



        $service = new AdaptivePaymentsService();

        try {
            $response = $service->Pay($payRequest);
        } catch (Exception $ex) {

            $path = Yii::getPathOfAlias('webroot.protected.classes.paypal-sdk.lib.samples.Common');

            $file = $path . '/Error.php';
            require_once $file;

            exit;
        }

        $logger->log("Received payResponse:");
        /* Make the call to PayPal to get the Pay token
          If the API call succeded, then redirect the buyer to PayPal
          to begin to authorize payment.  If an error occured, show the
          resulting errors */

        $logger->error("Received ConvertCurrencyResponse:");
        $ack = strtoupper($response->responseEnvelope->ack);
        if ($ack != "SUCCESS") {
            echo "<b>Error </b>";
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        } else {
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>$ack</div> </td></tr>";
            echo "</table>";
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }
    }

    public static function convert() {

        $path = Yii::getPathOfAlias('webroot.protected.classes.paypal-sdk.lib.services.AdaptivePayments');

        $file = $path . '/AdaptivePayments.php';
        require_once $file;

        $file = $path . '/AdaptivePaymentsService.php';
        require_once $file;

        $logger = new PPLoggingManager('ConvertCurrency');


        // create request
        $baseAmountList = new CurrencyList();
        $baseAmountList->currency[] = new CurrencyType('GBP', 200);


        $convertToCurrencyList = new CurrencyCodeList();

        $convertToCurrencyList->currencyCode[] = 'EUR';

        $convertCurrencyReq = new ConvertCurrencyRequest(new RequestEnvelope("en_GB"), $baseAmountList, $convertToCurrencyList);

        $convertCurrencyReq->countryCode = 'GB';

        //<option>SENDER_SIDE</option>
        //<option>RECEIVER_SIDE</option>
        //<option>BALANCE_TRANSFER</option>

        $convertCurrencyReq->conversionType = 'SENDER_SIDE';


        $logger->log("Created ConvertCurrencyRequest Object");


        $service = new AdaptivePaymentsService();
        try {
            $response = $service->ConvertCurrency($convertCurrencyReq);
        } catch (Exception $ex) {
            require_once 'Common/Error.php';
            exit;
        }

        $logger->error("Received ConvertCurrencyResponse:");
        $ack = strtoupper($response->responseEnvelope->ack);
        if ($ack != "SUCCESS") {
            echo "<b>Error </b>";
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        } else {
            echo "<table>";
            echo "<tr><td>Ack :</td><td><div id='Ack'>$ack</div> </td></tr>";
            echo "</table>";
            echo "<pre>";
            print_r($response);
            echo "</pre>";
        }
        //require_once 'Common/Response.php';
    }

    public static function doDirectPaymentOtherSyntax() {

        // Include config file
        //$path = Yii::getPathOfAlias('webroot.protected.config');
        //$file = $path . '/paypal.php';
        //require_once $file;


        $api_username = self::USER_NAME;
        $api_password = self::PASSWORD;
        $api_signature = self::SIGNATURE;

        //  Store request params in an array
        $request_params = array(
            'METHOD' => 'DoDirectPayment',
            'USER' => $api_username,
            'PWD' => $api_password,
            'SIGNATURE' => $api_signature,
            'VERSION' => '51.0',
            'PAYMENTACTION' => 'Sale',
            'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
            'CREDITCARDTYPE' => 'Visa',
            'ACCT' => '5522340006063638',
            'EXPDATE' => '022013',
            'CVV2' => '000',
            'FIRSTNAME' => 'John',
            'LASTNAME' => 'Smith',
            'STREET' => '12 Cambridge Gardens',
            'CITY' => 'London',
            'STATE' => '',
            'COUNTRYCODE' => 'GB',
            'ZIP' => 'W10 5UB',
            'AMT' => '100.00',
            'CURRENCYCODE' => 'GBP',
            'DESC' => 'Testing Payments Pro'
        );

        // Loop through $request_params array to generate the NVP string.
        $nvp_string = '';
        foreach ($request_params as $var => $val) {
            $nvp_string .= '&' . $var . '=' . urlencode($val);
        }

        // Send NVP string to PayPal and store response
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, 'https://api-3t.$environment.paypal.com/nvp');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
        $result = curl_exec($curl);
        curl_close($curl);

        // Parse the API response
        //$nvp_response_array = parse_str($result);

        $array = self::NVPToArray($result);

        echo var_dump($array);
    }

    // Function to convert NTP string to an array
    public static function NVPToArray($NVPString) {

        $proArray = array();

        while (strlen($NVPString)) {
            // name
            $keypos = strpos($NVPString, '=');
            $keyval = substr($NVPString, 0, $keypos);
            // value
            $valuepos = strpos($NVPString, '&') ? strpos($NVPString, '&') : strlen($NVPString);
            $valval = substr($NVPString, $keypos + 1, $valuepos - $keypos - 1);
            // decoding the respose
            $proArray[$keyval] = urldecode($valval);
            $NVPString = substr($NVPString, $valuepos + 1, strlen($NVPString));
        }

        return $proArray;
    }


}
?>
