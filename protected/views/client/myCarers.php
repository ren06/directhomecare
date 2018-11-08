<style type="text/css">
    #mycarers{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = 'Your carers';
$this->pageSubTitle = 'They have responded to your job posts';
?>

<div class="row" style="min-height:15em">
    <div class="large-12 medium-12 small-12 columns">
        <?php        
        $conversationCount = 0;
        foreach ($client->conversations as $conversation) {
            if ($conversation->isVisibleForClient()) {
                $this->renderPartial('/conversation/_conversation', array('conversation' => $conversation, 'viewer' => Constants::USER_CLIENT));
                $conversationCount++;
            }
        }     
        if($conversationCount == 0){
            echo 'Your Job has been posted. Wait up to one week for carers to get back to you.';
        }
        ?>
    </div>
</div>