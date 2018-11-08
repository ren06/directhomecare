<?php

class ServiceUserSelect extends CAction {

    public function run($checkBoxState, $index) {

        UIServices::widgetCheckBoxSelect($checkBoxState, $index, Session::SERVICE_USERS_SELECTED);
    }

}

class ServiceUserDelete extends CAction {

    public function run($index) {

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        $serviceUserId = $userIndexes[$index];

        if (ServiceUserCondition::isServiceUserUsed($serviceUserId)) {

            echo Yii::t('texts', 'WIDGETS_VIEWS_SERVICE_USERS_ERROR1');
        } else {

            ServiceUserCondition::deleteServiceUserConditions($serviceUserId);
            //ServiceUser::model()->deleteByPk($serviceUserId);
            ServiceUser::deleteServiceUser($serviceUserId);

            Session::removeSelectedServiceUser($serviceUserId);

            unset($userIndexes[$index]);

            Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;

            $selectedServiceUsers = Yii::app()->session[Session::SERVICE_USERS_SELECTED];

            unset($selectedServiceUsers[$serviceUserId]);
        }
    }

}

class ServiceUserCancelNew extends CAction {

    public function run($index) {

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        unset($userIndexes[$index]);

        Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;
    }

}

class ServiceUserCancelEdit extends CAction {

    private $widgetPathView = 'application.components.widgets.service_users.views._service_user';
    public $scenario;

    public function run($index) {

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        $serviceUserId = $userIndexes[$index];

        $serviceUser = ServiceUser::model()->findByPk($serviceUserId);

        Yii::app()->clientScript->scriptMap['*.js'] = false;

        $output = $this->getController()->renderPartial($this->widgetPathView, array(
            'serviceUser' => $serviceUser,
            'index' => $index,
            'scenario' => $this->scenario,
            'errorMessages' => null, //used in non AJAX context
            'actionPath' => $this->getController()->id . '/serviceUsers.',
            'newServiceUser' => $serviceUser->isNewRecord,
                ), true);

        echo $output;
    }

}

class ServiceUserAdd extends CAction {

    private $widgetPathView = 'application.components.widgets.service_users.views._service_user';
    public $scenario;

    public function run() {

        $serviceUser = new ServiceUser();

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        if (!empty($userIndexes)) {
            $maxIndex = max(array_keys($userIndexes));
            $index = $maxIndex + 1;
        } else {
            $index = 0;
        }

        $userIndexes[$index] = -1; //new unassigned id
        Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;

        Yii::app()->clientScript->scriptMap['*.js'] = false;



        $output = $this->getController()->renderPartial($this->widgetPathView, array(
            'serviceUser' => $serviceUser,
            'index' => $index,
            'scenario' => $this->scenario,
            'errorMessages' => null, //used in non AJAX context
            'newServiceUser' => true,
            'actionPath' => $this->getController()->id . '/serviceUsers.'
                ), true, true);

        echo $output;
    }

}

class ServiceUserSave extends CAction {

    //find a way to get this dynamically
    private $widgetPathView = 'application.components.widgets.service_users.views._service_user';
    public $scenario;

