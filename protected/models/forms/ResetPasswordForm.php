<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * reset password data
 */
class ResetPasswordForm extends CFormModel {

    public $oldPassword;
    public $newPassword;
    public $newPasswordRepeat;

    const RESET_PASSWORD_DETAILS = 1;
    const RESET_PASSWORD_FORGOTTEN = 2;

    public function __construct($scenario=self::RESET_PASSWORD_DETAILS) {
        parent::__construct($scenario);
    }

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('newPassword, newPasswordRepeat', 'required'),
            array('oldPassword', 'required', 'on' => self::RESET_PASSWORD_DETAILS),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {

        $scenario = $this->scenario;

        if ($scenario == self::RESET_PASSWORD_DETAILS) {
            return array(                
                'newPassword' => 'New password',
                'newPasswordRepeat' => 'Repeat password',
            );
        } else {
            return array(
                'oldPassword' => 'Temp password',
                'newPassword' => 'New password',
                'newPasswordRepeat' => 'Repeat password',
            );
        }
    }

}