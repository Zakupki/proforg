<?php
/*if($_SERVER['REMOTE_ADDR']!='91.209.51.1572')
    die('Сайт временно не доступен.');*/

if($_SERVER['REMOTE_ADDR']=='193.93.78.1062'){
    error_reporting(1);
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('error_reporting', E_ALL ^ E_NOTICE);
}else{
    error_reporting(0);
    ini_set('display_errors', 0);
    //error_reporting(E_ALL ^ E_NOTICE);
    /*define('YII_ENABLE_ERROR_HANDLER', false);
    define('YII_ENABLE_EXCEPTION_HANDLER', false);*/
}

//define('YII_ENABLE_ERROR_HANDLER', false);
//define('YII_ENABLE_EXCEPTION_HANDLER', false);

define('MD5_KEY', 'osdgkadhgk');

//defined('YII_DEBUG') or define('YII_DEBUG',true);
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

@include(dirname(__FILE__).'/../init.php');

require_once(dirname(__FILE__).'/../common/lib/Yii/yii.php');

$config = CMap::mergeArray(
    require(dirname(__FILE__).'/../common/config/main.php'),
    require(dirname(__FILE__).'/../frontend/config/main.php'),

    require(dirname(__FILE__).'/../common/config/main-local.php'),
    @include(dirname(__FILE__).'/../frontend/config/main-local.php')
);

Yii::createWebApplication($config)->run();