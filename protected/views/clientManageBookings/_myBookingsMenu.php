<?php
$bookingNumber = Booking::getBookingsClientCount(Yii::app()->user->id);
$complaintNumber = Mission::getComplaintMissionsClientCount(Yii::app()->user->id);
$missionNumber = Mission::getMissionsClientCount(Yii::app()->user->id);
$transactionNumber = ClientTransaction::getTransactionsClientCount(Yii::app()->user->id);

if($selectedMenu == 'currentBookings'){echo '<u>';}

echo CHtml::link(Yii::t('texts', 'LINK_BOOKINGS') . '&#160;(' . $bookingNumber . ')', $this->createUrl("myBookings"), array('class' => 'rc-link'));

if($selectedMenu == 'currentBookings'){echo '</u>';}

echo '&#160;&#160;&#124;&#160;&#160;';

if($complaintNumber > 0){
if($selectedMenu == 'carerVisitsComplaint'){echo '<u>';}
echo CHtml::link(Yii::t('texts', 'LINK_OPENED_COMPLAINTS') . '&#160;(' . $complaintNumber . ')', $this->createUrl("carerVisitsComplaint"), array('class' => 'rc-linkred'));
if($selectedMenu == 'carerVisitsComplaint'){echo '</u>';}
echo '&#160;&#160;&#124;&#160;&#160;';
}


if($selectedMenu == 'carerVisits'){echo '<u>';}

echo CHtml::link(Yii::t('texts', 'LINK_VISITS') . '&#160;(' . $missionNumber . ')', $this->createUrl("carerVisits"), array('class' => 'rc-link'));

if($selectedMenu == 'carerVisits'){echo '</u>';}

echo '&#160;&#160;&#124;&#160;&#160;';

if($selectedMenu == 'transactionsHistory'){echo '<u>';}

echo CHtml::link(Yii::t('texts', 'LINK_PAYMENTS') . '&#160;(' . $transactionNumber . ')', $this->createUrl("transactionsHistory"), array('class' => 'rc-link'));

if($selectedMenu == 'transactionsHistory'){echo '</u>';}

?>
<br>
<br>