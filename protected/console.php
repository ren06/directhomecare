<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
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
    ),
    'emails' => include(dirname(__FILE__) . '/../../conf/emails.php'),
    'test' => include(dirname(__FILE__) . '/../../conf/test.php'),
);

