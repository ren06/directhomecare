<div class="rc-ad-container">
    <?php
    $imageUrl = Yii::app()->request->baseUrl . '/images/homecare-banner.gif';
    $image = CHtml::image($imageUrl, 'Home care', array('class' => 'rc-ad', 'alt' => 'Carer', 'height' => '90', 'width' => '728', 'title' => Yii::app()->name));
    echo CHtml::link($image, array('site/index'));
    ?>
</div>