    function run($index) {

        if (!empty($_POST['ServiceUser'])) {

            $params = array();
            parse_str($_POST['ServiceUser'], $params);

            $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

            $serviceUserId = $userIndexes[$index];

            if ($serviceUserId == -1) { //-1 arbitrary value for new service user
                $serviceUser = new ServiceUser();
            } else {
                $serviceUser = ServiceUser::model()->findByPk($serviceUserId);
            }
            
            $serviceUserData = $params['ServiceUser'][$index];
                     
            $serviceUser->first_name = $serviceUserData['first_name'];
            $serviceUser->last_name = $serviceUserData['last_name'];
            $serviceUser->gender = $serviceUserData['gender'];
            $serviceUser->note = $serviceUserData['note'];

            //check date validity
            $month = 1;//$params['Date'][$index]['Month'];
            $day = 1;//$params['Date'][$index]['Day'];
            $year = $params['Date'][$index]['Year'];

            if (checkdate($month, $day, $year)) {

                $day = sprintf("%02d", $day);
                $month = sprintf("%02d", $month);

                $date_value = "$year-$month-$day";
                $serviceUser->date_birth = $date_value;
            } else {

                unset($serviceUser->date_birth); //unset the value so that error is reported in the model
            }

            $errorMessagesFormArray = array();

            if (!$serviceUser->validate()) {

                //Form field errors
                $errorMessage = CActiveForm::validate(array($serviceUser), null, false);

                //"{"ServiceUser_last_name":["Last name cannot be blank."]}"
                $className = get_class($serviceUser);
                $errorMessages = str_replace($className . '_', $className . '_' . $index . '_', $errorMessage);
                $errorMessagesFormArray = json_decode($errorMessages, true);
            }

            $request = Yii::app()->request;

            $errorMessagesConditionsArray = Condition::saveConditions($serviceUser, $request, $index, true, true);

            //if no errors in form and conditions proceed
            if (empty($errorMessagesConditionsArray) && empty($errorMessagesFormArray)) {

                $serviceUser->save();
                $id = $serviceUser->id; //debug

                if ($serviceUserId == -1) { //-1 arbitrary value for new service user
                    $clientServieUser = new ClientServiceUser();
                    $clientServieUser->id_client = Yii::app()->user->id;
                    $clientServieUser->id_service_user = $serviceUser->id;
                    $clientServieUser->save();
                } else {
                    $serviceUser->updateNote();
                }

                Condition::saveConditions($serviceUser, $request, $index, true, true);

                //reload to get new conditions
                $serviceUser->refresh();

                //set selected in checkbox
                Session::setSelectedServiceUser($serviceUser->id);

                //update userIndex with new service user id
                $userIndexes = Yii::app()->session[Session::USERS_INDEXES];
                $userIndexes[$index] = $serviceUser->id;
                Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;

                Yii::app()->clientScript->scriptMap['*.js'] = false;

                $output = $this->getController()->renderPartial($this->widgetPathView, array(
                    'serviceUser' => $serviceUser,
                    'index' => $index,
                    'newServiceUser' => $serviceUser->isNewRecord,
                    'scenario' => $this->scenario,
                    'errorMessages' => null, //used in non AJAX context
                    'actionPath' => $this->getController()->id . '/serviceUsers.'
                        ), true);
                echo $output;
            } else {

                //convert any error to JSON
                $errorMessagesAllArray = array('formErrors' => $errorMessagesFormArray, 'conditionErrors' => $errorMessagesConditionsArray);

                $jsonErrors = json_encode($errorMessagesAllArray);

                echo $jsonErrors;
            }
        }
    }

}

class ServiceUsersWidget extends CWidget {

    const MISSION_SCENARIO = 1;
    const EDIT_SCENARIO = 2;

    //const CHANGE_SCENARIO = 3;

    public $scenario;
    public $client;
    public $serviceUsers;
    public $booking; //used in change scenario

    public static function actions() {
        return array(
            'saveUser' => array(
                'class' => 'application.components.widgets.service_users.ServiceUsersWidget.ServiceUserSave',
            ),
            'addUser' => array(
                'class' => 'application.components.widgets.service_users.ServiceUsersWidget.ServiceUserAdd',
            ),
            'cancelEditUser' => array(
                'class' => 'application.components.widgets.service_users.ServiceUsersWidget.ServiceUserCancelEdit',
            ),
            'cancelNewUser' => array(
                'class' => 'application.components.widgets.service_users.ServiceUsersWidget.ServiceUserCancelNew',
            ),
            'deleteUser' => array(
                'class' => 'application.components.widgets.service_users.ServiceUsersWidget.ServiceUserDelete',
            ),
            'selectServiceUser' => array(
                'class' => 'application.components.widgets.service_users.ServiceUsersWidget.ServiceUserSelect',
            ),
        );
    }

    public function run() {

        $this->render('serviceUsers', array('scenario' => $this->scenario));
    }

}

?>