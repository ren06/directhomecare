<?php

class UserLoginModule extends CWidget {

    public function run() {

        $model = new UserLoginForm();

       // collect user input data
        if (isset($_POST['UserLoginForm'])) {

            $model->attributes = $_POST['UserLoginForm'];

            if ($model->validate()) {

                $model->login();
                
                $url = Yii::app()->user->returnUrl;
                
                Yii::app()->controller->redirect($url);
            }
        }

        //display the login form
        $this->render('userLoginModule', array('model' => $model));
    }
}

?>