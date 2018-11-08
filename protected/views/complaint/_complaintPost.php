<?php
$role = Yii::app()->user->roles;
$author = $post->author;
$visibleBy = $post->visible_by;

//author can always see their message
if ($author == $role) {
    $visible = true;
} else {
    //author either admin or other party
    $visible = ($visibleBy == $role || $visibleBy == ComplaintPost::VISIBLE_BY_ALL);
}

if ($visible) {
    ?>
    <p>
        <?php
        if ($author == ComplaintPost::AUTHOR_ADMIN) {
            echo Yii::t('texts', 'LABEL_DIRECT_HAS_WRITEN') . '&#58;&#160;';
        } elseif ($author == ComplaintPost::AUTHOR_CLIENT) {
            echo Yii::t('texts', 'LABEL_CLIENT_HAS_WRITEN') . '&#58;&#160;';
        } elseif ($author == ComplaintPost::AUTHOR_CARER) {
            echo Yii::t('texts', 'LABEL_CARER_HAS_WRITEN') . '&#58;&#160;';
        }
        ?>
    </p>
    <div class="rc-container-chat">
        <?php echo $post->text; ?>
    </div>
    <?php
}
?>