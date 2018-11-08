<?php

/**
 * This is the model class for table "tbl_address".
 *
 * The followings are the available columns in table 'tbl_address':
 * @property integer $id
 * @property integer $data_type
 * @property string $address_line_1
 * @property string $address_line_2
 * @property string $city
 * @property string $county
 * @property string $post_code
 * @property string $country
 * @property string $landline
 * @property double $longitude
 * @property double $latitude
 * @property string $valid_from
 * @property string $valid_to
 * @property string $explanation 
 *
 * The followings are the available model relations:
 * @property Booking[] $bookings
 * @property Carer[] $carers
 * @property Client[] $tblClients
 * @property CreditCard[] $creditCards
 * @property Mission[] $missions
 */
class Address extends ActiveRecord {

    const TYPE_CARER_ADDRESS = 0;
    const TYPE_SERVICE_LOCATION_MASTER_DATA = 1;
    const TYPE_SERVICE_LOCATION_BOOKING = 2;
    const TYPE_SERVICE_LOCATION_MISSION = 3;
    const TYPE_BILLING_ADDRESS = 4;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Address the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_address';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('address_line_1, address_line_2, city, county, post_code, explanation', 'filter', 'filter' => 'strip_tags'),
            array('address_line_1, address_line_2, city, county, post_code, explanation', 'filter', 'filter' => 'trim'),
            array('data_type, address_line_1, city, post_code', 'required'),
            array('id, data_type', 'numerical', 'integerOnly' => true),
            array('longitude, latitude', 'numerical'),
            array('address_line_1, address_line_2, city, country', 'length', 'max' => 50),
            array('address_line_1, address_line_2, city', 'filter', 'filter' => array($this, 'filterUcLc')),
            array('post_code', 'length', 'max' => 10),
            array('post_code', 'checkPostCode'),
            array('landline', 'length', 'max' => 20),
            array('explanation', 'length', 'max' => 255),
            array('valid_from, valid_to', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, data_type, address_line_1, address_line_2, city, county, post_code, country, landline, longitude, latitude, valid_from, valid_to', 'safe', 'on' => 'search'),
        );
    }

    public function filterUcLc($value) {

        return ucwords(strtolower(trim($value)));
    }

    public function checkPostCode($attribute, $params) {

        //check post code with lookup
        if (Maps::isValidPostCode($this->post_code)) {
            $this->post_code = Util::formatPostCode($this->post_code);
        } else {
            $this->addError($attribute, Yii::t('texts', 'ERROR_POST_CODE_NOT_VALID'));
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bookings' => array(self::HAS_MANY, 'Booking', 'id_address'),
            'carers' => array(self::HAS_MANY, 'Carer', 'id_address'),
            'tblClients' => array(self::MANY_MANY, 'Client', 'tbl_client_location_address(id_address, id_client)'),
            'creditCards' => array(self::HAS_MANY, 'CreditCard', 'id_address'),
            'missions' => array(self::HAS_MANY, 'Mission', 'id_address'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'data_type' => 'Data Type',
            'address_line_1' => Yii::t('texts', 'LABEL_ADDRESS_LINE_1'),
            'address_line_2' => Yii::t('texts', 'LABEL_ADDRESS_LINE_2'),
            'city' => Yii::t('texts', 'LABEL_CITY'),
            'county' => Yii::t('texts', 'LABEL_COUNTY'),
            'post_code' => Yii::t('texts', 'LABEL_POST_CODE'),
            'country' => Yii::t('texts', 'LABEL_COUNTRY'),
            'landline' => Yii::t('texts', 'LABEL_LANDLINE'),
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'valid_from' => Yii::t('texts', 'LABEL_VALID_FROM'),
            'valid_to' => Yii::t('texts', 'LABEL_VALID_TO'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('data_type', $this->data_type);
        $criteria->compare('address_line_1', $this->address_line_1, true);
        $criteria->compare('address_line_2', $this->address_line_2, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('county', $this->county, true);
        $criteria->compare('post_code', $this->post_code, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('landline', $this->landline, true);
        $criteria->compare('longitude', $this->longitude);
        $criteria->compare('latitude', $this->latitude);
        $criteria->compare('valid_from', $this->valid_from, true);
        $criteria->compare('valid_to', $this->valid_to, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeSave() {

        $this->country = 'GB';

        $this->setCoordinates();

        return parent::beforeSave();
    }

    public function display($separator = '</br>') {

        $result = $this->address_line_1 . $separator;

        if ($this->address_line_2 != '') {
            $result .= $this->address_line_2 . $separator;
        }
        $result .= $this->city . $separator . $this->post_code;

        return $result;
    }

    public function displayShort($separator = '</br>') {

        $postCode = $this->post_code;
        $pos = strrpos($postCode, ' ');

        if ($pos > 0) {

            $postCode = trim(substr($postCode, 0, $pos));
        }

        return $this->city . $separator . $postCode;
    }

    /**
     * Set lontitude and longitude according to PostCodeCoordinate Does not save the object
     * 
     * @return boolean return true if an update was made, false if both lat and long where already set
     */
    public function setCoordinates() {

        if (isset($this->latitude) && isset($this->longitude)) {
            return false;
        } else {

            $result = Maps::getPostCodeData($this->post_code);

            // Center the map on geocoded address
            $this->latitude = $result['latitude'];
            $this->longitude = $result['longitude'];

            return true;
        }
    }

    /**
     *
     * Set billing address, create new address in master data if does not exist
     */
    public static function createBillingAddress($billingAddress, $serviceLocationId, $bookingId, $clientId) {

        //Create billing address
        if ($billingAddress instanceof BookingAddress) {
            //new address entered by the user
            $billingAddress->id_booking = $bookingId;
            $billingAddress->type = self::TYPE_BILLING;
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
            BookingAddress::create($serviceLocationId, $bookingId, BookingAddress::TYPE_BILLING);
        }
    }

    public static function deleteServiceLocationAddresses($id_client) {

        $sql = "SELECT * FROM `tbl_client_location_address` WHERE `id_client`=" . $id_client;

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($rows as $row) {

            ClientLocationAddress::model()->deleteByPk(array('id_client' => $row['id_client'], 'id_address' => $row['id_address']));
            Address::model()->deleteByPk($row['id_address']);
        }
    }

    public static function deleteServiceLocationAddress($id_client, $id_address) {

        $sql = "SELECT * FROM `tbl_client_location_address` WHERE `id_client`="
                . $id_client . " AND id_address=" . $id_address;

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        //should return one row
        foreach ($rows as $row) {

            self::model()->deleteByPk(array('id_client' => $row['id_client'], 'id_address' => $row['id_address']));

            Address::model()->deleteByPk($row['id_address']);
        }
    }

    public function isUsedBooking() {

        return (count($this->bookings) > 0);
    }

    public function isUsedCreditCard() {

        return (count($this->creditCards) > 0);
    }

    public static function copy($addressId, $type) {

        $address = self::loadModel($addressId);

        $newAddress = clone $address;
        $newAddress->data_type = $type;
        unset($newAddress->id);
        $newAddress->isNewRecord = true;

        $newAddress->insert();

        return $newAddress;
    }

    public function updateExplanation() {

        $ids = array();

        $bookings = $this->bookings;

        foreach ($bookings as $booking) {

            foreach ($booking->missions as $mission) {

                $address = $mission->address;

                if ($address->address_line_1 == $this->address_line_1 && $address->address_line_2 == $this->address_line_2 && $address->city == $this->city && $address->post_code == $this->post_code) {

                    $ids[] = $address->id;
                }
            }
        }

        $ids = array_unique($ids);

        if (!empty($ids)) {

            $idsCondition = implode(',', $ids);

            $explanation = $this->explanation;

            $sql = "UPDATE tbl_address SET explanation='$explanation'
                WHERE data_type=3 AND id IN( $idsCondition)";

            Yii::app()->db->createCommand($sql)->execute();
        }
    }

}