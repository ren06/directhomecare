<?php
if (isset($complaint)) {
    $posts = $complaint->complaintPosts;

    foreach ($posts as $post) {
        $this->renderPartial('/complaint/_complaintPost', array('post' => $post, 'user' => $user));
    }
}
?>
