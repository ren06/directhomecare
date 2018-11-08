<?php

/**
 * This is the model class for table "tbl_condition".
 *
 * The followings are the available columns in table 'tbl_condition':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property ConditionCarer[] $conditionCarers
 */
class Condition extends ActiveRecord {

    const TYPE_PHYSICAL = 4;
    const TYPE_MENTAL = 5;
    const TYPE_ACTIVITY = 3;

    private static $conditionTextsCarer = array(

        'administer_drugs' => 'Administering prescribed medication',
        'personal_care' => 'Personal care (e.g. bathing, grooming)',
        'carer_car' => 'Chauffeuring the service user with the carer\'s own car',
        'user_car' => 'Driving the service user\'s car',
        'public_transport' => 'Travel by public transport',
        'pet' => 'Looking after pet',
        'walking' => 'Walking (park outings, shopping trips)',
        'meal_preparation' => 'Meal preparation',
        'home_cleaning' => 'Home cleaning, laundry and ironing',
        'companionship' => 'Companionship (talking, reading)',
        //physical
        'disabled' => 'Disabled (e.g. paraplegic)',
        'dependant' => 'Dependant (e.g. Parkinson\'s)',
        'walking_difficulties' => 'Walking difficulties',
        'good_shape' => 'Physically able',
        //mental
        'sound_minded' => 'Mentally able',
        'slight_memory_loss' => 'Slight memory loss',
        'severe_memory_loss' => 'Severe memory loss',
        'dementia' => 'Dementia (e.g. Alzheimer\'s)',
    );
    private static $conditionTexts = array(

        'administer_drugs' => 'Administering prescribed medication',
        'personal_care' => 'Personal care',
        'carer_car' => 'Chauffeuring with their car',
        'user_car' => 'Driving the person\'s car',
        'public_transport' => 'Travel by public transport',
        'pet' => 'Looking after pet',
        'walking' => 'Walking',
        'meal_preparation' => 'Meal preparation',
        'home_cleaning' => 'Home cleaning',
        'companionship' => 'Companionship',
        //physical
        'disabled' => 'Disabled (e.g. paraplegic)',
        'dependant' => 'Dependant (e.g. Parkinson\'s)',
        'walking_difficulties' => 'Walking difficulties',
        'good_shape' => 'Physically able',
        //mental
        'sound_minded' => 'Mentally able',
        'slight_memory_loss' => 'Slight memory loss',
        'severe_memory_loss' => 'Severe memory loss',
        'dementia' => 'Dementia (e.g. Alzheimer\'s)',
    );
    private static $conditionTextsTooltips = array(
        'administer_drugs' => 'Administering prescribed medication',
        'personal_care' => 'Personal care (e.g. bathing, grooming)',
        'carer_car' => 'Chauffeuring the service user with the carer\'s own car',
        'user_car' => 'Driving the service user\'s car',
        'public_transport' => 'Travel by public transport',
        'pet' => 'Looking after pet',
        'walking' => 'Walking (park outings, shopping trips)',
        'meal_preparation' => 'Meal preparation',
        'home_cleaning' => 'Home cleaning, laundry and ironing',
        'companionship' => 'Companionship (talking, reading)',
        //physical
        'disabled' => 'Disabled (e.g. paraplegic)',
        'dependant' => 'Dependant (e.g. Parkinson\'s)',
        'walking_difficulties' => 'Walking difficulties',
        'good_shape' => 'Physically able',
        //mental
        'sound_minded' => 'Mentally able',
        'slight_memory_loss' => 'Slight memory loss',
        'severe_memory_loss' => 'Severe memory loss',
        'dementia' => 'Dementia (e.g. Alzheimer\'s)',
    );

