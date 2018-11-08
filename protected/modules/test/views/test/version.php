<p>
Yii version <? echo Yii::getVersion() ?>
</p>
JQuery version: <span id="version"></span>

<script type="text/javascript">
    $(document).ready(function() {  
        var version = $().jquery;

        $('#version').html(version);
    });
</script>

<?php echo phpinfo() ?>