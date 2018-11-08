<?php

if ($selectedMenu == 'Details') {
    echo '<u>';
}

echo CHtml::link(Yii::t('texts', 'LINK_DETAILS'), $this->createUrl("maintainDetails"), array('class' => 'rc-link'));

if ($selectedMenu == 'Details') {
    echo '</u>';
}

echo '&#160;&#160;&#124;&#160;&#160;';

if ($selectedMenu == 'TypeWork') {
    echo '<u>';
}

echo CHtml::link(Yii::t('texts', 'LINK_TYPE_OF_WORK'), $this->createUrl("maintainTypeWork"), array('class' => 'rc-link'));

if ($selectedMenu == 'TypeWork') {
    echo '</u>';
}

echo '&#160;&#160;&#124;&#160;&#160;';

if ($selectedMenu == 'Profile') {
    echo '<u>';
}

echo CHtml::link(Yii::t('texts', 'LINK_PROFILE'), $this->createUrl("maintainProfile"), array('class' => 'rc-link'));

if ($selectedMenu == 'Profile') {
    echo '</u>';
}

echo '&#160;&#160;&#124;&#160;&#160;';

if ($selectedMenu == 'AccountSettings') {
    echo '<u>';
}

echo CHtml::link(Yii::t('texts', 'LINK_ACCOUNT_SETTINGS'), $this->createUrl("maintainAccountSettings"), array('class' => 'rc-link'));

if ($selectedMenu == 'AccountSettings') {
    echo '</u>';
}
?>
<br>
<br>