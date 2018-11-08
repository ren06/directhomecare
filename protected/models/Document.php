<?php

/**
 * This is the model class for table "tbl_document".
 *
 * The followings are the available columns in table 'tbl_document':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $order
 *
 * The followings are the available model relations:
 * @property CarerDocument[] $carerDocuments
 */
class Document extends ActiveRecord {

    const TYPE_DIPLOMA = 1;
    const TYPE_IDENTIFICATION = 2;
    const TYPE_PHOTO = 3;
    const TYPE_CRIMINAL = 4;
    const TYPE_DRIVING_LICENCE = 5;
    const TYPE_TEXT = 6;

    public static $typeLabels = array(
        self::TYPE_DIPLOMA => 'Diploma',
        self::TYPE_IDENTIFICATION => 'ID/Passports',
        self::TYPE_PHOTO => 'Photo',
        self::TYPE_CRIMINAL => 'CRB check',
        self::TYPE_DRIVING_LICENCE => 'Driving Licence',
        self::TYPE_TEXT => 'Text',
    );
    private static $documentTexts = array(
        'photo' => 'Photo',
        'nvq_1' => 'Health and Social Care NVQ level 1',
        'nvq_2' => 'Health and Social Care NVQ level 2',
        'nvq_3' => 'Health and Social Care NVQ level 3',
        'qcf_1' => 'Health and Social Care QCF level 1',
        'qcf_2' => 'Health and Social Care QCF level 2',
        'qcf_3' => 'Health and Social Care QCF level 3',
        'moving_and_handling' => 'Moving and Handling of People',
        'personal_care_for_the_elderly' => 'Personal Care',
        'emergency_first_aid' => 'Emergency First Aid',
        'dementia' => 'Dementia Training',
        'stroke_awarness' => 'Stroke Awarness',
        'parkinson_s_disease' => 'Parkinson\'s Awareness',
        'food_hygiene' => 'Food Safety Awareness',
        'passport' => 'Passport',
        'identity_card' => 'Idendity card',
        'driving_licence' => 'Driving license',
        'crb_check' => 'Standard CRB check',
        'enhanced_crb_check' => 'Enhanced CRB check - Adults List',
        'id' => 'ID Card or Passport',
        'text_motivation' => 'Motivation text',
        'text_personality' => 'Personality text',
        'activities_coordinator' => 'Activities Coordinator',
        'autism_awarness' => 'Autism Awareness',
        'care_planning' => 'Care Planning',
        'challenging_behaviour' => 'Challenging Behaviour',
        'child_protection' => 'Child Protection',
        'communication_record_keeping' => 'Communication and Record Keeping',
        'confidentiality_awarness' => 'Confidentiality Awareness',
        'conflict_management' => 'Conflict Management',
        'coshh' => 'COSHH',
        'diabetes_awarness' => 'Diabetes Awareness',
        'dols' => 'DOLS',
        'eating_disorders' => 'Eating Disorders',
        'epilepsy_awarness' => 'Epilepsy Awareness',
        'equality_diversity' => 'Equality and Diversity',
        'fire_safety_awarness' => 'Fire Safety Awareness',
        'health_safety_awarness' => 'Health and Safety Awareness',
        'infection_control_awarness' => 'Infection Control Awareness',
        'lone_working_awarness' => 'Lone Working Awareness',
        'loss_bereavement' => 'Loss and Bereavement',
        'medication_awarness' => 'Medication Awareness',
        'mental_capacity_act_dols' => 'Mental Capacity Act and DOLS',
        'mrsac_diff_awarness' => 'MRSA/C. Diff Awareness',
        'nutrition_awarness' => 'Nutrition Awareness',
        'observation_skills_carers' => 'Observation Skills for Carers',
        'ocd_awarness' => 'OCD Awareness',
        'palliative_care_awarness' => 'Palliative Care Awareness',
        'person_centred_care' => 'Person Centred Care',
        'personality_disorders' => 'Personality Disorders',
        'physical_intervention' => 'Physical Intervention',
        'pressure_sore_awarness' => 'Pressure Sore Awareness',
        'risk_assessment_awarness' => 'Risk Assessment Awareness',
        'safeguarding_children' => 'Safeguarding of Children',
        'schizophrenia_awarness' => 'Schizophrenia Awareness',
        'self_harm' => 'Self Harm',
        'sova_awarness' => 'SOVA Awareness',
        'stress_awarness' => 'Stress Awareness',
        'ageing_process' => 'The Ageing Process',
        'wound_assessment' => 'Wound Assessment',
        'standard_dbs_check' => 'Standard DBS check',
        'enhanced_dbs_check_adult' => 'Enhanced DBS check - Adults List',
        'enhanced_dbs_check_children' => 'Enhanced DBS check - Children List',
        'enhanced_dbs_check_adult_child' => 'Enhanced DBS check - Adults and Children Lists',
        'enhanced_crb_check_children' => 'Enhanced CRB check - Children List',
        'enhanced_crb_check_adult_child' => 'Enhanced CRB check - Adults and Children Lists',
        'disclosure_scotland' => 'Disclosure Scotland',
    );

    /**
     * Returns the static model of the specified AR class.
     * @return Document the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_document';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, type, order', 'required'),
            array('type, order', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 30),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, type, order', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'carerDocuments' => array(self::HAS_MANY, 'CarerDocument', 'id_document'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('order', $this->order);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 
     * Return name from id
     */
    public static function getId($name) {

        $sql = "SELECT id FROM tbl_document WHERE tbl_document.name = '$name'";
        $row = Yii::app()->db->createCommand($sql)->queryRow();

        if ($row == null) {
            return null;
        } else {
            return $row['id'];
        }
    }

    /**
     * Retrieves all documents ids for a given type (e.g. self::TYPE_DIPLOMA)
     * @return array of ids
     */
    public static function getDocumentIds($type) {

        $documents = self::model()->findAll('type=:type', array(':type' => $type));
        $ids = array();

        foreach ($documents as $document) {

            $ids[] = ($document->id);
        }

        return $ids;
    }

    /**
     * Retrieves all documents ids for a given type (e.g. self::TYPE_DIPLOMA)
     * @return array of ids
     */
    public static function getDocuments($type) {

        $documents = self::model()->findAll('type=:type', array(':type' => $type));

        return $documents;
    }

    /**
     * Generates a list of the documents for a drop down
     * @return list data
     */
    public static function getDocumentList($type) {

        $documents = self::model()->findAll(array('condition' => 'type=:type', 'order' => 't.order ASC', 'params' => array(':type' => $type)));

        $documents = CHtml::listData($documents, 'id', 'name');

        //apply labels
        foreach ($documents as $key => $value) {
            $documents[$key] = self::getLabel($value);
        }

        return $documents;
    }

    /**
     * Retrieves the type of a given document id
     * @return document id
     */
    public static function getType($id) {

        $document = self::model()->findByPk($id);

        return $document->type;
    }

    public function defaultScope() {
        return array('order' => '`order` ASC');
    }

    public function getTypeLabel() {
        return self::$typeLabels[$this->type];
    }

    public function getNameLabel() {
        return self::$documentTexts[$this->name];
    }

    /**
     * Retrieves the label name of the document technical name
     * @return document id
     */
    public static function getLabel($name) {

        $values = self::$documentTexts;

        return $values[$name];
    }


}