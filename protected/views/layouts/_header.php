<?php
$userId = Yii::app()->user->id;
?>

<aside class="left-off-canvas-menu">
    <ul class="off-canvas-list">
        <li><label class="first rc-small-caps">direct <i class="fi-heart" style="color:#ce0069"></i> homecare</label></li>
        <?php
        // Show general links or not
        if (Yii::app()->user->isGuest) {
            $showMenu = true;
        } else {
            $wizardCompleted = Yii::app()->user->wizard_completed;
            if (Yii::app()->user->roles == Constants::USER_CARER) {
                $showMenu = ($wizardCompleted != Wizard::CARER_LAST_STEP_INDEX);
            } else {
                // $showMenu = ($wizardCompleted != Wizard2::CLIENT_LAST_STEP_INDEX);
                //by RC to show client menu even if no wizzard (as per process of job posting.
                $showMenu = false;
            }
        }
        if ($showMenu) {
            // no link HOME if on Homepage
            if (Yii::app()->controller->action->id != 'index' && Yii::app()->controller->action->id != 'home') {
                ?>
                <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/">HOME</a></li>
                <?php
            }
            ?>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/about">ABOUT</a></li>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/questions">QUESTIONS</a></li>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/contact">CONTACT</a></li>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/carer-jobs">JOBS</a></li>
            <?php
        } else { // Show menu Carer/Client
            $role = Yii::app()->user->roles;

            if (($role == Constants::USER_CARER)) {
                ?>
                <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MY_CLIENTS') . Message::getUnreadMessageCountText($role, $userId), array('carer/myClients'), array('id' => 'myclients')); ?></li>
                <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MY_MISSIONS'), array('carer/myMissions'), array('id' => 'mymissions')); ?></li>
                <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MONEY'), array('carer/money'), array('id' => 'money')); ?></li>
                <li><?php echo CHtml::link('▽ ' . Yii::app()->user->getState('full_name'), array('carer/maintainProfile'), array('id' => 'myinformation')); ?></li>
                <?php
            } elseif (($role == Constants::USER_CLIENT)) { 
                ?>               
                <li><?php echo CHtml::link(Yii::t('texts', 'POST A JOB'), array('client/newBooking'), array('id' => 'postajob')); ?></li>
                <li><?php echo CHtml::link(Yii::t('texts', 'MY CARERS') . Message::getUnreadMessageCountText($role, $userId), array('client/myCarers'), array('id' => 'mycarers')); ?></li>
                <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MY_BOOKINGS'), array('clientManageBookings/MyBookings'), array('id' => 'mybookings')); ?></li>
                <li><?php echo CHtml::link('▽ ' . Yii::t('texts', Yii::app()->user->getState('full_name')), array('clientMaintain/maintainDetails'), array('id' => 'myinformation', 'class' => 'rc-menu-lastbutton')); ?></li>
                <?php
            }
        }
        // LOG IN/OUT BUTTON
        if (Yii::app()->user->isGuest == true) {
            ?>
            <li class="has-form"><a href="#" data-reveal-id="myModal" class="small button">SIGN IN</a></li>
            <?php
        } else {
            ?>
            <li class="divider"></li>
            <li class="has-form"><?php echo CHtml::link(Yii::t('texts', 'BUTTON_LOG_OUT'), array('/site/logout'), array('name' => 'log_out_button')); ?></li>
            <?php
        }
        ?>
    </ul>
</aside>
<nav class="tab-bar show-for-small">
    <a class="left-off-canvas-toggle menu-icon">
        <span>direct&#160;homecare</span>
    </a>
