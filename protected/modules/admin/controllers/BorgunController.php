<?php

class BorgunController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '/layouts/column2';
    public $defaultAction = 'index';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function actionTransactionHistory($environment = null) {

        if (!isset($environment)) {
            if (Yii::app()->params['test']['paymentTest'] == false) {
                $environment = IPaymentHandler::ENVIRONMENT_LIVE;
            } else {
                $environment = IPaymentHandler::ENVIRONMENT_TEST;
            }
        }

        $paymentHandler = BorgunHandler::getInstance($environment);
        //$success = $paymentHandler->doRefund($transaction, $price);   
        $success = $paymentHandler->getTransactionHistory();

        if ($success) {

            $transactionList = $paymentHandler->getTransactionList();

            $transactions = $transactionList->transactions;

            foreach ($transactions as $transaction) {

                $transactionType = BorgunHandler::getTransactionTypeText($transaction->transactionType) . '(' . $transaction->transactionType . ')';
                //$transactionDate = Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate);
                $transactionDate = $transaction->transactionDate;

                if ($transaction->trCurrency == '826') {
                    $currency = 'GBP';
                } else {
                    $currency = 'XXX';
                }

                $amountPrice = new Price($transaction->trAmount / 100, $currency);
                $actionCode = $transaction->actionCode . ' (' . BorgunHandler::getCodeText($transaction->actionCode) . ')';

                $row = array(
                    'TransactionType' => $transactionType,
                    'TransactionNumber' => $transaction->transactionNumber,
                    'BatchNumber' => $transaction->batchNumber,
                    'FormattedTransactionDate' => Calendar::convert_BorgunDateTime_DisplayDateTime($transaction->transactionDate),
                    'TransactionDate' => $transactionDate,
                    'PAN' => $transaction->PAN,
                    'RRN' => $transaction->RRN,
                    'ActionCode' => $actionCode,
                    'AuthorizationCode' => $transaction->authorizationCode,
                    'Amount' => $amountPrice->text,
                    'Voided' => $transaction->voided,
                    'Status' => $transaction->status,
                );

                $rawData[] = $row;
            }

            //we want the latest transaction first

            $rawData = array_reverse($rawData);

            $dataProvider = new CArrayDataProvider($rawData, array(
                'keyField' => 'TransactionNumber',
                'totalItemCount' => count($rawData),
                'pagination' => array('pageSize' => 100,),
            ));

            $this->render('borgunTransactionHistory', array('dataProvider' => $dataProvider));
        } else {
            echo $paymentHandler->getLongErrorMessage();
        }
        //echo print_r($response, true);
    }

    public function actionTestBorgunRefund() {

        //default values
        $transaction = '';
        $amount = '1';
        $environment = 'test';
        $refundType = 'refund_sale';
        $creditCardNumber = '';
        $originalAmount = '';
        $transactionDate = '';

        if (isset($_POST['environment'])) {
            $environment = $_POST['environment'];
        }
        if (isset($_POST['refundType'])) {
            $refundType = $_POST['refundType'];
        }
        if (isset($_POST['amount'])) {
            $amount = $_POST['amount'];
        }
        if (isset($_POST['transaction'])) {
            $transaction = $_POST['transaction'];
        }
        if (isset($_POST['transactionDate'])) {
            $transactionDate = $_POST['transactionDate'];
        }

        if (!empty($_POST)) {

            if (isset($_POST['amount']) && isset($_POST['transaction']) && isset($_POST['transactionDate'])) {

                $amount = trim($_POST['amount']);
                $price = new Price($amount, 'GBP');
                $transaction = trim($_POST['transaction']);
                $transactionDate = $_POST['transactionDate'];

                $creditCardNumber = trim($_POST['creditCardNumber']);

                $paymentHandler = BorgunHandler::getInstance($environment);

                if ($refundType == 'refund_sale') {
                    $success = $paymentHandler->doRefund($transaction, $price, $creditCardNumber, $transactionDate);
                } else {
                    $originalAmount = $_POST['originalAmount'];
                    $originalPrice = new Price($originalAmount, 'GBP');
                    $success = $paymentHandler->doPartialReverse($transaction, $originalPrice, $price, $creditCardNumber);
                }

                if ($success) {

                    //$transactionId = $paymentHandler->getTransactionId();
                    //$response = $paymentHandler->getResponse();
                    //$res = print_r($response, TRUE);

                    $paymentSuccessful = $paymentHandler->isTransactionSuccessful();

                    if (!$paymentSuccessful) {
                        $errorMessage = '<br>Code: ' . $paymentHandler->getTransactionCode();
                        $errorMessage .= '<br>Message: ' . $paymentHandler->getPaymentMessage();

                        Yii::app()->user->setFlash('error', 'Refund Failed <br>' . $errorMessage);
                    } else {
                        Yii::app()->user->setFlash('success', 'Refund successful <br>');
                    }
                } else {
                    $errorMessage = $paymentHandler->getLongErrorMessage();
                    Yii::app()->user->setFlash('error', 'Borgun Error: ' . $errorMessage);
                }
            } else {
                Yii::app()->user->setFlash('error', 'Enter amount, transaction reference and transaction date <br>');
            }
        }

        $this->render('borgunRefund', array('environment' => $environment, 'amount' => $amount,
            'transaction' => $transaction, 'creditCardNumber' => $creditCardNumber,
            'refundType' => $refundType, 'originalAmount' => $originalAmount,
            'transactionDate' => $transactionDate));
    }

    //full test
    public function actionTestBorgunPayment() {

        $creditCards = array();

        $creditCards['0'] = null;

        $creditCardTest = new CreditCard();
        $creditCardTest->card_number = '4044335369371653'; //Random::getRandomNumber(16);
        $creditCardTest->card_type = CreditCard::TYPE_MASTERCARD_CREDIT;
        $creditCardTest->last_three_digits = '123';
        $creditCardTest->expiry_date = '2015-12-31';
        $creditCardTest->name_on_card = 'Renaud Theuillon';

        $creditCards['1'] = $creditCardTest;

        $creditCardTest = new CreditCard();
        $creditCardTest->card_number = '5587402000012011'; //Random::getRandomNumber(16);
        $creditCardTest->card_type = CreditCard::TYPE_MASTERCARD_CREDIT;
        $creditCardTest->last_three_digits = '415';
        $creditCardTest->expiry_date = '2018-09-30';
        $creditCardTest->name_on_card = 'Renaud Theuillon';
        $creditCards['2'] = $creditCardTest;

        $creditCardTest = new CreditCard();
        $creditCardTest->card_number = '4659465676233505'; //Random::getRandomNumber(16);
        $creditCardTest->card_type = CreditCard::TYPE_VISA_DEBIT;
        $creditCardTest->last_three_digits = '373';
        $creditCardTest->expiry_date = '2015-02-28';
        $creditCardTest->name_on_card = 'Renaud Theuillon';
        $creditCards['3'] = $creditCardTest;


        //check if user pre-defined or form

        if (isset($_POST['creditCards']) && $_POST['creditCards'] != '0') {

            $index = $_POST['creditCards'];
            $creditCard = $creditCards[$index];
        } elseif (isset($_POST['CreditCard'])) {

            $environment = $_POST['environment'];

            $creditCard = new CreditCard();

            $creditCard->attributes = $_POST['CreditCard'];

            $month = $_POST['Month'];
            $year = $_POST['Year'];

            if (is_numeric($month) && is_numeric($year) && checkdate($month, 1, $year)) {

                //add leading 0 if necessary
                $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $month = sprintf("%02d", $month);

                $date_value = "$year-$month-$day";

                if (!Calendar::dateIsBefore($date_value, Calendar::today(Calendar::FORMAT_DBDATE))) {
                    $creditCard->expiry_date = $date_value;
                }
            } else {

                unset($creditCard->expiry_date); //unset the value so that error is reported in the model
            }
        }

        if (isset($creditCard)) {

            if ($creditCard->validate()) {

                //launch
                $environment = $_POST['environment'];
                $amount = $_POST['amount'];
                $price = new Price($amount, 'GBP');

                $paymentHandler = BorgunHandler::getInstance($environment);
                $success = $paymentHandler->doDirectPayment(null, $creditCard, null, $price);

                if ($success) {

                    $transactionRef = $paymentHandler->getTransactionRef();

                    $response = $paymentHandler->getResponse();
                    $res = print_r($response, TRUE);

                    $paymentSuccessful = $paymentHandler->isTransactionSuccessful();

                    if (!$paymentSuccessful) {
                        $errorMessage = '<br>Code: ' . $paymentHandler->getTransactionCode();
                        $errorMessage .= '<br>Message: ' . $paymentHandler->getPaymentMessage();
                        $errorMessage .= '<br>Transaction Ref: ' . $transactionRef;

                        Yii::app()->user->setFlash('error', 'Payment Failed <br>' . $errorMessage);
                    } else {
                        Yii::app()->user->setFlash('success', 'Payment successful <br> Transaction Ref:  ' . $transactionRef);
                    }
                } else {
                    $errorMessage = $paymentHandler->getLongErrorMessage();
                    Yii::app()->user->setFlash('error', 'Borgun Error: ' . $errorMessage);
                }
            } else {
                Yii::app()->user->setFlash('error', 'Error enter right card data');
            }
        } else {
            $creditCard = new CreditCard();
            $environment = 'test';
            $amount = '10';
        }

        $this->render('borgunPayment', array('creditCard' => $creditCard, 'environment' => $environment, 'creditCards' => $creditCards, 'amount' => $amount));
    }

}