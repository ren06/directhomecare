<?php

class SandboxController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '/layouts/column1';
    public $typeConversion = array(
        'bigint' => 'long',
        'char' => 'string',
        'datetime' => 'timestamp',
        'decimal' => 'decimal',
        'float' => 'float',
        'int' => 'integer',
        'nchar' => 'string',
        'numeric' => 'double',
        'nvarchar' => 'string',
        'real' => 'float',
        'smalldatetime' => 'timestamp',
        'tinyint' => 'short',
        'varbinary' => 'binary',
        'varchar' => 'string',
    );
    public $ddicJDBCTypes = array(
        'string' => 'VARCHAR',
        'decimal' => 'DECIMAL',
        'double' => 'DOUBLE',
        'float' => 'REAL',
        'integer' => 'INTEGER',
        'long' => 'BIGINT',
        'short' => 'SMALLINT',
        'date' => 'DATE',
        'time' => 'TIME',
        'timestamp' => 'TIMESTAMP',
        'binary' => 'BLOB',
        'tinyint' => 'short',
        'varbinary' => 'binary',
        'varchar' => 'string',
    );

    private function decamelize($word) {
        return preg_replace(
                '/(^|[a-z])([A-Z])/e', 'strtolower(strlen("\\1") ? "\\1_\\2" : "\\2")', $word
        );
    }

    private function camelize($word) {
        return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $word);
    }

    public function actionTestCArrayDataProvider() {

        $rawData = array(array('column1' => 'data1line1', 'column2' => 'data2Line1'), array('column1' => 'data1line2', 'column2' => 'data2Line2'), array('column1' => 'data1line3', 'column2' => 'data2Line3'));


        $dataProvider = new CArrayDataProvider($rawData, array('keyField' => 'column1',));

        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
        ));
    }

    public function actionTestURLEncryption() {

        echo Encryption::encryptURLParam("yana.wadowska65@yahoo.com");
        $result = Encryption::decryptURLParam("fuMciZOGl%2BBwrdMUXPZcupuYPq3Ou0U0BLcDNihumdk%3D");
        $result = rawurldecode($id);
        $result = Encryption::decryptURL($id);
        echo $result;
        $message = new YiiMailMessage;

        $message->setBody("Test 2", 'text/html');
        $message->addTo($userModel->email);

        $message->addTo('rtheuillon@hotmail.com');
        Yii::app()->mail->getTransport()->setUsername('renaud.theuillon@googlemail.com');
        Yii::app()->mail->getTransport()->setPassword('');

        $message->from = Yii::app()->params['email']['adminEmail'];
        $message->subject = "A Test";

        echo Yii::app()->mail->send($message);
    }



    public function actionTestCurl() {

        $amount = 100;
        $from_Currency = 'GBP';
        $to_Currency = 'EUR';


        $amount = urlencode($amount);
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);

        $url = "http://www.google.com/ig/calculator?hl=en&q=$amount$from_Currency=?$to_Currency";

        $ch = curl_init();
        $timeout = 0;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawdata = curl_exec($ch);
        curl_close($ch);

        $data = explode('"', $rawdata);
        $data = explode(' ', $data['3']);
        $var = $data['0'];
        echo round($var, 2);
    }

    public function actionTestPayPal2() {

        PayPal::test2();
    }

    public function actionTestPayPalPay() {

        PayPal::pay();
    }

    public function actionTestPayPalTransactionDetail() {

        PayPal::getTransactionDetail();
    }

    //TEST ACTUEL
    public function actionTestPayPalDirectPayment() {


        $creditCard = CreditCard::loadModel(83);
        $client = $creditCard->client;
        $billingAddress = $creditCard->address;
        $price = new Price(250, 'GBP');

        $creditCard->card_number = '4044335369371653';

        PayPal::directPayment($client, $creditCard, $billingAddress, $price);
    }

    public function actionTestGetBalance() {

        PayPal::getBalance();
    }

    public function actionTestPayPalExpressCheckout() {

        PayPal::expressCheckout();
    }

    public function actionTestdoDirectPaymentSDK() {

        PayPal::doDirectPaymentSDK();
    }

    public function actionTestPaypalHTML() {

        PayPal::htmlPayment();
    }

    public function actionTestPayPal() {

        PayPal::convert();
    }

    public function actionTestMailTemplate() {

        $args = array(
            'key' => 'a7QUxQoWhNUbmXsRORmZsA',
            'template_name' => "client-registration-confirmation",
            'template_content' => array(
                array(
                    "name" => "clientName",
                    "content" => "John",
                ),
            ),
            'message' => array(
                "text" => null,
                "from_email" => "admin@directhomecare.com",
                "from_name" => "Direct Homecare",
                "subject" => "Your recent registration",
                "to" => array(array("email" => "rtheuillon@hotmail.com")),
                "track_opens" => true,
                "track_clicks" => true,
                "auto_text" => true
            )
        );
        // Open a curl session for making the call

        $curl = curl_init('https://mandrillapp.com/api/1.0/messages/send-template.json');

        // Tell curl to use HTTP POST
        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Tell curl not to return headers, but do return the response
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $args2 = json_encode($args);

        // Set the POST arguments to pass on
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args2);

        // Make the REST call, returning the result
        $response = curl_exec($curl);

        echo $response;
    }

    public function actionTestMail() {

        $args = array(
            'key' => 'a7QUxQoWhNUbmXsRORmZsA',
            'message' => array(
                "html" => "<p>\r\n\tHi Adam,</p>\r\n<p>\r\n\tThanks for <a href=\"http://mandrill.com\">registering</a>.</p>\r\n<p>etc etc</p>",
                "text" => null,
                "from_email" => "rtheuillon@hotmail.com",
                "from_name" => "Direct",
                "subject" => "Your recent registration",
                "to" => array(array("email" => "rtheuillon@hotmail.com")),
                "track_opens" => true,
                "track_clicks" => true,
                "auto_text" => true
            )
        );
        // Open a curl session for making the call

        $curl = curl_init('https://mandrillapp.com/api/1.0/messages/send.json');

        // Tell curl to use HTTP POST
        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Tell curl not to return headers, but do return the response
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $args2 = json_encode($args);

        // Set the POST arguments to pass on
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args2);

        // Make the REST call, returning the result
        $response = curl_exec($curl);

        echo $response;
    }

    public function actionTestEmailCarerSignup() {

        $carer = Carer::loadModel(1);

        Emails::sendToCarer_SignUp($carer);
    }

    public function actionTestEmailAllCarers() {

        Emails::sendToCarer_All_NewJob();
    }

    public function actionTestCreditCard() {

        $creditCard = CreditCard::loadModel(91);

        echo $creditCard->card_number;
        echo '<br>';
        echo var_dump(CreditCard::getCardNumberAdmin(91));
    }

    public function actionTestImage() {

        $doc = CarerDocument::loadModel(138);

        $doc->displaySavedImage();
    }

    public function actionCrop() {

        $url = CarerDocument::publishTempImage(45);

        $this->render('crop', array('url' => $url));
    }

    public function actionCropZoom() {

        // if (request()->getIsAjaxRequest()) {

        Yii::import('ext.cropzoom.JCropZoom');
        $saveToFilePath = Yii::getPathOfAlias('webroot.assets') . DIRECTORY_SEPARATOR . 'cropZoomTest';
        JCropZoom::getHandler()->process($saveToFilePath, true)->process($saveToFilePath . '.jpeg');
        die($saveToFilePath);
        //}

        $this->render('crop');
    }

    /**
     * @return void
     */
    public function actionHandleCropZoom() {

        if (request()->getIsAjaxRequest()) {

            Yii::import('ext.cropzoom.JCropZoom');
            $saveToFilePath = Yii::getPathOfAlias('webroot.assets') . DIRECTORY_SEPARATOR . 'cropZoomTest';
            //$res = JCropZoom::getHandler()->process($saveToFilePath, true);
            $res = JCropZoom::getHandler()->process($saveToFilePath . '.jpeg');



            $path = Yii::app()->request->hostInfo . Yii::app()->baseUrl . '/assets/cropZoomTest.jpeg';

            $result = "<img src='" . $path . "'>";
            die($result);
        }
    }

    public function actionCrop2() {

        $id = 45;

        $result = CarerDocument::publishTempImage($id);

        $this->render('crop2', array('url' => $result['url'], 'fileName' => $result['fileName'], 'id' => $id));
    }

    public function actionHandleCrop() {

        $doc = CarerDocument::loadModel($_POST['id']);

        $docFile = $doc->getFile();

        $docWidth = $docFile->width;

        $width = 400;

        $ratio = $docWidth / $width;

        $rootFolder = Yii::getPathOfAlias('application');
        $fileName = $_POST['fileName'];
        $originalFullPath = $rootFolder . '/../assets/' . $fileName;

        $saveToFilePath = Yii::getPathOfAlias('webroot.assets'); //. DIRECTORY_SEPARATOR . 'cropZoomTest';

        Yii::import('ext.jcrop.EJCropper');
        $jcropper = new EJCropper();
        $jcropper->thumbPath = $saveToFilePath; //'/my/images/thumbs';
        // some settings ...
        //$jcropper->jpeg_quality = 95;
        //$jcropper->png_compression = 8;
        $jcropper->targ_w = 96;
        $jcropper->targ_h = 120;

        // get the image cropping coordinates (or implement your own method)

        $x = $ratio * $_POST['imageId_x'];
        $y = $ratio * $_POST['imageId_y'];
        $h = $ratio * $_POST['imageId_h'];
        $w = $ratio * $_POST['imageId_w'];

        $coords = array('x' => $x, 'y' => $y, 'h' => $h, 'w' => $w);

        //$coords = $jcropper->getCoordsFromPost($_POST);
        // returns the path of the cropped image, source must be an absolute path.
        $fullFileName = $jcropper->crop($originalFullPath, $coords);

        //store in db
        $fileContent = new FileContentPhoto();
        $content = file_get_contents($fullFileName);

        $imageSize = getimagesize($fullFileName);
        $fileSize = filesize($fullFileName);

        //$fileContent->name = $fileName;
        $fileContent->type = 'application/octet-stream';
        $fileContent->size = $fileSize;
        //$fileContent->path = '';
        $fileContent->width = $imageSize[0];
        $fileContent->height = $imageSize[1];
        $fileContent->content = $content;
        $fileContent->extension = 'jpeg';

        $errors = $fileContent->validate();

        if ($fileContent->save()) {

            //delete temp file
            unlink($fullFileName);
        }

        $fileId = $fileContent->id;

        $doc->id_content_crop = $fileId;
        $res = $doc->save(false);
        $doc->refresh();

        $html = $doc->showCrop('', '');

        echo $html;
    }

    public function actionTestTimezone() {

        echo 'PHP Time: ' . Calendar::today(Calendar::FORMAT_DBDATETIME);
        echo '</br>';

        $sql = "SELECT NOW();";

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        echo 'DB Time: ' . "&#160;" . $result['NOW()'];
        ;
    }
    
    public function actionTestDates(){
        
        echo Calendar::numberDay_DBDate('2013-11-01', '2013-11-02');
    }

    public function actionTestOpenSSL() {

        $privateKey = openssl_pkey_new(array(
            'private_key_bits' => 1024,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ));

        echo openssl_error_string();
        //openssl_pkey_export_to_file($privateKey, 'private.key');
    }

    public function actionTestSage() {

        $simulatorURL = 'https://test.sagepay.com/Simulator/VSPDirectGateway.asp';


//        $strAbortURL = "https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorAbortTx";
//        $strAuthoriseURL = "https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorAuthoriseTx";
//        $strCancelURL = "https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorCancelTx";
//        $strPurchaseURL = "https://test.sagepay.com/simulator/VSPDirectGateway.asp";
//        $strRefundURL = "https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorRefundTx";
//        $strReleaseURL = "https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorReleaseTx";
//        $strRepeatURL = "https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorRepeatTx";
//        $strVoidURL = "https://test.sagepay.com/simulator/VSPServerGateway.asp?Service=VendorVoidTx";
//        $str3DCallbackPage = "https://test.sagepay.com/simulator/VSPDirectCallback.asp";
//        $strPayPalCompletionURL = "https://test.sagepay.com/simulator/paypalcomplete.asp";



        $strProtocol = "2.23";
        $strVendorName = 'directhomecare';
        $strTransactionType = 'PAYMENT';

        $strAuthoriseVendorTxCode = '';

        $sngAuthoriseAmount = 100;
        $strAuthoriseDescription = 'Test payment';
        $strAuthoriseVendorTxCode = Random::getRandomLetters(20); //This should be your own reference code to the transaction. Your site should provide a completely unique VendorTxCode for each transaction.

        $cardHolder = 'Renaud Theuillon';
        $cardNumber = '4929000000006';
        $expiryDate = '0620';
        $cardType = 'VISA';
        $CV2 = '123';
        $address = '88';
        $postCode = '412';

        //Build the Authorise message
        $strPost = "VPSProtocol=" . $strProtocol;
        $strPost = $strPost . "&TxType=" . $strTransactionType;
        $strPost = $strPost . "&Vendor=" . $strVendorName;
        $strPost = $strPost . "&VendorTxCode=" . $strAuthoriseVendorTxCode;
        $strPost = $strPost . "&Amount=" . number_format($sngAuthoriseAmount, 2);
        $strPost = $strPost . "&Currency=" . 'GBP';
        $strPost = $strPost . "&CardHolder=" . $cardHolder;
        $strPost = $strPost . "&CardNumber=" . $cardNumber;
        $strPost = $strPost . "&Description=" . $strAuthoriseDescription;
        $strPost = $strPost . "&ExpiryDate=" . $expiryDate;
        $strPost = $strPost . "&CardType=" . $cardType;
        $strPost = $strPost . "&CV2=" . $CV2;
        $strPost = $strPost . "&Address=" . $address;
        $strPost = $strPost . "&PostCode=" . $postCode;
        //$strPost = $strPost . "&CardType=" . $cardType;

        $strPost = $strPost . "&BillingSurname=" . 'Theuillon';
        $strPost = $strPost . "&BillingFirstnames=" . 'Renaud';
        $strPost = $strPost . "&BillingAddress1=" . 'address 1';
        $strPost = $strPost . "&BillingCity=" . 'London';
        $strPost = $strPost . "&BillingPostCode=" . 'W10 5UB';
        $strPost = $strPost . "&BillingCountry=" . 'GB';

        $strPost = $strPost . "&DeliverySurname=" . 'Theuillon';
        $strPost = $strPost . "&DeliveryFirstnames=" . 'Renaud';
        $strPost = $strPost . "&DeliveryAddress1=" . 'address 1';
        $strPost = $strPost . "&DeliveryCity=" . 'London';
        $strPost = $strPost . "&DeliveryPostCode=" . 'W10 5UB';
        $strPost = $strPost . "&DeliveryCountry=" . 'GB';



        // $strPost = $strPost . "&RelatedVPSTxId=" . $strVPSTxId;
        //$strPost = $strPost . "&RelatedVendorTxCode=" . $strVendorTxCode;
        //$strPost = $strPost . "&RelatedSecurityKey=" . $strSecurityKey;
        //$strPost = $strPost . "&RelatedTxAuthNo=" . $strTxAuthNo;
        // $strPost = $strPost . "&ApplyAVSCV2=0";

        $output = $this->requestPost($simulatorURL, $strPost);

        var_dump($output);
    }

    private function requestPost($url, $data) {
        // Set a one-minute timeout for this script
        set_time_limit(60);

        // Initialise output variable
        $output = array();

        // Open the cURL session
        $curlSession = curl_init();

        // Set the URL
        curl_setopt($curlSession, CURLOPT_URL, $url);
        // No headers, please
        curl_setopt($curlSession, CURLOPT_HEADER, 0);
        // It's a POST request
        curl_setopt($curlSession, CURLOPT_POST, 1);
        // Set the fields for the POST
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, $data);
        // Return it direct, don't print it out
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
        // This connection will timeout in 30 seconds
        curl_setopt($curlSession, CURLOPT_TIMEOUT, 30);
        //The next two lines must be present for the kit to work with newer version of cURL
        //You should remove them if you have any problems in earlier versions of cURL
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 1);

        //Send the request and store the result in an array

        $rawresponse = curl_exec($curlSession);
        //Store the raw response for later as it's useful to see for integration and understanding 
        $_SESSION["rawresponse"] = $rawresponse;
        //Split response into name=value pairs
        $response = explode(chr(10), $rawresponse);
        // Check that a connection was made
        if (curl_error($curlSession)) {
            // If it wasn't...
            $output['Status'] = "FAIL";
            $output['StatusDetail'] = curl_error($curlSession);
        }

        // Close the cURL session
        curl_close($curlSession);

        // Tokenise the response
        for ($i = 0; $i < count($response); $i++) {
            // Find position of first "=" character
            $splitAt = strpos($response[$i], "=");
            // Create an associative (hash) array with key/value pairs ('trim' strips excess whitespace)
            $output[trim(substr($response[$i], 0, $splitAt))] = trim(substr($response[$i], ($splitAt + 1)));
        } // END for ($i=0; $i<count($response); $i++)
        // Return the output
        return $output;
    }

    public function actionTestBorgun() {

        

        $merchantId = '95';
        $processor = '95';
        $merchantContractNumber = '9256684';
        $terminalId = '1';
        $transType = '1'; //normal transaction
        $amount = 10000; //amount in cents
        $currency = '826'; //GBP ISO

        $dbDatetime = Calendar::today(Calendar::FORMAT_DBDATETIME); //format YYMMDDhhmmss

        $dateTime = Calendar::convert_DBDateTime_BorgunDateTimeShortYear($dbDatetime);

        $cardNumber = '5587402000012029';
        $expirationDate = '1409';

        $transactionRef = MissionPayment::getUniqueTransactioNumber(); //value followed by a sequence, for example ACME00012345. The last six letters must contain a numeric value.
        //check exists


        $cvc2 = '123';



        //2013-07-18 12:06:24
        // payloadið er skeytið sem sent er til Borgunar
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
        $payload .= "&lt;PAN&gt;$cardNumber&lt;/PAN&gt;";
        $payload .= "&lt;ExpDate&gt;$expirationDate&lt;/ExpDate&gt;";
        $payload .= "&lt;RRN&gt;$transactionRef&lt;/RRN&gt;";
        $payload .= "&lt;CVC2&gt;$cvc2&lt;/CVC2&gt;";
        $payload .= '&lt;/getAuthorization&gt;';
        $payload .= '</getAuthReqXml>';
        $payload .= '</aut:getAuthorizationInput>';
        $payload .= '</soapenv:Body>';
        $payload .= '</soapenv:Envelope>';


        // starta curl - athugaðu að curl þarf að vera enabled í php stillingum til að þetta virki
        $ch = curl_init();

        // stilli á basic auth
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // (username:password) fyrir aðgang
        curl_setopt($ch, CURLOPT_USERPWD, "directhomecare:Hioc-345");

        //set header á false
        curl_setopt($ch, CURLOPT_HEADER, false);

        // stilli á http post
        curl_setopt($ch, CURLOPT_POST, true);

        // includa skeytið í sendinguna
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Fá svar til baka á true
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Force SSLv3
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        // If you receive errors when connecting to the production environment then try and fetch Mozilla CA 
        // Cert bundle from http://curl.haxx.se/docs/caextract.html or http://search.cpan.org/~abh/Mozilla-CA-20120823/lib/Mozilla/CA.pm.
        // and set CURLOPT_CAINFO as the path to cacert.pem.
        $certPath = Yii::getPathOfAlias('webroot.protected.certificates') . '/cacert.pem';

        curl_setopt($ch, CURLOPT_CAINFO, $certPath);
        // urlið hjá Borgun sem við sendum til - athugaðu þetta er test umhverfis urlið, raunumhverfis url-ið kemur frá Borgun
        curl_setopt($ch, CURLOPT_URL, $url);

        // keyri curl og fá svar í $result variable 
        $result = curl_exec($ch);

        // error handling
        if (curl_errno($ch)) {
            $result1 = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
        } else {
            $returnCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            switch ($returnCode) {
                case 404:
                    $result1 = 'ERROR -> 404 Not Found';
                    break;
                default:
                    break;
            }
        }

        # Þú getur gert var_dump($result); og fengið allt svarið dumpað á skjáinn.
        #
	# Svarið verður í xml formatti, þá þarftu að búa til xml object úr svarinu og vinna síðan með það í restinni á scriptinu.
        // Fyrst notum við htmlspecialchar_decode til að breyta &lt; og &gt; í < og > svo að parserinn þekki strengin sem xml
        $xml = htmlspecialchars_decode($result);

        // losum okkur síðan við smá texta frá svarinu og komum út með hreint og tært xml skjal fyrir parserinn	
        $first = explode('<getAuthResXml>', $xml);
        $second = explode('</getAuthResXml>', $first[1]);

        // Við notum SimpleXMLElement classinu sem fylgir php til að parsa xmlið í object sem við notum síðan	
        $xml_object = new SimpleXMLElement($second[0]);

        // Dæmi um notkun:
        //	echo $xml_object->CardType;
        //	þá færðu gildið frá CardType fieldinum í svarinu
        //	
        // Restin ætti að vera auðveld

        echo print_r($xml_object);
    }

    
    
}