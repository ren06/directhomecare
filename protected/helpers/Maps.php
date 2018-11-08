<?php

class Maps {
    //use GMaps - $address GMap string e.g 'London, UK' or 'W10 5UB, UK'
//    public static function getCoordinate($address) {
//
//        Yii::import('application.extensions.EGMap2.*');
//
//        $gMap = new EGMap();
//        $geocoded_address = new EGMapGeocodedAddress($address);
//
//        $geocoded_address->geocode($gMap->getGMapClient());
//
//        $result = array();
//        // Center the map on geocoded address
//        $result['latitude'] = $geocoded_address->getLat();
//        $result['longitude'] = $geocoded_address->getLng();
//
//        return $result;
//    }

    /**
     * Calculate distance between two UK post codes in miles or km
     *  
     * @param type $postCode1 e.g. W10 5UB
     * @param type $postCode2 e.g SW6 4EA
     * 
     * @return type distance in miles or km
     */
    public static function getDistance($postCode1, $postCode2, $miles = true) {

//        $address1 = $postCode1;
//        $address2 = $postCode2;

        $result1 = Maps::getPostCodeData($postCode1);
        $result2 = Maps::getPostCodeData($postCode2);

        // Center the map on geocoded address
        $address1Lat = $result1['latitude'];
        $address1Lng = $result1['longitude'];

        // Center the map on geocoded address
        $address2Lat = $result2['latitude'];
        $address2Lng = $result2['longitude'];


        $result = self::getGreatCircleDistance($address1Lat, $address1Lng, $address2Lat, $address2Lng, $miles);

//        $result = array();
//
//        $baseUrl = "https://maps.googleapis.com/maps/api/distancematrix/output?parameters";
//
//        $origins = "origins=Bobcaygeon+ON|41.43206,-81.38992";
//        $destinations = "destinations=Darling+Harbour+NSW+Australia";
//
//        $example = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$origins&destinations=$destinations&mode=bicycling&language=fr-FR&sensor=false";
//
//
//        $data = @file_get_contents($example);
//
//        $result = json_decode($data, true);

        return $result;
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $miles if true miles, if false km
     * @return float Distance between points in [km or miles] (same as earthRadius)
     */
    public static function getGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $miles = true) {

        $earthRadius = 6371000;

        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $km = $angle * $earthRadius / 1000;

        if ($miles) {
            $kmToMiles = 0.621371192;
            return $km * $kmToMiles;
        } else {
            return $km;
        }
    }

//    /**
//     * @throws CException if no valid post code
//     */
//    public static function getAddress($postCode) {
//
//        if (UIServices::checkInternetConnection()) {
//
//            //lookup post code
//            $result = PostCodeCoordinate::lookup($postCode);
//
//            $lat = $result['latitude'];
//            $lng = $result['longitude'];
//
//            // Now build the lookup:
//            $address_url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&sensor=false';
//            $address_json = json_decode(file_get_contents($address_url));
//            $address_data = $address_json->results[0]->address_components;
//            //$street = str_replace('Dr', 'Drive', $address_data[1]->long_name);
//
//            $city = "";
//            foreach ($address_data as $data) {
//
//                $type = $data->types;
//
//                if ($type[0] == 'postal_town') {
//                    $city = $data->long_name;
//                    break;
//                }
//            }
//        } else {
//
//            $lng = null;
//            $lat = null;
//            $street = null;
//            $city = null;
//        }
//
//        $address = new Address();
//        $address->post_code = $postCode;
//        //$address->address_line_1 = $street;
//        //$address->address_line_2;
//        $address->city = $city;
//        $address->longitude = $lng;
//        $address->latitude = $lat;
//
//        return $address;
//    }

    public static function isValidPostCode($postCode) {

        $postCode = trim(strtoupper($postCode));

        if (Util::isPostCodeValid($postCode)) { //regular expression check
            $result = PostCodeCoordinate::read($postCode);

            if ($result == false) {
                try {
                    $result = self::googleAPIPostCode($postCode);
                    PostCodeCoordinate::write($postCode, $result['lng'], $result['lat'], $result['city']);
                    return true;
                } catch (CException $e) {

                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function getPostCodeData($postCode) {

        if (self::isValidPostCode($postCode)) {
            return PostCodeCoordinate::read($postCode);
        } else {
            throw new CException("Invalid post code: $postCode");
        }
    }

    /**
     * return city and long lat in array
     * 
     * @param type $postCode
     */
    public static function googleAPIPostCode($postCode) {

        $postCode = trim(strtoupper($postCode));

        if (Util::isPostCodeValid($postCode)) {

            $address_url = 'http://maps.googleapis.com/maps/api/geocode/json?';
            $options = array("address" => $postCode, "sensor" => "false");
            $address_url .= http_build_query($options, '', '&');

            $address_json = json_decode(file_get_contents($address_url));

            if (isset($address_json->results[0]) &&
                    !isset($address_json->results[0]->partial_match)) {

                if (isset($address_json->results[0])) {

                    $address_data = $address_json->results[0]->address_components;
                    $geometry = $address_json->results[0]->geometry;

                    $city = null;
                    foreach ($address_data as $data) {

                        $type = $data->types;

                        if ($type[0] == 'postal_town') {
                            $city = $data->long_name;
                            break;
                        }
                    }

                    if (!isset($city)) {

                        foreach ($address_data as $data) {

                            $type = $data->types;

                            if ($type[0] == 'administrative_area_level_2') {
                                $city = $data->long_name;
                                if ($city == "Greater London") {
                                    $city = "London";
                                    break;
                                }
                            }
                        }
                    }

                    if (!isset($city)) {
                        throw new CException('GMaps cannot find the city for: ' . $postCode);
                    }

                    $result = array();
                    $result['city'] = $city;
                    $result['lat'] = $geometry->location->lat;
                    $result['lng'] = $geometry->location->lng;

                    return $result;
                }
            } else {
                throw new CException('Invalid post code: ' . $postCode);
            }
        } else {
            throw new CException('Invalid post code: ' . $postCode);
        }
    }

}

?>
