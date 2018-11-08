<?php

/**
 * This is the model class for table "tbl_client_carer_relation".
 *
 * The followings are the available columns in table 'tbl_client_carer_relation':
 * @property integer $id
 * @property integer $id_carer
 * @property integer $id_client
 * @property integer $relation
 * @property integer $rating_punctuality
 * @property integer $rating_initialive
 * @property integer $rating_kindness
 * @property integer $rating_presentation
 * @property integer $rating_skills
 * @property integer $rating_overall
 *
 * The followings are the available model relations:
 * @property Carer $idCarer
 * @property Client $idClient
 */
class ClientCarerRelation extends ActiveRecord {

    /*****
     * AT THE MOMENT ONLY NULL and 2 ARE USED IN LIVE SYSTEM
     */
    
    const RELATION_NONE = 0; //when user clears from black list
    const RELATION_FAVOURITE = 1;
    const RELATION_SELECTED = 2; 
    const RELATION_NOT_WANTED = 3;

    private static $relationLabels = array(
        self::RELATION_FAVOURITE => 'Favourite',
        self::RELATION_SELECTED => 'Selected',
        self::RELATION_NOT_WANTED => 'Not wanted',
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClientCarerRelation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_client_carer_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_carer, id_client, relation', 'required'),
            array('id_carer, id_client, relation, rating_punctuality, rating_initialive, rating_kindness, rating_presentation, rating_skills, rating_overall', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, id_carer, id_client, relation, rating_punctuality, rating_initialive, rating_kindness, rating_presentation, rating_skills, rating_overall', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'carer' => array(self::BELONGS_TO, 'Carer', 'id_carer'),
            'client' => array(self::BELONGS_TO, 'Client', 'id_client'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_carer' => 'Id Carer',
            'id_client' => 'Id Client',
            'favourite' => 'Favourite',
            'team' => 'Team',
            'not_wanted' => 'Not Wanted',
            'rating_punctuality' => 'Rating Punctuality',
            'rating_initialive' => 'Rating Initialive',
            'rating_kindness' => 'Rating Kindness',
            'rating_presentation' => 'Rating Presentation',
            'rating_skills' => 'Rating Skills',
            'rating_overall' => 'Rating Overall',
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
        $criteria->compare('id_carer', $this->id_carer);
        $criteria->compare('id_client', $this->id_client);
        $criteria->compare('favourite', $this->favourite);
        $criteria->compare('team', $this->team);
        $criteria->compare('not_wanted', $this->not_wanted);
        $criteria->compare('rating_punctuality', $this->rating_punctuality);
        $criteria->compare('rating_initialive', $this->rating_initialive);
        $criteria->compare('rating_kindness', $this->rating_kindness);
        $criteria->compare('rating_presentation', $this->rating_presentation);
        $criteria->compare('rating_skills', $this->rating_skills);
        $criteria->compare('rating_overall', $this->rating_overall);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getRelation($clientId, $carerId) {

//        return self::model()->find(array('condition' => 'id_carer=:idCarer AND id_client=:idClient',
//                    'params' => array(':idCarer' => $carerId, ':idClient' => $clientId)));

        $sql = "SELECT * FROM tbl_client_carer_relation WHERE id_carer=:idCarer AND id_client=:idClient";

        $model = self::model()->findBySql($sql, array(':idCarer' => $carerId, ':idClient' => $clientId));

        return $model;
    }

    /**
     * Get existing relation, init a new one if not present (does not save it)
     * 
     * @param type $clientId
     * @param type $carerId
     * @return \ClientCarerRelation
     */
    private static function getInstance($clientId, $carerId) {

        //get existing record if any
        $relation = self::getRelation($clientId, $carerId);

        if (!isset($relation)) {

            //otherwise create it
            $relation = new ClientCarerRelation();
            $relation->id_carer = $carerId;
            $relation->id_client = $clientId;
        }

        return $relation;
    }

    public static function setCarerRelation($clientId, $carerId, $relationType) {

        $relation = self::getInstance($clientId, $carerId);

        if ($relationType == self::RELATION_NONE) {
            $relation->delete();
        } else {
            $relation->relation = $relationType;

            $relation->save();
        }

        $sql = "SELECT
          (SELECT COUNT(*) FROM tbl_client_carer_relation WHERE relation = 1) as count_favourite,
          (SELECT COUNT(*) FROM tbl_client_carer_relation WHERE relation = 2) as count_team,
          (SELECT COUNT(*) FROM tbl_client_carer_relation WHERE relation = 3) as count_not_wanted
             FROM tbl_client_carer_relation";

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        $overallScore = $result['count_favourite'] * BusinessRules::getCarerScoreFavouriteCoeff() +
                $result['count_team'] * BusinessRules::getCarerScoreTeamCoeff() +
                $result['count_not_wanted'] * BusinessRules::getCarerScoreNotWantedCoeff();

        
          //if score positive, set 4-5 stars otherwise 2-3
        if($overallScore > 0){
           $overallRating = Random::getRandomRatingHigh();
        }
        else{
           $overallRating = Random::getRandomRatingLow(); 
        }
        
        $sql2 = "UPDATE tbl_carer 
            SET overall_score = $overallScore 
            , overall_rating = $overallRating 
            WHERE id = $carerId";
        
        Yii::app()->db->createCommand($sql2)->execute();
    }

    public function getValue() {

        $val = $this->relation;
        return $val;
    }

    public function getLabel() {

        $value = $this->getValue();

        return self::$relationLabels[$value];
    }

}