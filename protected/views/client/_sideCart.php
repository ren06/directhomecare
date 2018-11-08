<div class="rc-container-19-float-right">
    <div class="rc-module-bar">
        <div class="rc-module-name">
            <?php echo Yii::t('texts', 'Your order'); // RCRC ?>
        </div>
    </div>
    <?php
    $serviceUsersSelected = Session::getSelectedServiceUsers();
    if (isset($serviceUsersSelected)) {
        $serviceUsers = array_values($serviceUsersSelected);
        ?>
        <div class="rc-container-fb2-criteria-line-odd">
            <img alt="User" width="32" height="32" src="<?php echo Yii::app()->request->baseUrl . '/images/bigicon-user-green.png'; ?>" />
            <span style="line-height:2em">
                <?php
                for ($i = 0; $i < count($serviceUsers); $i++) {
                    $serviceUser = ServiceUser::loadModel($serviceUsers[$i]);
                    echo $serviceUser->fullName;
                }
                ?>
            </span>
        </div>
        <?php
    }
    $serviceLocationId = Session::getSelectedServiceLocation();
    if (isset($serviceLocationId)) {
        ?>
        <div class="rc-container-fb2-criteria-line-even">
            <img style="display:inline-block;" alt="Location" width="32" height="32" src="<?php echo Yii::app()->request->baseUrl . '/images/bigicon-location-green.png'; ?>" />
            <div style="display:inline-block;width:15em;">
                <?php
                echo Address::loadModel($serviceLocationId)->display('&#32;&#45;&#32;');
                ?>
            </div>
        </div>
        <?php
    }
    $quote = Session::getSelectedValidQuote();
    if (isset($quote) && !isset($hideDates)) {

        $quoteBooking = $quote->convertBookingHourly();
        $quotePrice = $quoteBooking->getQuoteTotalPrice();
        $daysBreakdown = $quotePrice->daysBreakdown;
        $numberDays = count($daysBreakdown);
        ?>
        <div class="rc-container-fb2-criteria-line-odd">           
            <table>
                <tr>
                    <td>
                        <img alt="Calendar" width="32" height="32" src="<?php echo Yii::app()->request->baseUrl . '/images/bigicon-calendar-green.png'; ?>" />
                    </td>
                    <td>
                        <table>
                            <?php
                            if ($numberDays == 1) {
                                $height = "2em";
                            } else {
                                $height = "16px";
                            }
                            for ($i = 0; $i < $numberDays; $i++) {
                                $dayBreakdown = $daysBreakdown[$i];
                                echo "<tr style='line-height:$height'>";
                                echo '<td >';
                                echo Calendar::getDayOfWeekText($dayBreakdown->date, Calendar::FORMAT_DBDATE, true) . '&#160;';
                                echo '</td>
                                        <td > ';
                                echo Calendar::convert_DBDateTime_DisplayDateText($dayBreakdown->date) . '&#160;&#160;' . $dayBreakdown->startTime . ' - ' . $dayBreakdown->endTime . '<br />';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </table>                        
                    </td>
                </tr>
            </table>
        </div>
        <div class="rc-container-fb2-criteria-line-even">
            <img alt="Calendar" width="32" height="32" src="<?php echo Yii::app()->request->baseUrl . '/images/bigicon-payment-green.png'; ?>" />
            <span style="line-height:2em">
                <?php
                echo Calendar::convert_Seconds_DayHoursMinutesSeconds($quotePrice->duration, true, true) . '&#160;' . Yii::t('texts', 'LABEL_AT') . '&#160;' . Prices::getPriceText(Constants::USER_CLIENT, Prices::HOURLY_PRICE) . '&#58;&#160;<b><span style="color:#66C">' . $quotePrice->totalPrice->text . '</span></b>';
                ?>
                <?php
//                if ($quoteBooking->getDays() > 14) {
//                    echo '<p><b>' . Yii::t('texts', 'LABEL_FIRST_2_WEEKS') . '</b>&#160;' . UIServices::renderHelpTooltip(Yii::t('texts', 'ALT_THE_SCHEDULE_IS_DONE_2_WEEKS')) . '</p>';
//                }
                ?>
            </span>
        </div>
        <?php
    }
// Carers selected
    $carerProfileType = 'short';
    $view = Constants::CARER_PROFILE_VIEW_GUEST;

    $carerId = Session::getSelectedCarer();
    if (isset($carerId)) {

        $carer = Carer::loadModel($carerId);
        ?>
        <div class="rc-container-fb2-criteria-line-odd">
            <img alt="Calendar" width="32" height="32" src="<?php echo Yii::app()->request->baseUrl . '/images/bigicon-carer-green.png'; ?>" />
            <span style="line-height:2em">Your carer</span>
            <?php
            //foreach ($carers as $carer) {
            ?>
            <div class="rc-fb2-cell" style="padding-bottom:0">
                <?php $this->renderPartial('application.views.carer.profile._carerProfileDetails', array('carerProfileType' => $carerProfileType, 'carer' => $carer, 'view' => $view)); ?>
            </div>
            <?php // }  ?>
        </div>
    <?php } ?>
</div>