<?php
error_reporting(1);
@ini_set('display_errors', 1);
@ini_set('error_reporting', E_ALL);

@include(dirname(__FILE__).'/../init.php');

require_once(dirname(__FILE__).'/../common/lib/Yii/yii.php');

$config = CMap::mergeArray(
    require(dirname(__FILE__).'/../common/config/main.php'),
    require(dirname(__FILE__).'/../manage/config/main.php'),

    require(dirname(__FILE__).'/../common/config/main-local.php'),
    @include(dirname(__FILE__).'/../manage/config/main-local.php')
);

Yii::createWebApplication($config)->run();