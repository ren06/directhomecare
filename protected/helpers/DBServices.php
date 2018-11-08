<?php

/*
 *  Multi purpose SQL stuff, when not related to an AR Model in general.
 */

class DBServices {

    /**
     * Used when registering
     * 
     * @param type $emailAddress
     * @return boolean found
     */
    public static function emailAddressExists($emailAddress) {

        $record = Carer::model()->findByAttributes(array('email_address' => $emailAddress));

        if ($record === null) {

            $record = Client::model()->findByAttributes(array('email_address' => $emailAddress));

            if ($record === null) {
                $recordFound = false;
            } else {
                $recordFound = true;
            }
        } else {
            $recordFound = true;
        }

        return $recordFound;
    }

    /**
     * 
     * Used for changing email
     * 
     * @param type $emailAddress
     * @return type Client or Carer
     */
    public static function getEmailAlreadyExist($emailAddress) {

        $record = Carer::model()->findByAttributes(array('email_address' => $emailAddress));

        if ($record === null) {

            $record = Client::model()->findByAttributes(array('email_address' => $emailAddress));

            return $record;
        } else {
            return $record;
        }
    }

    public static function getCarer($emailAddress) {

        $record = Carer::model()->findByAttributes(array('email_address' => $emailAddress));
        return $record;
    }

    /*
     * Return a Carer or Client mode object if found, null otherwise
     */

    public static function getPerson($emailAddress) {

        $record = Client::model()->findByAttributes(array('email_address' => $emailAddress));

        if ($record === null) {

            $record = Carer::model()->findByAttributes(array('email_address' => $emailAddress));
        }
        return $record;
    }

    public static function resetPassword($person) {

        $password = Random::getRandomPassword(8);

        $person->password = $password;

        $success = $person->savePassword();

        return $password;
    }

