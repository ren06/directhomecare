<?
$LINE_BREAK = 3;

// $attributeName = 'nationality';
// echo CHtml::label('Nationality', $attributeName);

//echo CHtml::dropDownList($attributeName, $nationality, $nationalities, array(
//    'ajax' => array(
//        'beforeSend' => 'function(request){ $("#ajaxLoaderSearch").show(); return true}',
//        'type' => 'POST',
//        'url' => $url,
//        'success' => "function(data){ refreshCarers(data); }",
//        'data' => array('filters' => "js:$('#$formId').serialize()"),
//)));
?>

<?php
if ($showGenderSelection == true) {
    ?>

    <p>
        <?php
        $url = Yii::app()->createUrl('/clientNewBooking/carerSelectionRefresh');
        echo Yii::t('texts', 'LABEL_GENDER_OF_THE_CARER') . ':&#160;&#160;&#160;';
        $attributeNameShowMale = 'showMale';
        $checkBoxId = $attributeNameShowMale . '_' . uniqid();
        echo CHtml::CheckBox($attributeNameShowMale, $showMale, array(
            'id' => $checkBoxId,
            //'onClick' => " $('#$attributeName').attr('checked', false);",
            'ajax' => array(
                'beforeSend' => "function(request){        
                    $(this).attr('checked', !this.checked);
                    $('#ajaxLoaderSearch').show();            
                }",
                'type' => 'POST',
                'url' => $url,
                'success' => "function(data){ refreshCarers(data); }",
                'data' => array('filters' => "js:$('#$formId').serialize()", 'view' => $view),
            )
                )
        );

        echo CHtml::label('Male', $checkBoxId);
        echo Yii::app()->params['radioButtonSeparator'];
        $attributeNameShowFemale = 'showFemale';
        $checkBoxId = $attributeNameShowFemale . '_' . uniqid();
        echo CHtml::CheckBox($attributeNameShowFemale, $showFemale, array(
            'id' => $checkBoxId,
            'ajax' => array(
                'beforeSend' => "function(request){
                    $(this).attr('checked', !this.checked);
                    $('#ajaxLoaderSearch').show();
                    return true;
                }",
                'type' => 'POST',
                'url' => $url,
                'success' => "function(data){ refreshCarers(data); }",
                'data' => array('filters' => "js:$('#$formId').serialize()", 'view' => $view),
        )));

        echo CHtml::label('Female', $checkBoxId);
        ?>
        <img class="rc-fb-small-loader" id="ajaxLoaderSearch" style="display:none;padding-top:4px;"  alt="Loader" src="<?php echo Yii::app()->request->baseUrl . '/images/ajax-loader.gif'; ?>"/>
    </p>
    <?php
    echo CHtml::hiddenField('clientId', $clientId);
    echo CHtml::hiddenField('view', $view);
    echo CHtml::hiddenField('formId', $formId);
    echo CHtml::hiddenField('maxDisplayCarers', $maxDisplayCarers);
    //used for POSTING with NEXT button
    echo CHtml::hiddenField('showFemalePost', $showFemale);
    echo CHtml::hiddenField('showMalePost', $showMale);

// echo 'Results: ' . count($carers);
} // close of $showGenderSelection


if (count($carers) == 0 || count($carers) == count($carersNotWanted)) {
    echo '<p><b>' . Yii::t('texts', 'NOTE_SORRY_BUT_THERE_ARE_NOAVAILABLE_CARERS') . '</b></p>';
} else {
    ?>
    <table class="rc-fb-table">
        <tr>
            <?php
            $displayedCarerNo = 0;
            foreach ($carers as $carer) {

                if ($displayedCarerNo == $maxDisplayCarers && ($view == Constants::CARER_PROFILE_VIEW_SELECT_TEAM)) {

                    break;
                }

                $obj = $carer->getClientRelation($clientId);
//        if ($obj != null && ($obj->relation == ClientCarerRelation::RELATION_NOT_WANTED)) {
//            $carersNotWanted[] = $carer;
//        } else {

                if ($obj != null && ($obj->relation == ClientCarerRelation::RELATION_FAVOURITE)) {
                    $favourite = true;
                } else {
                    $favourite = false;
                }

                $this->renderPartial('application.views.carer.profile._carerProfileMenu', array('carer' => $carer, 'clientId' => $clientId,
                    'formId' => $formId, 'lineBreak' => $LINE_BREAK, 'i' => $displayedCarerNo, 'notWanted' => false, 'favourite' => $favourite,
                    'carerProfileType' => 'medium', 'view' => $view), false, false);
                $displayedCarerNo++;
                //}               
            }
            if ($displayedCarerNo % 3 == 1) {
                echo '<td class="rc-fb-cell-blank"></td><td class="rc-fb-cell-blank"></td>';
            } elseif ($displayedCarerNo % 3 == 2) {
                echo '<td class="rc-fb-cell-blank"></td>';
            }
            ?>
        </tr>
    </table>
<?php } ?>


