<?php

class AuthorizationRequest {

    // Please see the Heimir Payment Gateway handbook for info on valid
    // values for the parameters.
    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $transType;
    public $trAmount;
    public $newAmount;
    public $trCurrency;
    public $dateAndTime;
    public $PAN;
    public $expDate;
    public $track1;
    public $track2;
    public $RRN;
    public $authCode;
    public $CVC2;
    public $securityLevelInd;
    public $UCAF;
    public $CAVV;
    public $XID;
    public $merchantName;
    public $merchantCity;
    public $merchantCountry;

    public function VerifyRequest() {
        return false;
    }

    /*
     * Convert AuthorizationRequest object to getAuthReq xml that Heimir understands.
     */

    public function GetXML() {
        $xml = '<?xml version="1.0" encoding="utf-8"?><getAuthorization>';
        $xml .= "<Version>$this->version</Version>";
        $xml .= "<Processor>$this->processor</Processor>";
        $xml .= "<MerchantID>$this->merchantID</MerchantID>";
        if (!IsNullOrEmptyString($this->terminalID))
            $xml .= "<TerminalID>$this->terminalID</TerminalID>";
        $xml .= "<TransType>$this->transType</TransType>";
        $xml .= "<TrAmount>$this->trAmount</TrAmount>";
        if ($this->transType == '4' || $this->transType == 4)
            $xml .= "<NewAmount>$this->newAmount</NewAmount>";
        $xml .= "<TrCurrency>$this->trCurrency</TrCurrency>";
        $xml .= "<DateAndTime>$this->dateAndTime</DateAndTime>";
        if (!IsNullOrEmptyString($this->PAN))
            $xml .= "<PAN>$this->PAN</PAN>";
        if (!IsNullOrEmptyString($this->expDate))
            $xml .= "<ExpDate>$this->expDate</ExpDate>";
        if (!IsNullOrEmptyString($this->track1))
            $xml .= "<Track1>$this->track1</Track1>";
        if (!IsNullOrEmptyString($this->track2))
            $xml .= "<Track2>$this->track2</Track2>";
        $xml .= "<RRN>$this->RRN</RRN>";
        if (!IsNullOrEmptyString($this->authCode))
            $xml .= "<AuthCode>$this->authCode</AuthCode>";
        if (!IsNullOrEmptyString($this->CVC2))
            $xml .= "<CVC2>$this->CVC2</CVC2>";
        if (!IsNullOrEmptyString($this->securityLevelInd))
            $xml .= "<SecurityLevelInd>$this->securityLevelInd</SecurityLevelInd>";
        if (!IsNullOrEmptyString($this->UCAF))
            $xml .= "<UCAF>$this->UCAF</UCAF>";
        if (!IsNullOrEmptyString($this->CAVV))
            $xml .= "<CAVV>$this->CAVV</CAVV>";
        if (!IsNullOrEmptyString($this->XID))
            $xml .= "<XID>$this->XID</XID>";
        if (!IsNullOrEmptyString($this->merchantName))
            $xml .= "<MerchantName>$this->merchantName</MerchantName>";
        if (!IsNullOrEmptyString($this->merchantCity))
            $xml .= "<MerchantCity>$this->merchantCity</MerchantCity>";
        if (!IsNullOrEmptyString($this->merchantCountry))
            $xml .= "<MerchantCountry>$this->merchantCountry</MerchantCountry>";
        $xml .= "</getAuthorization>";
        return $xml;
    }

}

class AuthorizationResponse {

    // Please see the Heimir Payment Gateway handbook for info on valid
    // values for the parameters.
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
     * Initialize AuthorizationResponse object with XML from Heimir
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