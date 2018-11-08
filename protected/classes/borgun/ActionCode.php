<?php

class ActionCodeResponse {

    public $status;
    public $actionCode;
    public $explanation;
    public $explanationIsl;
    public $explanationShort;
    public $explanationShortIsl;

    public function __construct($xml) {
        $this->status = new ResultStatus((string) $xml->Status->ResultCode, (string) $xml->Status->ResultText, (string) $xml->Status->ErrorMessage);
        $this->actionCode = (string) $xml->ActionCodeTexts->ActionCode;
        $this->explanation = (string) $xml->ActionCodeTexts->Explanation;
        $this->explanationIsl = (string) $xml->ActionCodeTexts->ExplanationIsl;
        $this->explanationShort = (string) $xml->ActionCodeTexts->ShortExplanation;
        $this->explanationShortIsl = (string) $xml->ActionCodeTexts->ShortExplanationIsl;
    }

}

?>