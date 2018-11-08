<?php

return array('connectionString' => "mysql:host=localhost;dbname=directhomecare",
            'emulatePrepare' => true,
            'schemaCachingDuration' => 604800, //one week
            //'schemaCacheID' => 'schemaCache', //to use if using different caching mechanism
            'enableParamLogging' => true,
            'username' => 'admin',
            'password' => 'admin',
            'charset' => 'utf8',
            'class' => 'CDbConnection',
            'enableProfiling' => true,
            'initSQLs'=>array("set time_zone='Europe/London';"),  
        )
?>

