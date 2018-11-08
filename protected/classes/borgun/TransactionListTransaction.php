<?php

class TransactionListTransaction {

    public $transactionType;
    public $transactionNumber;
    public $batchNumber;
    public $transactionDate;
    public $PAN;
    public $RRN;
    public $authorizationCode;
    public $actionCode;
    public $trAmount;
    public $trCurrency;
    public $cardType;
    public $voided;
    public $status; 

    public function __construct($xml) {

        $this->transactionType = (string) $xml->TransactionType;
        $this->transactionNumber = (string) $xml->TransactionNumber;
        $this->batchNumber = (string) $xml->BatchNumber;
        $this->transactionDate = (string) $xml->TransactionDate;
        $this->PAN = (string) $xml->PAN;
        $this->RRN = (string) $xml->RRN;
        $this->authorizationCode = (string) $xml->AuthorizationCode;
        $this->actionCode = (string) $xml->ActionCode;
        $this->trAmount = (string) $xml->TrAmount;
        $this->trCurrency = (string) $xml->TrCurrency;
        //$this->cardType = (string)$xml['CardType'];
        $this->voided = (string) $xml->Voided;
        $this->status = (string) $xml->Status;
    }

    public function getTransactionTypeLabel() {
        
    }

    public function getTransactionNumberLabel() {
        
    }

    public function getBatchNumberLabel() {
        
    }

    public function getTransactionDateLabel() {
        
    }

    public function getPANLabel() {
        
    }

    public function getRRNLabel() {
        
    }

    public function getAuthorizationCodeLabel() {
        
    }

    public function getActionCodeLabel() {
        
    }

    public function getTrAmountLabel() {
        
    }

    public function getTrCurrencyLabel() {
        
    }

    public function getCardTypeLabel() {
        
    }

    public function getVoidedLabel() {
        
    }

    public function getStatusLabel() {
        
    }

}

?>
