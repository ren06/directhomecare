<?php

// $type = $quote->type;

if ($quote->type == Constants::LIVE_IN) {
    $this->renderPartial('/quoteLiveIn/quoteLiveInResultMain', array('quoteLiveIn' => $quote, 'short' => $short));
} else {
    $this->renderPartial('/quoteHourly/_quoteHourlyResult', array('quote' => $quote));
}

?>