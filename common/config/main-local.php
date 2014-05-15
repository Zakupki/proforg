<?php
$config = array(
    /*'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=newzakup',
            'queryCacheID'=>'cache2',
            'schemaCachingDuration' =>3600,
            'username' => 'u_newzakup',
            'password' => 'IqUT5Ex0',
        ),
        'cache2' => array(
            'class' => 'system.caching.CFileCache',
            'keyPrefix' => 'default',
            'cachePath' => Yii::getPathOfAlias('common.runtime.cache')
        ),
    ),*/
   /* 'params' => array(
        'noreply' => 'no-reply@zakupki-online.com',
    )*/
);

@include('dev-local.php');

return $config;