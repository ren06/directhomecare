
<table id="resetPassword">
    <tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($resetPassword, 'oldPassword'); ?></td>
        <td class="rc-cell2"><?php echo CHtml::activePasswordField($resetPassword, 'oldPassword', array('maxlength' => 50, 'class' => 'rc-field toggable', 'autocomplete' => 'off')); ?></td>
        <td class="rc-cell3"><div id="ResetPasswordForm_oldPassword_em_" class="rc-error" style="display:none"></div></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($resetPassword, 'newPassword'); ?></td>
        <td class="rc-cell2"><?php echo CHtml::activePasswordField($resetPassword, 'newPassword', array('maxlength' => 50, 'class' => 'rc-field toggable', 'autocomplete' => 'off')); ?></td>
        <td class="rc-cell3"><div id="ResetPasswordForm_newPassword_em_" class="rc-error" style="display:none"></div></td>
    </tr>
    <tr>
        <td class="rc-cell1"><?php echo CHtml::activeLabelEx($resetPassword, 'newPasswordRepeat'); ?></td>
        <td class="rc-cell2"><?php echo CHtml::activePasswordField($resetPassword, 'newPasswordRepeat', array('maxlength' => 50, 'class' => 'rc-field toggable', 'autocomplete' => 'off')); ?></td>
        <td class="rc-cell3"><div id="ResetPasswordForm_newPasswordRepeat_em_" class="rc-error" style="display:none"></div></td>
    </tr>        
</table>