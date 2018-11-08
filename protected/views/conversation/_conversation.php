<?php
$lastMessage = $conversation->getLastMessage($viewer);

if ($lastMessage != null) {

    $result = '';

    if ($viewer == Constants::USER_CLIENT) {
        $firstName = $conversation->carer->first_name;
        $controller = 'client';
    } else {
        $firstName = $conversation->client->first_name;
        $controller = 'carer';
    }
    ?>

    <div class="row" style="border:1px #666 solid">
        <div class="large-2 medium-6 small-6 columns">
            <?php
            //show the photo of the carer to client
            if ($viewer == Constants::USER_CLIENT) {
                $photo = $conversation->carer->getPhotoForClient();
                if (isset($photo)) {
                    echo $photo->showImageForGuest('rc-image-profile-small');
                } else {
                    echo '<img src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/svgs/fi-torso.svg">';
                }
            } else {
                //show blank photo to the carer (no picture of client but better than nothing)
                echo '<img src="' . Yii::app()->request->hostInfo . Yii::app()->request->baseUrl . '/images/svgs/fi-torso.svg">';
            }
            ?>
        </div>
        <div class="large-3 medium-6 small-6 columns">
            <br>
            <b><?php echo $firstName; ?></b> <!-- Conv <?php echo $conversation->id ?> !-->
            <br>
            <?php echo $lastMessage->getDateTime(); ?>
        </div>
        <div class="large-3 medium-6 small-6 columns">
            <br>
            <?php echo $lastMessage->message; ?>
        </div>
        <div class="large-2 medium-3 small-3 columns">
            <br>
            <?php
            echo CHtml::button('VIEW / REPLY', array('submit' => array("$controller/conversation/id/" . $conversation->id), 'class' => 'button tiny expand'));

            if ($viewer == Constants::USER_CLIENT) {
                echo '<br>';
                echo CHtml::button(Yii::t('texts', 'BUTTON_BOOK'), array('submit' => array('client/bookCarer/id/' . $conversation->carer->id), 'class' => 'button tiny expand'));
            }

            //show 'New booking' if viewer is carer and message type is booking
            if ($lastMessage->type == Message::TYPE_BOOKING && $viewer == Constants::USER_CARER && $lastMessage->is_read == false) {
                echo '<b>New booking !</b>';
            }
            ?>
        </div>
        <div class="large-2 medium-3 small-3 columns">
            <br>
            <p>
                <?php
                echo ' ' . $conversation->getUnReadMessageeCountText($viewer);
                //show exclamation icon if the message comes from admin
                if ($lastMessage->author == Constants::USER_ADMIN && $lastMessage->is_read == false) {
                    echo " !";
                }
                ?>
            </p>
        </div>
    </div>
    <br>
    <br>

<?php
} //if last message not null ?>