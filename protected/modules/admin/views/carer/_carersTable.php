<style>
    .rc-image-profile-small {
        height:auto !important;
        border:1px solid #666;
        width:5em;
    }
</style>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'carer-grid',
    'dataProvider' => $dataProvider,
    'filter' => $model,
    'columns' => array(
        'id',
        array(
            'header' => 'Name',
            'value' => function($data, $row) {
        return $data->first_name . '&#160;' . $data->last_name;
    },
            'type' => 'raw',
        ),
        array(
            'header' => 'Photo',
            'value' => function($data, $row) {
        $photo = $data->getPhotoForClient();
        if (isset($photo)) {
            return $photo->showImageForClient('rc-image-profile-small');
            ;
        }
    },
            'type' => 'raw',
        ),
        'email_address',
        array(
            'header' => 'Age',
            'value' => function($data, $row) {
        return $data->getAge();
    },
            'type' => 'raw',
        ),
        array(
            'header' => 'Address',
            'value' => function($data, $row) {
        if (isset($data->address)) {
            return $data->address->display();
        }
    },
            'type' => 'raw',
        ),
        array(
            'header' => 'Gender',
            'value' => function($data, $row) {
        return $data->getGenderLabel();
    },
            'type' => 'raw',
        ),
        'nationality',
        'mobile_phone',
        array(
            'header' => 'Hourly work',
            'value' => function($data, $row) {
        return $data->getHourlyWorkText();
    },
            'type' => 'raw',
        ),
        array(
            'header' => 'Live-In work',
            'value' => function($data, $row) {
        return $data->getLiveInWorkText();
    },
            'type' => 'raw',
        ),
//        array(
//            'header' => 'With male',
//            'value' => function($data, $row) {
//        return $data->getWorkWithMaleText();
//    },
//            'type' => 'raw',
//        ),
//        array(
//            'header' => 'With female',
//            'value' => function($data, $row) {
//        return $data->getWorkWithFemaleText();
//    },
//            'type' => 'raw',
//        ),
        //'car_owner',
        array(
            'header' => 'Active',
            'value' => function($data, $row) {
        return ($data->active ? 'true' : 'false');
    },
            'type' => 'raw',
        ),
        array(
            'header' => 'Diplomas',
            'value' => function($data, $row) {
        $diplomas = $data->profileDisplayAllDocuments();
        echo '<ul>';
        foreach ($diplomas as $diploma) {

            echo '<li>' . $diploma . '</li>';
        }
        echo '</ul>';
    },
            'type' => 'raw',
        ),
        array(
            'header' => 'Deact.',
            'value' => function($data, $row) {
        return ($data->deactivated ? 'true' : 'false');
    },
            'type' => 'raw',
        ),
        array(
            'class' => 'ButtonColumn', // can be omitted, default
            'header' => Yii::t('texts', 'TABLES_HEADER_OPTIONS'),
            'headerHtmlOptions' => array('style' => 'width:144px'),
            'template' => '{select}',
            'buttons' => array(
                'select' => array(
                    'label' => 'Select',
                    'url' => 'Yii::app()->createUrl("/admin/carerAdmin/viewCarer", array("id"=>$data->id))',
                    'options' => array('class' => 'applyButton rc-linkbutton-small'),
                ),
            ),
        ),
    ),
));
?>