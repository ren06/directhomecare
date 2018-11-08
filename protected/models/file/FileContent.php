<?php

/**
 * This is the model class for table "tbl_file_content".
 *
 * The followings are the available columns in table 'tbl_file_content':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $size
 * @property string $path
 * @property integer $width
 * @property integer $height
 * @property integer $extension
 * @property integer $content 
 */
class FileContent extends ActiveRecord {

    private static $documentTypeFolders = array(
        //diploma
        Document::TYPE_PHOTO => 'photo',
        Document::TYPE_DIPLOMA => 'diplomas',
        Document::TYPE_IDENTIFICATION => 'identification',
        Document::TYPE_CRIMINAL => 'criminals',
        Document::TYPE_DRIVING_LICENCE => 'driving_licence',
    );

    /**
     * Returns the static model of the specified AR class.
     * @return FileContent the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_file_content';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('name, type, size, content', 'required'),
            //array('size', 'numerical', 'integerOnly'=>true),
            //array('name', 'length', 'max'=>70),
            //array('type', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, type, size, content, width, height', 'safe'),
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
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'size' => 'Size',
            'content' => 'Content',
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
        $criteria->compare('type', $this->type, true);
        $criteria->compare('size', $this->size);
        $criteria->compare('content', $this->content, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    public static function getDocumentTypeFolder($documentType) {

        return self::$documentTypeFolders[$documentType];
    }

}