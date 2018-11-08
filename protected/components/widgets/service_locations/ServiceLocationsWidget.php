<?php

class ServiceLocationSelect extends CAction {

    public function run($index) {

        UIServices::widgetRadioButtonSelect($index);
    }

}

class ServiceLocationAdd extends CAction {

    private $widgetPathView = 'application.components.widgets.service_locations.views._service_location';
    public $scenario;
    public $serviceLocations;

    public function run() {

        $serviceLocation = new Address();

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
                    'serviceLocation' => $serviceLocation,
                    'index' => $index,
                    'scenario' => $this->scenario,
                    'newServiceLocation' => true,
                    'booking' =>  Session::getSelectedValidQuote() ,
                    'size' => sizeof($this->serviceLocations),
                    'actionPath' => $this->getController()->id . '/serviceLocations.'
                        ), true, true);
        echo $output;
    }

}

class ServiceLocationCancelEdit extends CAction {

    private $widgetPathView = 'application.components.widgets.service_locations.views._service_location';
    public $scenario;
    public $serviceLocations;

    public function run($index) {

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        $serviceLocationId = $userIndexes[$index];

        $serviceLocation = Address::model()->findByPk($serviceLocationId);

        Yii::app()->clientScript->scriptMap['*.js'] = false;
        
        $scenario = $this->scenario;

        $output = $this->getController()->renderPartial($this->widgetPathView, array(
                    'serviceLocation' => $serviceLocation,
                    'index' => $index,
                    'scenario' => $scenario,
                    'newServiceLocation' => $serviceLocation->isNewRecord,
                    'size' => sizeof($this->serviceLocations),
                    'actionPath' => $this->getController()->id . '/serviceLocations.'
                        ), true, true);
        echo $output;
    }

}

class ServiceLocationCancelNew extends CAction {

    public function run($index) {

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        unset($userIndexes[$index]);

        Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;
    }

}

class ServiceLocationDelete extends CAction {

    public function run($index) {

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        $serviceLocationId = $userIndexes[$index];
        $clientId = Yii::app()->user->id;

        if (ClientLocationAddress::isServiceLocationUsed($serviceLocationId)) {

            echo Yii::t('texts', 'WIDGETS_SERVICE_LOCATION_ERROR1');
        } else {

            ClientLocationAddress::deleteServiceLocationAddress($clientId, $serviceLocationId);
            Session::removeSelectedServiceLocation($serviceLocationId);

            unset($userIndexes[$index]);

            Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;

            if (count($userIndexes) == 1) {

                //if only one location selects it
                $value = $userIndexes[0];

                Session::setSelectedServiceLocation($value);
            }
        }
    }
}

class ServiceLocationSave extends CAction {

    private $widgetPathView = 'application.components.widgets.service_locations.views._service_location';
    public $scenario;
    public $serviceLocations;

    public function run($index) {

        if (!empty($_POST['ServiceLocation'])) {

            $params = array();
            parse_str($_POST['ServiceLocation'], $params);

            $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

            $serviceLocationId = $userIndexes[$index];

            if ($serviceLocationId == -1) { //-1 arbitrary value for new service location
                $serviceLocation = new Address();
                $serviceLocation->data_type = Address::TYPE_SERVICE_LOCATION_MASTER_DATA;
            } else {
                $serviceLocation = Address::model()->findByPk($serviceLocationId);
            }

            $serviceLocation->attributes = $params['Address'][$index];

            if (!$serviceLocation->save()) {

                $errorMessage = CActiveForm::validate(array($serviceLocation), null, false);

                //[{'id':'Address_0_address_line_1','inputID':'Address_0_address_line_1','
                $className = get_class($serviceLocation);
                $errorMessage = str_replace($className . '_', $className . '_' . $index . '_', $errorMessage);

                echo $errorMessage;
            } else {

                //if new record create association
                if ($serviceLocationId == -1) {

                    $clientId = Yii::app()->user->id;

                    $serviceLocationAddress = new ClientLocationAddress();
                    $serviceLocationAddress->id_client = $clientId;
                    $serviceLocationAddress->id_address = $serviceLocation->id;

                    $serviceLocationAddress->save(false);
                }
                else{
                    
                    //update of record
                    //make sure every existing booking have their note explanation updated
                    $serviceLocation->updateExplanation();
                }

                //update userIndex with new service user id
                $userIndexes = Yii::app()->session[Session::USERS_INDEXES];
                $userIndexes[$index] = $serviceLocation->id;
                Yii::app()->session[Session::USERS_INDEXES] = $userIndexes;
                
                //set selected
                Session::setSelectedServiceLocation($serviceLocation->id);

                Yii::app()->clientScript->scriptMap['*.js'] = false;

                $output = $this->getController()->renderPartial($this->widgetPathView, array(
                            'serviceLocation' => $serviceLocation,
                            'index' => $index,
                            'scenario' => $this->scenario,
                            'newServiceLocation' => $serviceLocation->isNewRecord,
                            'newServiceSelected' => true,
                            'size' => sizeof($this->serviceLocations),
                            'booking' => Session::getSelectedValidQuote(),
                            'actionPath' => $this->getController()->id . '/serviceLocations.'
                                ), true, true);
                echo $output;
            }
        }
    }

}

class ServiceLocationsWidget extends CWidget {
    const MISSION_SCENARIO = 1;
    const EDIT_SCENARIO = 2;
    //const CHANGE_SCENARIO = 3;

    public $scenario;
    public $client;
    public $serviceLocations;
    public $booking; //used in change scenario

    public static function actions() {
        return array(
            'saveLocation' => 'application.components.widgets.service_locations.ServiceLocationsWidget.ServiceLocationSave',
            'addLocation' => 'application.components.widgets.service_locations.ServiceLocationsWidget.ServiceLocationAdd',
            'cancelEditLocation' => 'application.components.widgets.service_locations.ServiceLocationsWidget.ServiceLocationCancelEdit',
            'cancelNewLocation' => 'application.components.widgets.service_locations.ServiceLocationsWidget.ServiceLocationCancelNew',
            'deleteLocation' => 'application.components.widgets.service_locations.ServiceLocationsWidget.ServiceLocationDelete',
            'selectLocation' => 'application.components.widgets.service_locations.ServiceLocationsWidget.ServiceLocationSelect',
        );
    }

    public function run() {

        $this->render('serviceLocations', array('scenario' => $this->scenario));
    }

}

?>