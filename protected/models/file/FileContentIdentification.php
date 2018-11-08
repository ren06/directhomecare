<?php

/**
 * Description of FileContentPhoto
 *
 * @author Renaud
 */
class FileContentIdentification extends FileContent {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getDbConnection() {
        return Yii::app()->db_identification;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {

        $tableName = parent::tableName();
        
        preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
        return $matches[1] . '.' . $tableName;
    }

}

?>
