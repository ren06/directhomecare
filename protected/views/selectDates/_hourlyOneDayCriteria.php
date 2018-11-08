
<?php
if ($quote->hasErrors()) {
    $style = "display:visible";
    $error = CHtml::error($quote, "errorMessage");
} else {
    $style = "display:none";
    $error = '';
}
?>
<div class="flash-error" style="<?php echo $style ?>;margin-top:0;">
    <?php echo $error; ?>
</div>
<div class="row">
    <div class="large-4 medium-4 small-12 columns">
        DATE
        <br>
        <br>
    </div>
    <div class="large-4 medium-4 small-12 columns">
        START TIME
        <br>
        <br>
    </div>
    <div class="large-4 medium-4 small-12 columns">
        END TIME
        <br>
        <br>
    </div>
</div>
<div class="dayForms">
    <?php
    $models = $quote->dayForms;
    foreach ($models as $key => $model) {
        $this->renderPartial('/selectDates/_hourlyOneDay', array('dayForm' => $model, 'index' => $key));
    }
    Session::setSelectDatesMaxIndex(count($models) - 1);
    ?>
</div>
<br>
<br>
<?php
$url = $this->createUrl('site/addDay');

echo 'Do you need to book more than one visit ?&#160;&#160;&#160;&#160;';
echo CHtml::ajaxLink('&#160;&#160;ADD VISIT&#160;&#160;', $url, array('type' => 'GET',
    'success' => "function(result, status){
                   
                    var html = decodeURIComponent(escape(result['html']));
                    $('.dayForms').append(html);

                    var htmlPrice = decodeURIComponent(escape(result.htmlPrice));
                     $('#oneDayResult').html(htmlPrice);

                  }",
    //'data' => 'js:{index: $(".dayForms table").length}',
    'dataType' => 'json',
        ), array(
    'class' => 'rc-linkbutton-small'
));
?>
<br>
<br>
<hr />
<br>
<div class="row">
    <div class="large-6 medium-6 small-6 columns">
        TOTAL PRICE
    </div>
    <div class="large-6 medium-6 small-6 columns">
        <span id="oneDayResult">
            <?php $this->renderPartial('/selectDates/_hourlyResult', array('quote' => $quote)); ?>
        </span>
    </div>
</div>
<br>
<br>