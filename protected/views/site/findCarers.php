<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_FIND_CARERS'); ?>

<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_WHICH_CARERS_DO_YOU_LIKE'); ?></h2>
<div class="rc-container-60" style="margin-top:0;">
<!--    <p class="notes">-->
    <?php // echo Yii::t('texts', 'NOTES_SELECTION_OF_CARERS_YOU_CAN_ADJUST'); ?>
    <!--    </p>-->
    <?php
    $form = $this->beginWidget('CActiveForm', array('id' => 'find-carers-form',
        'enableAjaxValidation' => false,
        'method' => 'get',
        //'focus' => array($client, 'firstName'),
        //'stateful' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>

    <div  style="float:left; width:33.3%" id ="carers_criteria">
        <?php
        $this->renderPartial('_findCarersCriteria', array('carers' => $carers, 'criteria' => $criteria));
        ?>
    </div>
    <!--    <div style="width:2%" ></div>-->

    <div style="float:right; width:65%" id ="carers_results">
        <?php
        if (Yii::app()->user->hasFlash('success')):
            echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
        endif;
        if (Yii::app()->user->hasFlash('error')):
            echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
        endif;

        $this->renderPartial('_findCarersResults', array('carers' => $carers));
        ?>
    </div>

    <div style="clear:both;">
    </div>
</div>
<?php $this->endWidget() ?>
<script type="text/javascript">
    $(document).on("click", ".selectCarerChecxbox", function() {
        var thisCheck = $(this);
        if (thisCheck.is(':checked')) {
            $(this).closest('.rc-fb2-profile').toggleClass('rc-fb2-profile rc-fb2-profile-selected');
        }
        else {
            $(this).closest('.rc-fb2-profile-selected').toggleClass('rc-fb2-profile-selected rc-fb2-profile');
        }
    });
</script>