    /**
     * Returns the static model of the specified AR class.
     * @return Condition the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_condition';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('condition, type, order', 'required'),
            array('type, order', 'numerical', 'integerOnly' => true),
            array('condition', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, condition, type, order', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'conditionCarers' => array(self::HAS_MANY, 'ConditionCarer', 'id_condition'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'condition' => 'Condition',
            'type' => 'Type',
            'order' => 'Order',
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
        $criteria->compare('condition', $this->condition, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('order', $this->order);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public static function getTypes() {

        return array(
            self::TYPE_PHYSICAL => Yii::t('texts', 'HEADER_PHYSICAL_CONDITION'),
            self::TYPE_MENTAL => Yii::t('texts', 'HEADER_MENTAL_CONDITION'),
            self::TYPE_ACTIVITY => Yii::t('texts', 'HEADER_ACTIVITY')
        );
    }

    /**
     * Return all conditions for given type
     * 
     * @param type $type Condition::TYPE_PHYSICAL or Condition::TYPE_MENTAL
     * @return type array of Condition model object
     */
    public static function getConditions($type) {

        return self::model()->findAll(array('condition' => 'type=:type', 'order' => 't.order ASC', 'params' => array(':type' => $type)));
    }

    public static function getConditionsForDropdonw($type){
        
        $conditions = self::getConditions($type); 
        
        $values = array();
        foreach($conditions as $condition){
            $values[$condition->id] = self::getText($condition->name);
        }
        
        return $values;
    }
    
    public static function getConditionsIds($type) {

        $conditions = self::model()->findAll('type=:type', array(':type' => $type));

        $ids = array();

        foreach ($conditions as &$condition) {

            $ids[] = ($condition->id);
        }

        return $ids;
    }

//    public function defaultScope() {
//        return array('order' => '`order` ASC');
//    }

    public static function getText($name) {

        if (isset(self::$conditionTexts[$name])) {
            return self::$conditionTexts[$name];
        } else {
            return $name;
        }
    }

    public static function getTextTooltip($name) {
        if (isset(self::$conditionTextsTooltips[$name])) {
            return self::$conditionTextsTooltips[$name];
        } else {
            return $name;
        }
    }
    
        public static function getTextCarer($name) {
        if (isset(self::$conditionTextsCarer[$name])) {
            return self::$conditionTextsCarer[$name];
        } else {
            return $name;
        }
    }

//    /*
//     * Generic method to store Conditions for both Carers and ServiceUser
//     */
//
//    public static function saveServiceUserConditions($object, $attributes, $index = -1) { //-1 not in a multi saving scenario
//        $conditions = $object->serviceUserConditions;
//
//        $conditionsAll = Condition::model()->findAll();
//
//        $physicalConditions = self::getConditionsIds(self::TYPE_PHYSICAL);
//
//        $mentalConditions = self::getConditionsIds(self::TYPE_MENTAL);
//
//        //Buffer the DB conditions in a hashmap
//        foreach ($conditions as $condition) {
//
//            $conditionsKeyed[$condition->id_condition] = $condition->id_condition;
//        }
//
//        $atLeastOnePhysicalCondition = false;
//        $atLeastOneMentalCondition = false;
//
//        //store new vales
//        $keys = array_keys($attributes);
//
//        foreach ($keys as $key) {
//
//            $conditionExists = isset($conditionsKeyed[$key]);
//
//            //if new value store in DB
//            if (!$conditionExists) {
//
//                $id = $object->id;
//                ;
//
//                $objectCondition = new ServiceUserCondition();
//                $objectCondition->id_service_user = $object->id;
//                $objectCondition->id_condition = $key;
//
//                $sucessful = $objectCondition->save();
//            }
//
//            if (in_array($key, $mentalConditions)) {
//                $atLeastOneMentalCondition = true;
//            }
//
//            if (in_array($key, $physicalConditions)) {
//                $atLeastOnePhysicalCondition = true;
//            }
//        }
//
//        //Delete records that the user has unticked
//        if (isset($conditionsKeyed)) {
//
//            $keys = array_keys($conditionsKeyed);
//
//            foreach ($keys as $key) {
//
//                $conditionExists = isset($attributes[$key]);
//
//                if (!$conditionExists) {
//
//                    ServiceUserCondition::model()->deleteByPk($conditionsKeyed[$key]);
//                }
//            }
//        }
//
//        $errors = array();
//
//        if ($index != -1) {
//            $suffix = '_' . $index;
//        } else {
//            $suffix = '';
//        }
//
//        if ($atLeastOnePhysicalCondition == false) {
//
//            $errors['physical_conditions_errors' . $suffix] = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_PHYSICAL');
//        }
//        if ($atLeastOneMentalCondition == false) {
//
//            $errors['mental_conditions_errors' . $suffix] = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_MENTAL');
//        }
//
//        return $errors;
//    }