<?php if (count($carersNotWanted) > 0) { ?>
    <h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_BLACK_LISTED_CARERS'); ?></h2>
    <p class="rc-note">
        <?php echo Yii::t('texts', 'NOTE_THESE_CARERS_WILL_NEVER_BE_ASSIGNED_TO_YOU'); ?>
    </p>
    <table class="rc-fb-table">
        <tr>
            <?php
            $i = 0;
            foreach ($carersNotWanted as $carer) {
                $carerProfileType = 'short';
                $this->renderPartial('application.views.carer.profile._carerProfileMenu', array('carer' => $carer, 'clientId' => $clientId,
                    'formId' => $formId, 'lineBreak' => $LINE_BREAK, 'i' => $i, 'notWanted' => true, 'favourite' => false,
                    'carerProfileType' => 'short', 'view' => $view), false, false);
                $i++;
            }
            if ($i % 3 == 1) {
                echo '<td class="rc-fb-cell-blank"></td><td class="rc-fb-cell-blank"></td>';
            } elseif ($i % 3 == 2) {
                echo '<td class="rc-fb-cell-blank"></td>';
            }
            ?>
        </tr>
    </table>

<?php } ?>

<?php
if ($view == Constants::CARER_PROFILE_VIEW_SELECT_TEAM) {

    $url = Yii::app()->createUrl('clientNewBooking/showMoreCarers');

    if (count($carers) > $maxDisplayCarers) {
        echo '<br />';
        echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_SHOW_MORE_CARERS'), $url, array(
            'type' => 'POST',
            'beforeSend' => "function(request){          
            $(this).attr('checked', !this.checked);
            $('#ajaxLoaderMoreCarers').show();            
            }",
            'success' => "function(data){ refreshCarers(data); }",
            'data' => array(
                'carerId' => $carer->id,
                'maxDisplayCarers' => $maxDisplayCarers,
                'clientId' => $clientId,
                'view' => $view,
                'filters' => "js:$('#$formId').serialize()")
                ), array(//htmlOptions
            'href' => $url,
            'name' => "showMoreCarers" . uniqid(),
            //'style' => $favouriteOptionsStyle,
            'live' => false,
            'class' => 'rc-linkbutton'
                )
        );
    } else {
        // echo Yii::t('texts', 'NOTE_NO_MORE_CARERS');
    }
    ?>
    <img class="rc-fb-small-loader" id="ajaxLoaderMoreCarers" style="display:none;padding-top:4px;"  alt="Loader" src="<?php echo Yii::app()->request->baseUrl . '/images/ajax-loader.gif'; ?>"/>
    <?php
}
?>
<script type="text/javascript">
    function refreshCarers(html) {
        $('#carerProfiles').html(html);
    }

//    $(document).ready(function() {
//        $('.selectCarerChecxbox').html(html);
//    });

    $('.selectCarerChecxbox').click(function() {

        var thisCheck = $(this);

        if (thisCheck.is(':checked')) {
            $(this).closest('.rc-fb-cell').toggleClass('rc-fb-cell rc-fb-cell-selected');
        }
        else {
            $(this).closest('.rc-fb-cell-selected').toggleClass('rc-fb-cell-selected rc-fb-cell');
        }
    });
</script>
