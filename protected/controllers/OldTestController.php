<?php

class TestController extends Controller {

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

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                //'actions' => array('registration', 'populate', 'profile', 'create', 'testAjax'),
                'expression' => "UserIdentityUser::isGuest()", //'expression'=>'isset($user->role) && ($user->role==="editor")'
                'users' => array('*'),
            ),
        );
    }

    private function decamelize($word) {
        return preg_replace(
                '/(^|[a-z])([A-Z])/e', 'strtolower(strlen("\\1") ? "\\1_\\2" : "\\2")', $word
        );
    }

    private function camelize($word) {
        return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $word);
    }

    public function actionTestCreateDTD() {

        $tableCreationStart =
                '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                '<!-- MetaDataAPI generated on: Saturday, October 22, 2011 4:53:42 PM CEST -->' . PHP_EOL .
                '<DtDbTable xmlns="http://xml.sap.com/2002/10/metamodel/dictionary" xmlns:IDX="urn:sap.com:DtDictionary.DtDbTable:2.0" mmRelease="6.30" mmVersion="2.0" mmTimestamp="1319295222937" name="TABLE_NAME" package="com.sap.mii.rds.ddic.tables" masterLanguage="en">' . PHP_EOL;

        $tableCreationEnd = '</DtDbTable>' . PHP_EOL . PHP_EOL;

        $structureStart = "\t" . '<DtStructure.StructureElements>' . PHP_EOL;

        $structureEnd = "\t" . '</DtStructure.StructureElements>' . PHP_EOL;

        $fieldCreation =
                "\t\t" . '<DtField builtInType="DDIC_TYPE" dbDefault=" " jdbcTypeName="JDBC_TYPE_NAME" name="FIELD_NAME" notNull="NOT_NULL" position="INDEX">' . PHP_EOL .
                "\t\t\t" . '<DtField.ReferencedType>' . PHP_EOL .
                "\t\t\t\t" . '<Core.Reference package="com.sap.dictionary" name="DDIC_TYPE" type="DtSimpleType"/>' . PHP_EOL .
                "\t\t\t" . '</DtField.ReferencedType>' . PHP_EOL .
                "\t\t" . '</DtField>' . PHP_EOL;

        $fieldCreationDecimal =
                "\t\t" . '<DtField builtInType="DDIC_TYPE" dbDefault="0" fractionDigits="FRACTION_DIGITS" jdbcTypeName="DECIMAL" name="FIELD_NAME" notNull="NOT_NULL" position="INDEX" totalDigits="TOTAL_DIGITS">' . PHP_EOL .
                "\t\t\t" . '<DtField.ReferencedType>' . PHP_EOL .
                "\t\t\t\t" . '<Core.Reference package="com.sap.dictionary" name="DDIC_TYPE" type="DtSimpleType"/>' . PHP_EOL .
                "\t\t\t" . '</DtField.ReferencedType>' . PHP_EOL .
                "\t\t" . '</DtField>' . PHP_EOL;

        $fieldCreationString =
                "\t\t" . '<DtField builtInType="DDIC_TYPE" dbDefault=" " jdbcTypeName="VARCHAR" length="LENGTH" name="FIELD_NAME" notNull="NOT_NULL" position="INDEX">' . PHP_EOL .
                "\t\t\t" . '<DtField.ReferencedType>' . PHP_EOL .
                "\t\t\t\t" . '<Core.Reference package="com.sap.dictionary" name="DDIC_TYPE" type="DtSimpleType"/>' . PHP_EOL .
                "\t\t\t" . '</DtField.ReferencedType>' . PHP_EOL .
                "\t\t" . '</DtField>' . PHP_EOL;


        $primaryKeyStart =
                "\t" . '<DtDbTable.PrimaryKey>' . PHP_EOL .
                "\t\t" . '<DtPrimaryKey name="PrimaryKey">' . PHP_EOL .
                "\t\t\t" . '<DtPrimaryKey.KeyElements>' . PHP_EOL;

        $primaryKeyTemplate =
                "\t\t\t\t" . '<Core.Reference package="" name="TABLE_NAME" type="DtDbTable" path="StructureElement:FIELD_NAME"/>' . PHP_EOL;
        //		<Core.Reference package="" name="R_OBJ_DET_BY_LANG" type="DtDbTable" path="StructureElement:OBJECT_HIER_ID"/>
        $primaryKeyEnd =
                "\t\t\t" . '</DtPrimaryKey.KeyElements>' . PHP_EOL .
                "\t\t" . '</DtPrimaryKey>' . PHP_EOL .
                "\t" . '</DtDbTable.PrimaryKey>' . PHP_EOL;

        $result = '';

        if (isset($_POST['sql'])) {

            $sql = $_POST['sql'];

            $tables = explode("\n-", $sql);

            foreach ($tables as $table) {

                $sqlLines = explode("\n", $table);

                $index = -1;
                $fieldsXML = '';
                $primaryKeysXML = '';

                foreach ($sqlLines as $sqlLine) {

                    $sqlLine = trim($sqlLine);

                    if ($sqlLine == "") {
                        continue;
                    }


                    if ($index == -1) {
                        $tableName = 'R_' . $sqlLine;

                        if ($tableName == 'R_HTML') {
                            echo 'test';
                        }

                        $tableXMLStart = str_replace('TABLE_NAME', $tableName, $tableCreationStart);
                        $index = 0;
                        continue;
                    }

                    //explodes line
                    $values = explode("\t", $sqlLine);

                    //COLUMN_NAME, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION, NUMERIC_SCALE
                    //$values[0] = field name
                    $fieldName = $values[0];
                    $dataType = $values[1];

                    $javaDicType = $this->typeConversion[$dataType];
                    $jdbcTypeName = $this->ddicJDBCTypes[$javaDicType];

                    $notNull = ($values[2] == 'YES') ? 'false' : 'true';

                    $isPrimaryKey = (isset($values[6]) && $values[6] == 'YES') ? true : false;

                    if ($javaDicType == 'decimal') {
                        $precision = $values[4];
                        $scale = trim($values[5]);

                        $fieldXML = str_replace('JDBC_TYPE_NAME', $jdbcTypeName, $fieldCreationDecimal);
                        $fieldXML = str_replace('DDIC_TYPE', $javaDicType, $fieldXML);
                        $fieldXML = str_replace('NOT_NULL', $notNull, $fieldXML);
                        $fieldXML = str_replace('INDEX', $index, $fieldXML);
                        $fieldXML = str_replace('FIELD_NAME', $fieldName, $fieldXML);
                        $fieldXML = str_replace('FRACTION_DIGITS', $scale, $fieldXML);
                        $fieldXML = str_replace('TOTAL_DIGITS', $precision, $fieldXML);
                    } elseif ($javaDicType == 'string') {
                        $charMaxLength = $values[3];

                        $fieldXML = str_replace('JDBC_TYPE_NAME', $jdbcTypeName, $fieldCreationString);
                        $fieldXML = str_replace('DDIC_TYPE', $javaDicType, $fieldXML);
                        $fieldXML = str_replace('NOT_NULL', $notNull, $fieldXML);
                        $fieldXML = str_replace('INDEX', $index, $fieldXML);
                        $fieldXML = str_replace('FIELD_NAME', $fieldName, $fieldXML);
                        $fieldXML = str_replace('LENGTH', $charMaxLength, $fieldXML);

                        if ($charMaxLength > 1333) {

                            //type is CLOB                            
                            $fieldXML = str_replace('<DtField builtInType="string" dbDefault=" " jdbcTypeName="VARCHAR"', '<DtField builtInType="string" jdbcTypeName="CLOB"', $fieldXML);
                        }
                    } else {

                        $fieldXML = str_replace('JDBC_TYPE_NAME', $jdbcTypeName, $fieldCreation);
                        $fieldXML = str_replace('DDIC_TYPE', $javaDicType, $fieldXML);
                        $fieldXML = str_replace('NOT_NULL', $notNull, $fieldXML);
                        $fieldXML = str_replace('INDEX', $index, $fieldXML);
                        $fieldXML = str_replace('FIELD_NAME', $fieldName, $fieldXML);
                    }

                    if ($isPrimaryKey) {

                        $primaryKeyXML = str_replace('TABLE_NAME', $tableName, $primaryKeyTemplate);
                        $primaryKeyXML = str_replace('FIELD_NAME', $fieldName, $primaryKeyXML);
                        $primaryKeysXML .= $primaryKeyXML;
                    }

                    $fieldsXML .= $fieldXML;

                    $index++;
                }

                $tableResult = $tableXMLStart . $structureStart . $fieldsXML . $structureEnd;

                if (strlen($primaryKeysXML) > 1) {
                    $tableResult .= $primaryKeyStart . $primaryKeysXML . $primaryKeyEnd;
                }

                $tableResult .= $tableCreationEnd;

                //create file

                $path = "C:/eclipse/workspace.jdi/LocalDevelopment/RDS_BATCH_MFG_PC/sap.com/batch_mfg_db/_comp/src/packages/";

                //$path = "C:/Users/i031360/Desktop/";

                $fh = fopen($path . $tableName . '.dtdbtable', 'wb');
                fputs($fh, $tableResult);
                fclose($fh);

                $result = $result . $tableResult;
            }
        } else {
            $sql = '';
            $result = '';
        }

        $this->render('createDTDTable', array('sql' => $sql, 'result' => $result));
    }

    public function actionTestCreateFieldNames() {

        if (isset($_POST['originalFieldNames'])) {

            $originalFieldNames = $_POST['originalFieldNames'];

            $lines = explode("\n", $originalFieldNames);

            $result = '';

            $currentTable = '';

            foreach ($lines as $line) {

                $columns = explode("\t", $line);

                $table = $columns[0];

                if ($currentTable != $table) {

                    //new table
                    $result = $result . PHP_EOL . $table . PHP_EOL;
                    $currentTable = $table;
                }

                $toAdd = str_replace($table . "\t", '', $line);

                $result = $result . $toAdd;
                //$res = strtoupper($this->decamelize($field));
            }

            $fieldsNames = $result;
        } else {
            $originalFieldNames = '';
            $result = '';
        }

        $this->render('createFieldNames', array('result' => $result, 'originalFieldNames' => $originalFieldNames));
    }

    public function actionTestConvertFieldNames() {

        if (isset($_POST['originalFieldNames'])) {

            $originalFieldNames = $_POST['originalFieldNames'];

            $fieldsExploded = explode("\n", $originalFieldNames);

            $result = array();

            foreach ($fieldsExploded as $field) {

                $res = strtoupper($this->decamelize($field));

//                if (strlen($res) > 16) { // field names, 16 DB name
//                    $res = $res . strlen($res);
//                }

                $result[] = $res;
            }

            $fieldsNames = $result;
        } else {
            $originalFieldNames = '';
            $fieldsNames = array();
        }

        $this->render('convertFieldNames', array('fieldsNames' => $fieldsNames, 'originalFieldNames' => $originalFieldNames));
    }

    public function actionTestCreditCarers() {

        Mission::creditCarers();
    }

    public function actionTestCArrayDataProvider() {

        $rawData = array(array('column1' => 'data1line1', 'column2' => 'data2Line1'), array('column1' => 'data1line2', 'column2' => 'data2Line2'), array('column1' => 'data1line3', 'column2' => 'data2Line3'));


        $dataProvider = new CArrayDataProvider($rawData, array('keyField' => 'column1',));

        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'cssFile' => Yii::app()->request->baseUrl . '/css/grid.css',
        ));
    }

    public function actionTestLiveInMissionSlots() {

        $startDate = $date = mktime(0, 0, 0, 12, 1, date("Y"));
        $startDate = date('d/m/Y', $startDate);
        $endDate = $date = mktime(0, 0, 0, 12, 29, date("Y"));
        $endDate = date('d/m/Y', $endDate);

        //$startDate = '2012-12-13';
        //$endDate = '2013-01-15';

        $startDate = '2012-12-13';
        $endDate = '2012-12-31';

        $result = Calendar::getLiveInMissionSlots($startDate, $endDate);


        echo $startDate . ' - ' . $endDate;
        echo '</br>';
        echo var_dump($result);
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

    public function actionTestVersion() {

        $this->render('version');
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

//        $url = "https://api-3t.sandbox.paypal.com/nvp";
//
//        $userId = 'gb_1355828353_per@directhomecare.com';
//        $userPassword = 'tinquiete';
//
//        $url .= "?USER=$userId&PWD=$userPassword&SIGNATURE=YourSignature&METHOD=SetExpressCheckout&VERSION=78&AMT=10&cancelUrl=http://www.yourdomain.com/cancel.html&returnUrl=http://www.yourdomain.com/success.html";
//
//        $ch = curl_init();
//        $timeout = 0;
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//        $rawdata = curl_exec($ch);
//        curl_close($ch);
//
//        $data = explode('"', $rawdata);
//        $data = explode(' ', $data['3']);
//        $var = $data['0'];
//        echo round($var, 2);
//
//
        //$test = 'curl -s --insecure https://api-3t.sandbox.paypal.com/nvp -d "USER=YourUserID&PWD=YourPassword&SIGNATURE=YourSignature&METHOD=SetExpressCheckout&VERSION=78&AMT=10&cancelUrl=http://www.yourdomain.com/cancel.html&returnUrl=http://www.yourdomain.com/success.html"';
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

}