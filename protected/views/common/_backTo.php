<?php

switch ($scenario) {

    //care
    case NavigationScenario::CARER_BACK_TO_CURRENT_MISSIONS:
        $text = 'BUTTON_BACK_TO_CURRENT_MISSIONS';
        $url = 'carer/myMissions';
        break;

    case NavigationScenario::CARER_BACK_TO_OPENED_COMLAINTS:
        $text = 'BUTTON_BACK_TO_OPENED_COMPLAINTS';
        $url = 'carer/missionsComplaint';
        break;

    case NavigationScenario::CARER_BACK_TO_MISSIONS_VERIFIED;
        $text = 'BUTTON_BACK_TO_MISSIONS_BEING_VERIFIED';
        $url = 'carer/missionsVerifying';
        break;

    case NavigationScenario::CARER_BACK_TO_MISSIONS_HISTORY;
        $text = 'BUTTON_BACK_TO_MISSIONS_HISTORY';
        $url = 'carer/missionsHistory';
        break;

    case NavigationScenario::CARER_BACK_TO_MONEY;
        $text = 'BUTTON_BACK_TO_MONEY';
        $url = 'carer/money';
        break;

    
    //client
    case NavigationScenario::CLIENT_BACK_TO_BOOKINGS;
        $text = 'BUTTON_BACK_TO_BOOKINGS';
        $url = 'clientManageBookings/myBookings';
        break;
    case NavigationScenario::CLIENT_BACK_TO_VISITS;
        $text = 'BUTTON_BACK_TO_VISITS';
        $url = 'clientManageBookings/carerVisits';
        break;
    case NavigationScenario::CLIENT_BACK_TO_PAYMENTS;
        $text = 'BUTTON_BACK_TO_PAYMENTS';
        $url = 'clientManageBookings/transactionsHistory';
        break;
    
    case NavigationScenario::CLIENT_BACK_TO_OPENED_COMPLAINTS;
        $text = 'BUTTON_BACK_TO_OPENED_COMPLAINTS';
        $url = 'clientManageBookings/carerVisitsComplaint';
        break;
     
}

echo CHtml::link(Yii::t('texts', $text), $this->createAbsoluteUrl($url), array('class' => 'rc-linkbutton'));
?>
