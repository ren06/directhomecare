<?php

/**
 * This is the model class for table "tbl_address".
 *
 * The followings are the available columns in table 'tbl_address':
 * @property integer $type
 */
class ServiceAddress extends Address {
    const TYPE_SERVICE = 0;
    const TYPE_BILLING = 1;

    public $type;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_service_address';
    }

    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'service' => array(self::BELONGS_TO, 'Service', 'id_service'),
        );
    }

    public static function loadModels($ids) {
        $models = self::model()->findAllByPk($ids);
        if ($models === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $models;
    }

    public static function loadModel($id) {
        $model = self::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    /**
     *
     * @param type $serviceLocationId id of address that needs to be copied
     * @param type $serviceId service id to be associated with
     * @param type $type type (location or billing)
     * @return ServiceAddress 
     */
    public static function create($serviceLocationId, $serviceId, $type) {

        $serviceLocation = Address::loadModel($serviceLocationId);

        $serviceLocationService = new ServiceAddress();
        $serviceLocationService->attributes = $serviceLocation->attributes;
        $serviceLocationService->id_service = $serviceId;
        $serviceLocationService->type = $type;

        $valid = $serviceLocationService->validate();
        $errors = $serviceLocationService->getErrors();

        if ($serviceLocationService->save()) {
            return $serviceLocationService;
        } else {
            throw new CDbException('Service Location copy failed');
        }
    }
    
    /**
     *
     * Set billing address, create new address in master data if does not exist
     */
    public static function handleBillingAddress($billingAddress, $serviceLocationId, $serviceId) {

        //Create billing address
        if ($billingAddress instanceof ServiceAddress) {
            //new address entered by the user
            $billingAddress->id_service = $serviceId;
            $billingAddress->type = ServiceAddress::TYPE_BILLING;
            $billingAddress->save();

            //add it to master data service location
            $address = new Address();
            $address->attributes = $billingAddress->attributes;
            $address->id = null;
            $address->save();

            $location = new ServiceLocationAddress();
            $location->id_client = $clientId;
            $location->id_address = $address->id;
            $location->save();
        } else {
            //existing service location
            ServiceAddress::create($serviceLocationId, $serviceId, ServiceAddress::TYPE_BILLING);
        }
    }

}