    /**
     * Store last login date
     * 
     * @param type $id if of client or carer
     * @param type $role Constants::USER_CLIENT or USER_CARER
     */
    public static function storeLastLogin($id, $role) {

        $today = Calendar::today(Calendar::FORMAT_DBDATETIME);

        if ($role == Constants::USER_CLIENT) {

            $tableName = 'tbl_login_history_client';
            $foreignField = 'id_client';
        } elseif ($role == Constants::USER_CARER) {

            $tableName = 'tbl_login_history_carer';
            $foreignField = 'id_carer';
        }

        $sql = "INSERT INTO $tableName ($foreignField, login_date_time) VALUES ($id, '$today')";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function getLastLogin($id, $role) {

        if ($role == Constants::USER_CLIENT) {
            
        } elseif ($role == Constants::USER_CARER) {

            $sql = "SELECT MAX(login_date_time) FROM tbl_login_history_carer WHERE id_carer = $id";
        }

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if (isset($result)) {

            return $result['MAX(login_date_time)'];
        } else {
            return null;
        }
    }

//    /**
//     * 
//     * @param type $conditions array containing condition DB keys
//     * @param type $ageGroup array containing ageGroup keys
//     * @param type $active 0: not active, 1 active
//     * @param type $workWithMale 0: don't work with male, 1: work with male
//     * @param type $workWithFemale 0: don't work with female 1: work with female
//     * @param type $nationality if 'all' no filtering
//     * @param type $liveIn 2: search for carers who ticked live in, 
//     * @param type $hourly 2: search for carers who ticked hourly
//     * @return type array of Carer objects
//     */
//    public static function getFilteredCarers($conditions, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale) {
//
//        $countCoundition = count($conditions);
//        $inCondition = implode(',', $conditions);
//
//        $countAgeGroup = count($ageGroup);
//        $inAgeGroup = implode(',', $ageGroup);
//
//        $select = 'id, active, score, work_with_male, work_with_female, nationality, live_in, hourly_work, gender';
//
//        $sql = "SELECT $select FROM (";
//
//        $sql .= "SELECT $select FROM tbl_carer ca ";
//
//        if (isset($clientId)) {
//
//            $sql .= " WHERE EXISTS (SELECT car.id_carer FROM tbl_client_carer_relation car     
//                            WHERE car.id_client = $clientId AND (car.favourite = 1 OR car.team = 1) AND car.not_wanted = 0 AND ca.id = car.id_carer )
//                        UNION
//                       
//                    SELECT $select FROM tbl_carer a WHERE NOT EXISTS (SELECT * FROM tbl_client_carer_relation b WHERE a.id = b.id_carer AND b.id_client = $clientId )";
//        }
//
//        $sql .= ") as result ";
//
//        if ($countCoundition > 0) {
//            $sql .= "INNER JOIN (SELECT id_carer FROM tbl_carer_condition
//                         WHERE id_condition IN ($inCondition)
//                         GROUP BY id_carer HAVING COUNT(distinct id_condition) = $countCoundition
//                 ) as cac ON result.id = cac.id_carer ";
//        }
//
//        if ($countAgeGroup > 0) {
//
//            $sql .= "INNER JOIN (SELECT id_carer FROM tbl_age_group     
//                         WHERE age_group IN ($inAgeGroup)
//                         GROUP BY id_carer HAVING COUNT(distinct age_group) = $countAgeGroup
//                 ) as ag ON result.id = ag.id_carer ";
//        }
//
//        $whereSet = false;
//
//        if ($active) {
//
//            $sql .= " WHERE result.active = 1 ";
//            $whereSet = true;
//        }
//
//        if ($workWithMale) {
//
//            if ($whereSet) {
//                $sql .= " AND ";
//            } else {
//                $sql .= " WHERE ";
//                $whereSet = true;
//            }
//            $sql .= " result.work_with_male = 1 ";
//        }
//        if ($workWithFemale) {
//
//            if ($whereSet) {
//                $sql .= " AND ";
//            } else {
//                $sql .= " WHERE ";
//                $whereSet = true;
//            }
//            $sql .= " result.work_with_female = 1 ";
//        }
//
//        if ($nationality != 'all') {
//
//            if ($whereSet) {
//                $sql .= " AND ";
//            } else {
//                $sql .= " WHERE ";
//                $whereSet = true;
//            }
//            $sql .= " result.nationality = '$nationality'";
//        }
//
//        if ($liveIn) {
//
//            if ($whereSet) {
//                $sql .= " AND ";
//            } else {
//                $sql .= " WHERE ";
//                $whereSet = true;
//            }
//            $sql .= " result.live_in = 2"; //for some reason true = 2
//        }
//
//        if ($hourly) {
//
//            if ($whereSet) {
//                $sql .= " AND ";
//            } else {
//                $sql .= " WHERE ";
//                $whereSet = true;
//            }
//            $sql .= " result.hourly_work = 2"; //for some reason true = 2
//        }
//
//        if ($workWithMale) {
//
//            if ($whereSet) {
//                $sql .= " AND ";
//            } else {
//                $sql .= " WHERE ";
//                $whereSet = true;
//            }
//            $sql .= " result.work_with_male = 1 ";
//        }
//
//        //Gender
//        if ($showMale && $showFemale) {
//
//            $genderValue = ' IN (' . Constants::GENDER_MALE . ',' . Constants::GENDER_FEMALE . ')';
//        } elseif (!$showMale && !$showFemale) {
//
//            $genderValue = "= -1"; //never show anything
//        } else {
//
//            if ($showMale) {
//                $genderValue = '= ' . Constants::GENDER_MALE;
//            } else {
//                $genderValue = '= ' . Constants::GENDER_FEMALE;
//            }
//        }
//
//        if ($whereSet) {
//            $sql .= " AND ";
//        } else {
//            $sql .= " WHERE ";
//            $whereSet = true;
//        }
//        $sql .= " result.gender $genderValue";
//
//
//
//        $sql .= " ORDER BY result.score DESC";
//
//
//        // $results = Yii::app()->db->createCommand($sql)->queryAll();
//        $records = Yii::app()->db->createCommand($sql)->queryAll();
//
//        $ids = array();
//        foreach ($records as $record) {
//            $ids[] = $record['id'];
//        }
//
//
//        $idCommaSeparated = implode(',', $ids);
//
//        $criteria = new CDbCriteria;
//        $criteria->order = "FIELD(id, $idCommaSeparated)";
//
//        $results = Carer::model()->findAllByPk($ids); //, $criteria);
//
//        return $results;
//    }

    public static function getCarersNationalities($activeOnly = false) {

        $sql = "SELECT DISTINCT nationality FROM tbl_carer";

        if ($activeOnly) {
            $sql .= " WHERE active=1";
        }

        $nationalities = Yii::app()->db->createCommand($sql)->queryAll(false);

        $labels = Nationalities::getNationalities();

        $result = array('all' => 'All');

        foreach ($nationalities as $nationalitiy) {

            if ($nationalitiy[0] != '') { //nationality can be blank is carer did not continue further after step1
                $result[$nationalitiy[0]] = $labels[$nationalitiy[0]];
            }
        }

        return $result;
    }

    /**
     * 
     * @param type $activities array of activities ID
     * @param type $physicalCondition array of physical conditions ID
     * @param type $mentalCondition array of mental conditions ID
     * @param type $ageGroup array of age group id
     * @param type $active boolean 
     * @param type $workWithMale boolean
     * @param type $workWithFemale boolean
     * @param type $nationality 'all' everything, otherwise nationality key
     * @param type $liveIn boolean if true $hourly must be false
     * @param type $hourly boolean  if true $hourly $liveIn be false
     * @param type $showMale boolean
     * @param type $showFemale boolean 
     * @param type $clientId clientId
     * @param type $relations array of ClientCarerRelation constants e.g ClientCarerRelation::RELATION_FAVOURITE
     * @param type $limitRelation maxiumum number of carers for which the client has a relation
     * @param type $limitNoRelation maxiumum number of carers for which the client has no relation
     * @return type
     */
    public static function getFilteredCarers2($activities, $physicalCondition, $mentalCondition, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale, $postCode = null, $language = null, $clientId = null, $relations = null, $limitRelation = 0, $limitNoRelation = 0) {

        $select = 'id, active, overall_score, work_with_male, work_with_female, nationality, live_in, hourly_work, gender, relation, id_client';

        $sql = "SELECT DISTINCT $select FROM view_client_carer_relation_carer ca";

        //add age and service users conditions SQL conditions
        $sql .= self::addAgeAndActivitiesSQLConditions($activities, $ageGroup);

        $sql .= self::addPhysicalMentalSQLConditions($physicalCondition, $mentalCondition);

        $sql .= " WHERE deactivated = 0 ";

        if (isset($postCode)) { //post code must have been validated before!
            if ($hourly) {
                $maxRadius = BusinessRules::getCarerMaximumWorkHourlyRadius();
            } else {
                $maxRadius = BusinessRules::getCarerMaximumWorkLiveInRadius();
            }

            $postCodeCoord = PostCodeCoordinate::read($postCode);
            $lat = $postCodeCoord['latitude'];
            $lon = $postCodeCoord['longitude'];

            if (isset($lat) && isset($lon)) {

                $distanceCalc = "(3958*3.1415926*sqrt((ca.latitude - $lat)*(ca.latitude - $lat) + cos(ca.latitude/57.29578)*cos($lat/57.29578)*(ca.longitude - $lon)*(ca.longitude-$lon))/180) ";

                $sql .= " AND $distanceCalc <= ca.hourly_work_radius AND $distanceCalc <= $maxRadius ";
            }
        }

        if (isset($language)) {
            if (is_array($language)) {

                $languageCondition = implode(',', $language);

                $sql .= " AND ca.language IN ($languageCondition)";
            } else {
                $sql .= " AND ca.language = '$language' ";
            }
        }

        //add carer fields conditions
        $carerConditions = self::addCarerConditions(true, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale);

        $sql .= $carerConditions;

        if (isset($clientId)) {
            $sql .= " AND id_client = $clientId ";
        }

        if (isset($relations)) {

            $relationCondition = implode(',', $relations);

            $sql .= " AND relation IN ($relationCondition)";
        }

        $sql .= " ORDER BY last_login_date DESC, relation DESC, overall_score DESC";

        if ($limitRelation != 0) {

            $sql .= " LIMIT $limitRelation";
        }
//
//        $sql .= " ORDER BY 
//   CASE relation
//      WHEN 1 THEN 1
//      WHEN 2 THEN 2
//      WHEN 3 THEN 3   
//      ELSE 4 
//   END, score DESC";
        //get all carers with client relation set
        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();
        foreach ($records as $record) {
            $ids[] = $record['id'];
        }

        ////get all carers with NO client relation set
        if (isset($clientId)) {
            $idsCarerNoRelation = self::getCarersIdsNoRelation($clientId, $activities, $physicalCondition, $mentalCondition, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale, $limitNoRelation);

            foreach ($idsCarerNoRelation as $idcarer) {
                $ids[] = $idcarer;
            }
        }

        $idCommaSeparated = implode(',', $ids);

        //Sort carers
        $criteria = new CDbCriteria;
        if (count($ids) > 0) {
            $criteria->order = "FIELD(id, $idCommaSeparated)";
        }

        //retrieve active records for ids of carer with relation and with no relation
        $results = Carer::model()->findAllByPk($ids, $criteria);

        return $results;
    }

    public static function getCarersIdsNoRelation($clientId, $activities, $physicalCondition, $mentalCondition, $ageGroup, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale, $limit = 0) {

        $sql = "SELECT * FROM tbl_carer ca ";

        //add age and service users conditions SQL conditions
        $sql .= self::addAgeAndActivitiesSQLConditions($activities, $ageGroup);

        $sql .= self::addPhysicalMentalSQLConditions($physicalCondition, $mentalCondition);

        $carerConditions = self::addCarerConditions(true, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale);

        $sql .= " AND NOT EXISTS
            (SELECT * FROM tbl_client_carer_relation b WHERE ca.id = b.id_carer AND b.id_client = $clientId) ";

        $sql .= $carerConditions;

        $sql .= " WHERE deactivated = 0 ORDER BY overall_score DESC ";

        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }

        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();
        foreach ($records as $record) {
            $ids[] = $record['id'];
        }

        return $ids;
    }

