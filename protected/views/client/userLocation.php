<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'Details of the booking');
$this->pageSubTitle = 'Everything the carer needs to know';

$form = $this->beginWidget('CActiveForm', array('id' => 'user-location-form',
    'enableAjaxValidation' => false,
    'focus' => array($client, 'ServiceUser[0][first_name]'),
    'action' => 'registration', //by default current action in URL
    'stateful' => true,
        ));
?>

<div class="row">
    <div class="large-6 medium-8 small-12 columns">

        <?php
        if (Yii::app()->params['test']['showPopulateData'] == true) {
            echo '<span>' . CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populateUserLocation')) . '</span>';
            echo '<br><br>';
        }
        ?> 
        <h4><?php echo Yii::t('texts', 'Who is the service for ?'); ?></h4>
        <?php
        if (Yii::app()->user->hasFlash('success')):
            echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
        endif;
        if (Yii::app()->user->hasFlash('error')):
            echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
        endif
        ?>

        <div id="service_users">
            <?php
//            for ($i = 0; $i < count($serviceUsers); $i++):
//                $this->renderPartial('_serviceUser', array(
//                    'serviceUser' => $serviceUsers[$i],
//                    'conditionErrors' => '',
//                    'index' => $i,
//                    'count' => count($serviceUsers),
//                    'form' => $form,
//                ));
//            endfor
            $this->renderPartial('_serviceUser', array(
                'serviceUser' => $serviceUsers[0],
                'conditionErrors' => '',
                'index' => 0,
                'count' => 1,
                'form' => $form,
            ));
            ?>
        </div>
        <h4><?php echo Yii::t('texts', 'The carer should provide'); ?></h4>
        <?php
        $job = $client->getLastJob();

        if (isset($job)) {
            echo $form->error($job, 'formActivities', array('class' => 'rc-error'));
            $activitiesValues = $job->formActivities;
        } else {
            $activitiesValues = array();
        }
        $activities = Condition::getConditions(Condition::TYPE_ACTIVITY);

        $i = 0;
        foreach ($activities as &$activity) {
            $i++;
            ?>
            <div>
                <?php
                $attributeName = 'activities_condition_';
                $activityId = $activity->id;
                $attributeName = $attributeName . $activityId;

                if (in_array($activityId, $activitiesValues)) {
                    $value = true;
                } else {
                    $value = false;
                }
                echo CHtml::checkBox($attributeName, $value, array('id' => $attributeName, 'value' => $activityId, 'onClick' => ''));
                $text = Condition::getText($activity->name);
                $tooltip = Condition::getTextTooltip($activity->name);
                $value = UIServices::renderTooltip($text, $tooltip, '', true);
                echo CHtml::label($value, $attributeName);
                ?>
            </div>
            <?php
        }
        ?>
        <h4><?php echo Yii::t('texts', 'Address to be visited'); ?></h4>
        <?php //        for ($i = 0; $i < count($serviceLocations); $i++): ?>
        <?php
//            $this->renderPartial('_serviceLocation', array(
//                'client' => $client,
//                'serviceLocation' => $serviceLocations[$i],
//                'index' => $i,
//                'count' => 1, //count($serviceLocations),
//                'form' => $form,
//                'disableCityPostcode' => false,
//            ));
//
//        endfor
        $this->renderPartial('_serviceLocation', array(
            'client' => $client,
            'serviceLocation' => $serviceLocations[0],
            'index' => $i,
            'count' => 1, //count($serviceLocations),
            'form' => $form,
            'disableCityPostcode' => false,
        ));
        ?>
        <script type="text/javascript">
            $(".service_location-add").click(function() {
                $.ajax({
                    success: function(html) {
                        $(".service_locations").append(html);
                    },
                    type: 'get',
                    url: '<?php echo $this->createUrl('addServiceLocation') ?>',
                    data: {
                        index: <?php echo -1 //echo count($carerDocuments)                              ?>
                    },
                    cache: false,
                    dataType: 'html'
                });
            });
        </script>
        <div class="row">
            <div class="large-6 medium-6 small-12 columns">
                <?php
                echo CHtml::button(Yii::t('texts', 'BUTTON_BACK'), array('submit' => array(Wizard2::getActiveStepName(), 'nav' => 'back'), 'class' => 'button expand',));
                ?>
            </div>
            <div class="large-6 medium-6 small-12 columns">
                <?php
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('submit' => array(Wizard2::getActiveStepName(), 'nav' => 'next'), 'class' => 'button expand alert'));
                ?>
            </div>
            <?php $this->renderPartial('/common/_ajaxLoader'); ?> 
        </div>
        <?php
        // $client = Session::getClient();
        // $this->renderPartial('/client/_sideCart', array('client' => $client));
        ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
    //When page loads set focus on Postcode field
    $(document).ready(function() {

        $('#ServiceUser_0_first_name').focus();
    });

    $(document).on("click", ".checkbox-service-user", function() {

        var isChecked = $(this).is(":checked") ? 1 : 0;
        var index = $(this).val();

        $.ajax({
            url: '<?php echo CHtml::normalizeUrl('selectServiceUser') ?>',
            type: 'get',
            data: {checkBoxValue: isChecked, index: index}
        });
    });

    $(document).on("click", ".radio-location", function() {

        var isChecked = $(this).is(":checked") ? 1 : 0;
        var index = $(this).val();

        $.ajax({
            url: '<?php echo CHtml::normalizeUrl('selectLocation') ?>',
            type: 'get',
            data: {checkBoxValue: isChecked, index: index}
        });
    });
</script>