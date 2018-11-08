<?php
$selectedMenu;
$currentNumber = Mission::getCurrentMissionCount(Yii::app()->user->id);
$complaintNumber = Mission::getComplaintMissionsCarerCount(Yii::app()->user->id);
$verifyingNumber = Mission::getVerifyingMissionCount(Yii::app()->user->id);
$historyNumber = Mission::getHistoryMissionCount(Yii::app()->user->id);

if($selectedMenu == 'currentMissions'){echo '<u>';}
echo CHtml::link(Yii::t('texts', 'LINK_CURRENT_MISSIONS') . '&#160;(' . $currentNumber . ')', $this->createUrl("myMissions"), array('class' => 'rc-link'));
if($selectedMenu == 'currentMissions'){echo '</u>';}

echo '&#160;&#160;&#124;&#160;&#160;';

if($complaintNumber > 0){
if($selectedMenu == 'complaintMissions'){echo '<u>';}
echo CHtml::link(Yii::t('texts', 'LINK_OPENED_COMPLAINTS') . '&#160;(' . $complaintNumber . ')', $this->createUrl("missionsComplaint"), array('class' => 'rc-linkred'));
if($selectedMenu == 'complaintMissions'){echo '</u>';}
echo '&#160;&#160;&#124;&#160;&#160;';
}

if($selectedMenu == 'verifyingMissions'){echo '<u>';}
echo CHtml::link(Yii::t('texts', 'LINK_MISSIONS_BEING_VERIFIED') . '&#160;(' . $verifyingNumber . ')', $this->createUrl("missionsVerifying"), array('class' => 'rc-link'));
if($selectedMenu == 'verifyingMissions'){echo '</u>';}

echo '&#160;&#160;&#124;&#160;&#160;';

if($selectedMenu == 'historyMissions'){echo '<u>';}
echo CHtml::link(Yii::t('texts', 'LINK_MISSIONS_HISTORY') . '&#160;(' . $historyNumber . ')', $this->createUrl("missionsHistory"), array('class' => 'rc-link'));
if($selectedMenu == 'historyMissions'){echo '</u>';}
?>
<br>
<br>