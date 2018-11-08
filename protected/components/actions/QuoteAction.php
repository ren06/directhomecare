<?php

class QuoteAction extends CAction {

    const NAME = 'quote';

     public function run() {
                  
        //GO TO NEW LOGIC 
        $this->getController()->redirect(array('site/index')); 
         
        $view = Wizard::VIEW_QUOTE;

        Wizard::handleClientSecurity($view);

        Wizard::setStepActive($view);

        $quoteLiveIn = Session::getQuote(Constants::TAB_LIVEIN);
        $quoteHourlyOneDay = Session::getQuote(Constants::TAB_HOURLY_ONE);
        $quoteHourlyFourteenDays = Session::getQuote(Constants::TAB_HOURLY_FOURTEEN);
        $quoteHourlyRegularly = Session::getQuote(Constants::TAB_HOURLY_REGULARLY);

        if (isset($_GET['quoteType'])) { //coming from homepage
            $quoteType = $_GET['quoteType'];

            Session::setSelectedQuoteType($quoteType);

            //default tab
            if ($quoteType == Constants::HOURLY) {

                $tab = Session::getSelectedTab();

                if (!isset($tab)) {

                    Session::setSelectedTab(Constants::TAB_HOURLY_ONE);
                }
            } else {
                Session::setSelectedTab(Constants::TAB_LIVEIN);
            }
        }

        $selectedTab = Session::getSelectedTab();
        
        //logic relevant when first time or user refreshes the page.
        switch ($selectedTab) {

            case Constants::TAB_LIVEIN:
                $quoteLiveIn->validateQuote();
                $err = $quoteLiveIn->errors;
                break;

            case Constants::TAB_HOURLY_ONE:
                $quoteHourlyOneDay->validateQuote();
                $err = $quoteHourlyOneDay->errors;
                break;

            case Constants::TAB_HOURLY_FOURTEEN:
                $quoteHourlyFourteenDays->validateQuote();
                $err = $quoteHourlyFourteenDays->errors;
                break;

            case Constants::TAB_HOURLY_REGULARLY:
                $quoteHourlyRegularly->validateQuote();
                $err = $quoteHourlyRegularly->errors;             
                break;
        }

        //handle navigation and save the live in request
        if (isset($_GET['navigation']) || (isset($_GET['toView']) && $_GET['toView'] != $view)) {

            //handle navigation
            if (isset($_GET['navigation'])) {

                if ($_GET['navigation'] == 'next') {

                    $nextStep = Wizard::getNextStep($view);
                    Yii::log('Session Id: ' . Yii::app()->session->sessionID . ': "Next" button clicked', CLogger::LEVEL_INFO, 'quote');
                }
            } elseif (isset($_GET['toView'])) {

                $nextStep = $_GET['toView'];
            }

            Wizard::setStepCompleted($view);

            //redirect to next screen
            $this->getController()->redirect(array($nextStep));
        }

        $this->getController()->render('/clientRegistration/quoteMain', array('quoteLiveIn' => $quoteLiveIn, 'quoteHourlyOneDay' => $quoteHourlyOneDay,
            'quoteHourlyFourteenDays' => $quoteHourlyFourteenDays, 'quoteHourlyRegularly' => $quoteHourlyRegularly));
    }

}

?>
