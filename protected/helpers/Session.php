<?php

class Session {

    const CREDIT_CARD_SELECTED = 'creditCardSelected';
    const SERVICE_USERS_SELECTED = 'ServiceUsersSelected';
    const SERVICE_LOCATIONS_SELECTED = 'ServiceLocationsSelected';
    const LIVE_IN_REQUEST_SELECTED = 'LiveInRequestSelected';
    const HOURLY_REQUEST_SELECTED = 'HourlySelected';
    const SERVICE_USERS_FIRST_TIME = 'ServiceUsersFirstTime';
    const SERVICE_LOCATIONS_FIRST_TIME = 'ServiceLocationsFirstTime';
    const USERS_INDEXES = 'UserIndexes';
    const SELECTED_MISSION = 'SelectedMission';
    const SELECTED_MISSION_TYPE = 'SelectedMissionType';
    const SELECTED_BILLING_ADDRESS = 'SelectedBillingAddress';
    const SELECTED_VALID_QUOTE_TYPE = 'SelectedQuoteType';
    const SELECTED_TAB = 'SelectedTab';
    const SELECTED_HOURLY_TYPE = 'SelectedHourlyType';
    const SHOW_RESULT_LIVE_IN = 'ShowResultLiveIn';
    const SHOW_RESULT_HOURLY = 'ShowResultHourly';
    const NEW_BOOKING_ID = 'NewBookingId'; //just booked booking, service confirmation
    const SELECT_CARERS_MAX_DISPLAY = 'SelectCarersMaxDisplay';
    const ALL_QUOTES = 'AllQuotes';
    const TEMP_MISSIONS = 'TempMissions';
    const CARER_LANGUAGES = 'CarerLanguages';
    //wizard
    const SERVICE_POSTCODE = "ServicePostCode";
    const FIND_CARERS_CRITERIA = 'FindCarersCriteria';
    const SELECTED_CARERS = 'SelectedCarers';
    const SELECTED_CARER = 'SelectedCarer';
    const SELECTED_VALID_QUOTE = 'SelectedValidQuote';
    const SIGNED_IN_CLIENT = 'SignedInClient'; //store data of proper client
    const SIGN_IN_DATA = 'SignInData'; //store data on sign in page 
    const SIGN_UP_FIRST_TIME = 'SignupData';
    const SERVICE_LOCATIONS = 'ServiceLocations';
    const SERVICE_USERS = 'ServiceUsers';
    const CREDIT_CARD = 'CreditCard';
    const CREDIT_CARD_RADIO_BUTTON = 'CreditCardRadioButton';
    const PAYMENT_PAGE_FIRST_TIME = 'PaymentPageFirstTime';
    const SHOW_ERRORS = 'ShowErrors';
    const MESSAGE_ME_EMAIL = 'MessageMeEmail';
    const SELECT_DATES_MAX_INDEX = 'SelectDatesMaxIndex';
    const BACK_URL = 'BackUrl';

    /**
     * Get selected quote
     * 
     * If it does not exist create an empty one
     * 
     * @return type Booking
     */
    public static function getSelectedQuoteType() {
        $quoteType = Yii::app()->session[self::SELECTED_VALID_QUOTE_TYPE];

        if (!isset($quoteType) || $quoteType == '') {
            $quoteType = Yii::app()->params['test']['defaultService'];
            self::setSelectedQuoteType($quoteType);
        }
        return $quoteType;
    }

    /**
     * 
     * @return selected quote by the user
     */
    public static function getSelectedQuote() {

        $tab = self::getSelectedTab();

        return self::getQuote($tab);
    }

    /**
     * 
     * @return selected quote for currently selected quote type. Returns new object if none before
     */
    public static function getSelectedLiveInQuote() {

        $model = Yii::app()->session[self::LIVE_IN_REQUEST_SELECTED];

        if (!isset($model)) {
            $model = Booking::create(Booking::TYPE_LIVE_IN);
        }

        return $model;
    }

    public static function getSelectedHourlyQuote() {

        $model = Yii::app()->session[self::HOURLY_REQUEST_SELECTED];

        if (!isset($model)) {
            $model = Booking::create(Booking::TYPE_HOURLY);
        }

        return $model;
    }

    /**
     * 
     * @param type $quoteType Hourly or Live-in
     */
    public static function setSelectedQuoteType($quoteType) {
        Yii::app()->session[self::SELECTED_VALID_QUOTE_TYPE] = $quoteType;
    }

