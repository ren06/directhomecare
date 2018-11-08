<?php
$onChangeJs = "js: refreshList(this, true);";

$carersCount = count($carers);

$maxDisplayCarers = Session::getSelectCarersMaxDisplay();

//hidden field used to pass the carer value
echo CHtml::hiddenField('carer', '', array('id' => 'selectedCarer'));

if ($carersCount == 0) {
    echo '<p><b>' . Yii::t('texts', 'NOTE_SORRY_BUT_THERE_ARE_NOAVAILABLE_CARERS') . '</b></p>';
} else {
    $displayedCarerNo = 0;
    foreach ($carers as $carer) {
        if ($displayedCarerNo == $maxDisplayCarers) {
            break;
        }

        $this->renderPartial('_findCarersResultCarerProfile', array('carer' => $carer, 'showButton' => true, 'isNewsletter' => false));
        $displayedCarerNo++;
    }
    ?>
    <p style="margin-top:1em">
        <?php
        if ($carersCount > $maxDisplayCarers) {
            echo "Showing $maxDisplayCarers of $carersCount carers.";
        } else {
            echo "Showing $carersCount of $carersCount carers.";
        }
        ?>
    </p>
    <?php
    if (count($carers) > $maxDisplayCarers) {
        echo '<div class="rc-container-button-leftallign">';
        echo CHtml::button('SHOW MORE CARERS', array('onClick' => $onChangeJs, 'class' => 'rc-button'));
        $loaderImageUrl = Yii::app()->request->baseUrl . '/images/ajax-loader.gif';
        $loaderHtml = "<img id='showMoreLoader' class='rc-fb-small-loader' style='display:none;padding-left:4px;' alt='Loader' src='$loaderImageUrl'/>";
        echo $loaderHtml;
        echo '</div>';
    }
}
?>

<?php
$sessionId = Yii::app()->session->sessionID;

//Show email box if user has not given his email already and is a guest
if (!ClientProspect::isSessionIdExists($sessionId) && Yii::app()->user->isGuest) {
    ?>

    <div id='leave_email'>
        <p>
            Haven't found the ideal carer? Leave us your email and we will contact you when we have new carers covering <b><?php echo Session::getPostCode() ?></b>.
        </p>
        <br />
        Your email <?php echo CHtml::textField("email_prospect", '', array('class' => 'rc-field', 'id' => 'email_prospect')); ?> 
        <?php
        echo ' ' . CHtml::ajaxButton(Yii::t('texts', 'SEND ME PROFILES'), $this->createUrl('site/storeProspectEmail'), array(
            'beforeSend' => 'function() { $("#error_message").css("visibility", "hidden"); }',
            'success' => 'function(data) {
                            
                 if(data == "error"){
                
                     $("#error_message").css("visibility", "visible");
                 }
                 else{
                     $("#leave_email").html("<p>Thank you, we will contact you as soon as we have new carers.</p>");
                    
                  }                        
        }',
            'type' => 'POST',
            'data' => array('email_prospect' => "js:$('#email_prospect').val()", 'post_code' => Session::getPostCode()),
                ), array('class' => 'rc-button-small', 'name' => 'submit_prospect_email')
        );
        ?>

    </div>

    <div id='error_message' class='error' style='visibility:hidden;color:#C36'>
        Please enter a valid email.
    </div>


<?php } ?>