    private static function addCarerConditions($whereSet, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale) {

        $sql = '';

        if ($active) {

            if ($whereSet) {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
                $whereSet = true;
            }
            $sql .= " active = 1 ";
        }

        if ($workWithMale) {

            if ($whereSet) {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
                $whereSet = true;
            }
            $sql .= " work_with_male = " . true;
        }
        if ($workWithFemale) {

            if ($whereSet) {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
                $whereSet = true;
            }
            $sql .= " work_with_female = " . true;
        }

        if ($nationality != 'all') {

            if ($whereSet) {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
                $whereSet = true;
            }
            $sql .= " nationality = '$nationality'";
        }

        if ($liveIn) {

            if ($whereSet) {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
                $whereSet = true;
            }
            $sql .= " live_in = " . Constants::DB_TRUE;
        }

        if ($hourly) {

            if ($whereSet) {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
                $whereSet = true;
            }
            $sql .= " hourly_work = " . Constants::DB_TRUE;
        }

        if ($workWithMale) {

            if ($whereSet) {
                $sql .= " AND ";
            } else {
                $sql .= " WHERE ";
                $whereSet = true;
            }
            $sql .= " work_with_male = " . true;
        }

        //Gender
        if ($showMale && $showFemale) {

            $genderValue = ' IN (' . Constants::GENDER_MALE . ',' . Constants::GENDER_FEMALE . ')';
        } elseif (!$showMale && !$showFemale) {

            $genderValue = "= -1"; //never show anything
        } else {

            if ($showMale) {
                $genderValue = '= ' . Constants::GENDER_MALE;
            } else {
                $genderValue = '= ' . Constants::GENDER_FEMALE;
            }
        }

        if ($whereSet) {
            $sql .= " AND ";
        } else {
            $sql .= " WHERE ";
            $whereSet = true;
        }
        $sql .= " gender $genderValue";

        return $sql;
    }

