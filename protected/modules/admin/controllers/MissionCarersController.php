<?php

class MissionCarersController extends Controller {

    const SCENARIO_CARER_SELECTION = 1;
    const SCENARIO_CARER_NOT_CONFIRMED = 2;

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
                'actions' => array('index', 'view', 'manageMissionsCarers', 'delete', 'updateStatuses', 'carerMissionsAll', 'setCarerSelected',
                    'missionCarersApplied', 'missionsNoCarerApplied', 'missionsNoCarerSelected', 'viewMission', 'missionsCarerNotConfirmed', 'missionsCarerAssigned',
                    'changeCarerSelected', 'updateMissionStatuses', 'updateDiscarded', 'deleteSelected', 'updateMissionStatuses2', 'missionsCarerAssignedNotStarted',
                    'createApplyRelation', 'cancelByAdmin', 'setCarerAssigned'),
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
        $model = new MissionCarers;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['MissionCarers'])) {
            $model->attributes = $_POST['MissionCarers'];
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

        if (isset($_POST['MissionCarers'])) {
            $model->attributes = $_POST['MissionCarers'];
            if ($model->save())
            //$this->redirect(array('view', 'id' => $model->id));
                $this->redirect(array('admin'));
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
        $dataProvider = new CActiveDataProvider('MissionCarers');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionManageMissionsCarers() {

        $model = new MissionCarers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MissionCarers']))
            $model->attributes = $_GET['MissionCarers'];

        $modelMission = new Mission('search');
        $modelMission->unsetAttributes();  // clear any default values
        if (isset($_GET['MissionCarers']))
            $modelMission->attributes = $_GET['MissionCarers'];


        $this->render('manageMissionsCarers', array(
            'model' => $model,
            'modelMission' => $modelMission,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = MissionCarers::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'live-in-mission-carers-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Set status in DB and send email
     * 
     * @param type $missionId
     * @param type $carerId
     */
    public function actionSetCarerSelected($missionId, $carerId) {

        MissionCarers::setCarerSelected($missionId, $carerId);

        $carer = Carer::loadModel($carerId);

        Emails::sendToCarer_SelectedForMission($carer);

        $this->redirect(array('missionCarers/missionsNoCarerSelected'));
    }

    /**
     * Set status in DB and send email
     * 
     * @param type $missionId
     * @param type $carerId
     */
    public function actionSetCarerAssigned($missionId, $carerId) {

        MissionCarers::setCarerAssigned($missionId, $carerId);

        $carer = Carer::loadModel($carerId);

        //Emails::sendToCarer_SelectedForMission($carer);
        Emails::sendToCarer_AssignedDirectlyMission($carer);

        $this->redirect(array('missionCarers/missionsNoCarerSelected'));
    }

    public function actionUpdateStatuses() {

        if (isset($_POST['autoId'])) {

            $autoIdAll = $_POST['autoId'];

            if (isset($autoIdAll)) {

                if (count($autoIdAll) > 0) {
                    foreach ($autoIdAll as $autoId) {

                        $model = $this->loadModel($autoId);
                        $model->status = $_POST['status'];
                        $model->save();
                    }
                }
            }
        }

        //refresh
        $this->redirect('manageMissionsCarers');
    }

    public function actionUpdateMissionStatuses() {

        if (isset($_POST['autoIdMission'])) {

            $autoIdAll = $_POST['autoIdMission'];

            if (count($autoIdAll) > 0) {
                foreach ($autoIdAll as $autoId) {

                    $mission = Mission::loadModelAdmin($autoId);
                    $mission->status = $_POST['missionStatus'];
                    $mission->save();
                }
            }
        }

        //refresh
        $this->redirect('manageMissionsCarers');
    }

    public function actionCancelByAdmin() {

        if (isset($_POST['autoIdMission'])) {

            $autoIdAll = $_POST['autoIdMission'];

            $successText = '';
            $errorText = '';

            if (count($autoIdAll) > 0) {
                $success = true;
                foreach ($autoIdAll as $autoId) {

                    $mission = Mission::loadModel($autoId);

                    $success = $mission->cancelByAdmin(true, true);

                    if (!$success) {
                        $errorText = "Mission $autoId not cancelled! Reason: " . $mission->error_text;
                        break;
                    } else {
                        $successText = $successText . '<br />' . "Mission $autoId cancelled successfully";
                    }
                }
                if ($success) {
                    Yii::app()->user->setFlash('success', $successText);
                } else {
                    Yii::app()->user->setFlash('error', $errorText . '<br>' . $successText);
                }
            }
        }

        //refresh
        $this->redirect('manageMissionsCarers');
    }

    public function actionUpdateMissionStatuses2() {

        if (isset($_POST['autoIdMission'])) {

            $autoIdAll = $_POST['autoIdMission'];

            if (count($autoIdAll) > 0) {
                foreach ($autoIdAll as $autoId) {

                    $mission = Mission::loadModel($autoId);
                    $status = $_POST['missionStatus'];
                    $mission->status = $_POST['missionStatus'];
                    $mission->save();
                }
            }
        }

        //refresh
        $this->redirect('manageMissionsCarers');
    }

    public function actionCreateApplyRelation() {

        if (isset($_POST['carerId']) && isset($_POST['missionId'])) {

            $carerId = $_POST['carerId'];
            $missionId = $_POST['missionId'];

            if (!Carer::recordExists($carerId)) {

                Yii::app()->user->setFlash('error', 'Enter a valid carer Id');
            } elseif (!Mission::recordExists($missionId)) {
                Yii::app()->user->setFlash('error', 'Enter a valid mission Id');
            } else {

                $status = Mission::getCarerMissionStatus($carerId, $missionId);
                $labels = MissionCarers::getAllStatusOptions();
                $statusText = $labels[$status];
                if ($status == MissionCarers::UNAPPLIED) {

                    Mission::apply($carerId, $missionId);
                    Yii::app()->user->setFlash('success', "Relation 'Applied' created for carer $carerId and mission $missionId");
                } else {
                    Yii::app()->user->setFlash('error', "Relation '$statusText' already exists for carer $carerId and mission $missionId");
                }
            }
        } else {
            Yii::app()->user->setFlash('error', 'Enter a valid carer Id and mission Id');
        }

        //refresh
        $this->redirect('manageMissionsCarers');
    }

    public function actionDeleteSelected() {

        if (isset($_POST['autoId'])) {

            $autoIdAll = $_POST['autoId'];

            if (count($autoIdAll) > 0) {

                foreach ($autoIdAll as $autoId) {

                    $model = MissionCarers::loadModel($autoId);
                    $model->delete();
                }
            }
        }

        //refresh
        $this->redirect('manageMissionsCarers');
    }

    public function actionUpdateDiscarded() {

        if (isset($_POST['autoId'])) {

            $autoIdAll = $_POST['autoId'];

            if (isset($autoIdAll)) {

                if (count($autoIdAll) > 0) {
                    foreach ($autoIdAll as $autoId) {

                        $model = $this->loadModel($autoId);
                        $model->discarded = $_POST['discarded'];
                        $model->save();
                    }
                }
            }
        }

        //refresh
        $this->redirect('manageMissionsCarers');
    }

    public function actionCarerMissionsAll() {

        $this->redirect(array('missionCarers/admin'));
    }

    /**
     * No carer selected, all statuses of the missions are APPLIED
     */
    public function actionMissionsNoCarerSelected() {

        $missions = Mission::getMissionsNoCarerSelected();

        $this->render('missionsNoCarerSelected', array(
            'missions' => $missions,
        ));
    }

    /**
     * No carer applied, no entries in LiveInMissionCarers
     */
    public function actionMissionsNoCarerApplied() {

        $missions = Mission::getMissionsNoCarerApplied();

        $this->render('missionsNoCarerApplied', array(
            'missions' => $missions,
        ));
    }

    /**
     * Missions where carer has not confirmed
     */
    public function actionMissionsCarerNotConfirmed() {

        $missions = Mission::getMissionsCarerNotConfirmed();

        $this->render('missionsCarerNotConfirmed', array(
            'missions' => $missions,
        ));
    }

    public function actionMissionsCarerAssigned() {

        $missions = Mission::getMissionsCarerAssigned();

        $this->render('missionsCarerAssigned', array(
            'missions' => $missions,
        ));
    }

    public function actionMissionsCarerAssignedNotStarted() {

        $missions = Mission::getMissionsCarerAssignedNotStarted();

        $this->render('missionsCarerAssigned', array(
            'missions' => $missions,
        ));
    }

    public function actionMissionCarersApplied($id, $scenario) {

        $model = new MissionCarers('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MissionCarers']))
            $model->attributes = $_GET['MissionCarers'];

        $mission = Mission::loadModelAdmin($id);
        $dataProvider = $model->searchByMission($id);

        $missionCarers = $dataProvider->getData();

        $scenario = self::SCENARIO_CARER_SELECTION;

        foreach ($missionCarers as $missionCarer) {

            if ($missionCarer->status == MissionCarers::SELECTED) {

                $scenario = self::SCENARIO_CARER_NOT_CONFIRMED;
                break;
            }
        }

        $_GET['scenario'] = $scenario;

        $this->render('missionCarersApplied', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
            'mission' => $mission,
            'scenario2' => $scenario,
        ));
    }

    public function actionChangeCarerSelected($missionId, $carerId) {

        MissionCarers::changeCarerSelected($missionId, $carerId);

        $this->redirect(array('/admin/missionCarers/missionCarersApplied/id/' . $missionId . '/scenario/2'));
    }

}
