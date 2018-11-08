<?php
$sql = "SELECT * FROM tbl_rating_article WHERE page_name = '$this->pageTitle'";
$result = Yii::app()->db->createCommand($sql)->queryRow();
$starNumber = 5;

if ($result != false) {
    $numberEntries = $result['number_entries'];
    $average = $result['average'];
    $value = round($average);
} else {
    $numberEntries = 0;
    $average = 0;
    $value = round($starNumber / 2);
}
?>
<!--<br /> byRC: the schema.org way to keep and replace.
<div itemscope="itemscope" itemprop="breadcrumb" itemtype="http://schema.org/WebPage">
    <a class="rc-link" rel="home" href="<?php // echo Yii::app()->request->baseUrl;    ?>"></a> >
    <a class="rc-link" href="<?php // echo Yii::app()->request->baseUrl;    ?>/glossary.html"></a> >
    <a class="rc-link" href="<?php // echo Yii::app()->request->requestUri;    ?>"></a>
</div>-->
<!--<p>
    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a class="rc-link" href="<?php echo Yii::app()->request->baseUrl; ?>/" itemprop="url">
            <span itemprop="title">HOME CARE</span>
        </a> > 
    </span>
    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a class="rc-link" href="<?php echo Yii::app()->request->baseUrl; ?>/glossary.html" itemprop="url">
            <span itemprop="title">GLOSSARY</span>
        </a>
        > 
    </span>-->
<!--    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a class="rc-link" href="<?php // echo Yii::app()->request->requestUri; ?>" itemprop="url">
            <span itemprop="title"><?php // echo strtoupper($this->pageTitle); ?></span>
        </a>
    </span>-->
<!--</p>-->

<!--<div itemscope="itemscope" itemtype="http://schema.org/Product">
    <span itemprop="name" style="display:inline-block"><?php // echo $this->pageTitle;   ?></span>:
    <span style="display:inline-block">
<?php
//        $this->widget('CStarRating', array(
//            'name' => 'rating',
//            'starCount' => $starNumber,
//            'minRating' => 1,
//            'maxRating' => $starNumber,
//            'value' => $value,
//            'allowEmpty' => false, //remove cancel button
//            'callback' => '
//                    function(){
//                    $.ajax({
//                        type: "POST",
//                        dataType: "json",
//                        url: "' . Yii::app()->createUrl('site/rating') . '",
//                        data: "page_name=' . $this->pageTitle . '&rate=" + $(this).val(),
//                        success: function(data){
//                            var entries = data.entries;
//                            var average = data.average;
//                            $("#averageValue").html(average);
//                            $("#numberEntries").html(entries);
//                    }})}'
//        ));
//        
?>
    </span>
    <span itemscope="itemscope" itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating">
        Rated <span itemprop="ratingValue"><span id="averageValue"><?php //echo $average;   ?></span></span>/<?php //echo $starNumber;   ?>
        based on <span itemprop="ratingCount"><span id="numberEntries"><?php // echo $numberEntries;   ?></span></span> customer ratings.
    </span>
</div>
<br />-->
<div itemscope="itemscope" itemtype="http://schema.org/Article">
    <p>
        <span itemprop="name" style="display:inline-block"><?php echo $this->pageTitle; ?></span><br />
        Written by <span itemscope="itemscope" itemprop="author" itemtype="http://schema.org/Person"><span itemprop="name"><a href="https://plus.google.com/+JuliaMorganDirect?rel=author" target="_blank" class="rc-link">Julia Morgan</a></span>, <span itemprop="jobTitle">Advisor</span></span> on <span itemprop="dateCreated">Oct 23, 2013</span><br />
    </p>
    <!-- the end </div> must be placed at the end of the article on the view -->