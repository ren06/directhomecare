<?php

/**
 * Description of ValidFromTo
 *
 * @author Renaud
 */
class ValidFromTo extends CBehavior {

    public function setValidityDates() {

        $ar = CActiveRecord::model(get_class($this));

        if ($this instanceof CreditCard) {
            $attributes = array('id_client' => $this->id_client, 'valid_to' => self::INFINTY_DATE);
        } elseif ($this instanceof Address) {
            $attributes['type'] = $this->type;
        } elseif ($this instanceof Address) {
            $attributes['type'] = $this->type;
        }

        $result = $ar->findByAttributes($attributes);

        if (isset($result)) {

            $currentRecord = $result[0];
            $currentRecord->valid_to = Calendar::getDateTimeNow(self::FORMAT_DB);
            $currentRecord->save();
        }

        $this->valid_from = Calendar::getDateTimeNow(self::FORMAT_DB);
        $this->valid_to = self::INFINTY_DATE;
    }

}

?>
