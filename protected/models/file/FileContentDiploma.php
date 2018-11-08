<?php

/**
 * Description of FileContentPhoto
 *
 * @author Renaud
 */
class FileContentDiploma extends FileContent {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getDbConnection() {
        return Yii::app()->db_diploma;
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