    public static function setSelectedTab($selectedTab) {
        Yii::app()->session[self::SELECTED_TAB] = $selectedTab;
    }

    public static function getSelectedTab() {
        $value = Yii::app()->session[self::SELECTED_TAB];

        if (isset($value)) {
            return $value;
        } else {
            return Constants::TAB_HOURLY_ONE;
        }
    }

    private static function initQuotes($quote = 'all') {

        $array = Yii::app()->session[self::ALL_QUOTES];

        if (!isset($array)) {

            $array = array();
        }

        //live in
        if ($quote == Constants::TAB_LIVEIN || $quote == 'all') {

            $bookingLiveIn = new BookingLiveIn();
            $bookingLiveIn->initFirstTime();

            $array[Constants::TAB_LIVEIN] = $bookingLiveIn;
        }

        //hourly one day
        if ($quote == Constants::TAB_HOURLY_ONE || $quote == 'all') {

//            $bookingHourlyOneDay = new BookingHourlyOneDayForm();
//            $bookingHourlyOneDay->initFirstTime();
//
//            $array[Constants::TAB_HOURLY_ONE] = $bookingHourlyOneDay;
            //changed to new form
            $hourlyHourlyQuoteSimple = new HourlyQuoteSimpleForm();
            $hourlyHourlyQuoteSimple->initFirstTime();
            $array[Constants::TAB_HOURLY_ONE] = $hourlyHourlyQuoteSimple;
        }

        //hourly fourteen
        if ($quote == Constants::TAB_HOURLY_FOURTEEN || $quote == 'all') {

            $bookingHourlyFourteen = new BookingHourly(Booking::SCENARIO_QUOTE_FOURTEEN);
            $bookingHourlyFourteen->initFirstTime();

            $array[Constants::TAB_HOURLY_FOURTEEN] = $bookingHourlyFourteen;
        }

        //hourly regularly
        if ($quote == Constants::TAB_HOURLY_REGULARLY || $quote == 'all') {

            $bookingRegularly = new BookingHourlyRegularlyForm(Booking::SCENARIO_RECURRING);
            $bookingRegularly->initFirstTime();

            $array[Constants::TAB_HOURLY_REGULARLY] = $bookingRegularly;
        }

        Yii::app()->session[self::ALL_QUOTES] = $array;
    }

    /**
     * 
     * If tab is empty return current tab based on self::getSelectedTab()
     * 
     * @param type $tab tab index for which you want the quote
     * @return type Quote
     */
    public static function getQuote($tab = null) {

        $array = Yii::app()->session[self::ALL_QUOTES];

        if ($array == null) {
            //first time
            self::initQuotes('all');
        }

        if ($tab == null) {

            $tab = self::getSelectedTab();
        }

        if (!isset($array[$tab])) {
            //to fix some SESSION bugs
            self::initQuotes($tab);
            $array = Yii::app()->session[self::ALL_QUOTES];
        }

        return $array[$tab];
    }

    /**
     * Store a quote for given tab
     * 
     * If no tab provided use current tab
     * 
     * @param type $quote
     * @param type $tab
     */
    public static function setQuote($quote, $tab = null) {

        if ($tab == null) {

            $tab = self::getSelectedTab();
        }

        $array = Yii::app()->session[self::ALL_QUOTES];

        if ($array == null) {
            $array = array();
            $array[$tab] = $quote;
        } else {

            $array[$tab] = $quote;
        }

        Yii::app()->session[self::ALL_QUOTES] = $array;
    }

    /**
     * Return selected billing address id
     */
    public static function getSelectedBillingAddress() {
        return Yii::app()->session[self::SELECTED_BILLING_ADDRESS];
    }

    public static function setSelectedBillingAddress($model) {
        Yii::app()->session[self::SELECTED_BILLING_ADDRESS] = $model;
    }

    public static function getSelectedMissionType() {
        return Yii::app()->session[self::SELECTED_MISSION_TYPE];
    }

    public static function setSelectedMissionType($type) {

        Yii::app()->session[self::SELECTED_MISSION_TYPE] = $type;
    }

    public static function getSelectedMission() {
        return Yii::app()->session[self::SELECTED_MISSION];
    }

    public static function setSelectedMission($id) {

        Yii::app()->session[self::SELECTED_MISSION] = $id;
    }

    /*
     * Returns currently selected service users ids
     */

    public static function getSelectedServiceUsers() {
        return Yii::app()->session[self::SERVICE_USERS_SELECTED];
    }

    /*
     * Set selected service users id, add them
     */

