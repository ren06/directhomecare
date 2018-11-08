<?php

class TransactionListRequest {

    public $version;
    public $processor;
    public $merchantID;
    public $terminalID;
    public $batchNumber;
    public $fromDate;
    public $toDate;
    public $RRN;
 
    public function GetXML() {
        $xml = '<?xml version="1.0" encoding="utf-8"?><TransactionListRequest>';
        $xml .= "\n<Version>$this->version</Version>\n";
        $xml .= "<Processor>$this->processor</Processor>\n";
        $xml .= "<MerchantId>$this->merchantID</MerchantId>\n";
        if (!IsNullOrEmptyString($this->terminalID))
            $xml .= "<TerminalId>$this->terminalID</TerminalId>\n";
        if (!IsNullOrEmptyString($this->batchNumber))
            $xml .= "<BatchNumber>$this->batchNumber</BatchNumber>\n";
        if (!IsNullOrEmptyString($this->fromDate))
            $xml .= "<FromDate>$this->fromDate</FromDate>\n";
        if (!IsNullOrEmptyString($this->toDate))
            $xml .= "<ToDate>$this->toDate</ToDate>\n";
        if (!IsNullOrEmptyString($this->RRN))
            $xml .= "<RRN>$this->RRN</RRN>\n";
        $xml .= "</TransactionListRequest>";
        return $xml;
    }

}

?>
