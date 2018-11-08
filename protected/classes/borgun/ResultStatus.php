<?php

class ResultStatus {

    public $resultCode;
    public $resultText;
    public $errorMessage;

    public function __construct($iResultCode, $iResultText, $iErrorMessage) {
        $this->resultCode = $iResultCode;
        $this->resultText = $iResultText;
        $this->errorMessage = $iErrorMessage;
    }

}

?>