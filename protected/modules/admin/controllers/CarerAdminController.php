<?php

class CarerAdminController extends Controller {

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
            //  'accessControl', // perform access control for CRUD operations
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
                'actions' => array('carers', 'clients', 'index', 'view'),
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
     * Lists all models.
     */
    public function actionIndex() {

        $dataProvider = new CActiveDataProvider('Carer', array(
            'criteria' => array(
                //'condition' => 'id_client=' . Yii::app()->user->id,
                'order' => 'id DESC',
            //'with' => array('author'),
            ),))
        ;
        $this->render('/carer/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionManageCarers() {

        $model = new Carer('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Carer']))
            $model->attributes = $_GET['Carer'];

        $this->render('/carer/manageCarers', array(
            'model' => $model,
        ));
    }

    public function actionViewCarer($id) {

        $carer = Carer::loadModel($id);

        $this->render('/carer/viewCarer', array(
            'carer' => $carer,
        ));
    }

    //All missions
    public function actionViewCarerMissions($id) {

        $carer = Carer::loadModel($id);

        $this->render('/carer/viewCarerMissions', array('carer' => $carer));
    }

    //One mission
    public function actionApproveDocumentsCarers($all) {

        $criteria = new CDbCriteria;

        if ($all) {
            $criteria->join = 'INNER JOIN tbl_carer_document cd ON t.id = cd.id_carer';
        } else {
            $criteria->join = 'LEFT JOIN tbl_carer_document cd ON t.id = cd.id_carer';
            $criteria->condition = 'cd.active = :active AND cd.status = :status';
            $criteria->params = array(':active' => CarerDocument::UNACTIVE, ':status' => CarerDocument::STATUS_UNAPPROVED);
        }
        $criteria->distinct = true;

        $carers = Carer::model()->resetScope()->findAll($criteria);

        $this->render('/carer/approveDocumentsCarers', array('carers' => $carers, 'all' => $all));
    }

    public function actionApproveCarerDocuments($carerId) {

        // $document = CarerDocument::loadModelAdmin($documentId);

        $carer = Carer::loadModelAdmin($carerId);

        $this->render('/carer/approveCarerDocuments', array('carer' => $carer));
    }

    public function actionGetPdfAdmin($documentId) {

        $carerDocument = CarerDocument::loadModelAdmin($documentId);

        $fileContent = $carerDocument->getFile();

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-length: ' . $fileContent->size);
        header('Content-Type: ' . $fileContent->extension);
        // header('Content-Disposition: attachment; filename=' . 'name');

        echo $fileContent->content;
    }

    public function actionModifyImage($documentId) {

        $imageDocument = CarerDocument::loadModelAdmin($documentId);

        $this->render('/carer/modifyImage', array('imageDocument' => $imageDocument));
    }

    public function actionHandleCrop() {

        Yii::app()->session['ajaxCall'] = 1;
        $doc = CarerDocument::loadModel($_POST['id']);

        $docFile = $doc->getFile();

        $docWidth = $docFile->width;

        $width = 400;

        $ratio = $docWidth / $width;

        $rootFolder = Yii::getPathOfAlias('application');
        $fileName = $_POST['fileName']; //Yii::app()->session['fileName']; // $_POST['fileName'];
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

        if (empty($errors)) {
            
        }

        if ($fileContent->save()) {

            //delete temp file
            unlink($fullFileName);
        }

        $fileId = $fileContent->id;

        $doc->id_content_crop = $fileId;
        $res = $doc->save(false);
        $doc->refresh();

        $html = $doc->showCrop(120, 96);

        echo $html;
    }

    public function actionRotateImage($documentId, $angle) {

        //  if (Yii::app()->request->isAjaxRequest) {

        $carerDocument = CarerDocument::loadModelAdmin($documentId);

        $fileContent = $carerDocument->getFile();

        $originalContentStr = $fileContent->content;
        $extension = $fileContent->extension;

        $imageSource = imagecreatefromstring($originalContentStr);

        $imageRotate = imagerotate($imageSource, $angle, 0);

        ob_start();
        switch (strtolower($extension)) {
            case "png":
                imagepng($imageRotate, null);
                break;
            case "jpeg":
                imagejpeg($imageRotate, null);
                break;
            case "jpg":
                imagejpeg($imageRotate, null);
                break;
            case "gif":
                imagegif($imageRotate, null);
                break;
        }

        $img_data = ob_get_contents();
        $width = imagesx($imageRotate);
        $height = imagesy($imageRotate);

        ob_end_clean();

        $fileContent->width = $width;
        $fileContent->height = $height;
        $fileContent->content = $img_data;

        $fileContent->save();

        imagedestroy($imageSource);
        imagedestroy($imageRotate);

        $html = $this->renderPartial('application.views.carer._showImage', array('fileContent' => $fileContent, 'width' => 450, 'class' => '', 'doc' => $carerDocument, 'visible' => true), true);

        echo $html;

        $this->redirect(array("carerAdmin/modifyImage/documentId/$documentId"));
        // }
    }

    public function actionGetImageAdmin($documentId, $crop) {

        $carerDocument = CarerDocument::loadModelAdmin($documentId);

        if ($crop) {

            $fileContent = $carerDocument->getCropFile();
        } else {
            $fileContent = $carerDocument->getFile();
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-length: ' . $fileContent->size);
        header('Content-Type: ' . $fileContent->extension);
        // header('Content-Disposition: attachment; filename=' . 'name');

        echo $fileContent->content;
    }

    private function storeAdminDocumentChanges($documentId) {

        $document = CarerDocument::loadModelAdmin($documentId);

        if (isset($_POST['text'])) {
            $document->text = $_POST['text'];
        }

        if (isset($_POST['CarerDocument']['id_document'])) {
            $document->id_document = $_POST['CarerDocument']['id_document'];
        }

        if (isset($_POST['CarerDocument']['year_obtained'])) {
            $document->year_obtained = $_POST['CarerDocument']['year_obtained'];
        }

        $document->save();
    }

    public function actionApproveDocument() {

        $documentId = $_POST['documentId'];
        $carerId = $_POST['carerId'];

        //store changes
        $this->storeAdminDocumentChanges($documentId);

        CarerDocument::approve($documentId);

        $carer = Carer::loadModel($carerId);
        $carer->setActiveOrNot();

        $this->redirect(array('approveCarerDocuments', 'carerId' => $carerId));
    }

    public function actionRejectDocument() {

        $documentId = $_POST['documentId'];
        $carerId = $_POST['carerId'];

        //store changes
        $this->storeAdminDocumentChanges($documentId);

        if (!isset($_POST['reject_reasons'])) {

            $this->redirect(array('approveCarerDocuments', 'carerId' => $carerId));
        } else {
            $reasons = $_POST['reject_reasons'];
            $text = null;
            foreach ($reasons as $reason) {

                $text .= $reason . '<br />';
            }

            CarerDocument::reject($documentId, $text);

            //update carer status
            $carer = Carer::loadModel($carerId);
            $carer->setActiveOrNot();

            $this->redirect(array('approveCarerDocuments', 'carerId' => $carerId));
        }
    }

    public function actionWaitingDocument() {

        $documentId = $_POST['documentId'];
        $carerId = $_POST['carerId'];

        //store changes
        $this->storeAdminDocumentChanges($documentId);

        CarerDocument::waiting($documentId);

        $this->redirect(array('approveCarerDocuments', 'carerId' => $carerId));
    }

    public function actionDeleteActiveDocument() {

        $documentId = $_POST['documentId'];
        $carerId = $_POST['carerId'];

        $carerDocument = CarerDocument::loadModelAdmin($documentId);
        $carerDocument->delete();

        $carer = Carer::loadModel($carerId);
        $carer->setActiveOrNot();

        $this->redirect(array('approveCarerDocuments', 'carerId' => $carerId));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('/carer/view', array('model' => $this->loadModel($id),
                'asDialog' => !empty($_GET['asDialog'])), false, true);
            Yii::app()->end();
        } else {
            $this->render('/carer/view', array(
                'model' => $this->loadModel($id),
            ));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Carer;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Carer'])) {
            $model->attributes = $_POST['Carer'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('/carer/create', array(
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
        $model->setScenario('search'); //bypass particular rules
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Carer'])) {
            $model->attributes = $_POST['Carer'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('/carer/update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
//    public function actionDelete($id) {
//        $this->loadModel($id)->delete();
//
//        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//        if (!isset($_GET['ajax']))
//            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//    }

    public function actionDelete($id) {

        $model = Carer::loadModelAdmin($id);
        $model->delete();

        $this->redirect(array('manageCarers'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Carer('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Carer']))
            $model->attributes = $_GET['Carer'];

        $this->render('/carer/admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Carer::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'carer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionLoginHistory() {

        $model = new LoginHistoryCarer();

        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['LoginHistoryCarer']))
            $model->attributes = $_GET['LoginHistoryCarer'];

        $this->render('/carer/loginHistory', array(
            'model' => $model,
        ));
    }

    public function actionCarerWithdrawals() {

        $dataProvider = CarerWithdrawal::getAllDataProvider();

        $this->render('/carer/carerWithdrawals', array('dataProvider' => $dataProvider,));
    }

    public function actionShowUpdateWithdrawalDetailsDialog($withdrawalId) {

        UIServices::unregisterAllScripts();

        //$withdrawal = CarerWithdrawal::loadModel($id);

        $output = $this->renderPartial('/carer/_withdrawalDetailsDialog', array('withdrawalId' => $withdrawalId), true, true);

        echo $output;
    }

    public function actionUpdateWithdrawalDetails() {

        $withdrawalId = $_POST['withdrawalId'];
        $success = $_POST['success'];

        $withdrawal = CarerWithdrawal::loadModel($withdrawalId);

        $withdrawal->status = $success;

        if ($success == CarerWithdrawal::STATUS_COMPLETED) {

            $reference = $_POST['reference'];
            $transactionDate = $_POST['transaction_date'];

            $withdrawal->payment_date = Calendar::convert_DisplayDate_DBDate($transactionDate);
            $withdrawal->bank_reference = $reference;
        }

        $withdrawal->save(false);
    }

    public function actionSetBankTransferFailed($withdrawalId) {

        $withdrawal = CarerWithdrawal::loadModel($withdrawalId);
        $withdrawal->status = CarerWithdrawal::STATUS_FAILED;
        $withdrawal->save(false);

        //recredit carer        
        CarerTransaction::createFailedWithdrawal($withdrawal->id_carer_transaction);

        $this->redirect(array('carerWithdrawals'));
    }

    public function actionViewCarerProfile($id) {

        $carer = Carer::loadModelAdmin($id);

        $this->render('application.views.carer.profile._carerProfileDetails', array('carer' => $carer, 'view' => Constants::CARER_PROFILE_VIEW_ADMIN, 'carerProfileType' => 'long'));
    }

    public function actionAllCarersProfiles() {

        ini_set('max_execution_time', 300);

        $ageGroup = array();

        $active = false;
        $workWithMale = false;
        $workWithFemale = false;
        $nationality = 'all';
        $liveIn = false;
        $hourly = false;

        $default = false;

        $searchValues = array(
            'active' => $default,
            'liveIn' => $default,
            'hourly' => $default,
            'work_with_male' => $default,
            'work_with_female' => $default,
            'ageGroup_0' => $default,
            'ageGroup_1' => $default,
            'ageGroup_2' => $default,
            'ageGroup_3' => $default,
        );

        //get all conditions
        $activitiesIds = Condition::getConditionsIds(Condition::TYPE_ACTIVITY);
        $physicalIds = Condition::getConditionsIds(Condition::TYPE_PHYSICAL);
        $mentalIds = Condition::getConditionsIds(Condition::TYPE_MENTAL);

        //$activitiesAll = array_merge(array_merge($activitiesIds, $physicalIds), $mentalIds);

        foreach ($activitiesIds as $activityId) {
            $searchValues['activities_condition_' . $activityId] = $default;
        }

        $searchValues['physical_condition'] = 'physical_condition_46';

        $searchValues['mental_condition'] = 'mental_condition_50';

        $activities = array();
        $physicals = array();
        $mentals = array();

        if (isset($_POST) && !empty($_POST)) {

            if (isset($_POST['nationality'])) {
                $nationality = $_POST['nationality'];
            }

            foreach ($searchValues as $key => $searchValue) {

                $searchValues[$key] = true;

                if (isset($_POST[$key])) {

                    //add value
                    if (Util::startsWith($key, 'activities_')) {

                        $val = Util::lastCharactersAfter($key, 'activities_condition_');
                        $activities[] = $val;
                    } elseif (Util::startsWith($key, 'physical_condition')) {

                        $val = Util::lastCharactersAfter($_POST[$key], 'physical_condition_');
                        $physicals[] = $val;
                        $searchValues[$key] = $_POST[$key];
                    } elseif (Util::startsWith($key, 'mental_condition')) {

                        $val = Util::lastCharactersAfter($_POST[$key], 'mental_condition_');
                        $mentals[] = $val;
                        $searchValues[$key] = $_POST[$key];
                    } elseif (Util::startsWith($key, 'ageGroup_')) {

                        $val = Util::lastCharactersAfter($key, '_');
                        $ageGroup[] = $val;
                    } elseif (Util::startsWith($key, 'work_with_')) {

                        if ($key == 'work_with_male') {
                            $workWithMale = true;
                        } else {
                            $workWithFemale = true;
                        }
                    } elseif ($key == 'active') {
                        $active = true;
                    } elseif ($key == 'liveIn') {
                        $liveIn = true;
                    } elseif ($key == 'hourly') {
                        $hourly = true;
                    }
                } else {
                    $searchValues[$key] = false;
                }
            }
        }

        //adapt activities and physical
        $physicalTemp = array();
        foreach ($physicals as $physicalId) {
            $model = Condition::loadModel($physicalId);
            $physicalTemp = array_merge($physicalTemp, $model->getConditionsIdsUp());
        }
        $physicals = array_unique($physicalTemp);

        $mentalTemp = array();
        foreach ($mentals as $mentalId) {
            $model = Condition::loadModel($mentalId);
            $mentalTemp = array_merge($mentalTemp, $model->getConditionsIdsUp());
        }
        $mentals = array_unique($mentalTemp);

        $carers = DBServices::getFilteredCarers2($activities, $physicals, $mentals, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, true, true);

        //DBServices::getFilteredCarers2($activities, $physicalCondition, $mentalCondition, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale, $postCode, $language, $clientId, $relations, $limitRelation, $limitNoRelation)

        $nationalities = DBServices::getCarersNationalities();

        $this->render('application.views.carer.profile.allCarersProfiles', array('carers' => $carers, 'searchValues' => $searchValues,
            'nationalities' => $nationalities, 'nationality' => $nationality, 'view' => Constants::CARER_PROFILE_VIEW_ADMIN));
    }

    public function actionSetActiveOrNot() {

        $allCarers = Carer::model()->findAll();
        $i = 0;
        foreach ($allCarers as $allCarer) {

            $allCarer->setActiveOrNot();
            $i++;
        }

        echo 'Finish ' . $i . ' processed';
    }

    public function actionCarersSelectionClient() {

        $clientsBooking = Client::getClientsMadeBooking();

        $clients = array();
        foreach ($clientsBooking as $client) {

            $serviceUsers = $client->serviceUsers;
            $serviceUser = $serviceUsers[0];
            $conditions = $serviceUser->conditions;
            $conditionsAll = '';
            foreach ($conditions as $condition) {
                $conditionsAll .= $condition->id . ', ';
            }
            //display gender, conditions, agegroup, 

            $clients[$client['id']] = $client['id'] . ' ' . $client['first_name'] . ' ' . $client['last_name']
                    . ' | Service user: Conditions ' . $conditionsAll . 'Gender ' . $serviceUser->getGenderText()
                    . ', Age Group ' . $serviceUser->ageGroup;
        }

        if (isset($_POST['clientId'])) {
            $clientId = $_POST['clientId'];
        } else {
            $clientId = $clientsBooking[0]->id;
        }

        $client = Client::loadModel($clientId);

        $serviceUsersIds = array($client->serviceUsers[0]->id);

        //simulate booking wizard

        $quote = ClientTesting::createHourlyQuote();
        Session::setQuote($quote);
        foreach ($serviceUsersIds as $serviceUserId) {
            Session::setSelectedServiceUser($serviceUserId);
        }
        //end simulate booking wizard
        //$bookingType = Session::getSelectedQuoteType();
        $serviceUserIds = Session::getSelectedServiceUsers();

        $showMale = true;
        $showFemale = true;
        $nationality = 'all';


        $carers = $client->getClientCarersSelection($quote, $serviceUserIds, $showMale, $showFemale, $nationality, 100);

        $carersNotWanted = $client->getClientCarersNotWanted($quote, $serviceUserIds, $showMale, $showFemale, $nationality);
        //$carersNotWanted = null;
        $nationalities = DBServices::getCarersNationalities(true);

        $this->render('application.views.carer.profile.clientSelection', array('carers' => $carers, 'clientId' => $clientId, 'clients' => $clients,
            'nationality' => $nationality, 'nationalities' => $nationalities, 'showMale' => $showMale, 'showFemale' => $showFemale,
            'carersNotWanted' => $carersNotWanted, 'formId' => 'choose-carers-form', 'view' => Constants::CARER_PROFILE_VIEW_ADMIN,
        ));
    }

    public function actionAssignOtherCarer($carerId, $missionId) {

        MissionCarers::changeCarerAssigned($missionId, $carerId);

        $this->redirect(array('/admin/missionCarers/missionsCarerAssignedNotStarted'));
    }

    public function actionShowCarersMap() {

        $carers = Carer::model()->findAll();

        $this->render('/carer/carersMap', array('carers' => $carers));
    }

    public function actionSelectHomePageCarers() {

        if (!empty($_POST)) {

            $posts = $_POST;
            $ids = array();
            foreach ($posts as $key => $value) {

                if (Util::startsWith($key, 'select_carer')) {
                    $ids[] = $value;
                }
            }

            $idsIN = implode(',', $ids);

            $sql = "UPDATE tbl_carer SET show_homepage = 1 WHERE id IN ($idsIN)";
            Yii::app()->db->createCommand($sql)->execute();

            $sql = "UPDATE tbl_carer SET show_homepage = 0 WHERE id NOT IN ($idsIN)";
            Yii::app()->db->createCommand($sql)->execute();

            $this->redirect(array('carerAdmin/selectHomePageCarers'));
        }


        $sql = "SELECT * FROM tbl_carer c WHERE EXISTS (SELECT * FROM tbl_carer_document cd WHERE c.id = cd.id_carer AND cd.id_content_crop IS NOT NULL)";

        $carers = Carer::model()->findAllBySql($sql);

        $this->render('/carer/homePageCarers', array('carers' => $carers));
    }

    public function actionSearchByQualification() {

        $carers = array();
        $postCode = '';
        $error = null;

        $carers = Carer::getCarerWithQualification($postCode, Constants::HOURLY);


        $this->render('/carer/searchByQualification', array('postCode' => $postCode, 'carers' => $carers, 'error' => $error));
    }

    public function actionSearchByPostCode() {

        $carers = array();
        $postCode = '';
        $error = null;

        if (isset($_GET['postCode'])) {

            $postCode = $_GET['postCode'];

            if (Maps::isValidPostCode($postCode)) {

                $carers = Carer::getCarersFromPostCode($postCode, Constants::HOURLY);
            } else {
                $error = 'Invalid post code';
            }
        }

        $this->render('/carer/searchByPostCode', array('postCode' => $postCode, 'carers' => $carers, 'error' => $error));
    }

    public function actionCreateCompensation($carerId) {

        $carer = Carer::loadModel($carerId);

        if (!empty($_POST)) {

            $hours = $_POST['hours'];

            $unitPrice = Prices::getPrice(Constants::USER_CARER, 'hourly_price');

            $amount = $unitPrice->multiply($hours);
            
            $missionId =  $_POST['missionId'];

            CarerTransaction::createCarerCompensation($carerId, $missionId, $amount);

            Yii::app()->user->setFlash('success', "Amount credited: " . $amount->text);

            $this->redirect(array('viewCarer', 'id' => $carerId));
        }

        $this->render('/carer/createCompensation', array('carer' => $carer));
    }

}
