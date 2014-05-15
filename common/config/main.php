<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).DS.'..'.DS.'..'.DS);

Yii::setPathOfAlias('root', ROOT);
Yii::setPathOfAlias('common', ROOT.'common');
require(ROOT.'common/helpers/G.php');

$config = array(
    'preload' => array('log'),
    'import' => array(
        'common.models.*',
		'common.models.paymentsystems.*',
        'common.components.*',
        'common.modules.rights.*',
        'common.modules.rights.components.*',
        'common.extensions.yii-eauth.*',
        'common.extensions.crontab.*',
        'common.extensions.yii-eauth.lib.*',
        'common.extensions.lightopenid.*',
        'common.extensions.yii-eauth.*',
        'common.extensions.yii-eauth.services.*',
        'common.extensions.browser.*',
    ),
    'modules' => array(
        'rights' => array(
            'class' => 'common.modules.rights.RightsModule',
            'superuserName' => 'admin',
            'authenticatedName' => 'authenticated',
            'userIdColumn' => 'id',
            'userNameColumn' => 'login',
            'layout' => 'root.backend.views.rights.layouts.main',
            'appLayout' => 'root.backend.views.layouts.main',
        ),
    ),
    'components' => array(
    	'user' => array(
            'class' => 'WebUser',
            'loginUrl' => array('/site/login'),
            'stateKeyPrefix' => '123',
            'allowAutoLogin' => true,
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=newzakup',
            'username' => 'u_newzakup',
            'password' => 'IqUT5Ex0',
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'tablePrefix' => 'z_',
            /*'queryCacheID'=>'cache3',
            'schemaCachingDuration' => 86400,*/
        ),
        'request' => array(
            'class' => 'HttpRequest',
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'token',
            'enableCookieValidation' => true,
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'authManager' => array(
            'class' => 'root.common.components.DbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'z_auth_item',
            'itemChildTable' => 'z_auth_item_child',
            'assignmentTable' => 'z_auth_assignment',
            'rightsTable' => 'z_rights',
        ),
        'cache2' => array(
            'class' => 'system.caching.CFileCache',
            'keyPrefix' => 'default',
            'cachePath' => Yii::getPathOfAlias('common.runtime.cache')
        ),
        'cache' => array(
            'class' => 'system.caching.CDummyCache',
        ),
       /* 'cache'=>array(
            'class'=>'system.caching.CMemCache',
            'servers'=>array(
                array('host'=>'127.0.0.1', 'port'=>11211),
            ),
        ),*/
        'mail' => array(
            'class' => 'common.extensions.yii-mail.YiiMail',
            'transportType' => 'php',
            /*'transportOptions' => array(
                'host' => 'smtp.googlemail.com',
                'username' => 'support@zakupki-online.com',
                'password' => 'qwezxc1!',
                'port' => '25',
                'encryption'=>'tls',
            ),*/
            'logging' => false,
            'dryRun' => false
        ),
        'detectMobileBrowser' => array(
            'class' => 'frontend.extensions.yii-detectmobilebrowser.XDetectMobileBrowser',
        ),
        'eauth' => array(
            'class' => 'common.extensions.yii-eauth.EAuth',
            'popup' => false, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'services' => array( // You can change the providers and their classes.
                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'FacebookOAuthService',
                    'client_id' => '509418155763464',
                    'client_secret' => '42e6366c20836b90edf1fa3208ceb075',
                ),
            ),
        ),
        'epassgen' => array(
            'class' => 'common.extensions.epasswordgenerator.EPasswordGenerator',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                /*array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace, info',
                    'categories'=>'system.*',
                ),*/
                array(
                    'class'=>'CEmailLogRoute',
                    'levels'=>'error, notice',
                    'emails'=>'dmitriy.bozhok@gmail.com',
                ),
            ),
        ),
    ),

    'behaviors' => array(
        'appConfigBehavior'
    ),

    'params' => array(
        // relative to Yii::app()->basePath/..
        'webRoot' => 'newzakupki.reactor.ua',
        'adminEmail' => 'info@zakupki-online.ua',
        'noreply' => 'info@zakupki-online.com',
        'cacheDuration' => 3600,
        'uploadUrl' => 'upload',
    ),
);

return $config;