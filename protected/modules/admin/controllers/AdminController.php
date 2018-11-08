<?php

class AdminController extends Controller {

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

    public function actionIndex() {
        $this->render('home');
    }

    /**
     * Displays the login page for ADMIN
     */
    public function actionAdminLogin() {

        $model = new AdminLoginForm();

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['AdminLoginForm'])) {
            $model->attributes = $_POST['AdminLoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('adminLogin', array('model' => $model));
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

    public function actionRoleSetup() {

        //drop and recreated DB tables

        $sql = "drop table if exists `AuthAssignment`;# MySQL returned an empty result set (i.e. zero rows).
        # MySQL returned an empty result set (i.e. zero rows).

        drop table if exists `AuthItemChild`;# MySQL returned an empty result set (i.e. zero rows).
        # MySQL returned an empty result set (i.e. zero rows).

        drop table if exists `AuthItem`;# MySQL returned an empty result set (i.e. zero rows).
        # MySQL returned an empty result set (i.e. zero rows).


        create table `AuthItem`
        (
           `name`                 varchar(64) not null,
           `type`                 integer not null,
           `description`          text,
           `bizrule`              text,
           `data`                 text,
           primary key (`name`)
        ) engine InnoDB;# MySQL returned an empty result set (i.e. zero rows).
        # MySQL returned an empty result set (i.e. zero rows).


        create table `AuthItemChild`
        (
           `parent`               varchar(64) not null,
           `child`                varchar(64) not null,
           primary key (`parent`,`child`),
           foreign key (`parent`) references `AuthItem` (`name`) on delete cascade on update cascade,
           foreign key (`child`) references `AuthItem` (`name`) on delete cascade on update cascade
        ) engine InnoDB;# MySQL returned an empty result set (i.e. zero rows).
        # MySQL returned an empty result set (i.e. zero rows).


        create table `AuthAssignment`
        (
           `itemname`             varchar(64) not null,
           `userid`               varchar(64) not null,
           `bizrule`              text,
           `data`                 text,
           primary key (`itemname`,`userid`),
           foreign key (`itemname`) references `AuthItem` (`name`) on delete cascade on update cascade
        ) engine InnoDB;# MySQL returned an empty result set (i.e. zero rows).
        # MySQL returned an empty result set (i.e. zero rows).
        # MySQL returned an empty result set (i.e. zero rows).
        ";

        Yii::app()->db->createCommand($sql)->execute();

        //Define authorisations
        // See http://www.yiiframework.com/wiki/136/getting-to-understand-hierarchical-rbac-scheme/

        $auth = Yii::app()->authManager;

        //carers operations
//        $auth->createOperation('op_createFakeMission');
//        $auth->createOperation('op_carers_recent');
//        $auth->createOperation('op_carers_search');
//        $auth->createOperation('op_carers_loginHistory');
//        $auth->createOperation('op_carers_shiftAssignment');
//        $auth->createOperation('op_carers_unprocessedDocument');
//        $auth->createOperation('op_carers_withDocument');
        //Tasks
//        $task = $auth->createTask('task_carerGeneral', 'Task Carer General');
//        $task->addChild('op_carers_recent');
//        $task->addChild('op_carers_search');
//        $task = $auth->createTask('task_carerAdmin', 'Task Carer Admin');
//        $task->addChild('op_createFakeMission');
        //Roles
        $role = $auth->createRole('role_carerGeneral');

        $role = $auth->createRole('role_carerAdmin');

        $role = $auth->createRole('role_cronjobAdmin');

        $role = $auth->createRole('role_clientAdmin');

        $role = $auth->createRole('role_superadmin');
        $role->addChild('role_carerGeneral');
        $role->addChild('role_carerAdmin');
        $role->addChild('role_cronjobAdmin');
        $role->addChild('role_clientAdmin');

        //Assign roles to user
        //super admins
        $auth->assign('role_superadmin', 'rcohard');

        $auth->assign('role_superadmin', 'rtheuillon');

        //Emilie
        $auth->assign('role_carerGeneral', 'eharper');
        $auth->assign('role_clientAdmin', 'eharper');


        echo '<b>Setup successfuly completed!</b>';
    }

    public function actionClearDBCache() {

        Yii::app()->db->schema->refresh();
        Yii::app()->cache->flush();
        apc_clear_cache();
        echo 'Schema Cache cleared';
    }

    public function actionMigrateConditions2() {

        //Yii::app()->db->createCommand('SET FOREIGN_KEY_CHECKS = 0;')->execute();

        $transaction = Yii::app()->db->beginTransaction();

        try {

            $carers = Carer::model()->findAll();
            $serviceUsers = ServiceUser::model()->findAll();

            $allPersons = array_merge($carers, $serviceUsers);

            $recordsCreated = 0;

            foreach ($allPersons as $person) {

                if ($person instanceof Carer) {
                    $isCarer = true;
                } else {
                    $isCarer = false;
                }

                if ($isCarer) {

                    $conditions = $person->carerConditions;
                    $carerId = $person->id;
                } else {
                    $conditions = $person->serviceUserConditions;
                    $serviceUserId = $person->id;
                }

                $conditionsKeys = array();

                foreach ($conditions as $condition) {

                    $conditionsKeys[$condition->id_condition] = $condition->id_condition;
                }

                //18    no_physical_problems
                //14    cant_walk
                //13    parkinson
                //12    paraplegic
                //11    tetraplegic
                //handle physical problem
                if (array_key_exists(11, $conditionsKeys)) {
                    $newConditionKey = 49; //disabled
                } elseif (array_key_exists(12, $conditionsKeys)) {
                    $newConditionKey = 49; //disabled
                } elseif (array_key_exists(13, $conditionsKeys)) {
                    $newConditionKey = 48; //dependant
                } elseif (array_key_exists(14, $conditionsKeys)) {
                    $newConditionKey = 47; //walking_difficulties
                } elseif (array_key_exists(18, $conditionsKeys)) {
                    $newConditionKey = 46; //good shape
                } else {
                    $newConditionKey = 0;
                }

                if ($newConditionKey != 0) {

                    //delete all

                    if ($isCarer) {
                        $sql = "DELETE FROM tbl_carer_condition WHERE ";
                        $sql .= " id_carer = $carerId  ";
                    } else {
                        $sql = "DELETE FROM tbl_service_user_condition WHERE ";
                        $sql .= " id_service_user = $serviceUserId  ";
                    }

                    $sql .= " and id_condition IN (11, 12, 13, 14, 18); ";
                    Yii::app()->db->createCommand($sql)->execute();

                    //create new value
                    if ($isCarer) {
                        $newCondition = new CarerCondition();
                        $newCondition->id_carer = $condition->id_carer;
                    } else {
                        $newCondition = new ServiceUserCondition();
                        $newCondition->id_service_user = $condition->id_service_user;
                    }
                    $newCondition->id_condition = $newConditionKey;
                    $newCondition->save();
                    $recordsCreated++;
                }


                //21    no_mental_problems
                //20    memory_problems
                //19    alzheimer
                //handle metnal problem
                if (array_key_exists(19, $conditionsKeys)) {
                    $newConditionKey = 53; //dementia
                } elseif (array_key_exists(20, $conditionsKeys)) {
                    $newConditionKey = 51; //slight memory loss
                } elseif (array_key_exists(21, $conditionsKeys)) {
                    $newConditionKey = 50; //sound minded
                } else {
                    $newConditionKey = 0;
                }

                if ($newConditionKey != 0) {

                    //delete all
                    if ($isCarer) {
                        $sql = "DELETE FROM tbl_carer_condition WHERE ";
                        $sql .= " id_carer = $carerId  ";
                    } else {
                        $sql = "DELETE FROM tbl_service_user_condition WHERE ";
                        $sql .= " id_service_user = $serviceUserId  ";
                    }

                    $sql .= " and id_condition IN (19, 20, 21); ";
                    Yii::app()->db->createCommand($sql)->execute();

                    //create new value
                    if ($isCarer) {
                        $newCondition = new CarerCondition();
                        $newCondition->id_carer = $condition->id_carer;
                    } else {
                        $newCondition = new ServiceUserCondition();
                        $newCondition->id_service_user = $condition->id_service_user;
                    }
                    $newCondition->id_condition = $newConditionKey;
                    $newCondition->save();
                    $recordsCreated++;
                }

                //handle activities
                if (array_key_exists(16, $conditionsKeys) || array_key_exists(17, $conditionsKeys)) {

                    //create new value
                    if ($isCarer) {
                        $newCondition = new CarerCondition();
                        $newCondition->id_carer = $condition->id_carer;
                    } else {
                        $newCondition = new ServiceUserCondition();
                        $newCondition->id_service_user = $condition->id_service_user;
                    }
                    $newCondition->id_condition = 31;
                    $newCondition->save();
                    $recordsCreated++;

                    if ($isCarer) {
                        $newCondition = new CarerCondition();
                        $newCondition->id_carer = $condition->id_carer;
                    } else {
                        $newCondition = new ServiceUserCondition();
                        $newCondition->id_service_user = $condition->id_service_user;
                    }
                    $newCondition->id_condition = 32;
                    $newCondition->save();
                    $recordsCreated++;

                    if ($isCarer) {
                        $newCondition = new CarerCondition();
                        $newCondition->id_carer = $condition->id_carer;
                    } else {
                        $newCondition = new ServiceUserCondition();
                        $newCondition->id_service_user = $condition->id_service_user;
                    }
                    $newCondition->id_condition = 30;
                    $newCondition->save();
                    $recordsCreated++;

                    if ($isCarer) {
                        $newCondition = new CarerCondition();
                        $newCondition->id_carer = $condition->id_carer;
                    } else {
                        $newCondition = new ServiceUserCondition();
                        $newCondition->id_service_user = $condition->id_service_user;
                    }
                    $newCondition->id_condition = 39;
                    $newCondition->save();
                    $recordsCreated++;

                    //delete all
                    if ($isCarer) {
                        $sql = "DELETE FROM tbl_carer_condition WHERE ";
                        $sql .= " id_carer = $carerId  ";
                    } else {
                        $sql = "DELETE FROM tbl_service_user_condition WHERE ";
                        $sql .= " id_service_user = $serviceUserId  ";
                    }

                    $sql .= " and id_condition IN (16, 17); ";
                    Yii::app()->db->createCommand($sql)->execute();
                }

                if (array_key_exists(15, $conditionsKeys)) {

                    if ($isCarer) {
                        $newCondition = new CarerCondition();
                        $newCondition->id_carer = $condition->id_carer;
                    } else {
                        $newCondition = new ServiceUserCondition();
                        $newCondition->id_service_user = $condition->id_service_user;
                    }
                    $newCondition->id_condition = 44;
                    $newCondition->save();
                    $recordsCreated++;

                    //delete all
                    if ($isCarer) {
                        $sql = "DELETE FROM tbl_carer_condition WHERE ";
                        $sql .= " id_carer = $carerId  ";
                    } else {
                        $sql = "DELETE FROM tbl_service_user_condition WHERE ";
                        $sql .= " id_service_user = $serviceUserId  ";
                    }

                    $sql .= " and id_condition IN (15); ";
                    Yii::app()->db->createCommand($sql)->execute();
                }
            }

            $transaction->commit();

            echo "Migation completed. Records created $recordsCreated.";
        } catch (CException $e) {

            $transaction->rollBack();

            echo 'Error' . $e;
        }
    }

    public function actionSwapMaleFemale() {

        $transaction = Yii::app()->db->beginTransaction();

        try {

//            $sql = "SELECT * FROM tbl_carer WHERE created < 2013.11.31";
//
//            $carers = Carer::model()->findAllBySql($sql);

            $date = '2013-11-19 23:05:59';


            $sql = " UPDATE tbl_carer SET gender = 5 WHERE gender = 1 AND created < '$date';";
            Yii::app()->db->createCommand($sql)->execute();

            $sql = " UPDATE tbl_carer SET gender = 6 WHERE gender = 2 AND created < '$date';";
            Yii::app()->db->createCommand($sql)->execute();

            $sql = " UPDATE tbl_carer SET gender = 2 WHERE gender = 5 AND created < '$date';";
            Yii::app()->db->createCommand($sql)->execute();

            $sql = " UPDATE tbl_carer SET gender = 1 WHERE gender = 6 AND created < '$date';";
            Yii::app()->db->createCommand($sql)->execute();


            $transaction->commit();

            echo "Conversion Done";
        } catch (CException $e) {

            $transaction->rollBack();

            echo 'Error' . $e;
        }
    }

    public function actionAddLanguageCarers() {

        $transaction = Yii::app()->db->beginTransaction();

        try {

            //select carers who arrived at least to step 3
            $sql = "SELECT * FROM tbl_carer WHERE wizard_completed >= 2";

            $carers = Carer::model()->findAllBySql($sql);

//            /$levels = Languages::getLanguageLevels();
            $i = 0;
            //$native = 0;
            //$fluent = 0;
            foreach ($carers as $carer) {

                $i = $i + CarerLanguage::addLanguage($carer);
            }

            $transaction->commit();

            echo "Conversion Done for " . count($carers) . " carers."; // $i did not have languages: $native native, $fluent fluent.";
        } catch (CException $e) {

            $transaction->rollBack();

            echo 'Error' . $e;
        }
    }

    public function actionMigrateWizardCompleted() {

        $transaction = Yii::app()->db->beginTransaction();

        try {

//2 arrive sur location => devient 2 signup completed
//3 arrive sur users  => devient 2 signup completed
//4 arrive sur select carers  => devient 3 userLocation completed
//5 arrive sur payment => devient 3 userLocation completed
//6 arrive sur conf => 4 payment completed

            $date = '2014-02-10 23:05:59';

            $sql = "SELECT * FROM tbl_client WHERE created < '$date'";

            $clients = Client::model()->findAllBySql($sql);

            $result = '';

            foreach ($clients as $client) {

                $wizardCompletedOld = $client->wizard_completed;

                switch ($wizardCompletedOld) {

                    case 2:
                        $wizardCompletedNew = 2;
                        break;
                    case 3:
                        $wizardCompletedNew = 2;
                        break;
                    case 4:
                        $wizardCompletedNew = 3;
                        break;
                    case 5:
                        $wizardCompletedNew = 3;
                        break;
                    case 6:
                        $wizardCompletedNew = 4;
                        break;
                }

                $client->wizard_completed = $wizardCompletedNew;
                $client->save(false);

                $result .= '<br>Client ' . $client->id . ' old: ' . $wizardCompletedOld . ' new: ' . $wizardCompletedNew . ' <br>';
            }

            $transaction->commit();

            echo "Conversion Done <br>";
            echo $result;
        } catch (CException $e) {

            $transaction->rollBack();

            echo 'Error' . $e;
        }
    }

    public function actionClientProspectsAdmin() {
        $model = new ClientProspect('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ClientProspect']))
            $model->attributes = $_GET['ClientProspect'];

        $this->render('clientProspects', array(
            'model' => $model,
        ));
    }

    /**
     * Update the coordiates of all address objects
     */
    public function actionUpdateAddressPostCodeCoordinates() {

        $addresses = Address::model()->findAll();

        $changed = 0;
        $unchanged = 0;

        foreach ($addresses as $address) {

            $result = $address->setCoordinates();
            $address->save();
            if ($result) {
                $changed++;
            } else {
                $unchanged++;
            }
        }

        echo "Changed: $changed, unchanged: $unchanged";
    }

    public function actionUpdateCarerAddresses() {

        $carers = Carer::model()->findAll('id_address IS NOT NULL');
        $i = 0;
        echo 'Carer with an address: ' . count($carers) . '<br>';

        foreach ($carers as $carer) {

            $address = $carer->address;

            if (isset($address)) {

                if (isset($address->post_code) && $address->post_code != "") {

                    $addressData = Maps::getPostCodeData($address->post_code);
                    $newCity = $addressData['city'];
                    echo 'Existing City: ' . $address->city . ' Retrieved City: ' . $newCity;

                    if ($address->city != $newCity) {

                        if (Util::isPostCodeValid($newCity)) {
                            throw CException('Postcode found for City: ' . $newCity);
                        }
                        $dbTransaction = Yii::app()->db->beginTransaction();

                        try {
                            echo ' <b>Changing city for carer ' . $carer->id . '</b>';
                            $address->city = $newCity;
                            $i++;
                            $address->save(false);
                            echo ' changed ';
                            $dbTransaction->commit();
                        } catch (CException $e) {

                            Yii::log('', CLogger::LEVEL_ERROR, $e->message . ' ' . $carer->id);

                            $dbTransaction->rollBack();

                            echo $e->message . ' carer id: ' . $carer->id . ' address id: ' . $address->id;
                        }
                    }
                    echo '<br />';
                }
            }
        }


        echo 'Finished. ' . $i . ' processsed';
    }

    // http://localhost:8888/directhomecare/admin/admin/sendEmailsInvestors
    // insert into tbl_investors (email) select email_address from tbl_carer union (select email_address from tbl_client)

    public function actionSendEmailsInvestors() {

        $sql = 'SELECT * FROM tbl_investors WHERE sent = 0 LIMIT 200';

        $results = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($results as $result) {

            $email = $result['email'];

            Emails::sendInvestors_Email($email);

            $sql = "UPDATE tbl_investors SET sent=1 WHERE email='$email'";
            Yii::app()->db->createCommand($sql)->execute();
        }

        //update database

        echo 'Emails sent to ' . count($results) . ' persons';
    }

}
