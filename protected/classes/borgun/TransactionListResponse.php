<?php

class TransactionListResponse {

    public $version;
    public $processor;
    public $merchantID;
    //public $terminalID;
    public $actionCode;
    public $transactions;

    public function __construct($array) {
        $this->version = (string) $array['Version'];
        $this->processor = (string) $array['Processor'];
        $this->merchantID = (string) $array['MerchantID'];
        //$this->terminalID = (string)$xml['TerminalId'];
        $this->actionCode = (string) $array['ActionCode'];

        $this->transactions = array();
        $transactions = $array['Transaction'];

        foreach ($transactions as $trans) {
            $this->transactions[] = new TransactionListTransaction($trans);
        }
    }

}

?>
