<?php

class ShowPriceAction extends CAction {

    const NAME = 'showPrice';

    function run() {

        if (isset($_POST['selectedTab'])) {
            $selectedTab = $_POST['selectedTab']; //always sure we get the right tab     
            Session::setSelectedTab($selectedTab);
        } else {
            $selectedTab = Session::getSelectedTab(); //used if the user refreshes the page
        }

        //read the right quote according to current tab
        switch ($selectedTab) {

            case Constants::TAB_HOURLY_ONE:
                $quote = ReadHttpRequest::readBookingHourlyOneDay();
                break;

            case Constants::TAB_HOURLY_FOURTEEN:
                $quote = ReadHttpRequest::readBookingHourlyFourteen();
                break;

            case Constants::TAB_HOURLY_REGULARLY:
                $quote = ReadHttpRequest::readBookingHourlyRegularly();
                break;

            case Constants::TAB_LIVEIN:
                $quote = ReadHttpRequest::readBookingLiveIn();
                break;
        }

        //common tasks to all quotes: set first time to false, validate and store
        $quote->firstTime = false;
        $quote->validateQuote();
       
        Session::setQuote($quote, $selectedTab);
        
        if($quote->showResult){
            Session::setSelectedValidQuote($quote);
        }

        //log quote for statistics
        Yii::log($quote->getQuoteInfo(), CLogger::LEVEL_INFO, 'quote');

        //render the right view
        switch ($selectedTab) {

            case Constants::TAB_HOURLY_ONE:

                $result = $this->getController()->renderPartial('/quoteHourly/quoteHourlyTabOneDay', array('quote' => $quote), true, true);
                 echo $result;
                break;

            case Constants::TAB_HOURLY_FOURTEEN:
                $result = $this->getController()->renderPartial('/quoteHourly/quoteHourlyTabFourteenDays', array('quote' => $quote), true, true);
                 echo $result;
                break;

            case Constants::TAB_HOURLY_REGULARLY:
                $result = $this->getController()->renderPartial('/quoteHourly/quoteHourlyTabRegularly', array('quote' => $quote), true, true);
                 echo $result;
                break;

            case Constants::TAB_LIVEIN:

                $this->getController()->render('/clientRegistration/quoteMain', array('quoteLiveIn' => $quote));

                
                break;
        }

       
    }

}

?>
