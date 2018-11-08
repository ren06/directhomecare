<?php
$this->pageTitle = Yii::t('texts', 'HEADER_INDENTIFICATION');
$this->pageSubTitle = 'We keep your information safe !';
$this->keyWords = 'user login';
$this->description = 'User login.';
?>

<div class="row">
    <div class="columns large-6 medium-6 small-12">
        <?php
        $this->renderPartial('_userLogin', array('model' => $model, 'dialog' => false));
        ?>
    </div>
</div>
