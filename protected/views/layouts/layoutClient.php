<?php $this->beginContent('//layouts/main'); ?>

<header>
    <div class="row">
        <div class="large-12 columns">
            <h1><?php echo $this->pageTitle; ?></h1>
            <h4><?php echo $this->pageSubTitle; ?></h4>
        </div>
    </div>
</header>
<br>
<br>
<?php echo $content; ?>
<br>
<br>
<?php
if (Yii::app()->params['test']['showChat'] == true) {
    ?>
    <!-- Zopim Live Chat Script -->
    <script type="text/javascript">
        window.$zopim || (function(d, s) {
            var z = $zopim = function(c) {
                z._.push(c)
            }, $ = z.s =
                    d.createElement(s), e = d.getElementsByTagName(s)[0];
            z.set = function(o) {
                z.set.
                        _.push(o)
            };
            z._ = [];
            z.set._ = [];
            $.async = !0;
            $.setAttribute('charset', 'utf-8');
            $.src = '//v2.zopim.com/?WXw9EBZgfc51fKvOiH3nxDAZm3mC0iHU';
            z.t = +new Date;
            $.
                    type = 'text/javascript';
            e.parentNode.insertBefore($, e)
        })(document, 'script');
    </script>
    <!-- /Zopim Live Chat Script -->
    <?php
}
$this->endContent();
?>