    public static function setSelectedServiceUser($id) {
        $selectedServiceUsers = Yii::app()->session[self::SERVICE_USERS_SELECTED];
        $selectedServiceUsers[$id] = (int) $id;
        Yii::app()->session[self::SERVICE_USERS_SELECTED] = $selectedServiceUsers;
    }

    /**
     * Set all
     * 
     * @param type $ids
     */
    public static function setSelectedServiceUsers($serviceUsersIds) {
        Yii::app()->session[self::SERVICE_USERS_SELECTED] = $serviceUsersIds;
    }

    public static function removeSelectedServiceUser($id) {

        $selectedServiceUsers = Yii::app()->session[self::SERVICE_USERS_SELECTED];
        unset($selectedServiceUsers[$id]);
        Yii::app()->session[self::SERVICE_USERS_SELECTED] = $selectedServiceUsers;
    }

    public static function getSelectedServiceLocation() {

        return Yii::app()->session[self::SERVICE_LOCATIONS_SELECTED];
    }

    public static function setSelectedServiceLocation($id) {

        Yii::app()->session[self::SERVICE_LOCATIONS_SELECTED] = $id;
    }

    public static function removeSelectedServiceLocation($id) {
//        $selectedServiceLocations = Yii::app()->session[self::SERVICE_LOCATIONS_SELECTED];
//        unset($selectedServiceLocations[$id]);
        unset(Yii::app()->session[self::SERVICE_LOCATIONS_SELECTED]); // = $selectedServiceLocations;
    }

    /*
     * returns true if given service user is currently selected
     */

    public static function serviceUserIsSelected($serviceUseId) {

        $selectedServiceUsers = Yii::app()->session[self::SERVICE_USERS_SELECTED];
        $value = isset($selectedServiceUsers[$serviceUseId]);

        return $value;
    }

    /// Credit card
    public static function getSelectedCreditCard() {

        return Yii::app()->session[self::CREDIT_CARD_SELECTED];
    }

    public static function setSelectedCreditCard($id) {

        Yii::app()->session[self::CREDIT_CARD_SELECTED] = $id;
    }

    public static function removeSelectedCreditCard() {

        unset(Yii::app()->session[self::CREDIT_CARD_SELECTED]);
    }

    /*
     * returns true if given service location is currently selected
     */

    public static function serviceLocationIsSelected($serviceLocationId) {

        $selectedServiceLocation = Yii::app()->session[self::SERVICE_LOCATIONS_SELECTED];

        return ($serviceLocationId == $selectedServiceLocation);
    }

    /*
     * Clear the session variables
     */

    public static function initNewBooking() {

        unset(Yii::app()->session[self::CREDIT_CARD_SELECTED]);

        unset(Yii::app()->session[self::LIVE_IN_REQUEST_SELECTED]);
        unset(Yii::app()->session[self::HOURLY_REQUEST_SELECTED]);
        unset(Yii::app()->session[self::SHOW_RESULT_LIVE_IN]);
        unset(Yii::app()->session[self::SHOW_RESULT_HOURLY]);
        unset(Yii::app()->session[self::SELECTED_VALID_QUOTE_TYPE]);
        unset(Yii::app()->session[self::USERS_INDEXES]);

        unset(Yii::app()->session[self::SELECTED_MISSION]);
        unset(Yii::app()->session[self::SELECTED_MISSION_TYPE]);
        unset(Yii::app()->session[self::SELECTED_BILLING_ADDRESS]);
        unset(Yii::app()->session[self::SELECTED_VALID_QUOTE]);

        //new
        unset(Yii::app()->session[self::ALL_QUOTES]);
        unset(Yii::app()->session[self::SELECTED_TAB]);
        unset(Yii::app()->session[self::SELECTED_CARERS]);
        unset(Yii::app()->session[self::SELECTED_CARER]);
        unset(Yii::app()->session[self::SELECT_CARERS_MAX_DISPLAY]);


        Yii::app()->session[self::SERVICE_USERS_FIRST_TIME] = true;
        Yii::app()->session[self::SERVICE_LOCATIONS_FIRST_TIME] = true;

        //new wizard
        unset(Yii::app()->session[self::FIND_CARERS_CRITERIA]);
        unset(Yii::app()->session[self::SIGNED_IN_CLIENT]);
        unset(Yii::app()->session[self::SIGN_IN_DATA]);
        unset(Yii::app()->session[self::SIGN_UP_FIRST_TIME]);
        unset(Yii::app()->session[self::SERVICE_LOCATIONS]);
        unset(Yii::app()->session[self::SERVICE_USERS]);
        unset(Yii::app()->session[self::SERVICE_LOCATIONS_SELECTED]);
        unset(Yii::app()->session[self::SERVICE_USERS_SELECTED]);
        unset(Yii::app()->session[self::CREDIT_CARD]);
        unset(Yii::app()->session[self::CREDIT_CARD_RADIO_BUTTON]);
        unset(Yii::app()->session[self::MESSAGE_ME_EMAIL]);
        unset(Yii::app()->session[self::SELECT_DATES_MAX_INDEX]);
        Yii::app()->session[self::PAYMENT_PAGE_FIRST_TIME] = true;

        //destroy wizard steps
        unset(Yii::app()->session['steps']);
    }

