<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
    <div id="logoff">
        <?php
        //Log off button
        $loginUrl = Yii::app()->user->loginUrl[0];

        if (isset(Yii::app()->user->roles) && Yii::app()->user->roles == 'admin') {

            echo CHtml::link('HOME', Yii::app()->createUrl('admin/admin/index'));
            echo ' | ';
            echo CHtml::link('APPROVE DOCUMENTS', Yii::app()->createUrl('admin/carerAdmin/approveDocumentsCarers/all/0'));
            echo ' | ';
            echo CHtml::button(Yii::t('texts', 'Log out'), array('class' => 'rc-button-small', 'submit' => array('admin/adminLogout')));
        } else {
            //current action must be login page                       
            $action = $this->action;
            $currentActionUrl = 'admin/' . $action->controller->id . '/' . $action->id;

            //assert($loginUrl == $currentActionUrl);
        }
        ?>


    </div>
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>