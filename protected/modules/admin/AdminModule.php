<?php

class AdminModule extends CWebModule {

    public $defaultController = 'admin';
    public $mySettings; //custom properties you can config in config/main.php
    public $defaultLayout = '/layouts/column1';

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
            'admin.controllers.*',
            'admin.views.carer.*',
        ));

        Yii::app()->setComponents(array(
            'user' => array(
                'class' => 'WebUserAdmin',
                // enable cookie-based authentication
                'allowAutoLogin' => false,
                //'returnUrl' => array('admin/admin/index'),
                'loginUrl' => array('0' => 'admin/admin/adminLogin')
            ),
            'errorHandler' => array(
                // use 'site/error' action to display errors
                'errorAction' => 'admin/admin/error',
            ),
            'authManager' => array(
                'class' => 'CDbAuthManager',
                'connectionID' => 'db',
            ),
        ));
    }

    public function behaviors() {
        return array(
            'onBeginRequest' => array(
                'class' => 'application.components.behaviors.RequireLogin',
            )
        );
    }

    public function beforeControllerAction($controller, $action) {

        $controller->layout = $this->defaultLayout;

        //for any action of this module check if user is authenticated
        //check if different than login page
        $loginUrl = Yii::app()->user->loginUrl[0];

        $actionUrl = 'admin/' . $action->controller->id . '/' . $action->id;

        if ($loginUrl != $actionUrl) {

            if (Yii::app()->user->isGuest || Yii::app()->user->roles != 'admin') {
                Yii::app()->user->loginRequired();
            }
        }

        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }
        else
            return false;
    }

}
