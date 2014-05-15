<?php
$config = array(
	'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'bozhok1984',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '31.42.52.10', '91.209.51.157','46.164.168.239','195.177.72.222', '91.214.120.134','193.93.78.106', '::1'),
            'generatorPaths' => array(
                'backend.gii',
                'backend.extensions.bootstrap.gii',
            ),
        ),
	),
);
@include('dev-local.php');

return $config;