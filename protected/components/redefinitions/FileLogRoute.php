<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileLogRoute
 *
 * @author Renaud
 */
class FileLogRoute extends CFileLogRoute {

    protected function formatLogMessage($message, $level, $category, $time) {
        //enter code here your custom message format
        //return @date('Y/m/d H:i:s', $time) . " [$level] [$category] $message\n";

        if (null === $time)
            $time = time();

        $level = strtoupper($level[0]);

        if (Yii::app()->user->isGuest) {
            $userRole = 'Guest';
        } else {
            $userId = Yii::app()->user->id;

            if (isset(Yii::app()->user->roles)) {
                $role = Yii::app()->user->roles;
                $roleDescr = ($role == Constants::USER_CLIENT ? 'Client' : 'Carer' );
            } else {
                $roleDescr = '';
            }
            $userRole = "UserId: $userId Role: $roleDescr";

            if ($level == 'E') {

                $whitelist = array('127.0.0.1', "::1");
                $address = $_SERVER['REMOTE_ADDR'];
                if (!in_array($address, $whitelist)) {

                    $html = nl2br($message);
                    $subject = 'Error caused by ' . $userRole;
                    $to = Yii::app()->params['emails']['adminDebug'];
                    $from = 'admin@directhomecare.com';

                    $success = Emails::sendMail($html, $subject, $to, $from);
                }
            }
        }


        return @date('d M H:i:s', $time) . ' ' . $userRole . ' [' . sprintf('%-30s', $category) . '] ' . ': <' . $level . '> ' . $message . PHP_EOL;
    }

}

?>
