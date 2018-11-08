<style type="text/css">
    #newservice{
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_YOUR_ORDER') ?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'order-form',
    'method' => 'post',
    'enableAjaxValidation' => false,
        ));
?>
<?php echo Wizard::generateWizard(); ?>
<div class="rc-container-40">
    <h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_DETAILS_OF_THE_SERVICE_YOU_HAVE_SELECTED'); ?></h2>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'LABEL_BOOKING'); ?>
        </div>
    </div>
    <div class="rc-module-inside">
        <p>
            <b><?php echo Yii::t('texts', 'HEADER_USERS') . '&#58;&#160;'; ?></b>
            <?php
            //TODO: put this in the controller, harmonize both logics

            if ($this->id == 'clientNewBooking') {//controller name
                $serviceUsersIds = Session::getSelectedServiceUsers();
                foreach ($serviceUsersIds as $serviceUsersId) {
                    $serviceUsers[] = ServiceUser::loadModel($serviceUsersId);
                }
            } else {
                $serviceUsers = $client->serviceUsers;
            }
            for ($i = 0; $i < count($serviceUsers); $i++) {
                echo $serviceUsers[$i]->fullName;
            }
            ?>
        </p>
        <p>
            <b><?php echo Yii::t('texts', 'HEADER_LOCATION') . '&#58;&#160;'; ?></b>
            <?php
            //TODO: put this in the controller
            $serviceLocationId = Session::getSelectedServiceLocation();
            echo Address::loadModel($serviceLocationId)->display('&#160;&#160;&#45;&#160;&#160;');
            ?>
        </p>
        <?php
        $this->renderPartial('/quote/_resultOrder', array('quote' => $quote, 'short' => true));
        ?>
    </div>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'HEADER_CLIENT_CONTACT_DETAILS'); ?>
        </div>
    </div>
    <div class="rc-module-inside">
        <p class="rc-note">
            <?php echo Yii::t('texts', 'NOTE_THE_CARER_MIGHT_NEED_TO_GET_IN_TOUCH'); ?>
        </p>
        <table>
            <tr>
                <td class="rc-cell1"><?php echo CHtml::activeLabelEx($client, 'mobile_phone'); ?></td>
                <td class="rc-cell2"><?php echo CHtml::activeTextField($client, 'mobile_phone', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
                <td class="rc-cell3"><?php echo CHtml::error($client, 'mobile_phone', array('class' => 'rc-error')); ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="rc-container-button">
    <div class="buttons">
        <?php
        echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'rc-button-white', 'submit' => array('serviceOrder', 'navigation' => 'back')));
        echo CHtml::submitButton(Yii::t('texts', 'BUTTON_CONTINUE'), array('class' => 'rc-button', 'submit' => array('serviceOrder', 'navigation' => 'next')));
        ?>
    </div>  
    <?php $this->renderPartial('/common/_ajaxLoader'); ?>  
</div>

<?php $this->endWidget(); ?>