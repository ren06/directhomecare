<?php

class Constants {
    
    //Payment type
    const PAYMENT_CARD_ONLY = 0;
    const PAYMENT_VOUCHER_ONLY = 1;
    const PAYMENT_CARD_AND_VOUCHER = 2;
    //
    //FBC
    const CARER_PROFILE_VIEW_SELECT_TEAM = 0;
    const CARER_PROFILE_VIEW_ADMIN = 1;
    const CARER_PROFILE_VIEW_MY_CARERS = 2;
    const CARER_PROFILE_VIEW_GUEST = 3;

    //db booleans, 0=no value, 1=false, 2=true
    const DB_NONE = 0;
    const DB_FALSE = 1;
    const DB_TRUE = 2;

    //gender
    const GENDER_NONE = 0;
    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;
    const GENDER_BOTH = 3;

    //Who for
    const FOR_OTHER = 0;
    const FOR_MYSELF = 1;
      
    //user
    const USER_CLIENT = 1;
    const USER_CARER = 2;
    const USER_ADMIN = 3;
    const USER_ALL = 4;
    //type work
    const LIVE_IN = 1;
    const HOURLY = 2;
     //booking subtype
    const ONE_OFF = 0;
    const TWO_FOURTEEN = 1;
    const REGULARLY = 2;
    const SEVERAL_DAYS = 3;
    //data
    const DATA_MASTER = 0;
    const DATA_TRANSACTIONAL = 1;
    const SUCCESS = 1;
    const FAILED = 0;
    //tab
    const TAB_LIVEIN = 1;
    const TAB_HOURLY_ONE = 2;
    const TAB_HOURLY_FOURTEEN = 3;
    const TAB_HOURLY_REGULARLY = 4;

}

class UIConstants {

    const RADIO_BUTTON_BILLING_ADDRESS_OTHER = 'radio_button_billing_address_other_';
    const RADIO_BUTTON_BILLING_ADDRESS = 'radio_button_billing_address_';
    const RADIO_BUTTON_CREDIT_CARD_OTHER = 'card_other';
    const RADIO_BUTTON_CREDIT_CARD = 'radio_button_credit_card_';
    const RADIO_BUTTON_CREDIT_CARD2 = 'radio_button_credit_card';

}

class NavigationScenario {
    //carer

    const CARER_BACK_TO_CURRENT_MISSIONS = 10;
    const CARER_BACK_TO_OPENED_COMLAINTS = 11;
    const CARER_BACK_TO_MISSIONS_VERIFIED = 12;
    const CARER_BACK_TO_MISSIONS_HISTORY = 13;
    const CARER_BACK_TO_MONEY = 14;

    //client
    const CLIENT_BACK_TO_NONE = 0;
    const CLIENT_BACK_TO_BOOKINGS = 50;
    const CLIENT_BACK_TO_VISITS = 51;
    const CLIENT_BACK_TO_PAYMENTS = 52;
    const CLIENT_BACK_TO_OPENED_COMPLAINTS = 53;

}

?>
