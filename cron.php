<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:41
 */
/*error_reporting(E_ALL);
@ini_set('display_errors', 1);*/
define('YII_DEBUG', true);
define('YII_TRACE_LEVEL', 10);


defined('YII_DEBUG') or define('YII_DEBUG',true);

// including Yii
require_once('common/lib/Yii/yii.php');

// we'll use a separate config file
$configFile=dirname(__FILE__).'/common/config/cron.php';
//echo $configFile;
// creating and running console application
Yii::createConsoleApplication($configFile)->run();