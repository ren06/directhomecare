<?php

class BookingAdminController extends Controller {

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
            // 'accessControl', // perform access control for CRUD operations
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
                'actions' => array('index', 'view', 'admin'),
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

    public function actionClients($wzd) {

        $dataProvider = new CActiveDataProvider('Booking', array(
            'criteria' => array(
                'condition' => 'wizard_completed=' . $wzd,
                'order' => 'id DESC',
            //'with' => array('author'),
            ),))
        ;

        $this->render('/booking/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('/booking/view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Booking;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Booking'])) {
            $model->attributes = $_POST['Booking'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('/booking/create', array(
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

        if (isset($_POST['Booking'])) {
            $model->attributes = $_POST['Booking'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('/booking/update', array(
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
        $dataProvider = new CActiveDataProvider('Booking');
        $this->render('/booking/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Booking('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Booking']))
            $model->attributes = $_GET['Booking'];

        $this->render('/booking/admin', array(
            'model' => $model,
        ));
    }

    public function actionManageBookings() {

        $model = new Booking('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Booking']))
            $model->attributes = $_GET['Booking'];

        $this->render('/booking/manageClients', array(
            'model' => $model,
        ));
    }

    public function actionViewClient($id) {

        $client = Booking::loadModel($id);

        $this->render('/booking/viewClient', array(
            'client' => $client,
        ));
    }

    //All missions
    public function actionViewClientMissions($id) {

        $client = Booking::loadModel($id);

        $this->render('/client/viewClientMissions', array('client' => $client));
    }

    public function actionUpdateDiscarded() {

        if (isset($_POST['autoId'])) {

            $autoIdAll = $_POST['autoId'];

            if (isset($autoIdAll)) {

                if (count($autoIdAll) > 0) {
                    foreach ($autoIdAll as $autoId) {

                        $model = Booking::loadModel($autoId);

                        //can only set discarded to a non active request

                        $discarded = $_POST['discarded'];

                        $model->discarded_by_client = $discarded;

                        $model->save();
                    }
                }
            }
        }

        //refresh
        $this->redirect('admin');
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'client-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionEditBooking($id) {

        $booking = Booking::loadModel($id);

        $this->render('/booking/editBooking', array('booking' => $booking));
    }

    public function actionClientsBookings() {

        $clients = Client::getClientsMadeBooking();
                       
        $this->render('/booking/clientsBookings', array('clients' => $clients));
    }

}
