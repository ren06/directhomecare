<?php

/**
 * This is the model class for table "tbl_postcode_coordinate".
 *
 * The followings are the available columns in table 'tbl_postcode_coordinate':
 * @property string $post_code
 * @property double $longitude
 * @property double $latitude
 */
class PostCodeCoordinate extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PostCodeCoordinate the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_postcode_coordinate';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_code, longitude, latitude, city', 'required'),
            array('longitude, latitude', 'numerical'),
            array('post_code', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('post_code, longitude, latitude, city', 'safe', 'on' => 'search'),
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
            'post_code' => 'Post Code',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
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

        $criteria->compare('post_code', $this->post_code, true);
        $criteria->compare('longitude', $this->longitude);
        $criteria->compare('latitude', $this->latitude);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function read($postCode) {

        $sql = "SELECT longitude, latitude, city FROM tbl_postcode_coordinate WHERE post_code = '$postCode'";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if ($result === false) {
            return null;
        } else {
            return $result;
        }
    }

    public static function write($postCode, $lng, $lat, $city) {

        $model = new PostCodeCoordinate();
        $model->post_code = $postCode;
        $model->longitude = $lng;
        $model->latitude = $lat;
        $model->city = $city;

        $res = $model->save();
    }
}