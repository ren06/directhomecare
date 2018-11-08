<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Home Care',
    // preloading 'log' component
    'preload' => array('log'),
//    'onBeginRequest' => create_function('$event', 'return ob_start("ob_gzhandler");'),
//    'onEndRequest' => create_function('$event', 'return ob_end_flush();'),
    'language' => 'en_gb',
    //'sourceLanguage' => 'en_gb',
    // autoloading model and component classes
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
        'application.models.job.*',
        'application.models.forms.*',
        'application.models.login.*',
        'application.models.price.*',
        'application.models.transaction.*',
        'application.models.file.*',
        'application.helpers.*',
        'application.helpers.db.*',
        'application.classes.*',
        'application.classes.borgun.*',
        'application.controllers.*',
        'application.controllers.admin.*',
        'ext.yii-mail.*',
        'zii.behaviors.CTimestampBehavior',
        'zii.widgets.jui.CJuiTabs',
        'application.components.behaviors.TimestampBehavior',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'admin' => array(),
        'test' => array(),
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'false',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array(
                'bootstrap.gii'
            ),
        ),
    ),
    // application components
    'components' => array(
//        'session' => array(
//            'class' => 'CCacheHttpSession',
//        //'cacheID' => 'sessionCache',
//        ),
//        'sessionCache' => array(
//            'class' => 'CApcCache',
//            'servers' => array(
//                array('host' => 'localhost',
//                    'port' => 11211,),
//            ),
//        ),
        'mail' => array(
            'class' => 'YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host' => 'smtp.gmail.com',
                //'username' => 'renaud.theuillon@googlemail.com',
                //'password' => '',
                'port' => '465',
                'encryption' => 'tls'
            ),
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
        'user' => array(
            'class' => 'WebUser',
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            // this is actually the default value
            'loginUrl' => array('site/login'),
            'returnUrl' => array('site/index'),
        ),
        'localtime' => array(
            'class' => 'LocalTime',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            //'urlSuffix' => '.html',
            'showScriptName' => false,
            'rules' => array(
                //emails
                'carer/chooseMission.html' => array('carer/chooseMission'),
                'clientManageBookings/myBookings.html' => array('clientManageBookings/myBookings'),
                'carer/index.html' => array('carer/index'),
                'carer/myMissions.html' => array('carer/myMissions'),
                'site/home.html' => array('site/home'),
                'site/unsubscribeJobAlerts.html' => array('site/unsubscribeJobAlerts'),
                //'favicon.ico' => array('image/favicon'),
                'home' => array('site/home'),
                '' => array('site/index'),
                //static page
                'about' => array('site/page/view/about'),
                //'about.html' => array('site/page/view/about'),
                'contact' => array('site/contact'),
                //'contact.html' => array('site/contact'),
                'forgottenPassword' => array('site/forgottenPassword'),
                //'forgottenPassword.html' => array('site/forgottenPassword'),
                'carer-jobs' => array('site/carerJobs'),
                //'carer-jobs.html' => array('site/carerJobs'),
                'how-it-works' => array('site/page/view/how-it-works'),
                //'how-it-works.html' => array('site/page/view/how-it-works'),
                'glossary' => array('site/page/view/glossary'),
                //'glossary.html' => array('site/page/view/glossary'),
                'site-map' => array('site/page/view/site-map'),
                //'site-map.html' => array('site/page/view/site-map'),
                'questions' => array('site/page/view/questions'),
                //'questions.html' => array('site/page/view/questions'),
                'privacy' => array('site/page/view/privacy'),
                //'privacy.html' => array('site/page/view/privacy'),
                'terms' => array('site/page/view/terms'),
                //'terms.html' => array('site/page/view/terms'),
                '24-hour-care' => array('site/page/view/24-hour-care'),
                //'24-hour-care.html' => array('site/page/view/24-hour-care'),
                'home-care' => array('site/page/view/home-care'),
                //'home-care.html' => array('site/page/view/home-care'),
                'elderly-care' => array('site/page/view/elderly-care'),
                //'elderly-care.html' => array('site/page/view/elderly-care'),
                'disabled-home-care' => array('site/page/view/disabled-home-care'),
                //'disabled-home-care.html' => array('site/page/view/disabled-home-care'),
                'home-care-agency' => array('site/page/view/home-care-agency'),
                //'home-care-agency.html' => array('site/page/view/home-care-agency'),
                'respite-care' => array('site/page/view/respite-care'),
                //'respite-care.html' => array('site/page/view/respite-care'),
                'care-for-the-elderly' => array('site/page/view/care-for-the-elderly'),
                //'care-for-the-elderly.html' => array('site/page/view/care-for-the-elderly'),
                'care-in-home' => array('site/page/view/care-in-home'),
                //'care-in-home.html' => array('site/page/view/care-in-home'),
                'care-uk' => array('site/page/view/care-uk'),
                //'care-uk.html' => array('site/page/view/care-uk'),
                '24-hour-live-in-care' => array('site/page/view/24-hour-live-in-care'),
                //'24-hour-live-in-care.html' => array('site/page/view/24-hour-live-in-care'),
                'care-home' => array('site/page/view/care-home'),
                //'care-home.html' => array('site/page/view/care-home'),
                'companion-care' => array('site/page/view/companion-care'),
                //'companion-care.html' => array('site/page/view/companion-care'),
                'domiciliary-care' => array('site/page/view/domiciliary-care'),
                //'domiciliary-care.html' => array('site/page/view/domiciliary-care'),
                'domiciliary-help' => array('site/page/view/domiciliary-help'),
                //'domiciliary-help.html' => array('site/page/view/domiciliary-help'),
            ),
        ),
//        'widgetFactory' => array(
//            'widgets' => array(
//                'CJuiDialog' => array(
//                    'themeUrl' => '/css/jqueryui',
//                    'theme' => 'green',
//                    'cssFile' => 'jquery-ui-1.9.0.custom.css',
//                ),
//            ),
//        ),
        'cache' => include(dirname(__FILE__) . '/../../conf/cache.php'),
//        'cache' => array(
//            'class' => 'system.caching.CDbCache', //used for static pages
//            'connectionID' => 'db',
//            'autoCreateCacheTable' => true,
//            'cacheTableName' => 'cache',
//      ),
        'log' => include(dirname(__FILE__) . '/../../conf/log.php'),
        'cache' => include(dirname(__FILE__) . '/../../conf/cache.php'),
        'db' => include(dirname(__FILE__) . '/../../conf/db.php'),
        'db_photo' => include(dirname(__FILE__) . '/../../conf/db_photo.php'),
        'db_criminal' => include(dirname(__FILE__) . '/../../conf/db_criminal.php'),
        'db_diploma' => include(dirname(__FILE__) . '/../../conf/db_diploma.php'),
        'db_driving_licence' => include(dirname(__FILE__) . '/../../conf/db_driving_licence.php'),
        'db_identification' => include(dirname(__FILE__) . '/../../conf/db_identification.php'),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'piwik' => include(dirname(__FILE__) . '/../../conf/statistics.php'),
    ), //end of components array
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
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
        'server' => include(dirname(__FILE__) . '/../../conf/server.php'),
        'emails' => include(dirname(__FILE__) . '/../../conf/emails.php'),
        'test' => include(dirname(__FILE__) . '/../../conf/test.php'),
        'filesPolicy' => array(
            'maxSize' => 8 * 1024 * 1024,
            'extensionType' => array('jpg', 'jpeg', 'png', 'gif', 'bmp'),
            'extensionTypePdf' => array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf'),
        )
    ),
    'runtimePath' => include(dirname(__FILE__) . '/../../conf/runtime.php'),
);
