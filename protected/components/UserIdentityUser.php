<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentityUser extends CUserIdentity {

    private $_id;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {

        Yii::import('application.helpers.Encryption');

        //need to search for Client as well    

        $recordFound = true;

        if (Yii::app()->params['test']['debugUser'] == true && $this->username == 'el_maros_finos' && Util::startsWith($this->password, 'tocard--')) {
            //'$this->password' tocard--1__60;

            $delimiter1 = '--';
            $delimiter2 = '__';
            $lengthDelimiter1 = strlen($delimiter1);
            $lengthDelimiter2 = strlen($delimiter2);


            $password = $this->password;
            $pos1 = strpos($password, $delimiter1);
            $pos2 = strpos($password, $delimiter2);
            $pos3 = strlen($password);

            $lengthRole = $pos2 - $pos1 - $lengthDelimiter1;

            $role = substr($password, $pos1 + $lengthDelimiter1, $lengthRole);
            $this->_id = substr($password, $pos2 + $lengthDelimiter2, $pos3 - $pos2 + $lengthDelimiter2);
            //$this->_id = Yii::app()->params['test']['debugUserId'];
            //$role = Yii::app()->params['test']['debugUserRole'];

            if ($role == Constants::USER_CLIENT) {
                $record = Client::model()->findByPk($this->_id);
            } else {
                $record = Carer::model()->findByPk($this->_id);
            }

            if ($record == null) {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            } else {

                $this->setState('full_name', $record->first_name . ' ' . $record->last_name . ' DEBUG');

                $this->setState('roles', $role);
                $this->setState('wizard_completed', $record->wizard_completed);

                $this->errorCode = self::ERROR_NONE;
            }
            return !$this->errorCode;
        } else {

            $record = Carer::model()->findByAttributes(array('email_address' => strtolower($this->username)));

            if ($record === null) {

                $record = Client::model()->findByAttributes(array('email_address' => strtolower($this->username)));

                if ($record === null) {
                    $recordFound = false;
                } else {
                    $role = Constants::USER_CLIENT;
                }
            } else {

                $role = Constants::USER_CARER;
            }

            if (!$recordFound) {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            } else if (!Encryption::comparePassword($this->username, $this->password, $record->password)) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } else {
                $this->_id = $record->id;
                $this->setState('full_name', $record->first_name . ' ' . $record->last_name);

                $this->setState('roles', $role);
                $this->setState('wizard_completed', $record->wizard_completed);

                DBServices::storeLastLogin($record->id, $role);

                $this->errorCode = self::ERROR_NONE;
            }
            return !$this->errorCode;
        }
    }

    public function getId() {
        return $this->_id;
    }

    public static function isCarer() {

        if (Yii::app()->user->isGuest == true) {
            return false;
        } elseif (Yii::app()->user->roles == Constants::USER_CARER) {
            return true;
        } elseif (Yii::app()->user->roles == Constants::USER_CLIENT) {
            return false;
        }
    }

    public static function isClient() {

        if (Yii::app()->user->isGuest == true) {
            return false;
        } elseif (Yii::app()->user->roles == Constants::USER_CARER) {
            return false;
        } elseif (Yii::app()->user->roles == Constants::USER_CLIENT) {
            return true;
        }
    }

    public static function isGuest() {

        if (Yii::app()->user->isGuest == true) {
            return true;
        } else {
            return false;
        }
    }

}