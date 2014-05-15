<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:43
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).DS.'..'.DS.'..'.DS);

Yii::setPathOfAlias('root', ROOT);
Yii::setPathOfAlias('common', ROOT.'common');
Yii::setPathOfAlias('frontend', ROOT.'frontend');

return array(
    // This path may be different. You can probably get it from `config/main.php`.
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'sourceLanguage' => 'ru_RU',
    'language' => 'ru',
    'name'=>'Cron',
    'preload'=>array('log'),
    'import'=>array(
        'common.components.*',
        'common.models.*',
        'common.extensions.*',
        'common.extensions.yii-mail.*'
    ),
    // We'll log cron messages to the separate files
    'components'=>array(
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron.log',
                    'levels'=>'error, warning',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron_trace.log',
                    'levels'=>'trace',
                ),
            ),
        ),
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

        // Your DB connection
        //newzakupki
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=newzakup',
            //'schemaCachingDuration' => 1,
            'username' => 'u_newzakup',
            'password' => 'IqUT5Ex0',
            'tablePrefix' => 'z_'
        ),
        //newzakupki2
        /*'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=newzakuph',
            //'schemaCachingDuration' => 1,
            'username' => 'u_newzakupB',
            'password' => '6buPUqkS',
            'tablePrefix' => 'z_'
        ),*/
    ),
);