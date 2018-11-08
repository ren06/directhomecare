<?php
$carerId = $carer->id;

//if ($view == Constants::CLIENT_CARERS_VIEW_MY_CARERS) {
//    $url = Yii::app()->createUrl('clientMaintain/setCarerRelation');
//} else {
$url = Yii::app()->createUrl('clientNewBooking/setCarerRelation');
//}

if ($notWanted) {

    $blacklistOptionStyle = 'display:none';
    $unBlacklistOptionsStyle = 'display:visible';
    $favouriteOptionsStyle = 'display:none';
    $unfavouriteOptionsStyle = 'display:none';
} else {

    $blacklistOptionStyle = 'display:visible';
    $unBlacklistOptionsStyle = 'display:none';

    if ($favourite) {

        $favouriteOptionsStyle = 'display:none';
        $unfavouriteOptionsStyle = 'display:visible';
        $blacklistOptionStyle = 'display:none'; //Added by RC
    } else {

        $favouriteOptionsStyle = 'display:visible';
        $unfavouriteOptionsStyle = 'display:none';
    }

    //add hidden field for POST
    //echo CHtml::hiddenField('selected_carers_' . $carerId, $carerId);
}

if ($i % $lineBreak === 0) {
    echo '</tr>';
    echo '<tr>';
}

if ($view == Constants::CARER_PROFILE_VIEW_SELECT_TEAM) {
//checked
    $checked = in_array($carerId, Session::getSelectedCarers());
    $attributeName = 'selected_carers_' . $carerId;

    if ($checked) {
        echo '<td class="rc-fb-cell-selected">';
    } else {
        echo '<td class="rc-fb-cell">';
    }

    echo '<div style="text-align:center;">';
    echo CHtml::checkBox($attributeName, $checked, array('class' => 'selectCarerChecxbox'));
    echo CHtml::label(Yii::t('texts', 'LINK_SELECTED'), $attributeName, array('class' => 'rc-link')) . '<br /><br />';
    echo '</div>';
} else {
    echo '<td class="rc-fb-cell">';
}

//echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_FAVOURITE_LIST'), $url, array(
//    'type' => 'POST',
//    'beforeSend' => "function(request){ $('#ajaxLoaderSearch_$carerId').show(); return true}",
//    'success' => "function(data){ refreshCarers(data); }",
//    'data' => array(
//        'carerId' => $carer->id,
//        'relation' => ClientCarerRelation::RELATION_FAVOURITE,
//        'clientId' => $clientId,
//        'view' => $view,
//        'filters' => "js:$('#$formId').serialize()")
//        ), array(//htmlOptions
//    'href' => $url,
//    'name' => "favourite$carerId" . uniqid(),
//    'style' => $favouriteOptionsStyle,
//    'live' => false,
//    'class' => 'rc-link'
//        )
//);
//echo '<img class="rc-fb-icon-favourite" style="' . $unfavouriteOptionsStyle . '" title="' . Yii::t('texts', 'ALT_FAVOURITE_CARERS') . '" src="' . Yii::app()->request->baseUrl . '/images/icon-favourite.png"/>' . CHtml::ajaxLink(Yii::t('texts', 'BUTTON_UNFAVOURITE_LIST'), $url, array(
//    'type' => 'POST',
//    'beforeSend' => "function(request){ $('#ajaxLoaderSearch_$carerId').show(); return true}",
//    'success' => "function(data){ refreshCarers(data); }",
//    'data' => array(
//        'carerId' => $carer->id,
//        'relation' => ClientCarerRelation::RELATION_NONE,
//        'clientId' => $clientId,
//        'view' => $view,
//        'filters' => "js:$('#$formId').serialize()")
//        ), array(//htmlOptions
//    'href' => $url,
//    'name' => "unfavourite$carerId" . uniqid(),
//    'style' => $unfavouriteOptionsStyle,
//    'live' => false,
//    'class' => 'rc-link'
//        )
//);


if ($this->id == 'carerAdminController') {
    echo ' | ';
    echo CHtml::ajaxLink("I can have this carer", $url, array(
        'type' => 'POST',
        'beforeSend' => "function(request){ $('#ajaxLoaderSearch_$carerId').show(); return true}",
        'success' => "function(data){ refreshCarers(data); }",
        'data' => array(
            'carerId' => $carer->id,
            'relation' => ClientCarerRelation::RELATION_SELECTED,
            'clientId' => $clientId,
            'view' => $view,
            'filters' => "js:$('#$formId').serialize()")
            ), array(//htmlOptions
        'href' => $url,
        'name' => "selected$carerId" . uniqid(),
        'live' => false,
        //'style' => $normalOptionsStyle,
        'class' => 'rc-link'
            )
    );
}
//echo '<span style="' . $blacklistOptionStyle . '"> | </span>';
//echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_BLACK_LIST'), $url, array(
//    'type' => 'POST',
//    'beforeSend' => "function(request){ $('#ajaxLoaderSearch_$carerId').show(); return true}",
//    'success' => "function(data){ refreshCarers(data); }",
//    'data' => array(
//        'carerId' => $carer->id,
//        'relation' => ClientCarerRelation::RELATION_NOT_WANTED,
//        'clientId' => $clientId,
//        'view' => $view,
//        'filters' => "js:$('#$formId').serialize()")
//        ), array(//htmlOptions
//    'href' => $url,
//    'name' => "notwanted$carerId" . uniqid(),
//    'style' => $blacklistOptionStyle,
//    'live' => false,
//    'class' => 'rc-link'
//        )
//);
//echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_UN-BLACK_LIST'), $url, array(
//    'type' => 'POST',
//    'beforeSend' => "function(request){ $('#ajaxLoaderSearch_$carerId').show(); return true}",
//    'success' => "function(data){ refreshCarers(data); }",
//    'data' => array('carerId' => $carer->id,
//        'relation' => ClientCarerRelation::RELATION_NONE,
//        'clientId' => $clientId,
//        'view' => $view,
//        'filters' => "js:$('#$formId').serialize()")
//        ), array(//htmlOptions
//    'href' => $url, 'name' => "put_back$carerId" . uniqid(),
//    'style' => $unBlacklistOptionsStyle,
//    'live' => false,
//    'class' => 'rc-link'
//        )
//);
?>
<img class="rc-fb-small-loader" id="ajaxLoaderSearch_<?php echo $carerId ?>" style="display:none"  alt="Loader" src="<?php echo Yii::app()->request->baseUrl . '/images/ajax-loader.gif'; ?>"/>
<?php


$params = array('carer' => $carer, 'carerProfileType' => $carerProfileType, 'view' => $view);
if (isset($clientId)) {

    $params['clientId'] = $clientId;
}

echo $this->renderPartial('application.views.carer.profile._carerProfileDetails', $params);
echo '</td>';
?>