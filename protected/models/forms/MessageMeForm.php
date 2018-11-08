<?php

/**
* Used to send email to Carer from Client
*/
class MessageMeForm extends CFormModel{

	
	public $email;
	public $text;
        
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// email, text
			array('email, text', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'Your email',
                        'text'=>'Message',
		);
	}
}