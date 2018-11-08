
<?php if (Yii::app()->params['test']['showSpec'] == true) { ?>
<b>Choose shifts table</b><br />
Display shifts if: no carers are ASSIGNED AND shift is ACTIVE<br />
<b>Status</b><br />
No status<br />
<b>Buttons</b><br />
if end-user is APPLIED: "Cancel application"<br />
elseif end-user is VERIFIED: "Apply"<br />
else: "Complete profile"<br />
<?php } ?>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_CHOOSE_MISSION') ?>

<table class="rc-carer-price-table">
    <tr>
        <td><h2 class="rc-h2red" style="padding-top:0;"><?php echo Yii::t('texts', 'NOTE_WORK_SELF_EMPLOYED_AND_RECEIVE') ;?></h2></td>
        <td><?php echo Yii::t('texts', 'LABEL_LIVE_IN_CARE_UPPER') . '<br />' ;?><span class="rc-quote-price"><?php echo Prices::getPriceDisplay(Constants::USER_CARER, Prices::LIVE_IN_DAILY_PRICE, NULL); ?></span><span class="rc-quote-price-per"><?php echo '&#160;' . Yii::t('texts', 'LABEL_PER_DAY');?></span></td>
        <td><?php echo Yii::t('texts', 'LABEL_HOURLY_CARE_UPPER') . '<br />' ;?><span class="rc-quote-price"><?php echo Prices::getPriceDisplay(Constants::USER_CARER, Prices::HOURLY_PRICE, NULL); ?></span><span class="rc-quote-price-per"><?php echo '&#160;' . Yii::t('texts', 'LABEL_PER_HOUR');?></span></td>
    </tr>
</table>

<p class="rc-note">
    <?php //echo Yii::t('texts', 'NOTE_APPLY_TO_MISSIONS_HERE', array('{newMissionAdvertisedTimeInHours}' => BusinessRules::getNewMissionAdvertisedTimeInHours())); ?>
</p>

<?php if(Yii::app()->user->hasFlash('notice')):?>
<div class="flash-notice">
<?php echo Yii::app()->user->getFlash('notice')?>
</div>
<?php endif ?>

<h3>Direct Homecare has changed.</h3>

<br>

All you have to do now is to wait for customers to contact you. You will see your messages <?php echo CHtml::link('here', array('carer/myClients'), array()) ?>.
<br><br>
You can then discuss with your clients and once you have agreed on a shift the client will book you.
<br><br>
<?php
//if (count($dataProvider->getData()) > 0) {
//
//    //show table
//    //$dataProvider->setPagination(false);
//    $this->renderPartial('_missionsTable', array('id' => Tables::AVAILABLE_MISSIONS_GRID, 'dataProvider' => $dataProvider, 'columns' => Tables::getCarerAvailableMissionsColumnConfig($carerActive)));
//} else {
//    echo '<br /><br />';
//    echo '<b>' . Yii::t('texts', 'TABLES_NO_MISSIONS') . '</b>';
//    echo '<br /><br />';
//}
////show Google Maps
//UIServices::showMissionsMap($dataProvider->getData(), Carer::loadModel(Yii::app()->user->id)->address);



?>

<div id="hook" style="display:none">
    <div id="dialog"></div>
</div>

<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/dialogYesTable.js');
?>

<script type="text/javascript">    
    
    $('body').on('click', '.applyButton', function(){
  
        var url = $(this).attr('href');//give id

        $.ajax({
            success: function(html){
                //$('#<?php echo Tables::AVAILABLE_MISSIONS_GRID ?>').replaceWith(html);
                $(this).replaceWith(html);
            },
            context: this,
            type: 'post', url: url ,
            cache: false, dataType: 'html'
        });
        
        return false;
            
    });

    //popup shown
    $('body').on('click', '.cancelAppliedButton', function(){
  
        var url = $(this).attr('href');//give id

        $.ajax({
            success: function(html){
                $('#dialog').html(html);
                $("#dialog").dialog("open");
            },
            context: this,
            type: 'post', url: url ,
            cache: false, dataType: 'html'
        });
        
        return false;
            
    });

</script>