    public static function isServiceUsersFirstTime() {

        return Yii::app()->session[self::SERVICE_USERS_FIRST_TIME];
    }

    public static function setServiceUsersFirstTime($value) {

        Yii::app()->session[self::SERVICE_USERS_FIRST_TIME] = $value;
    }

    public static function isServiceLocationsFirstTime() {

        return Yii::app()->session[self::SERVICE_LOCATIONS_FIRST_TIME];
    }

    public static function setServiceLocationsFirstTime($value) {

        Yii::app()->session[self::SERVICE_LOCATIONS_FIRST_TIME] = $value;
    }

    public static function setNewBookingId($bookingId) {
        Yii::app()->session[self::NEW_BOOKING_ID] = $bookingId;
    }

    public static function getNewBookingId() {
        return Yii::app()->session[self::NEW_BOOKING_ID];
    }

    public static function setSelectedCarers($selectedCarers) {

        Yii::app()->session[self::SELECTED_CARERS] = $selectedCarers;
    }

    public static function getSelectedCarers() {

        return Yii::app()->session[self::SELECTED_CARERS];
    }

    public static function setSelectedCarer($carerId) {

        Yii::app()->session[self::SELECTED_CARER] = $carerId;
    }

    public static function getSelectedCarer() {

        return Yii::app()->session[self::SELECTED_CARER];
    }

    public static function setSelectedValidQuote($selectedQuote) {

        Yii::app()->session[self::SELECTED_VALID_QUOTE] = $selectedQuote;
    }

    public static function getSelectedValidQuote() {

        return Yii::app()->session[self::SELECTED_VALID_QUOTE];
    }

    public static function setSelectCarersMaxDisplay($maxDisplayCarers) {

        Yii::app()->session[self::SELECT_CARERS_MAX_DISPLAY] = $maxDisplayCarers;
    }

    public static function getSelectCarersMaxDisplay() {

        return Yii::app()->session[self::SELECT_CARERS_MAX_DISPLAY];
    }

//    public static function getSelectedHourlyType() {
//
//        if (!isset(Yii::app()->session[self::SELECTED_HOURLY_TYPE])) {
//
//            Yii::app()->session[self::SELECTED_HOURLY_TYPE] = BookingHourly::SUBTYPE_ONEDAY;
//        }
//
//        return Yii::app()->session[self::SELECTED_HOURLY_TYPE];
//    }
//    public static function setSelectedHourlyType($subType) {
//
//        Yii::app()->session[self::SELECTED_HOURLY_TYPE] = $subType;
//    }
//    //array expected
//    public static function setHourlyBookingDaysTimes($values) {
//
//        Yii::app()->session[self::HOURLY_BOOKING_DAYS_TIMES] = $values;
//    }
//
//    public static function getHourlyBookingDaysTimes() {
//
//        return Yii::app()->session[self::HOURLY_BOOKING_DAYS_TIMES];
//    }

    /**
     * 
     * @return type Carer or Client
     */
    public static function getLoggedPerson() {

        $userId = Yii::app()->user->id;
        $role = Yii::app()->user->roles;

        if ($role == Constants::USER_CLIENT) {
            $person = Client::loadModel($userId);
        } else {
            $person = Carer::loadModel($userId);
        }

        return $person;
    }

    public static function setTempMission($mission) {

        Yii::app()->session[self::TEMP_MISSIONS] = $mission;
    }

    public static function getTempMission($id) {

        $currentValues = Yii::app()->session[self::TEMP_MISSIONS];
        if (isset($currentValues)) {
            if (isset($currentValues[$id])) {
                return $currentValues[$id];
            }
        }
        return null;
    }

    /**
     * Return array of CarerLanguage model
     */
    public static function getCarerLanguages() {

        return Yii::app()->session[self::CARER_LANGUAGES];
    }

