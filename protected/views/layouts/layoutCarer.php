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
$this->endContent();
?>