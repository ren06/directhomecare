<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>;
    }
</style>

<?php $this->pageTitle = Yii::t('texts', 'HEADER_PAYMENT') ?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'service_payment-form',
    'enableAjaxValidation' => false,
    'stateful' => true,
        ));
?>
<?php echo Wizard::generateWizard(); ?>

<h2 class="rc-h2red"><?php echo Yii::t('texts', 'HEADER_COMPLETE_YOUR_ORDER'); ?></h2>
<p class="rc-note">
    <?php echo Yii::t('texts', 'NOTE_YOU_WILL_BE_BILLED_AS_DIRECT'); ?><br />
    <?php echo Yii::t('texts', 'LABEL_YOUR_DETAILS_WILL_ALWAYS_BE_KEPT_PRIVATE'); ?>
</p>
<?php
if (Yii::app()->params['test']['showPopulateData'] == true) {
    if ($scenario == ClientCommonController::SCENARIO_REGISTRATION_BOOKING) {
        echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('clientRegistration/populateCreditCard'));
    }
}
?>
<div class="rc-container-40">
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
<!--            <p>
        <?php
//                $payment = $quote->calculatePayment(Constants::USER_CLIENT);
//                echo Yii::t('texts', 'LABEL_PRICE') . '&#58;&#160;' . $payment['toPay']->text;
        ?>
        </p>-->
        <?php
        //need to convert to calculate the price
        if ($quote instanceof BookingHourlyOneDayForm) {

            //One Day
            $quote = $quote->convertBookingHourly();
        } elseif ($quote instanceof BookingHourly) {

            //Fourteen 
            $quote->subtype = Booking::SUBTYPE_TWO_FOURTEEN;
        } elseif ($quote instanceof BookingHourlyRegularlyForm) {

            //Regulary
            $quote = $quote->convertBookingHourly();
        }

        //if customer has credit show breakdown of payment
        $payment = $quote->calculatePayment(Constants::USER_CLIENT, $client->id);
        if ($payment['paidCredit']->amount > 0) {
            ?>
            <p>
                <?php echo Yii::t('texts', 'LABEL_BALANCE_VOUCHER') . '&#58;&#160;' . ClientTransaction::getCreditBalance($client->id)->text; ?>
            </p>
            <p>
                <?php echo Yii::t('texts', 'LABEL_TO_PAY_WITH_VOUCHER') . '&#58;&#160;' . $payment['paidCredit']->text; ?>
            </p>
            <p>
                <?php echo Yii::t('texts', 'LABEL_TO_PAY_BY_CARD') . '&#58;&#160;' . $payment['paidCash']->text; ?>
            </p>
            <p>
                <?php echo Yii::t('texts', 'LABEL_REMAINING_VOUCHER_AFTER_PAYMENT') . '&#58;&#160;' . $payment['remainingCreditBalance']->text; ?>
            </p>
            <?php
        }
        ?>
    </div>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'HEADER_CLIENT_CONTACT_DETAILS'); ?>
        </div>
    </div>
    <div class="rc-module-inside">
        <table>
            <tr>
                <td class="rc-cell1"><?php echo $form->labelEx($client, 'first_name'); ?></td>
                <td class="rc-cell2"><?php echo $form->textField($client, 'first_name', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
                <td class="rc-cell3"><?php echo $form->error($client, 'first_name', array('class' => 'rc-error')); ?></td>
            </tr>
            <tr>
                <td class="rc-cell1"><?php echo $form->labelEx($client, 'last_name'); ?></td>
                <td class="rc-cell2"><?php echo $form->textField($client, 'last_name', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
                <td class="rc-cell3"><?php echo $form->error($client, 'last_name', array('class' => 'rc-error')); ?></td>
            </tr>
            <tr>
                <td class="rc-cell1"><?php echo CHtml::activeLabelEx($client, 'mobile_phone') . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_THE_CARER_MIGHT_NEED_TO_GET_IN_TOUCH')); ?></td>
                <td class="rc-cell2"><?php echo CHtml::activeTextField($client, 'mobile_phone', array('maxlength' => 50, 'class' => 'rc-field')); ?></td>
                <td class="rc-cell3"><?php echo CHtml::error($client, 'mobile_phone', array('class' => 'rc-error')); ?></td>
            </tr>
        </table>
    </div>
    <?php
    //if credit is enough to cover the full amount of the service, don't display Paypal
    if ($payment['toPay']->amount == $payment['paidCredit']->amount) {
        //no card module
    } else {
        //display card module
        ?>
        <div class="rc-module-bar">
            <div class="rc-module-name">
                <?php echo Yii::t('texts', 'HEADER_CARD_DETAILS'); ?>
            </div>
        </div>
        <div class="rc-module-inside" id="credit_card" style="display:visible">
            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="flash-error">
                    <?php echo Yii::app()->user->getFlash('error') ?>
                </div>
            <?php endif ?>
            <?php
            if ($scenario == ClientCommonController::SCENARIO_REGISTRATION_BOOKING) {
                $this->renderPartial('/creditCard/_newCreditCard', array('form' => $form, 'creditCard' => $creditCard, 'client' => $client,
                    'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton, 'billingAddress' => $billingAddress));
            } else {
                $this->renderPartial('/creditCard/_selectOrNewCreditCard', array('form' => $form, 'client' => $client, 'creditCard' => $creditCard, 'creditCards' => $creditCards,
                    'selectedCreditCardRadioButton' => $selectedCreditCardRadioButton, 'selectedBillingAddressRadioButton' => $selectedBillingAddressRadioButton,
                    'billingAddress' => $billingAddress));
            }
            ?>
            <?php include Yii::app()->basePath . '/views/creditCard/_cardLogo.php'; ?>
        </div>
        <?php
    }
    ?>
    <?php
//if ($client->wizard_completed != Wizard::CLIENT_LAST_STEP_INDEX) {
// $cssNewCustomerOnly = 'style="display:block"';
//    } else {//LOGIN
//        $cssNewCustomerOnly = 'style="display:none"';
//    }
    ?>
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'NOTE_TERMS_AND_CONDITIONS'); ?>
        </div>
    </div>
    <div class="rc-module-inside new_customer_only_field">
        <p>
            <?php echo $form->labelEx($client, 'terms_conditions'); ?>
            <?php
            echo $form->checkBox($client, 'terms_conditions');
            ?>
            <span onclick="$('#Client_terms_conditions').attr('checked', true);">
                <?
                echo Yii::t('texts', 'NOTE_I_HAVE_READ_AND_ACCEPT_THE');
                ?>
            </span>
            <a target="_blank" class="rc-link" href="<?php echo Yii::app()->request->baseUrl; ?>/terms.html"><?php echo Yii::t('texts', 'NOTE_TERMS_AND_CONDITIONS'); ?></a>.
        </p>
        <?php echo $form->error($client, 'terms_conditions', array('class' => 'rc-error')); ?>
    </div>
    <div class="rc-container-button">
        <div class="buttons">
            <?php
            echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('id' => 'backButton', 'class' => 'rc-button-white', 'submit' => array('servicePayment', 'navigation' => 'back')));

            if (Yii::app()->params['test']['allowClientPayment'] == true) {

                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_PAY'), array('id' => 'payButton', 'class' => 'rc-button', 'submit' => array('servicePayment', 'navigation' => 'next')));
            } else {
                echo Html::disabledButton(Yii::t('texts', 'BUTTON_PAY'));
            }
            ?>
        </div>
        <?php $this->renderPartial('/common/_ajaxLoader', array('text' => '<b>' . Yii::t('texts', 'NOTE_SECURELY_PROCESSING') . '</b><br /><br />', 'buttonId')); ?> 
    </div>
</div>
<?php $this->endWidget(); ?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/billingAddressRadioButtonSelect.js'); ?>

<?php
//Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/ui/submitButton.js');
?>

<?php
if (Yii::app()->piwik->siteID != '0') {
    ?>
    <script type="text/javascript">
                    /*<![CDATA[*/
                    var _paq = _paq || [];
                    (function() {
                        _paq.push(['setSiteId', '1']);
                        _paq.push(['setTrackerUrl', 'https://directhomecare.com/stats_piwik/piwik.php']);
                        _paq.push(['addEcommerceItem',
                            "<?php
    if ($quote->type == Constants::LIVE_IN) {
        echo '2';
    } else {
        echo '1';
    }
    ?>", // "1" for Hourly or "2" for Live-in.
                            "<?php
    if ($quote->type == Constants::LIVE_IN) {
        echo 'Home Care Live-in';
    } else {
        echo 'Home Care Hourly';
    }
    ?>", // "Home Care Hourly" or "Home Care Live-in".
                            "Home Care", //product category
    <?php
    if ($quote->type == Constants::LIVE_IN) {
        $price = Prices::getPrice(Constants::USER_CLIENT, Prices::LIVE_IN_DAILY_PRICE);
    } else {
        $price = Prices::getPrice(Constants::USER_CLIENT, Prices::HOURLY_PRICE);
    }
    echo $price->amount;
    ?>, // Price per hour for Hourly or per day for Live-in. Integer.
    <?php
    if ($quote->type == Constants::LIVE_IN) {
        $slots = $quote->getLiveInMissionSlots();
        $slot = $slots[0];
        $dura = $slot->duration;
        $pos = strpos($dura, '&#160;');
        $duration = substr($dura, 0, $pos);
    } else {
        $quotePrice = $quote->getQuoteTotalPrice();
        $duration = $quotePrice->duration / 3600;
    }
    echo $duration;
    ?> // Number of hours for Hourly or number of days for Live-in. Integer.
                        ]);
                        _paq.push(['trackEcommerceCartUpdate',
    <?php
    if ($quote->type == Constants::LIVE_IN) {
        echo $slots[0]->toPay->amount;
    } else {
        echo $quotePrice->totalPrice->amount;
    }
    ?>// Cart amount. Integer.
                        ]);
                        // Call the file
                        var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
                        g.type = 'text/javascript';
                        g.defer = true;
                        g.async = true;
                        g.src = 'https://directhomecare.com/stats_piwik/piwik.js';
                        s.parentNode.insertBefore(g, s);
                    })();
                    /*]]>*/
    </script>
    <?php
}
?>