    /**
     * 
     * Set CarerLanguageModel
     * 
     * @param type array of CarerLanguage
     */
    public static function setCarerLanguages($carerLanguages) {

        Yii::app()->session[self::CARER_LANGUAGES] = $carerLanguages;
    }

    /**
     * 
     * @param type $criteria array
     */
    public static function setFindCarersCriteria($criteria) {

        Yii::app()->session[self::FIND_CARERS_CRITERIA] = $criteria;
    }

    /**
     *  
     * @param type $criteria array
     */
    public static function getFindCarersCriteria() {

        return Yii::app()->session[self::FIND_CARERS_CRITERIA];
    }

    public static function getClient() {

        return Yii::app()->session[self::SIGNED_IN_CLIENT];
    }

    public static function setClient($client) {

        Yii::app()->session[self::SIGNED_IN_CLIENT] = $client;
    }

    public static function getSigninClient() {

        return Yii::app()->session[self::SIGN_IN_DATA];
    }

    public static function setSigninClient($client) {

        Yii::app()->session[self::SIGN_IN_DATA] = $client;
    }

    public static function getSignupClientFirstTime() {

        if (isset(Yii::app()->session[self::SIGN_UP_FIRST_TIME])) {
            return Yii::app()->session[self::SIGN_UP_FIRST_TIME];
        } else {
            Yii::app()->session[self::SIGN_UP_FIRST_TIME] = false;
            return true;
        }
    }

    public static function setSignupClientFirstTime($boolean) {

        Yii::app()->session[self::SIGN_UP_FIRST_TIME] = $boolean;
    }

    public static function getServiceLocations() {

        return Yii::app()->session[self::SERVICE_LOCATIONS];
    }

    public static function setServiceLocations($serviceLocations) {

        Yii::app()->session[self::SERVICE_LOCATIONS] = $serviceLocations;
    }

    public static function getServiceUsers() {

        return Yii::app()->session[self::SERVICE_USERS];
    }

    public static function setServiceUsers($serviceUsers) {

        Yii::app()->session[self::SERVICE_USERS] = $serviceUsers;
    }

    public static function getCreditCard() {

        return Yii::app()->session[self::CREDIT_CARD];
    }

    public static function setCreditCard($creditCard) {

        Yii::app()->session[self::CREDIT_CARD] = $creditCard;
    }

    public static function getCreditCardRadioButton() {

        return Yii::app()->session[self::CREDIT_CARD_RADIO_BUTTON];
    }

    public static function getPaymentPageFirstTime() {

        return Yii::app()->session[self::PAYMENT_PAGE_FIRST_TIME];
    }

    public static function setPaymentPageFirstTime($value) {

        Yii::app()->session[self::PAYMENT_PAGE_FIRST_TIME] = $value;
    }

    public static function setCreditCardRadioButton($radioButton) {

        Yii::app()->session[self::CREDIT_CARD_RADIO_BUTTON] = $radioButton;
    }

    public static function setShowErrors($boolean) {

        Yii::app()->session[self::SHOW_ERRORS] = $boolean;
    }

    public static function getShowErrors() {
        return Yii::app()->session[self::SHOW_ERRORS];
    }

    public static function setPostCode($postCode) {

        Yii::app()->session[self::SERVICE_POSTCODE] = $postCode;
    }

    public static function getPostCode() {
        return Yii::app()->session[self::SERVICE_POSTCODE];
    }

    public static function getMessageMeEmail() {
        return Yii::app()->session[self::MESSAGE_ME_EMAIL];
    }

    public static function setMessageMeEmail($email) {
        Yii::app()->session[self::MESSAGE_ME_EMAIL] = $email;
    }

    public static function getSelectDatesMaxIndex() {
        return Yii::app()->session[self::SELECT_DATES_MAX_INDEX];
    }

    public static function setSelectDatesMaxIndex($index) {

        Yii::app()->session[self::SELECT_DATES_MAX_INDEX] = $index;
    }

    public static function increaseSelectDatesMaxIndex() {

        $index = Yii::app()->session[self::SELECT_DATES_MAX_INDEX];
        if (!isset($index)) {
            $index = 0;
        } else {
            $index++;
        }
        Yii::app()->session[self::SELECT_DATES_MAX_INDEX] = $index;

        return $index;
    }

    public static function getBackUrl() {
        return Yii::app()->session[self::BACK_URL];
    }

    public static function setBackUrl($url) {
        Yii::app()->session[self::BACK_URL] = $url;
    }

}

?>