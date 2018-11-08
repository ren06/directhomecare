<?php

class BatchRequest {

    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $dateAndTime;

    public function GetXML() {
        $xml = '<?xml version="1.0" encoding="utf-8"?><newBatch>';
        $xml .= "<Version>$this->version</Version>";
        $xml .= "<Processor>$this->processor</Processor>";
        $xml .= "<MerchantID>$this->merchantID</MerchantID>";
        if (!IsNullOrEmptyString($this->terminalID))
            $xml .= "<TerminalID>$this->terminalID</TerminalID>";
        if (!IsNullOrEmptyString($this->dateAndTime))
            $xml .= "<DateAndTime>$this->dateAndTime</DateAndTime>";
        $xml .= "</newBatch>";
        return $xml;
    }

}

class BatchResponse {

    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $dateAndTime;
    public $newBatch;
    public $oldBatch;
    public $cardAccTerminal;
    public $actionCode;
    public $storeTerminal;

    public function __construct($xml) {
        $this->version = (string) $xml->Version;
        $this->processor = (string) $xml->Processor;
        $this->merchantID = (string) $xml->MerchantID;
        $this->terminalID = (string) $xml->TerminalID;
        $this->dateAndTime = (string) $xml->DateAndTime;
        $this->newBatch = (string) $xml->NewBatch;
        $this->oldBatch = (string) $xml->OldBatch;
        $this->cardAccTerminal = (string) $xml->CardAccTerminal;
        $this->actionCode = (string) $xml->ActionCode;
        $this->storeTerminal = (string) $xml->StoreTerminal;
    }

}

?>