</nav>
<nav class="top-bar hide-for-small" data-topbar >
    <ul class="title-area">
        <li class="name">
            <?php
            if (Yii::app()->user->isGuest == true) {
                $action = '/';
            } else {
                $action = '/home';
            }
            ?>
            <h1><a href="<?php echo Yii::app()->request->baseUrl . $action; ?>">direct <i class="fi-heart" style="color:#ce0069"></i> homecare</a></h1>
        </li>
    </ul>
    <section class="top-bar-section">
        <ul class="right">
            <?php
            // Show general links or not
            if (Yii::app()->user->isGuest) {
                $showMenu = true;
            } else {
                $wizardCompleted = Yii::app()->user->wizard_completed;
                if (Yii::app()->user->roles == Constants::USER_CARER) {
                    $showMenu = ($wizardCompleted != Wizard::CARER_LAST_STEP_INDEX);
                } else {
                    $showMenu = false;
                }
            }
            if ($showMenu) {
                // no link HOME if on Homepage
                if (Yii::app()->controller->action->id != 'index' && Yii::app()->controller->action->id != 'home') {
                    ?>
                    <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/">HOME</a></li>
                    <?php
                }
                ?>
                <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/about">ABOUT</a></li>
                <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/questions">QUESTIONS</a></li>
                <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/contact">CONTACT</a></li>
                <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/carer-jobs">JOBS</a></li>
                <?php
            } else { // Show menu Carer/Client
                $role = Yii::app()->user->roles;
                // $wizardCompleted = Yii::app()->user->wizard_completed;
                if (($role == Constants::USER_CARER)) {
                    ?>
                    <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MY_CLIENTS') . Message::getUnreadMessageCountText($role, $userId), array('carer/myClients'), array('id' => 'myclients')); ?></li>
                    <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MY_MISSIONS'), array('carer/myMissions'), array('id' => 'mymissions')); ?></li>
                    <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MONEY'), array('carer/money'), array('id' => 'money')); ?></li>
                    <li><?php echo CHtml::link('▽ ' . Yii::app()->user->getState('full_name'), array('carer/maintainProfile'), array('id' => 'myinformation')); ?></li>

                    <?php
                } elseif (($role == Constants::USER_CLIENT)) {
                    ?>
                    <li><?php echo CHtml::link(Yii::t('texts', 'Special Offers!'), array('client/specialOffers'), array('id' => 'specialOffers')); ?></li>
                    <li><?php echo CHtml::link(Yii::t('texts', 'POST A JOB'), array('client/newBooking'), array('id' => 'postajob')); ?></li>
                    <li><?php echo CHtml::link(Yii::t('texts', 'MY CARERS') . Message::getUnreadMessageCountText($role, $userId), array('client/myCarers'), array('id' => 'mycarers')); ?></li>
                    <li><?php echo CHtml::link(Yii::t('texts', 'LINK_MY_BOOKINGS'), array('clientManageBookings/MyBookings'), array('id' => 'mybookings')); ?></li>
                    <li><?php echo CHtml::link('▽ ' . Yii::t('texts', Yii::app()->user->getState('full_name')), array('clientMaintain/maintainDetails'), array('id' => 'myinformation', 'class' => 'rc-menu-lastbutton')); ?></li>
                    <?php
                }
            }
            // LOG IN/OUT BUTTON
            if (Yii::app()->user->isGuest == true) {
                ?>
                <li class="has-form"><a href="#" data-reveal-id="myModal" class="small button">SIGN IN</a></li>
                <?php
            } else {
                ?>
                <li class="divider"></li>
                <li class="has-form"><?php echo CHtml::link(Yii::t('texts', 'BUTTON_LOG_OUT'), array('/site/logout'), array('name' => 'log_out_button')); ?></li>
                <?php
            }
            ?>
        </ul>
    </section>
</nav>
<?php
if (Yii::app()->params['test']['setTime'] == true) {
    echo '<div style="display:absolute;top:0;right:0;">' . Calendar::convert_DBDateTime_DisplayDateText(Calendar::today(Calendar::FORMAT_DBDATETIME)) . ' ' . Calendar::convert_DBDateTime_DisplayTime(Calendar::today(Calendar::FORMAT_DBDATETIME)) . '</div>';
}

// Sign in form: leave out of menus because used by both menus
?>
<div id="myModal" class="reveal-modal small" data-reveal>
    <div class="row">
        <div class="large-12 medium-12 small-12 columns">
            <h4>Sign in to Direct Homecare</h4>
            <br>
            <br>
            <?php
            $model = new UserLoginForm();
            $this->renderPartial('/site/_userLogin', array('model' => $model, 'dialog' => true));
            ?>
        </div>
    </div>
    <a class="close-reveal-modal">&#215;</a>
</div>
