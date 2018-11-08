<?php

return array(
    'class' => 'CLogRouter',
    'routes' => array(
        array(
            'class' => 'FileLogRoute',
            'levels' => 'error, warning',
        ),
        array(
            'class' => 'CFileLogRoute',
            'logFile' => 'borgun.log',
            'categories' => 'payment',
        ),
        array(
            'class' => 'CFileLogRoute',
            'logFile' => 'quote.log',
            'categories' => 'quote',
        ),
        array(
            'class' => 'EmailLogRoute',
            'logFile' => 'email.log',
            'categories' => 'email',
           //'levels' => 'error, warning, info'
        ),
//                array(
//                    'class' => 'CProfileLogRoute',
//                    'report' => 'summary', //or callstack
//                // lists execution time of every marked code block
//                // report can also be set to callstack
//                ),
        array(
            // logging SQL queries: 
            'class' => 'CFileLogRoute',
            'levels' => 'trace',
            'categories' => 'system.db.CDbCommand',
            'LogFile' => 'db.trace',
            'maxFileSize' => 1024 * 100, //100 MB
        ),
    //uncomment the following to show log messages on web pages
    //array(
    //    'class' => 'CWebLogRoute',
    //),
    ),
        )
?>
