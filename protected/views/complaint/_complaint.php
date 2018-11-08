<?php
$missionId = $mission->id;

if ($user == Constants::USER_CLIENT) {
    $div = "discussion_client";
} else {
    $div = "discussion_carer";
}
?>
<div id="<?php echo $div ?>" >
    <p>
        <?php
        if (Yii::app()->user->roles != $user) {
            if (isset($complaint)) {
                echo Complaint::getNote($mission, $complaint);
            }
        } else {
            echo Complaint::getNote($mission, $complaint);
        }
        ?>
    </p>
    <?php
    $this->renderPartial('/complaint/_complaintDiscussion', array('missionId' => $missionId, 'complaint' => $complaint, 'user' => $user));
    
    //check if at least one response visible
    if ($complaint == null) {
        $text = Yii::t('texts', 'BUTTON_OPEN_A_COMPLAINT');
    }
    elseif (isset($complaint) && $complaint->solved) {
        $text = Yii::t('texts', 'BUTTON_REOPEN_COMPLAINT');
    }
     elseif (isset($complaint) && $complaint->solved == false) {
        $text = Yii::t('texts', 'BUTTON_TYPE_AN_ANSWER');
    }

    $buttonVisible = Complaint::isButtonVisible($mission, $complaint);

    //if there is a complaint check there is at least one post visible by current viewer so they can respond
    if (Yii::app()->user->roles != $user) { //the bit at the top
        if (isset($complaint) && $complaint->solved == false) {
            $buttonVisible = $buttonVisible && $complaint->isOnePostVisibleFor(Yii::app()->user->roles);
        } else {
            $buttonVisible = false;
        }
    }

    if ($buttonVisible) {

        if (Yii::app()->user->roles == Constants::USER_CLIENT) {
            $url = $this->createUrl('clientManageBookings/addComplaintDialog');
        } else {
            $url = $this->createUrl('carer/addComplaintDialog');
        }

        echo '<br />' . CHtml::ajaxButton($text, $url, array(
            'beforeSend' => 'function() {}',
            'success' => 'function(html) { $("#dialog").html(html); }',
            'type' => 'POST',
            'data' => array('missionId' => $mission->id, 'user' => $user),
            'error' => 'function(data) {  }',), array('class' => 'rc-button', 'id' => 'addComplaint_' . $user)
        );
    }
    ?>
</div>