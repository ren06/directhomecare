<?php
class CarerValidator extends CValidator {
    protected function validateAttribute($object, $attribute) {
        $value = $object->$attribute;
        $record = Carer::model()->findByAttributes(array('email_address' => $value));
        $recordFound = false;
        if ($record === null) {
            $record = Client::model()->findByAttributes(array('email_address' => $value));
            if (!$record === null) {
                $recordFound = true;
            }
        }
        if ($recordFound)
            $object->addError($object, $attribute, Yii::t('texts', 'ERROR_THIS_EMAIL_ADDRESS_IS_REGISTERED_PLEASE_LOGIN'));
    }
}
?>