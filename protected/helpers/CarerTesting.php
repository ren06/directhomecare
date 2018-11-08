<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CarerTesting
 *
 * @author I031360
 */
class CarerTesting {

    //put your code here

    public static function createCarer() {

        $dbTransaction = Yii::app()->db->beginTransaction();

        try {

            $carer = new Carer(Carer::SCENARIO_NEW_CARER);

            $gender = Random::getRandomGender();

            if ($gender == Constants::GENDER_FEMALE) {
                $firstName = Random::getRandomCarerFirstNameFemale();
            } else {
                $firstName = Random::getRandomCarerFirstNameMale();
            }
            $lastName = Random::getRandomCarerLastName();

            $emailAddress = Random::getRandomEmail($firstName, $lastName);
            $password = 'test';

            $carer->email_address = $emailAddress;
            $carer->first_name = $firstName;
            $carer->last_name = $lastName;
            $carer->password = $password; //plain to pass validation
            $carer->repeat_password = $password;
            $carer->active = true;

            $carer->setScenario(Carer::SCENARIO_CREATE_CARER);
            $carer->save();

            //update with hashed password
            $carer->password = Encryption::encryptPassword($emailAddress, $password, false);
            $carer->date_birth = Random::getRandomDateBirth(1970, 1990);
            $carer->mobile_phone = Random::getRandomMobilePhone();
            $carer->gender = $gender;

            $carer->terms_conditions = true;
            $carer->legally_work = true;
            $carer->nationality = Random::getRandomNationality();
            $carer->wizard_completed = Wizard::CARER_LAST_STEP_INDEX;

            $address = new Address();
            $address->address_line_1 = 'Flat ' . Random::getRandomNumber();
            $address->address_line_2 = 'Cambridge Gardens';
            $address->city = 'London';
            $address->post_code = Random::getRandomUKPostCode();            
            $address->data_type = Address::TYPE_CARER_ADDRESS;            
            $address->save();

            $carer->id_address = $address->id;
            $carer->save();
            
            CarerLanguage::addLanguage($carer);
            

            $conditionsAll = Condition::getConditions(Condition::TYPE_ACTIVITY);

            foreach ($conditionsAll as $condition) {

                if (Random::getRandomBoolean()) {
                    try {
                        $objectCondition = new CarerCondition();
                        $objectCondition->id_carer = $carer->id;
                        $objectCondition->id_condition = $condition->id;

                        $objectCondition->save();
                    } catch (CException $e) {
                        
                    }
                }
            }

            try {
                $objectCondition = new CarerCondition();
                $objectCondition->id_carer = $carer->id;
                $cond = Random::getRandomMentalCondition(); //dementia
                $objectCondition->id_condition = $cond;

                $objectCondition->save();

                $objectCondition = new CarerCondition();
                $objectCondition->id_carer = $carer->id;
                $cond = Random::getRandomPhysicalCondition(); //disabled
                $objectCondition->id_condition = $cond;

                $objectCondition->save();
            } catch (CException $e) {
                
            }
            //add 
//            $sql = 'DELETE FROM tbl_age_group WHERE id_carer = ' . $carer->id;
//            Yii::app()->db->createCommand($sql)->execute();

            try {
                $atLeastOne = false;
                if (Random::getRandomBoolean()) {
                    $ageGroup = new AgeGroup();
                    $ageGroup->id_carer = $carer->id;
                    $ageGroup->age_group = '1';
                    $result = $ageGroup->save();
                    $atLeastOne = true;
                }
                if (Random::getRandomBoolean()) {

                    $ageGroup = new AgeGroup();
                    $ageGroup->id_carer = $carer->id;
                    $ageGroup->age_group = '2';
                    $result = $ageGroup->save();
                    $atLeastOne = true;
                }

                if (Random::getRandomBoolean()) {

                    $ageGroup = new AgeGroup();
                    $ageGroup->id_carer = $carer->id;
                    $ageGroup->age_group = '3';
                    $result = $ageGroup->save();
                    $atLeastOne = true;
                }
                if (!$atLeastOne || Random::getRandomBoolean()) {

                    $ageGroup = new AgeGroup();
                    $ageGroup->id_carer = $carer->id;
                    $ageGroup->age_group = '0';
                    $result = $ageGroup->save();
                }
            } catch (CException $e) {
                
            }

            $carer->hourly_work = rand(Constants::DB_FALSE, Constants::DB_TRUE);
            $carer->live_in = rand(Constants::DB_FALSE, Constants::DB_TRUE);

            $carer->live_in_work_radius = rand(1, 50);
            $carer->hourly_work_radius = rand(1, 50);

            $boolean1 = Random::getRandomBoolean();

            if ($boolean1 == false) {
                $boolean2 = true;
            } else {
                $boolean2 = Random::getRandomBoolean();
            }

            $carer->work_with_male = $boolean1;
            $carer->work_with_female = $boolean2;
            $carer->save();

            $dbTransaction->commit();

            return $carer;
        } catch (CException $e) {

            $dbTransaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

}

?>