    private static function addAgeAndActivitiesSQLConditions($conditions, $ageGroup) {

        $countCoundition = count($conditions);
        $inCondition = implode(',', $conditions);

        $countAgeGroup = count($ageGroup);
        $inAgeGroup = implode(',', $ageGroup);

        $sql = '';

        if ($countCoundition > 0) {
            $sql .= " INNER JOIN (SELECT id_carer FROM tbl_carer_condition
                         WHERE id_condition IN ($inCondition)
                         GROUP BY id_carer HAVING COUNT(distinct id_condition) = $countCoundition
                 ) as cac ON ca.id = cac.id_carer ";
        }

        if ($countAgeGroup > 0) {

            $sql .= " INNER JOIN (SELECT id_carer FROM tbl_age_group     
                         WHERE age_group IN ($inAgeGroup)
                         GROUP BY id_carer HAVING COUNT(distinct age_group) = $countAgeGroup
                 ) as ag ON ca.id = ag.id_carer ";
        }

        return $sql;
    }

    private static function addPhysicalMentalSQLConditions($physicalConditionsIds, $mentalConditionsIds) {

        $sql = '';

        if (count($physicalConditionsIds) > 0) {

            $physicalInCondition = implode(',', $physicalConditionsIds);

            $sql = "INNER JOIN (SELECT id_carer FROM tbl_carer_condition
                         WHERE (id_condition IN ($physicalInCondition)) 
                       
                ) as cac2 ON ca.id = cac2.id_carer ";
        }

        if (count($mentalConditionsIds)) {

            $mentalInCondition = implode(',', $mentalConditionsIds);

            $sql .= "INNER JOIN (SELECT id_carer FROM tbl_carer_condition
                         WHERE (id_condition IN ($mentalInCondition))
                       
                ) as cac3 ON ca.id = cac3.id_carer ";
        }

        return $sql;
    }

    public static function getCarersNotWanted($clientId, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale) {

        $sql = "SELECT id FROM view_client_carer_relation_carer a WHERE id_client = $clientId AND relation = " . ClientCarerRelation::RELATION_NOT_WANTED;

        $sql .= " AND deactivated = 0 ";

        $carerConditions = self::addCarerConditions(true, $active, $workWithMale, $workWithFemale, $nationality, $liveIn, $hourly, $showMale, $showFemale);

        $sql .= $carerConditions;

        $sql .= " ORDER BY overall_score DESC";

        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $ids = array();
        foreach ($records as $record) {
            $ids[] = $record['id'];
        }

        $idCommaSeparated = implode(',', $ids);

        $criteria = new CDbCriteria;
        if (count($ids) > 0) {
            $criteria->order = "FIELD(id, $idCommaSeparated)";
        }

        $carers = Carer::model()->findAllByPk($ids, $criteria);

        return $carers;
    }

}

?>