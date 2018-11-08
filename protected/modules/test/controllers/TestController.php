<?php

class TestController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    // public $layout = '/layouts/column1';
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

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                //'actions' => array('index'),
                'users' => array('testAdmin'),
            ),
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('error', 'testAdmin'),
                'users' => array('*'),
            ),
//            array('deny', // deny all users
//                'users' => array('*'),
//            ),
        );
    }

    public function actionIndex() {
        $this->render('home');
    }

    /**
     * Displays the login page for ADMIN
     */
    public function actionTestLogin() {

        $model = new TestLoginForm();

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['TestLoginForm'])) {
            $model->attributes = $_POST['TestLoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('testLogin', array('model' => $model));
    }

    public function actionAdminLogout() {

        Yii::app()->user->logout();
        Yii::app()->session->destroy();
        $this->redirect('index');
        //$this->redirect(Yii::app()->getModule('admin')->getComponent('user')->loginUrl);
    }

    public function actionError() {

        //$this->layout = '//layouts/layoutSite';

        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
            // $this->render('error', $error);
                echo $error['message'];
        }
    }

    public function actionTestVersion() {

        $this->render('version');
    }

    public function actionTestMemCache() {

        echo 'Using PHP Memcache <br>';
        $memcache = new Memcache;
        $memcache->connect('localhost', 11211) or die("Could not connect");

        $version = $memcache->getVersion();
        echo "Server's version: " . $version . "<br/>\n";

        $tmp_object = new stdClass;
        $tmp_object->str_attr = 'test';
        $tmp_object->int_attr = 123;

        $memcache->set('key', $tmp_object, false, 10) or die("Failed to save data at the server");
        echo "Store data in the cache (data will expire in 10 seconds)<br/>\n";

        $get_result = $memcache->get('key');
        echo "Data from the cache:<br/>\n";

        var_dump($get_result);

        $c = Yii::app()->cache;

        $class = get_class($c);

        echo "<br><br>Using Yii $class <br>";

        $c->set('x', 1);
        $c->set('y', 2);
        $c->set('z', 3);
        $data = $c->mget(array('x', 'z'));
        echo $data['z']; // 3
    }

    public function actionCreateRandomCarer($number) {

        ini_set('max_execution_time', 300);

        for ($i = 0; $i < $number; $i++) {
            $carer = CarerTesting::createCarer();
        }

        echo "$number carers created";
    }

    public function actionDeleteCarers($fromId, $toId) {

        ini_set('max_execution_time', 0);

        $sql = "SELECT * FROM tbl_carer WHERE id >= $fromId AND id <= $toId ";

        $carers = Carer::model()->findAllBySql($sql);

        $i = 0;
        foreach ($carers as $carer) {

            $carer->delete();
            $i++;
        }

        echo "$i carers deleted";
    }

    public function actionVoucher() {

        $voucherCode = "30A1140B1";

        $endodedVoucher = VoucherCarer::encodeVoucher($voucherCode);

        echo $endodedVoucher;

//        $endodedVoucher = VoucherCarer::decodeVoucher($endodedVoucher);
//
//        echo $endodedVoucher;
    }

    public function actionCreateOldClientsConversation() {

        $transaction = Yii::app()->db->beginTransaction();
        
        $count = 0;
        
        try {

            $clients = Client::getClientsMadeBooking();

            //$client = Client::loadModel(117);

            //$clients = array($client);

            foreach ($clients as $client) {

                $carersAll = $client->getMyCarers(true, true, 'all', true);

                $carers = $carersAll['carers'];

                foreach ($carers as $carer) {

                    $conversation = new Conversation();
                    $conversation->id_client = $client->id;
                    $conversation->id_carer = $carer->id;

                    $conversation->validate();
                    $err = $conversation->errors;
                    $conversation->save();

                    //$conversation->createFirstMessageForCarer($this);
                    //CODE :
                    $message = new Message();
                    $message->id_conversation = $conversation->id;
                    $message->type = Message::TYPE_PREVIOUS_CUSTOMER;
                    $message->author = Message::AUTHOR_ADMIN;
                    //$message->id_job = $job->id;
                    $message->message = 'Direct Homecare allows you now to chat with all the Carers you have been in touch with. You can discuss here about your requirements and make a booking once you have agreed on a job.';
                    $message->is_read = 0;
                    $message->visible_by = Constants::USER_CLIENT;

                    $message->validate();
                    $err = $message->errors;
                    $message->save();

                    $conversation->save();
                    
                    $count++;

                    //END CODE
                }
            }

            $transaction->commit();
            
            echo "Success - $count conversation created ";
            
        } catch (CException $e) {
            
            echo 'Error - rollaback';

            $transaction->rollBack();
        }
    }

}