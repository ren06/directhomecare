<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
        ));
?>

<h2>Shift</h2>
<?php echo $mission->displayMissionAdmin(); ?>

<?php
//$shortListedCarersIds = $mission->booking->getShortListedCarersIds();
?>
<br />
<br />
<hr>
<h2>Applying carers</h2>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'live-in-mission-carers-grid',
    'dataProvider' => $dataProvider,
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'CDataColumn',
            'value' => '$data->id',
            'header' => 'Id',
            'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'width:60px'),
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->id_mission',
            'header' => 'Mission',
            'type' => 'raw',
            'headerHtmlOptions' => array('style' => 'width:60px'),
        ),
//        array(
//            'class' => 'CLinkColumn',
//            'labelExpression' => '$data->id_applying_carer',
//            'urlExpression' => 'array("carerAdmin/view/id/".$data->id_applying_carer)',
//            'header' => 'Carer',
//        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {

                $carer = $data->applyingCarer;

                $params = array('carer' => $carer, 'carerProfileType' => 'long', 'view' => Constants::CARER_PROFILE_VIEW_ADMIN);

                $profileHtml = Yii::app()->controller->renderPartial('application.views.carer.profile._carerProfileDetails', $params, true, false);

                $text = $carer->fullName . ' (' . $carer->id . ')';
                $url = Yii::app()->controller->createUrl("carerAdmin/view/id/" . $data->id_applying_carer);

                $html = '<a rel="tooltip" href="' . $url . '" title=\'
                        <span>' . $profileHtml . '</span>
                        \'>' . $text .
                        '</a>';
                echo $html;
            },
            'header' => 'Carer',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => '$data->getStatusLabel()',
            'header' => 'Status',
            'type' => 'raw',
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {

                $carerId = $data->id_applying_carer;
                $mission = $data->mission;
                if ($mission->isShortListed($carerId)) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            },
            'header' => 'Short-listed?',
            'type' => 'raw',
        ),
        array(
            'class' => 'ButtonColumn', // can be omitted, default
            'header' => 'Application',
            'template' => '{select}',
            'headerHtmlOptions' => array('style' => 'width:150px'),
            'buttons' => array(
                'select' => array(
                    //'label' => 'Set to selected', 
                    'label' => 'Set to Assigned',
                    'url' => function($data, $row) {

                        if ($_GET['scenario'] == MissionCarersController::SCENARIO_CARER_SELECTION) {

                            //return Yii::app()->createUrl("admin/missionCarers/setCarerSelected", array("missionId" => $data->id_mission, "carerId" => $data->id_applying_carer));
                            return Yii::app()->createUrl("admin/missionCarers/setCarerAssigned", array("missionId" => $data->id_mission, "carerId" => $data->id_applying_carer));
                        } else {
                            //return Yii::app()->createUrl("admin/missionCarers/changeCarerSelected", array("missionId" => $data->id_mission, "carerId" => $data->id_applying_carer));
                            return Yii::app()->createUrl("admin/missionCarers/assignOtherCarer", array("missionId" => $data->id_mission, "carerId" => $data->id_applying_carer));
                        }
                    },
                    //'url' => 'Yii::app()->createUrl("admin/missionCarers/setCarerSelected", array("liveInMissionId"=>$data->id_live_in_mission, "carerId" => $data->id_applying_carer))',
                    'options' => array('class' => 'applyButton rc-linkbutton-small', 'title' => 'Click to apply to this shift.'),
                    'visible' => '$data->status == MissionCarers::APPLIED',
                ),
//                'selected' => array(
//                    'label' => 'Selected', //Text label of the button.
//                    'url' => '',
//                    'visible' => '$data->status == missionCarers::SELECTED',
//                )
            ),
        ),
        array(
            'class' => 'CDataColumn',
            'value' => function($data, $row) {

                if ($data->status == MissionCarers::SELECTED) {

                    $res = Calendar::elapsedTime_FromNow($data->modified, true);
                    return $res;
                }
            },
            'header' => 'When selected',
            'type' => 'raw',
        ),
    )
        )
);
?>

<?php $this->endWidget(); ?>

<script>
    $(function() {
        $.widget("ui.tooltip", $.ui.tooltip, {
            options: {
                content: function() {
                    return $(this).prop('title');
                }
            }
        });

        $('[rel=tooltip]').tooltip();
    });
</script>
