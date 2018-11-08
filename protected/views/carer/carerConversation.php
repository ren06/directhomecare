<style type="text/css">
    #myclients{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$carer = $conversation->carer;
$client = $conversation->client;

$this->pageTitle = 'Conversation with ' . $client->first_name;
$this->pageSubTitle = 'Respond to clients requests';
?>

<div class="row">
    <div class="large-6 medium-6 small-12 columns">
        <div class="flash-success" style="display:none">
        </div>
        <div class="flash-notice" style="display:none">
        </div>
        <?php
        echo '<span id="errorMessage" style="display:none">Message cannot be empty</span>';
        echo CHtml::textArea("newMessage", $newMessage->message, array('id' => 'newMessageId', 'style' => 'min-height:8em'));
        echo CHtml::ajaxButton('SEND MESSAGE', $this->createUrl('carer/sendMessage'), array(
            'beforeSend' => "function(xhr, opts) {            
            var text = $.trim($('#newMessageId').val());            
            if( text == '' ) {
                document.getElementById('errorMessage').style.display = 'block';
                xhr.abort();
            }
            else{
            document.getElementById('errorMessage').style.display = 'hidden';
            }
        }",
            'success' => "function(html) {    
                            $('#conversation').prepend(html);
                            $('#newMessageId').val('');
                                                   
                        }",
            'type' => 'POST',
            'data' => array('newMessage' => "js:$('#newMessageId').val()", 'conversationId' => $conversation->id),
            'error' => 'function(data) {
                        }',
                ), array('class' => 'button tiny', 'id' => 'sendMessage')
        );
        echo '<hr/>';
        echo '<br>';
        ?>
        <div id="conversation">
            <?php
            foreach ($conversation->messages as $message) {
                $this->renderPartial('/conversation/_message', array('message' => $message, 'viewer' => Constants::USER_CARER, 'carer' => $carer, 'client' => $client));
                echo '<br>';
            }
            ?>
        </div>
    </div>
    <div class="large-6 medium-6 small-12 columns">
        <?php $this->renderPartial('/site/_carerProfileConversation', array('carer' => $carer, 'showButton' => false, 
            'isNewsletter' => false, 'viewer' => Constants::USER_CARER)); ?>
    </div>
</div>