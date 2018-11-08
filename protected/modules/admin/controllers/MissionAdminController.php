<?php

class MissionAdminController extends Controller {

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
                'actions' => array('index', 'view', 'admin', 'delete', 'copyMission', 'update', 'viewMission',
                    'createMissionSlotAborted', 'deleteMissionSlotAborted', 'updateSlotType',
                    'cancelForClient', 'cancelForCarer'),
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
        $this->render('/mission/view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new LiveInMission;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Mission'])) {
            $model->attributes = $_POST['Mission'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('/mission/create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $model = Mission::loadModelAdmin($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['MissionHourly'])) {
            $model->scenario = 'edit';
            $end = $model->start_date_time;
            $model->attributes = $_POST['MissionHourly'];
            $end2 = $model->start_date_time;
            if ($model->validate()) {
                $model->save(false);


                $this->redirect(array('/admin/missionAdmin/viewMission', 'missionId' => $model->id));
            } else {
                $errors = $model->errors;
            }
        }

        $this->render('/mission/update', array(
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
        $dataProvider = new CActiveDataProvider('Mission');
        $this->render('/mission/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Mission('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Mission']))
            $model->attributes = $_GET['Mission'];

        $this->render('/mission/admin', array(
            'model' => $model,
        ));
    }

    public function actionCopyMission() {

        if (isset($_POST['MissionHourly'])) {

            $id = $_POST['MissionHourly']['id'];
            $existingModel = Mission::loadModelAdmin($id);

            $model = new MissionHourly();
            $model->scenario = 'edit';

            $model->attributes = $existingModel->attributes;
            $model->start_date_time = $_POST['MissionHourly']['start_date_time'];
            $model->end_date_time = $_POST['MissionHourly']['end_date_time'];
            $model->id = null;

            $model->save();
        }

        $this->redirect(array('bookingAdmin/editBooking/id/' . $model->id_booking));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Mission::model()->resetScope()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'live-in-mission-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionViewMission($missionId) {

        $mission = Mission::loadChild($missionId);

        $missionSlotAborted = Yii::app()->user->getState('missionSlotAborted');
        if (!isset($missionSlotAborted)) {
            $missionSlotAborted = new MissionSlotAborted();
        } else {
            Yii::app()->user->setState('missionSlotAborted', null);
        }

        if (!$missionSlotAborted->isNewRecord) {
            $missionSlotAborted = new MissionSlotAborted();
        }

        //show all $missions aborted
        $paymentResult = Yii::app()->user->getState('paymentResult');

        if (!isset($paymentResult)) {
            $paymentResult = '';
        } else {
            Yii::app()->user->setState('paymentResult', null);
        }

        $this->render('/mission/viewMission', array(
            'mission' => $mission, 'missionSlotAborted' => $missionSlotAborted, 'paymentResult' => $paymentResult
        ));
    }

    //Abort
    public function actionCreateMissionSlotAborted() {

        if (isset($_POST)) {

            $dbTransaction = Yii::app()->db->beginTransaction();

            try {

                $paymentResult = '';

                $missionId = $_POST['missionId'];

                $missionSlotAborted = new MissionSlotAborted();

                $start_date_time = ReadHttpRequest::readStartDateTime();
                $end_date_time = ReadHttpRequest::readEndDateTime();

                $missionSlotAborted->start_date_time = $start_date_time;
                $missionSlotAborted->end_date_time = $end_date_time;

                if (isset($_POST['aborted_by'])) {
                    $missionSlotAborted->aborted_by = $_POST['aborted_by'];
                }

                //check continuity           
                //Others
                $missionSlotAborted->id_mission = $missionId;
                $missionSlotAborted->reported_by = (isset($_POST['reported_by']) ? $_POST['reported_by'] : '');
                $missionSlotAborted->created_by = MissionSlotAborted::CREATED_BY_ADMIN;
                $missionSlotAborted->type = (isset($_POST['type']) ? $_POST['type'] : '');

                if ($missionSlotAborted->validate()) {

                    $saveSuccessful = $missionSlotAborted->save();

                    if ($missionSlotAborted->aborted_by == MissionSlotAborted::ABORTED_BY_CARER) {

                        $mission = Mission::loadModel($missionId);

                        $client = $mission->booking->client;
                        $missionPayment = $mission->missionPayment;
                        $creditCard = $mission->booking->creditCard;
                        $billingAddress = $creditCard->address;

                        $price = $missionSlotAborted->getPrice(Constants::USER_CLIENT);

                        //Make reimbursement
                        //If voucher were used use NonReferencedCredit
                        $clientTransation = $missionPayment->getPaymentTransaction();

                        $payPalHandler = PayPalHandler::getInstance();

                        $paidVoucher = $clientTransation->getPaidCredit();

                        if ($paidVoucher->amount > 0) {
                            $successful = $payPalHandler->doNonReferencedCredit($client, $creditCard, $billingAddress, $price);
                        } else {
                            $memo = 'Reimburse payment';
                            $successful = $payPalHandler->refundTransaction($missionPayment->transaction_id, $price, $memo);
                        }

                        if ($successful == true) {

                            ClientTransaction::createReimbursment($mission->booking->id_client, $mission->id_mission_payment, $price, true);
                            $dbTransaction->commit();
                            $paymentResult = 'Payment successful';
                        } else {

                            $dbTransaction->rollBack();
                            $array = $payPalHandler->getResponse();
                            $paymentResult = $payPalHandler->getLongErrorMessage();
                        }
                        Yii::app()->user->setState('paymentResult', $paymentResult);
                    } else {
                        if ($saveSuccessful) {
                            $dbTransaction->commit();
                        } else {
                            $dbTransaction->rollBack();
                        }
                    }
                }

                Yii::app()->user->setState('missionSlotAborted', $missionSlotAborted);
            } catch (CException $e) {

                $dbTransaction->rollBack();
            }
        }

        $this->redirect(array('viewMission', 'missionId' => $missionId));
    }

    public function actionDeleteMissionSlotAborted() {

        if (isset($_POST['slotId'])) {

            $id = $_POST['slotId'];

            //$model = MissionSlotAborted::model()->findByPk($id);
            //$missionId = $model->mission->id;

            MissionSlotAborted::model()->deleteByPk($id);
        }
    }

    public function actionUpdateSlotType() {

        if (isset($_POST['aborted_by'])) {

            $abortedBy = $_POST['aborted_by'];
        }

        if ($abortedBy == MissionSlotAborted::ABORTED_BY_CLIENT) {
            $options = MissionSlotAborted::getTypeClientOptions();
        } else {
            $options = MissionSlotAborted::getTypeCarerOptions();
        }

        $keys = array_keys($options);

        foreach ($keys as $key) {
            echo CHtml::tag('option', array('value' => $key), CHtml::encode($options[$key]), true);
        }
    }

    public function actionCancelForCarer($id) {

        $mission = Mission::loadModel($id);

        if ($mission->isActive()) {

            $client = $mission->booking->client;
            $carer = $mission->getAssignedCarer();

            $mission->cancelAssigned($carer->id);

            Yii::app()->user->setFlash('success', 'Mission cancelled for carer');

            $this->redirect(array('/admin/clientAdmin/viewClient/id/' . $client->id));
        } else {
            $this->renderText('Mission is cancelled');
        }
    }

    public function actionCancelForClient($id) {

        $mission = Mission::loadModel($id);

        if ($mission->isActive()) {
            $client = $mission->booking->client;

            $resultAmounts = $mission->calculateCancelMoneyAmountsClient();

            $voucherAmountText = $resultAmounts['voucher']->text;

            $total = $mission->getTotalLivePrice(Constants::USER_CLIENT);

            $text = Yii::t('texts', 'NOTE_CANCEL_VISIT', array('{totalPaid}' => $total->text, '{totalVoucher}' => $voucherAmountText));

            if (isset($_POST['cancel'])) {

                $mission->cancelByClient();
                Yii::app()->user->setFlash('success', 'Mission cancelled for client');
                $this->redirect(array('/admin/clientAdmin/viewClient/id/' . $client->id));
            }

            $this->render('/mission/cancelForClient', array(
                'mission' => $mission, 'client' => $client, 'text' => $text
            ));
        } else {

            $this->renderText('Mission is cancelled');
        }
    }

}
