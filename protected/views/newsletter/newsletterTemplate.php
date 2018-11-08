<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <body style="font-family:arial;color:#333;-webkit-text-size-adjust:none;">
        <table align="center" style="font-size:16px;" cellpadding="16" cellspacing="0" bgcolor="#FFF">
            <tr>
                <td width="450">
                    <img src="https://directhomecare.com/images/email-logo-4.png" alt="<?php echo Yii::t('emails', 'EMAIL_ALT_DIRECT_HOMECARE'); ?>" border="0"/>
                </td>
            </tr>
        </table>
        <table align="center" cellpadding="16" cellspacing="0" bgcolor="#EEE">
            <tr>
                <td width="450">
                    <span style="font-weight:bold;font-size:26px">
                        <?php echo $emailTitle; ?>
                    </span>
                    <div style="font-size:16px;line-height:26px;">
                        <?php echo $emailContent; ?>
                    </div>
                    <?php
                    if ($emailButtonText == false) {
                        // If there is no button to be displayed, declare emailButtonText as false instead of string.
                    } else {
                        ?>
                        <div align="center" style="padding-top:16px">
                            <a id="button" title="<?php echo Yii::t('emails', 'EMAIL_ALT_GO_TO_THE_WEB'); ?>" href="<?php echo $buttonBookUrl; ?>" style="background-color:#66C;color:#FFF;cursor:pointer;font-family:arial;font-size:16px;font-weight:bold;line-height:32px;margin:0px 4px;padding:6px 9px;text-align:center;text-decoration:none;">
                                <?php echo $emailButtonText; ?>
                            </a>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <table align="center" style="font-size:10px;" cellpadding="16" cellspacing="0" bgcolor="#FFF">
            <tr>
                <td width="450">
                    <?php echo Yii::t('emails', 'EMAIL_NOTE_UK_LEGAL_ADDRESS'); ?>
                    <br />
                    <br />
                    <?php
                    echo Yii::t('emails', 'EMAIL_NOTE_SYSTEM_INFO_DO_NOT_REPLY');
                    if (isset($unsubscribeLink)) {
                        echo ' ' . '<a style="color:#666" href="' . $unsubscribeLink . '">' . Yii::t('emails', 'EMAIL_UNSUBSCRIBE') . '</a>' . ' ' . Yii::t('emails', 'EMAIL_FROM_NEWSLETTER') . '.';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </body>
</html>