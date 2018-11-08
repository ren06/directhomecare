<?php
if ($this->index >= 0) {
    $day = "Date[$this->index][" . "Day]";
    $month = "Date[$this->index][" . "Month]";
    $year = "Date[$this->index][" . "Year]";
} else {
    $day = Yii::t('texts', 'DAY_SIMPLE');
    $month = Yii::t('texts', 'MONTH_SIMPLE');
    $year = Yii::t('texts', 'YEAR_SIMPLE');
}
?>
<?php
if (!$this->hideDay) {
    echo CHtml::dropDownList($day, $this->selectedDay, $this->days, $this->htmlOptions);
    ?>
    
    <?php
}
if (!$this->hideMonth) {
echo CHtml::dropDownList($month, $this->selectedMonth, $this->months, $this->htmlOptions);
    ?>
    
    <?php
}
echo CHtml::dropDownList($year, $this->selectedYear, $this->years, $this->htmlOptions); ?>


