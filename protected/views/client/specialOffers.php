<style type="text/css">
    #specialOffers{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'Special offers!');
//$this->pageSubTitle = 'Refer your friends';
?>

<div class="row">
    <div class="large-12 medium-12 small-12 columns">
        <h3> Refer your friends!</h3>
        <br/>
        Each friend will get <b>2 hours for £<?php echo BusinessRules::getReferralAmount() ?></b>.
        <br /> <br />
        You will get a voucher of <b>£<?php echo BusinessRules::getRefereeAmount() ?></b> for each friend who completes a booking.
    </div>
</div>
<br/><br/>
<div class="row">

    <div class="large-6 medium-6 small-12 columns">

        <?php if (Yii::app()->user->hasFlash('error')): ?>
            <div class="rc-error">
                <?php echo Yii::app()->user->getFlash('error'); ?>
            </div>
            <br/>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
            <br/>
        <?php endif ?>

        <?php
        echo CHtml::form();
        echo CHtml::textField('email_referral', '', array('id' => 'email_referral', 'placeholder' => 'Enter email', 'style' => 'height:61px;font-size:16px;'));
        echo CHtml::submitButton(Yii::t('texts', 'SEND'), array('submit' => array('freeCarers'), 'class' => 'button expand alert'));
        echo CHtml::endForm();
        ?>
        <?php $this->renderPartial('/common/_ajaxLoader'); ?> 

        <h3>Your Referrals</h3>
        <br/>
        <?php
        $referrals = $client->referrals;

        if (count($referrals) > 0) {

            echo '<table class="items">';

            foreach ($referrals as $referral) {

                echo '<tr>';
                echo '<td style = "width:520px;" >' . $referral->email_referral . '</td><td width:120px;><i>' . $referral->getStatusText() . '</i></td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'No emails sent';
        }
        ?>

    </div>

</div>
