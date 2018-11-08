<?php $this->beginContent('/layouts/main'); ?>


<div class="rc-container-screen">
    <div class="rc-container-main">
        <h2 class="rc-h2red"><?php echo $this->pageTitle ?></h2>
        <div class="rc-container-40">
            <?php echo $content; ?>
        </div>
    </div>
</div>
<?php
$this->keyWords = $this->pageTitle;
$this->description = $this->pageTitle;

$this->endContent();
?>
