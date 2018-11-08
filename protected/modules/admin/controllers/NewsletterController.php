<?php

class NewsletterController extends Controller {

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
                'users' => array('admin'),
            ),
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('error', 'adminLogin'),
                'users' => array('*'),
            ),
//            array('deny', // deny all users
//                'users' => array('*'),
//            ),
        );
    }

    public function actionSendClientsNewsletter() {

        //get all clients with an email address
    }

    /**
     * Send to all carers who are missing documents
     */
    public function actionSendCarersNewsletter() {

//        Yii::import('application.commands.*');
//        $command = new ProcessNewslettersCommand("test", "test");
//        $command->run(null);
    }

}