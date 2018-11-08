<?php

return array(
            'adminName' => 'Administrator',
            'adminEmail' => 'eh@directhomecare.com',
            'adminEmailBcc' => 'rc@directhomecare.com',
            'adminDebug' => 'renaud.theuillon@googlemail.com', //receives email alert when error in production
            'shiftAdminEmail' => array('rc@directhomecare.com', 'rt@directhomecare.com'),
            //TESTING
            'emailsOn' => true, //emails are sent
            'emailTestOn' => true, //test email addresses are used
            'emailCron' => false, //if true use db table/cron, if false use mail chimp directly - overrties $useCron value of Emails::sendMail()
            'emailTestReceiver' => 'renaud.theuillon@googlemail.com', ////receiver if malTestOn = true 
            // info@directchildcare.com 
            // rxc@gmx.com
            // rtheuillon@hotmail.com 
            // renaud.theuillon@googlemail.com
            // rc@directhomecare.com
            
        )
?>