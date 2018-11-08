<?php

class ProcessingResultsRequest {

    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $batch;

    public function GetXML() {
        $xml = '<?xml version="1.0" encoding="utf-8"?><RequestProcessingResults>';
        $xml .= "<Version>$this->version</Version>";
        $xml .= "<Processor>$this->processor</Processor>";
        $xml .= "<MerchantID>$this->merchantID</MerchantID>";
        if (!IsNullOrEmptyString($this->terminalID))
            $xml .= "<TerminalID>$this->terminalID</TerminalID>";
        $xml .= "<Batch>$this->batch</Batch>";
        $xml .= "</RequestProcessingResults>";
        return $xml;
    }

}

class ProcessingResultsResponse {

    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $actionCode;
    public $batch;
    public $transactions;

    public function __construct($xml) {
        $this->version = (string) $xml->Version;
        $this->processor = (string) $xml->Processor;
        $this->merchantID = (string) $xml->MerchantID;
        $this->terminalID = (string) $xml->TerminalID;
        $this->batch = (string) $xml->Batch;
        $this->actionCode = (string) $xml->ActionCode;

        $this->transactions = array();
        foreach ($xml->Transactions as $trans) {
            $this->transactions[] = new ProcessingResultsTrans($trans);
        }
    }

}

class ProcessingResultsTrans {

    public $transactionNumber;
    public $trAmount;
    public $trCurrency;
    public $dateAndTime;
    public $PAN;
    public $RRN;
    public $authCode;
    public $actionCode;
    public $cardType;

    public function __construct($xml) {
        $this->transactionAmount = (string) $xml->TransactionAmount;
        $this->trAmount = (string) $xml->TrAmount;
        $this->trCurrency = (string) $xml->TrCurrency;
        $this->dateAndTime = (string) $xml->DateAndTime;
        $this->PAN = (string) $xml->PAN;
        $this->RRN = (string) $xml->RRN;
        $this->authCode = (string) $xml->AuthCode;
        $this->actionCode = (string) $xml->ActionCode;
        $this->cardType = (string) $xml->CardType;
    }

}

?>