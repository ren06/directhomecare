<?php

class ComplaintController extends Controller {

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

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view', 'admin', 'viewMessages', 'addResponse', 'toggleSolved'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Complaint;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Complaint'])) {
            $model->attributes = $_POST['Complaint'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Complaint'])) {
            $model->attributes = $_POST['Complaint'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Complaint');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Complaint('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Complaint']))
            $model->attributes = $_GET['Complaint'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Complaint::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'client-complaint-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionViewMessages($id) {

        $complaint = Complaint::loadModel($id);

        $this->render('complaintPosts', array(
            'complaint' => $complaint,));
    }

    public function actionAddResponse() {

        $complaintId = $_POST['complaintId'];
        $author = ComplaintPost::AUTHOR_ADMIN;
        $text = $_POST['ComplaintPost']['text'];

        //otherwiser use specified in form
        //$visibleBy = $_POST['$visibleBy'];

        $visibleByCarer = isset($_POST['visible_by_carer']);
        $visibleByClient = isset($_POST['visible_by_client']);

        //both true
        if ($visibleByCarer && $visibleByClient) {
            $visibleBy = Constants::USER_ALL;
        } else {
            if ($visibleByCarer) {
                $visibleBy = Constants::USER_CARER;
            } elseif ($visibleByClient) {
                $visibleBy = Constants::USER_CLIENT;
            } else {
                //if admin forgot, by default the creator of the complaint
                $complaint = Complaint::loadModel($complaintId);

                $visibleBy = $complaint->created_by;
            }
        }

        $post = ComplaintPost::createPost($complaintId, $author, $visibleBy, $text);

        //notify client
        
        if($visibleBy == ComplaintPost::VISIBLE_BY_ALL || $visibleBy == ComplaintPost::VISIBLE_BY_CLIENT){
        
            $person = $post->complaint->client;
        }
        elseif($visibleBy == ComplaintPost::VISIBLE_BY_ALL || $visibleBy == ComplaintPost::VISIBLE_BY_CARER){
          
            $person = $post->complaint->carer;
        }

        Emails::sendToClient_NewResponseToComplaint($person);

        //show update
        $this->redirect(array('viewMessages', 'id' => $complaintId));
    }

    public function actionToggleSolved() {

        $solved = $_POST['solved'];
        $complaintId = $_POST['complaintId'];

        if ($solved == Complaint::UNSOLVED) {
            $solved = Complaint::SOLVED;
        } else {
            $solved = Complaint::UNSOLVED;
        }

        Complaint::setSolvedValue($complaintId, $solved);

        //show update
        $this->redirect(array('viewMessages', 'id' => $complaintId));
    }

}