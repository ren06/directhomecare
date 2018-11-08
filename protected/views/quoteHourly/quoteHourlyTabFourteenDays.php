<div id="fourteenDays">
    <div id ="fourteenDayCriteria">
        <?php
        $this->renderPartial('/quoteHourly/_quoteHourlyCriteriaFourteenDays', array('quote' => $quote));
        ?>
    </div>
    <div id ="fourteenDayResult">
        <?php
        if ($quote->showResult) {

            $this->renderPartial('/quoteHourly/quoteHourlyResultMain', array('quote' => $quote));
        }
        ?>
    </div>
</div>