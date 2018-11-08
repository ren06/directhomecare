<?php

class Encryption {

    const KEY = 'S(RJS&D)~SD';
    const MAX = 100000;

    // const 'SECURE_KEY' = 'Your Secure Key'


    static public function encrypt_very_light($str) {

        return str_rot13($str);
    }

    static public function decrypt_very_light($str) {

        return str_rot13($str);
    }

    /**
     * Encrypt a value
     */
    static public function encrypt_light($str) {

        $str = gzdeflate($str, 9);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::KEY, $str, MCRYPT_MODE_ECB, $iv);
    }

    /**
     * Decrypt a value
     */
    static public function decrypt_light($str) {

        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return gzinflate(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::KEY, $str, MCRYPT_MODE_ECB, $iv));
    }

    public static function encrypt($value) {

        $key = self::KEY;

        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $value, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encrypted;
    }

    public static function decrypt($hashedValue) {

        $hashedValue = trim($hashedValue);
        $key = self::KEY;
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($hashedValue), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

        return $decrypted;
    }

    public static function encryptPassword($userName, $plainPassword) {

        $username = $userName;
        $password = $plainPassword;

        // Create a 256 bit (64 characters) long random salt
        // Let's add 'something random' and the username
        // to the salt as well for added security
        $salt = hash('sha256', uniqid(mt_rand(), true) . 'something random' . strtolower($username));

        // Prefix the password with the salt
        $hash = $salt . $password;

        // Hash the salted password a bunch of times
        for ($i = 0; $i < self::MAX; $i++) {
            $hash = hash('sha256', $hash);
        }

        // Prefix the hash with the salt so we can find it back later
        $hash = $salt . $hash;
        return $hash;
    }

    public static function comparePassword($userName, $enteredPassword, $hashedPassword) {

        $username = $userName;
        $password = $enteredPassword;


        // The first 64 characters of the hash is the salt
        $salt = substr($hashedPassword, 0, 64);

        $hash = $salt . $password;

        // Hash the password as we did before
        for ($i = 0; $i < self::MAX; $i++) {
            $hash = hash('sha256', $hash);
        }

        $hash = $salt . $hash;

        return($hash == $hashedPassword);
    }

    public static function encryptURLParam($plainURL, $urlencode = true) {

        $encoded = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::KEY, $plainURL, MCRYPT_MODE_ECB, mcrypt_create_iv(
                        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND));

        $result = self::mybase64_encode($encoded);

        if ($urlencode) {
            $result = rawurlencode($result);
        }

        return $result;
    }

    public static function decryptURLParam($enryptedURL, $urldecode = false) {

        if ($urldecode) {
            $enryptedURL = rawurldecode($enryptedURL);
        }

        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::KEY, self::mybase64_decode($enryptedURL), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(
                                        MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    public static function test($message) {

        $encryptedmessage = self::encryptURLParam($message);

        return self::decryptURLParam($encryptedmessage);
    }

    private static function mybase64_encode($s) {
        return str_replace(array('+', '/'), array(',', '-'), base64_encode($s));
    }

    private static function mybase64_decode($s) {
        return base64_decode(str_replace(array(',', '-'), array('+', '/'), $s));
    }

}

?>