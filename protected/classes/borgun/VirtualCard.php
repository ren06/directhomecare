<?php

class VirtualCardRequest {

    public $MerchantContractNumber;
    public $PAN;

    public function GetXML() {
        return '<?xml version="1.0" encoding="utf-8"?><getVirtualCard><MerchantContractNumber>' . $this->MerchantContractNumber . '</MerchantContractNumber><PAN>' . $this->PAN . '</PAN></ getVirtualCard >';
    }

}

class VirtualCardResponse {

    public $Status;
    public $PAN;

    public function __construct($xml) {
        $this->Status = new ResultStatus((string) $xml->Status->ResultCode, (string) $xml->Status->ResultText, (string) $xml->Status->ErrorMessage);
        $this->PAN = (string) $xml->VirtualCard;
    }

}

?>