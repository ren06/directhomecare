<?php

/**
 * This is the model class for table "tbl_client_carer_search_criteria".
 *
 * The followings are the available columns in table 'tbl_client_carer_search_criteria':
 * @property integer $id
 * @property integer $id_client
 * @property string $criteria_name
 * @property string $criteria_value
 *
 * The followings are the available model relations:
 * @property Client $idClient
 */
class ClientCarerSearchCriteria extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClientCarerSearchCriteria the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_client_carer_search_criteria';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('criteria_name, criteria_value', 'required'),
            array('id_client', 'numerical', 'integerOnly' => true),
            array('criteria_name, criteria_value', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_client, id_session, criteria_name, criteria_value', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_client' => 'Id Client',
            'criteria_name' => 'Criteria Name',
            'criteria_value' => 'Criteria Value',
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
        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('criteria_name', $this->criteria_name, true);
        $criteria->compare('criteria_value', $this->criteria_value, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function get($clientId = null, $sessionId = null) {

        $searchCriteria = array();

        $criteria = new CDbCriteria;
        if(isset($clientId)){
        $criteria->compare('id_client', $clientId);
        }
        else{
        $criteria->compare('id_session', $sessionId);    
        }
        $results = self::model()->findAll($criteria);

        foreach ($results as $result) {
            $searchCriteria[$result->criteria_name] = $result->criteria_value;
        }

        return $searchCriteria;
    }

    public static function store($clientId, $sessionId, $searchCriteria) {

        assert(isset($clientId) || isset($sessionId));
        assert(!(isset($clientId) && isset($sessionId)));

        $criteria = new CDbCriteria();

        if (isset($clientId)) {
            $criteria->compare('id_client', $clientId);
        } else {

            $criteria->compare('id_session', $sessionId);
        }

        $ok = self::model()->deleteAll($criteria); //delete existing data

        $records = array();
        foreach ($searchCriteria as $key => $value) {

            if ($key != 'YII_PAGE_STATE') {

                $model = new ClientCarerSearchCriteria();
                $model->id_client = $clientId;
                $model->id_session = $sessionId;
                $model->criteria_name = $key;
                $model->criteria_value = $value;
                $model->save();
            }
            //$records[] = array('id_client' => $clientId, 'criteria_name' => $key, 'criteria_value' => $value);
        }

        //Yii 1.1.14
//        $builder = Yii::app()->db->schema->commandBuilder;
//        $command = $builder->createMultipleInsertCommand('tbl_client_carer_search_criteria', $records);
//        $command->execute();
    }

}