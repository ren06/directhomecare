<?php

class Util {

    public static function contains($haystack, $needle) {
        return (strpos($haystack, $needle) != false);
    }

    public static function startsWith($haystack, $needle) {

        // Recommended version, using strpos
        return strpos($haystack, $needle) === 0;
    }

    public static function lastCharacters($haystack, $numberCharacters) {

        $length = strlen($haystack);
        return substr($haystack, $length - $numberCharacters, $numberCharacters);
    }

    public static function lastCharactersAfter($haystack, $needle) {

        $pos = strpos($haystack, $needle);
        return substr($haystack, strlen($needle) + $pos, strlen($haystack) - $pos - 1);
    }

    public static function removeEndString($string, $endString) {

        return substr($string, 0, strlen($string) - strlen($endString));
    }

    public static function firstCharacters($haystack, $numberCharacters) {

        return substr($haystack, 0, $numberCharacters);
    }

    public static function array_insert(&$array, $element, $position = null) {

        if (count($array) == 0) {
            $array[] = $element;
        } elseif (is_numeric($position) && $position < 0) {
            if ((count($array) + position) < 0) {
                $array = array_insert($array, $element, 0);
            } else {
                $array[count($array) + $position] = $element;
            }
        } elseif (is_numeric($position) && isset($array[$position])) {
            $part1 = array_slice($array, 0, $position, true);
            $part2 = array_slice($array, $position, null, true);
            $array = array_merge($part1, array($position => $element), $part2);
            foreach ($array as $key => $item) {
                if (is_null($item)) {
                    unset($array[$key]);
                }
            }
        } elseif (is_null($position)) {
            $array[] = $element;
        } elseif (!isset($array[$position])) {
            $array[$position] = $element;
        }
        $array = array_merge($array);
        return $array;
    }

    public static function array_put_to_position(&$array, $object, $position, $name = null) {
        $count = 0;
        $return = array();
        foreach ($array as $k => $v) {
            // insert new object
            if ($count == $position) {
                if (!$name)
                    $name = $count;
                $return[$name] = $object;
                $inserted = true;
            }
            // insert old object
            $return[$k] = $v;
            $count++;
        }
        if (!$name)
            $name = $count;
        if (!$inserted)
            $return[$name];
        $array = $return;
        return $array;
    }
    
    /*     
     * Post code must be correct
     * 
     * @param type $postCode like W105UB
     * @return type $postCode like W10 5UB
     */
    public static function formatPostCode($postCode) {

        $postCode = strip_tags(trim(strtoupper($postCode)));

        $test = $postCode;
        $character = substr($test, -4, 1);
        
        if ($character == " ") {
            $postCode = $postCode;
        } else {
            function stringrpl($x, $r, $str) {
                $out = "";
                $temp = substr($str, $x);
                $out = substr_replace($str, "$r", $x);
                $out .= $temp;
                return $out;
            }

            $test = stringrpl(-3, " ", $test);
            $postCode = $test;
        }

        return $postCode;
    }

    /**
     * 
     * Check if post code is valid syntaxically
     * 
     * @param type $postcode
     * @return boolean true if valid, false if not
     */
    public static function isPostCodeValid($postcode) {

        if (!isset($postcode)) {
            return false;
        }
        //$pattern = "/^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/";

        $pattern = "/^(GIR ?0AA|[A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]([0-9ABEHMNPRV-Y])?)|[0-9][A-HJKPS-UW]) ?[0-9][ABD-HJLNP-UW-Z]{2})$/";

        return(preg_match($pattern, $postcode));
    }

    public static function isMobilePhoneValid($phonNumber) {

        $pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/";

        return(preg_match($pattern, $phonNumber));
    }

}

?>