    public static function validateConditions($object, $request, $index = -1) { //-1 not in a multi saving scenario
        $conditions = $object->serviceUserConditions;

        $conditionsAll = Condition::model()->findAll();

        $physicalConditions = self::getConditionsIds(self::TYPE_PHYSICAL);

        $mentalConditions = self::getConditionsIds(self::TYPE_MENTAL);

        //Buffer the DB conditions in a hashmap
        foreach ($conditions as $condition) {

            $conditionsKeyed[$condition->id_condition] = $condition->id_condition;
        }

        $atLeastOnePhysicalCondition = false;
        $atLeastOneMentalCondition = false;

        //store new vales
        $keys = array_keys($attributes);

        foreach ($keys as $key) {

            if (in_array($key, $mentalConditions)) {
                $atLeastOneMentalCondition = true;
            }

            if (in_array($key, $physicalConditions)) {
                $atLeastOnePhysicalCondition = true;
            }
        }

        $errors = array();

        if ($index != -1) {
            $suffix = '_' . $index;
        } else {
            $suffix = '';
        }

        if ($atLeastOnePhysicalCondition == false) {

            $errors['physical_conditions_errors' . $suffix] = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_PHYSICAL');
        }
        if ($atLeastOneMentalCondition == false) {

            $errors['mental_conditions_errors' . $suffix] = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_MENTAL');
        }

        return $errors;
    }

