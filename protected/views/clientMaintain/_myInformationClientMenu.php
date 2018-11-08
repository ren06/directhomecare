<?php

if($selectedMenu == 'Details'){echo '<u>';}

echo CHtml::link(Yii::t('texts', 'LINK_DETAILS'), $this->createUrl("maintainDetails"), array('class' => 'rc-link'));

if($selectedMenu == 'Details'){echo '</u>';}

//echo '&#160;&#160;&#124;&#160;&#160;';
//
//if($selectedMenu == 'serviceUsers'){echo '<u>';}
//
//echo CHtml::link(Yii::t('texts', 'LINK_USERS_PLURAL'), $this->createUrl("maintainServiceUsers"), array('class' => 'rc-link'));
//
//if($selectedMenu == 'serviceUsers'){echo '</u>';}
//
//echo '&#160;&#160;&#124;&#160;&#160;';
//
//if($selectedMenu == 'serviceLocations'){echo '<u>';}
//
//echo CHtml::link(Yii::t('texts', 'LINK_LOCATIONS'), $this->createUrl("maintainServiceLocations"), array('class' => 'rc-link'));
//
//if($selectedMenu == 'serviceLocations'){echo '</u>';}

echo '&#160;&#160;&#124;&#160;&#160;';

if($selectedMenu == 'maintainPaymentDetails'){echo '<u>';}

echo CHtml::link(Yii::t('texts', 'LINK_CARD_DETAILS'), $this->createUrl("maintainPaymentDetails"), array('class' => 'rc-link'));

if($selectedMenu == 'maintainPaymentDetails'){echo '</u>';}

// echo '&#160;&#160;&#124;&#160;&#160;';

// if($selectedMenu == 'myCarers'){echo '<u>';}

// echo CHtml::link(Yii::t('texts', 'LINK_MY_CARERS'), $this->createUrl("myCarers"), array('class' => 'rc-link'));

// if($selectedMenu == 'myCarers'){echo '</u>';}

?>
<br>
<br>