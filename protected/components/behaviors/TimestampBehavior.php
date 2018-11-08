<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimeStampBehavior
 *
 * @author Renaud
 */
class TimestampBehavior extends CTimestampBehavior{
   
    protected function getTimestampByAttribute($attribute) {
		if ($this->timestampExpression instanceof CDbExpression)
			return $this->timestampExpression;
		elseif ($this->timestampExpression !== null)
			//return @eval('return '.$this->timestampExpression.';');
                        return $this->timestampExpression;

		$columnType = $this->getOwner()->getTableSchema()->getColumn($attribute)->dbType;
		return $this->getTimestampByColumnType($columnType);
	}
}

?>