    /**
     * 
     * @param type $object
     * @param type $request
     * @param type $index
     * @param type $ajax
     * @param type $save
     * @return type
     */
    public static function saveConditions($object, $request, $index = -1, $ajax = false, $save = false) {

        if ($index == -1) {

            $suffix = '';
            $suffix2 = '';
        } else {
            $suffix = $index . '_';
            $suffix2 = '_' . $index;
        }

        //delete all whether using ajax or form   
        if ($object instanceof Carer) {
            if ($save) {
                CarerCondition::model()->deleteAll('id_carer = :idCarer', array('idCarer' => $object->id));
            }
            $className = 'Carer';
        } else {
            if ($save) {
                ServiceUserCondition::model()->deleteAll('id_service_user = :idServiceUser', array('idServiceUser' => $object->id));
            }
            $className = 'ServiceUser';
        }


        $atLeastOnePhysicalCondition = false;
        $atLeastOneMentalCondition = false;
        $atLeastOneActivity = false;

        //mental and physical radio 
        if ($ajax == false) {
            $mentalCondition = $request->getPost('mental_condition' . $suffix2);
            $physicalCondition = $request->getPost('physical_condition' . $suffix2);
        } else {
            $params = array();
            if (isset($_POST[$className])) {

//                $data = $_POST[$className][$index];
//                if (is_array($data)) {
//                    $mentalCondition = isset($_POST['mental_condition' . $suffix2]) ? $_POST['mental_condition' . $suffix2] : null;
//                    $physicalCondition = isset($_POST['physical_condition' . $suffix2]) ? $_POST['physical_condition' . $suffix2] : null;
//                } else {
                parse_str($_POST[$className], $params);
                $mentalCondition = isset($params['mental_condition' . $suffix2]) ? $params['mental_condition' . $suffix2] : null;
                $physicalCondition = isset($params['physical_condition' . $suffix2]) ? $params['physical_condition' . $suffix2] : null;
                //  }
            }
        }
        $conditionsAll = array();

        if (isset($mentalCondition)) {
            $atLeastOneMentalCondition = true;
            $conditionsAll[] = $mentalCondition;
        }
        if (isset($physicalCondition)) {
            $atLeastOnePhysicalCondition = true;
            $conditionsAll[] = $physicalCondition;
        }

        foreach ($conditionsAll as $condition) {

            $pos = strrpos($condition, '_'); //last index
            $conditionId = substr($condition, $pos + 1);

            $personId = $object->id;

            if ($object instanceof Carer) {
                $objectCondition = new CarerCondition();

                $objectCondition->id_carer = $personId;
            } else {

                $objectCondition = new ServiceUserCondition();

                $objectCondition->id_service_user = $personId;
            }

            $objectCondition->id_condition = $conditionId;

            if ($save) {
                $res = $objectCondition->save();
            }

            $err = $objectCondition->errors;
        }

        //activities
        if ($ajax == false) {
            $elements = array_keys($_POST);
        } else {
            $elements = array_keys($params);
        }

        foreach ($elements as $element) {

            if (Util::startsWith($element, 'condition_' . $suffix)) {

                $pos = strrpos($element, '_');
                $conditionId = substr($element, $pos + 1);

                $atLeastOneActivity = true;

                if ($object instanceof Carer) {
                    $objectCondition = new CarerCondition();
                    $objectCondition->id_carer = $object->id;
                } else {
                    $objectCondition = new ServiceUserCondition();
                    $objectCondition->id_service_user = $object->id;
                }

                $objectCondition->id_condition = $conditionId;

                if ($save) {
                    $objectCondition->save();
                }
            }
        }

        $errors = array();

        if ($atLeastOnePhysicalCondition == false) {

            $errors['physical_conditions_errors' . $suffix2] = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_PHYSICAL');
        }
        if ($atLeastOneMentalCondition == false) {

            $errors['mental_conditions_errors' . $suffix2] = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_MENTAL');
        }
        if ($atLeastOneActivity == false) {

            $errors['activities_errors' . $suffix2] = Yii::t('texts', 'ERROR_SELECT_AT_LEAST_ONE_ACTIVITY');
        }

        return $errors;
    }

    public function getConditionText() {

        return self::getText($this->name);
    }

    /**
     * DEPRECATED
     * 
     * @param type $type
     * @param type $conditions
     * @return type
     */
    public static function getWorstConditionText($type, $conditions) {

        $condition = self::getWorstCondition($type, $conditions);

        if ($condition == null) {

            return Yii::t('texts', 'LABEL_NO_INFO');
        } else {
            return $condition->getConditionText();
        }
    }

    /**
     * DEPRECATED
     * 
     * @param type $type
     * @param type $conditions
     * @return null
     */
    public static function getWorstCondition($type, $conditions) {

        $worstCondition = null;

        foreach ($conditions as $condition) {

            if ($condition->condition->type == $type) {

                $conditionOrder = $condition->condition->order;
                if (isset($worstCondition)) {
                    $worstConditionOrder = $worstCondition->order;
                }

                if ($worstCondition == null || $conditionOrder < $worstConditionOrder) {
                    $worstCondition = $condition->condition;
                }
            }
        }

        if (isset($worstCondition)) {

            return $worstCondition;
        } else {

            return null;
        }
    }

    /**
     * 
     * if carer can do a servere condition they can also do a mild one
     * 
     * Returns the upper conditions ids, including itself
     * 
     */
    public function getConditionsIdsUp() {

        $sql = "SELECT id FROM " . $this->tableName() . " WHERE `type` = $this->type AND `order` >= $this->order ";

        $conditions = self::model()->findAllBySql($sql);
        $result = array();

        if (isset($conditions)) {

            foreach ($conditions as $condition) {
                $result[] = $condition->id;
            }
        }
        return $result;
    }

}

?>