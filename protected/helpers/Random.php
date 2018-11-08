<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of random
 *
 * @author I031360
 */
class Random {

    public static function getRandomPassword($number) {

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $size = strlen($characters) - 1;
        $result = '';
        for ($i = 0; $i < $number; $i++)
            $result .= $characters[mt_rand(0, $size)];

        return $result;
    }

    //put your code here
    public static function getRandomLetters($number = 1) {

        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $size = strlen($characters) - 1;
        $result = '';
        for ($i = 0; $i < $number; $i++)
            $result .= $characters[mt_rand(0, $size)];

        return $result;
    }

    public static function getRandomBoolean() {

        return mt_rand(0, 1);
    }

    public static function getRamdomMinutes() {

        $minutes = array('00', '15', '30', '45');
        return $minutes[array_rand($minutes)];
    }

    public static function getRandomGender() {
        $genders = array(Constants::GENDER_FEMALE, Constants::GENDER_MALE);
        return $genders[array_rand($genders)];
    }

    public static function getRandomNationality() {
        $nationalities = array_keys(Nationalities::getNationalities());

        return $nationalities[array_rand($nationalities)];
    }

    public static function getRandomNumber($number = 1) {

        $characters = '0123456789';
        $size = strlen($characters) - 1;
        $result = '';
        for ($i = 0; $i < $number; $i++)
            $result .= $characters[mt_rand(0, $size)];

        return $result;
    }

    public static function getRandomCarerFirstNameFemale() {

        $firstName = array('Elena', 'Aneta', 'Diana', 'Vera', 'Petra', 'Violeta', 'Viola', 'Yana', 'Ina', 'Ewa', 'Anna', 'Agnieszka');

        return $firstName[array_rand($firstName)];
    }

    public static function getRandomCarerFirstNameMale() {

        $firstName = array('Hassan', 'Piotr', 'Mustapha', 'Kevin', 'Luke', 'Ibrahim', 'John', 'James', 'Mo', 'Karim', 'David', 'James');

        return $firstName[array_rand($firstName)];
    }

    public static function getRandomCarerLastName() {


        $lastName = array('Angelova', 'Filipova', 'Ivanova', 'Koleva', 'Maneva', 'Marinova', 'Baranowska', 'Lisowska', 'Rusek', 'Szymanska', 'Wadowska');

        return $lastName[array_rand($lastName)];
    }

    public static function getRandomClientFirstName() {

        $firstName = array('James', 'Robert', 'Tim', 'Sebastian', 'Phil', 'Mark', 'Stuart', 'Graham', 'Luke', 'Justin');
        return $firstName[array_rand($firstName)];
    }

    public static function getRandomClientLastName() {

        $lastName = array('Brown', 'Burke', 'Bale', 'Brine', 'Bonner', 'Bray', 'Bradshaw', 'Bowley ', 'Bowers', 'Boyes', 'Bright', 'Beauchamp', 'Bridge', 'Brown', 'Ballings', 'Baileys');
        return $lastName[array_rand($lastName)];
    }

    public static function getRandomServiceUserFirstName($gender) {

        if ($gender == Constants::GENDER_FEMALE) {

            $firstName = array('Bertha', 'Constance', 'Edna', 'Ethel', 'Eunice', 'Gertrude', 'Gladys', 'Mildred', 'Myrtle', 'Ruth', 'Shirley', 'Wilhelmina', 'Wilma');
        } else {
            $firstName = array('Cecil', 'Harold', 'Ira', 'Melvin', 'Mortimer', 'Sheldon', 'Virgil', 'Wilbur');
        }
        return $firstName[array_rand($firstName)];
    }

    public static function getRandomServiceUserLastName() {

        $lastName = array('Terry', 'Smith', 'Cartney', 'Jackson', 'Ferguson', 'Bonner', 'Iron', 'Young', 'Black', 'Green', 'Anderson', 'Carston', 'Hart', 'Sainsbury', 'Collins');

        return $lastName[array_rand($lastName)];
    }

    public static function getRandomEmail($firstName, $lastName) {

        $providers = array('hotmail.com', 'yahoo.com', 'gmail.com', 'gmx.com', 'aol.com');

        $result = $firstName . '.' . $lastName . self::getRandomNumber(2) . '@' . $providers[array_rand($providers)];

        return strtolower($result);
    }

    public static function getRandomMobilePhone() {

        return '07' . self::getRandomNumber(9);
    }

    public static function getRandomDateBirth($minYear, $maxYear) {

        $year = rand($minYear, $maxYear);
        $month = rand(1, 9);
        $day = rand(10, 28);
        return $year . '-0' . $month . '-' . $day;
    }

    public static function getRandomUKPostCode() {

        $arr = array('SW6 4EA', 'W1G 9RX', 'N6 5AU', 'EC4M 7DQ', 'TW16 5NE', 'SW1E 5BA', 'WC2H 7AW', 'W1J 7PP', 'RG27 9JS', 'GU21 4YH',
            'W1B 5AG', 'M60 4EP', 'EC2R 8AH', 'N7 0NJ', 'W10 5AB', 'N81 1WA', 'N20 8EH', 'N3 2PX', 'W1U 3DN', 'W12 8BH', 'W12 8BH', 'SE7 7TB', ' E1 2RB', 'E15 4HP');
             
        $size = count($arr);

        $rand = self::getRandomNumber(1);

        while ($rand > $size - 1) {
            $rand = self::getRandomNumber(1);
        }

        return $arr[$rand];
    }

    public static function getRandomAddressLine1() {


        $line1 = array('Flat 1', 'Flat 2', 'Flat 3', 'Flat 4', 'Flat 5', 'Flat 6', 'Flat 7', 'Flat 8', 'Flat 9', 'Flat 10');

        return $line1[self::getRandomNumber(1)];
    }

    public static function getRandomAddressLine2() {

        $line2 = array('5 Regent street', '12 Oxford Street', '5 Salisbury road', '18 Cambridge Gardens', '3 Bardolph road',
            '12 Portobello road', '23 Westbourne road', '3 Talbot road', '21 Elgin Crescent', '7 Golborne road');

        return $line2[self::getRandomNumber(1)];
    }

    public static function getRamdomDate($start_date, $end_date) {

        // Convert to timetamps
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return date('Y-m-d H:i:s', $val);
    }

    public static function getRandomPhysicalCondition() {
        $conditions = Condition::getConditionsIds(Condition::TYPE_PHYSICAL);
        return $conditions[array_rand($conditions)];
    }

    public static function getRandomMentalCondition() {
        $conditions = Condition::getConditionsIds(Condition::TYPE_MENTAL);
        return $conditions[array_rand($conditions)];
    }

    public static function getRandomRatingHigh() {

        $minutes = array(4, 5);
        return $minutes[array_rand($minutes)];
    }

    public static function getRandomRatingLow() {

        $minutes = array(2, 3);
        return $minutes[array_rand($minutes)];
    }

}

?>