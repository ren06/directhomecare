<?php

$output = '<table class="rc-quote-control"><tr>
        <td class="rc-quote-cellbutton"></td>
        <td class="rc-quote-cellday">' . Yii::t('texts', 'LABEL_WEEK_DAY') . '</td>
        <td class="rc-quote-celltime">' . Yii::t('texts', 'LABEL_START_TIME') . '</td>
        <td class="rc-quote-celltime">' . Yii::t('texts', 'LABEL_END_TIME') . '</td>
    </tr>';
$dayForms = $quote->getDayForms();
$i = 0;

foreach ($dayForms as $dayForm) {
    $render = $this->renderPartial('/quoteHourly/_weekDay', array('model' => $dayForm, 'index' => $i), true);
    $output .= '<tr id =' . $i . '>' . $render . '</tr>';
    $i++;
}

$output .= '</table>';

if (count($dayForms) > 0) {
    echo $output;
}
?>