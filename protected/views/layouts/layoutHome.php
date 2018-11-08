<?php $this->beginContent('//layouts/main');

echo $content;

if (Yii::app()->params['test']['showChat'] == true) {
    ?>
    <!--Start of Zopim Live Chat Script-->
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
    <!--End of Zopim Live Chat Script-->
<?php }


$this->endContent();
?>