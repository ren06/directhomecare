<div id="regularly">
    <div id ="regularlyCriteria">
        <?php
        $this->renderPartial('/quoteHourly/_quoteHourlyCriteriaRegularly', array('quote' => $quote));
        ?>
    </div>
    <div id ="regularlyResult">
        <?php
        if ($quote->showResult) {

            $this->renderPartial('/quoteHourly/quoteHourlyResultMain', array('quote' => $quote));
        }
        ?>
    </div>
</div>