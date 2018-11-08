<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Price
 *
 * @author I031360
 */
class Price {

    //put your code here

    public $amount;
    public $currency;
    public $text;

    public function __construct($amount = 0, $currency = 'GBP') {

        $amountRounded = round(floatval($amount), 2);

        $this->amount = $amountRounded;
        $this->currency = $currency;

        if (isset($currency)) {
            $countryCurrency = Yii::app()->params['currencies'][$currency];
            $amountFormatted = number_format($this->amount, 2);
            $symbol = '&#163;'; //$countryCurrency['symbol'];
            $leftRight = $countryCurrency['leftRight'];

            if ($leftRight == 'left') {
                $this->text = $symbol . $amountFormatted;
            } else {
                $this->text = $amountFormatted . $symbol;
            }
        } else {

            $this->text = $amountRounded;
        }
    }

    public function add($price) {

        if ($this->currency == $price->currency) {

            return new Price($this->amount + $price->amount, $this->currency);
        }
    }

    public function substract($price) {

        if ($this->currency == $price->currency) {

            return new Price($this->amount - $price->amount, $this->currency);
        }
    }

    public function multiply($amount) {

        return new Price($this->amount * $amount, $this->currency);
    }

    public function divide($amount) {

        return new Price($this->amount / $amount, $this->currency);
    }

    public function isLowerOrEqual($price) {

        if ($this->currency == $price->currency) {

            return ($this->amount <= $price->amount);
        }
    }

    public static function formatPrice($toto) {

        return $toto . '.00';
    }

//    public static function total($prices) {
//
//        if (sizeof($prices) > 0) {
//
//            $priceTotal = $prices[0];
//
//            for ($i = 1; $i < sizeof($prices); $i++) {
//
//                $priceTotal->add($prices[$i]);
//            }
//
//            return $priceTotal;
//        } else {
//            return null;
//        }
//    }
}

?>
