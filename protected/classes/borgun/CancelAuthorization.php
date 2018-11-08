<?php

class CancelAuthRequest {

    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $transType;
    public $trAmount;
    public $trCurrency;
    public $dateAndTime;
    public $PAN;
    public $track1;
    public $track2;
    public $RRN;
    public $authCode;

    public function GetXML() {
        $xml = '<?xml version="1.0" encoding="utf-8"?><cancelAuthorization>';
        $xml .= "<Version>$this->version</Version>";
        $xml .= "<Processor>$this->processor</Processor>";
        $xml .= "<MerchantID>$this->merchantID</MerchantID>";
        if (!IsNullOrEmptyString($this->terminalID))
            $xml .= "<TerminalID>$this->terminalID</TerminalID>";
        $xml .= "<TransType>$this->transType</TransType>";
        $xml .= "<TrAmount>$this->trAmount</TrAmount>";
        $xml .= "<TrCurrency>$this->trCurrency</TrCurrency>";
        $xml .= "<DateAndTime>$this->dateAndTime</DateAndTime>";
        if (!IsNullOrEmptyString($this->PAN))
            $xml .= "<PAN>$this->PAN</PAN>";
        if (!IsNullOrEmptyString($this->track1))
            $xml .= "<Track1>$this->track1</Track1>";
        if (!IsNullOrEmptyString($this->track2))
            $xml .= "<Track2>$this->track2</Track2>";
        $xml .= "<RRN>$this->RRN</RRN>";
        if (!IsNullOrEmptyString($this->authCode))
            $xml .= "<AuthCode>$this->authCode</AuthCode>";
        $xml .= "</cancelAuthorization>";
        return $xml;
    }

}

class CancelAuthResponse {

    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $transType;
    public $trAmount;
    public $trCurrency;
    public $dateAndTime;
    public $PAN;
    public $RRN;
    public $transaction;
    public $batch;
    public $cardAccId;
    public $cardAccTerminal;
    public $cardAccName;
    public $authCode;
    public $actionCode;
    public $storeTerminal;
    public $cardType;
    public $message;

    /*
     * Initialize CancelAuthResponse object with XML from Heimir
     */

    public function __construct($xml) {
        $this->version = (string) $xml->Version;
        $this->processor = (string) $xml->Processor;
        $this->merchantID = (string) $xml->MerchantID;
        $this->terminalID = (string) $xml->TerminalID;
        $this->transType = (string) $xml->TransType;
        $this->trAmount = (string) $xml->TrAmount;
        $this->trCurrency = (string) $xml->TrCurrency;
        $this->dateAndTime = (string) $xml->DateAndTime;
        $this->PAN = (string) $xml->PAN;
        $this->RRN = (string) $xml->RRN;
        $this->transaction = (string) $xml->Transaction;
        $this->batch = (string) $xml->Batch;
        $this->cardAccId = (string) $xml->CardAccId;
        $this->cardAccTerminal = (string) $xml->CardAccTerminal;
        $this->cardAccName = (string) $xml->CardAccName;
        $this->authCode = (string) $xml->AuthCode;
        $this->actionCode = (string) $xml->ActionCode;
        $this->storeTerminal = (string) $xml->StoreTerminal;
        $this->cardType = (string) $xml->CardType;
        $this->message = (string) $xml->Message;
    }

}

?>