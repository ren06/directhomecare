<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
    <div id="logoff">
        <?php
        //Log off button and menu visible only if authenticated
  
        if (isset(Yii::app()->user->roles) && Yii::app()->user->roles == 'testAdmin') {
            
            echo CHtml::link('Main menu', Yii::app()->createUrl('test/test/index'));
            echo ' ';
            echo CHtml::button(Yii::t('texts', 'Log out'), array('class' => 'rc-button-small', 'submit' => array('test/adminLogout')));
        } 
        ?>

        <?php
        ?>

    </div>
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>