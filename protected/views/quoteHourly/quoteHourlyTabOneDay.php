<div id="oneDay">
    <div id ="oneDayCriteria">
        <?php
        $this->renderPartial('/quoteHourly/_quoteHourlyCriteriaOneDay', array('quote' => $quote));
        ?>
    </div>
    <div id ="oneDayResult">
        <?php
        if ($quote->showResult) {
            $this->renderPartial('/quoteHourly/quoteHourlyResultMain', array('quote' => $quote));
        }
        ?>
    </div>
</div>