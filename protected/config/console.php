<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

$original = array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.actions.*',
        'application.components.redefinitions.*',
        'application.components.validators.*',
        'application.components.behaviors.*',
        'application.components.widgets.quote_widget.*',
        'application.components.widgets.drop_down_date_picker.*',
        'application.components.widgets.service_users.*',
        'application.components.widgets.service_locations.*',
        'application.components.widgets.user_login_module*',
        'application.models.carer.*',
        'application.models.client.*',
        'application.models.booking.*',
        'application.models.mission.*',
        'application.models.forms.*',
        'application.models.login.*',
        'application.models.price.*',
        'application.models.transaction.*',
        'application.models.file.*',
        'application.helpers.*',
        'application.classes.*',
        'application.controllers.*',
        'application.controllers.admin.*',
        'ext.yii-mail.*',
        'zii.behaviors.CTimestampBehavior',
        'application.components.behaviors.TimestampBehavior',
    ),
    // application components
    'components' => array(
        'db' => include(dirname(__FILE__) . '/../../conf/db.php'),
        'db_photo' => include(dirname(__FILE__) . '/../../conf/db_photo.php'),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    // logging SQL queries: 
                    'class' => 'CFileLogRoute',
                    'levels' => 'trace',
                    'categories' => 'system.db.CDbCommand',
                    'LogFile' => 'db.trace',
                    'maxFileSize' => 1024 * 100, //100 MB
                ),
//                array(
//                    'class' => 'CProfileLogRoute',
//                    'report' => 'summary',
//                // lists execution time of every marked code block
//                // report can also be set to callstack
//                ),
//             //uncomment the following to show log messages on web pages
//                array(
//                    'class' => 'CWebLogRoute',
//                ),
            ),
        ),
    ),
    'language' => 'en_gb',
    'params' => array(
        'server' => include(dirname(__FILE__) . '/../../conf/server.php'),
        'datePickerLocation' => 'en_gb', //by RC
        'mapLocation' => 'UK', //by RC
        'countryCurrencies' => array('en_GB' => 'GBP', 'en_US' => 'USD', 'fr_FR' => 'EUR'),
        'currencies' => array('GBP' => array('symbol' => '£', 'leftRight' => 'left'),
            'USD' => array('symbol' => '$', 'leftRight' => 'left'),
            'EUR' => array('symbol' => '€', 'leftRight' => 'right')),
        'radioButtonSeparator' => '&#160;&#160;&#160;&#160;|&#160;&#160;&#160;&#160;',
        'radioButtonSpaceafter' => '&#160;&#160;&#160;&#160;&#160;&#160;&#160;',
        'texts' => array(
            'genericFormErrorMessage' => 'Some fields are not correclty filled.', //to text RTRT
            'others' => '...',
        ),
        'ageGroups' => array(
            'children' => array(0, 14),
            'young_adult' => array(15, 24),
            'adult' => array(25, 54),
            'elderly' => array(55, 150),
        ),
        'emails' => include(dirname(__FILE__) . '/../../conf/emails.php'),
        'test' => include(dirname(__FILE__) . '/../../conf/test.php'),
        'filesPolicy' => array(
            'maxSize' => 8 * 1024 * 1024,
            'extensionType' => array('jpg', 'jpeg', 'png', 'gif', 'bmp'),
        )
    ),
    'runtimePath' => include(dirname(__FILE__) . '/../../conf/runtime.php'),
);

//$serverSpecific = include(dirname(__FILE__) . '/../../conf/console.php');
//return array_merge($original, $serverSpecific);

return $original;



