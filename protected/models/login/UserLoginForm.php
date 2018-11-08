<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserLoginForm extends CFormModel {

    public $email_address;
    public $password;
    public $rememberMe;
    private $_identity; //stores UserIdentity object

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */

    public function rules() {
        return array(
            // username and password are required
            array('email_address, password', 'required'),
            array('email_address', 'filter', 'filter' => 'trim'),
            //array('email_address', 'email'),            
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'rememberMe' => Yii::t('texts', 'LABEL_REMEMBER_ME'),
            'email_address' => 'Email',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentityUser($this->email_address, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect username or password.');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentityUser($this->email_address, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentityUser::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            
            //store last login here maybe ??? or in authenticate()
            
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }
        else
            return false;
    }

}
