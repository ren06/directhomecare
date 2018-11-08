<style type="text/css">
    #newservice {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>;
    }
</style>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_PAYMENT');
$this->pageSubTitle = 'You get what you pay for';

$form = $this->beginWidget('CActiveForm', array('id' => 'service_payment-form',
    'enableAjaxValidation' => false,
    'stateful' => true,
        ));
?>
<div class="row">
    <div class="large-6 medium-8 small-12 columns">

        <?php if (Yii::app()->user->hasFlash('error')): ?>
            <div class="rc-error">
                <?php echo Yii::app()->user->getFlash('error'); ?>
            </div>
        <?php endif ?>

        <?php
        if (Yii::app()->params['test']['showPopulateData'] == true) {
            echo CHtml::link(Yii::t('texts', 'BUTTON_POPULATE_DATA'), array('populateOrderPayment'));
        }
        ?>
        <h4>Your order</h4>
        <p>The service is for 
            <?php
            $client = Session::getClient(); //RC not sure if needed

            $serviceUsersSelected = Session::getSelectedServiceUsers();
            if (isset($serviceUsersSelected)) {
                $serviceUsers = array_values($serviceUsersSelected);
                for ($i = 0; $i < count($serviceUsers); $i++) {
                    $serviceUser = ServiceUser::loadModel($serviceUsers[$i]);
                    echo $serviceUser->fullName;
                }
            }
            ?>
        </p>
        <p>
            <?php
            $serviceLocationId = Session::getSelectedServiceLocation();
            if (isset($serviceLocationId)) {
                echo Address::loadModel($serviceLocationId)->display('&#32;&#45;&#32;');
            }
            $quote = Session::getSelectedValidQuote();
            if (isset($quote) && !isset($hideDates)) {
                $quoteBooking = $quote->convertBookingHourly();
                $quotePrice = $quoteBooking->getQuoteTotalPrice();
                $daysBreakdown = $quotePrice->daysBreakdown;
                $numberDays = count($daysBreakdown);
                ?>
            </p>
            <p>
                <?php
                for ($i = 0; $i < $numberDays; $i++) {
                    $dayBreakdown = $daysBreakdown[$i];
                    echo Calendar::getDayOfWeekText($dayBreakdown->date, Calendar::FORMAT_DBDATE, true) . '&#160;';
                    echo Calendar::convert_DBDateTime_DisplayDateText($dayBreakdown->date) . '&#160;&#160;' . $dayBreakdown->startTime . ' - ' . $dayBreakdown->endTime . '<br>';
                }
                ?>
            </p>
            <p>
                <?php
                echo Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true) . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::HOURLY_PRICE) . '&#58;&#160;<b><span style="color:#66C">' . $quotePrice->totalPrice->text . '</span></b>';
            }

            // Carers selected
            $carerProfileType = 'short';
            $view = Constants::CARER_PROFILE_VIEW_GUEST;
            $carerId = Session::getSelectedCarer();
            if (isset($carerId)) {
                $carer = Carer::loadModel($carerId);
                $this->renderPartial('application.views.carer.profile._carerProfileDetails', array('carerProfileType' => $carerProfileType, 'carer' => $carer, 'view' => $view));
            }
            ?>
        </p>
        <h4>Details of the person making the booking</h4>
        <?php echo $form->textField($client, 'first_name', array('maxlength' => 50, 'placeholder' => 'First name')); ?>
        <?php echo $form->error($client, 'first_name', array('class' => 'rc-error')); ?>
        <?php echo $form->textField($client, 'last_name', array('maxlength' => 50, 'placeholder' => 'Last name')); ?>
        <?php echo $form->error($client, 'last_name', array('class' => 'rc-error')); ?>
        <?php echo CHtml::activeTextField($client, 'mobile_phone', array('maxlength' => 50, 'placeholder' => 'Mobile number')); ?></td>
        <?php echo CHtml::error($client, 'mobile_phone', array('class' => 'rc-error')); ?>



        <h4>Payment details</h4>
        <p>
            <?php echo Yii::t('texts', 'NOTE_YOU_WILL_BE_BILLED_AS_DIRECT'); ?>            
        </p>
        <?php
        //need to convert to calculate the price
        if ($quote instanceof HourlyQuoteSimpleForm) {
            $quote = $quote->convertBookingHourly();
        } elseif ($quote instanceof BookingHourlyOneDayForm) {

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
        $quotePrice = $quote->getQuoteTotalPrice();

        if ($paymentType == Constants::PAYMENT_VOUCHER_ONLY) {
            ?>
            <p>
                <b><?php echo Yii::t('texts', 'Voucher payment'); ?></b>
            </p>
            <p>
                To pay: <span style="color:#66C"><b><?php echo $payment['toPay']->text ?></b></span>
                <?php
                echo '  (' . Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true) . '&#160;' . Yii::t('texts', 'x') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::HOURLY_PRICE) . ')'
                ?>
            <p>
                <?php echo Yii::t('texts', 'LABEL_BALANCE_VOUCHER') . '&#58;&#160;' . ClientTransaction::getCreditBalance($client->id)->text; ?>
            </p>
            <p>
                <?php echo Yii::t('texts', 'LABEL_TO_PAY_WITH_VOUCHER') . '&#58;&#160; <b>' . $payment['paidCredit']->text . '</b>'; ?>
            </p>
            <p>
                <?php echo Yii::t('texts', 'LABEL_REMAINING_VOUCHER_AFTER_PAYMENT') . '&#58;&#160;' . $payment['remainingCreditBalance']->text; ?>
            </p>
            <?php
        }
        //if credit is enough to cover the full amount of the service, don't display Paypal
        elseif ($paymentType == Constants::PAYMENT_CARD_ONLY) {
            //display card module
            ?>
            <!-- <div class="rc-module-bar">
                <div class="rc-module-name">
            <?php // echo Yii::t('texts', 'Voucher Code'); ?>
                 </div>
            </div>
            <div class="rc-module-inside" id="credit_card" style="display:visible">
            <?php // echo CHtml::label("Voucher Code", ''); ?>
            <?php // echo CHtml::textField("", "", array('class' => 'rc-field-medium')) ?>
            <?php // echo CHtml::button("Submit", array('class' => 'rc-button')) ?>
            </div>-->

            <div id="credit_card" style="display:visible">
                <?php if (Yii::app()->user->hasFlash('error')): ?>
                    <div class="flash-error" style="margin-top:0;">
                        <?php echo Yii::app()->user->getFlash('error') ?>
                    </div>
                <?php endif ?>
            <!-- To Pay: <span style="color:#66C"><b><?php echo $payment['toPay']->text ?></b></span>
                <?php
                echo '  (' . Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true) . '&#160;' . Yii::t('texts', 'x') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::HOURLY_PRICE) . '):'
                ?>
                -->
                <?php
                if (count($existingCreditCards) > 0) {
                    $this->renderPartial('/creditCard/_selectOrNewCreditCard', array('form' => $form, 'creditCard' => $creditCard, 'client' => $client,
                        'creditCards' => $existingCreditCards, 'selectedCreditCardRadioButton' => $selectedCreditCardRadioButton));
                } else {
                    $this->renderPartial('/creditCard/_newCreditCard', array('form' => $form, 'creditCard' => $creditCard, 'client' => $client));
                }
                ?>
            </div>
            <?php
        } elseif ($paymentType == Constants::PAYMENT_CARD_AND_VOUCHER) {
            ?>
            <div id="credit_card" style="display:visible">
                To pay: <span style="color:#66C"><b><?php echo $payment['toPay']->text ?></b></span> 
                <?php
                echo '  (' . Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true) . '&#160;' . Yii::t('texts', 'x') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::HOURLY_PRICE) . ')'
                ?>
                <p>
                    <?php echo Yii::t('texts', 'LABEL_TO_PAY_WITH_VOUCHER') . '&#58;&#160; <b>' . $payment['paidCredit']->text . '</b>'; ?>
                </p>
                <p>
                    <?php echo Yii::t('texts', 'LABEL_TO_PAY_BY_CARD') . '&#58;&#160; <b>' . $payment['paidCash']->text . '</b>'; ?>
                </p>
                <?php
                if ($returningClient) {
                    $this->renderPartial('/creditCard/_selectOrNewCreditCard', array('form' => $form, 'creditCard' => $creditCard, 'client' => $client, 'creditCards' => $existingCreditCards, 'selectedCreditCardRadioButton' => $selectedCreditCardRadioButton));
                } else {
                    $this->renderPartial('/creditCard/_newCreditCard', array('form' => $form, 'creditCard' => $creditCard, 'client' => $client));
                }
                ?>
            </div>
            <?php
        }

        if (!$returningClient) {
            ?>
            <h4><?php echo Yii::t('texts', 'NOTE_TERMS_AND_CONDITIONS'); ?></h4>
            <p>
                <?php
                echo $form->checkBox($client, 'terms_conditions');
                ?>
                <span onclick="$('#Client_terms_conditions').attr('checked', true);">
                    <?php
                    echo Yii::t('texts', 'NOTE_I_HAVE_READ_AND_ACCEPT_THE');
                    ?>
                </span>
                <a target="_blank" href="<?php echo Yii::app()->request->baseUrl; ?>/terms.html"><?php echo Yii::t('texts', 'NOTE_TERMS_AND_CONDITIONS'); ?></a>.
            </p>
            <?php
            echo $form->error($client, 'terms_conditions', array('class' => 'rc-error'));
        }
        ?>
        <div class="row">
            <div class="large-6 medium-6 small-12 columns">
                <?php
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('submit' => array(Wizard2::getActiveStepName(), 'nav' => 'back'), 'id' => 'backButton', 'class' => 'button expand'));
                ?>
            </div>
            <div class="large-6 medium-6 small-12 columns">
                <?php
                if (Yii::app()->params['test']['allowClientPayment'] == true) {

                    echo CHtml::submitButton(Yii::t('texts', 'BUTTON_PAY'), array('submit' => array(Wizard2::getActiveStepName(), 'nav' => 'next'), 'id' => 'payButton', 'class' => 'button expand alert'));
                } else {
                    echo Html::disabledButton(Yii::t('texts', 'BUTTON_PAY'), array('class' => 'button expand alert'));
                }
                ?>
            </div>
            <?php $this->renderPartial('/common/_ajaxLoader', array('text' => '<b>' . Yii::t('texts', 'NOTE_SECURELY_PROCESSING') . '</b><br /><br />', 'buttonId')); ?> 
        </div>
    </div>
    <div class="large-6 medium-6 small-12 columns">
        <h4>7-day money back guarantee</h4>
        <p>
            <?php echo Yii::t('texts', 'NOTE_NO_QUESTIONS_ASKED'); ?> 
            <!-- See <a target="_blank" class="rc-link" href="<?php // echo Yii::app()->request->baseUrl;                      ?>/terms.html#complaint"><?php // echo Yii::t('texts', 'NOTE_TERMS_AND_CONDITIONS');                      ?></a>. -->
        </p>
        <br>
        <?php include Yii::app()->basePath . '/views/creditCard/_cardLogo.php'; ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
/////**** PIWIK STUFF ****////

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