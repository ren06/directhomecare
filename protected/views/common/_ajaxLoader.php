<?php if (!isset($html) || $html) { ?>

    <div class="loading" style="display:none">
        <?php
        if (isset($text)) {

            echo '<span id="loadingText" style="display:none" >' . $text . '</span>';
        }
        ?>
        <img alt="Loader" src="<?php echo Yii::app()->request->baseUrl . "/images/ajax-loader.gif"; //RC: no height and width for IE8 carer-jobs page otherwise space bellow Get Job button     ?>"/>
    </div>

    <?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/submitButton.js');
    ?>
    <?php
}
?>
<?php
if (!isset($javascript) || $javascript) {
    ?>

    <script type="text/javascript">

        //TODO: better event subscription when page loads first time

        $(document).ready(function() {

            $("input[type=submit]").click(function() {

                $(this).closest('.rc-container-button').find('.buttons').hide();
                $(this).closest('.rc-container-button').find('.loading').show();

                //special case for payment button
                if (this.id === 'payButton') {
                    $("#loadingText").show();
                }
                //alert('submit clicked');
            });

            $(".ajaxLoaderButton").click(function() {

                $(this).closest('.rc-container-button').find('.buttons').hide();
                $(this).closest('.rc-container-button').find('.loading').show();

                //special case for payment button
                if (this.id === 'payButton') {
                    $("#loadingText").show();
                }

    //                /alert('ajaxLoaderButton clicked');
            });
        })
    </script>

    <?php
}
?>