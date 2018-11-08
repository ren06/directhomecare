<?php

/**
 * This is the model class for table "tbl_client_location_address".
 *
 * The followings are the available columns in table 'tbl_client_location_address':
 * @property integer $id_client
 * @property integer $id_address
 */
class ClientLocationAddress extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClientLocationAddress the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_client_location_address';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_client, id_address', 'required'),
            array('id_client, id_address', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_client, id_address', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id_client' => 'Id Client',
            'id_address' => 'Id Address',
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

        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('id_address', $this->id_address);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function deleteServiceLocationAddresses($id_client) {

        $sql = "SELECT * FROM `tbl_client_location_address` WHERE `id_client`=" . $id_client;

        $rows = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($rows as $row) {

            self::model()->deleteByPk(array('id_client' => $row['id_client'], 'id_address' => $row['id_address']));
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

    public static function isServiceLocationUsed($id) {

//        $sql = "SELECT * FROM `tbl_live_in_request` WHERE `id_service_location`=" . $id;
//
//        $rows = Yii::app()->db->createCommand($sql)->queryAll();
//
//        return!empty($rows);

        return false;
    }

}