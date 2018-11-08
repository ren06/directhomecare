<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveRecord
 *
 * @author Renaud
 */
abstract class ActiveRecord extends CActiveRecord {

    public static function loadModels($ids) {
        $models = static::model()->findAllByPk($ids);
        if ($models === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $models;
    }

    public static function loadModel($id) {
        $model = static::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'FLASH_ERROR_404_THE_REQUESTED_PAGE_DOES_NOT_EXIST'));
        return $model;
    }

    public static function loadModelAdmin($id) {
        $model = static::model()->resetScope()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('texts', 'ADMIN MODEL LOAD FAILED'));
        return $model;
    }

    public static function recordExists($id) {
        $model = static::model()->resetScope()->findByPk($id);
        return ($model != null);
    }

}

?>
