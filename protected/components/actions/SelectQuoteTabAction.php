<?php

class SelectQuoteTabAction extends CAction {

    const NAME = 'selectQuoteTab';

    public function run() {

        Yii::app()->clientScript->scriptMap['*.js'] = false;

        //get selected quote
        $quoteType = $_POST['quote_type'];

        Session::setSelectedQuoteType($quoteType);
        
        //update the selected quote
        $newCurrentQuote = Session::getQuote();
        if($newCurrentQuote->showResult){
            Session::setSelectedValidQuote($newCurrentQuote);
        }
    }

}

?>
