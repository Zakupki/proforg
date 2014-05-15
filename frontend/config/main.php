<?php
Yii::setPathOfAlias('frontend', ROOT.'frontend');
Yii::setPathOfAlias('www', ROOT.'../..');
$config = array(
    'basePath' => ROOT.'frontend',
    'name' => 'Frontend',
    'theme' => '',
    'import' => array(
        'frontend.models.*',
        'frontend.components.*',
    ),
    'components' => array(
        'user' => array(
            'class' => 'WebUser',
            'loginUrl' => array('/site/login'),
            'stateKeyPrefix' => 'user',
            'allowAutoLogin' => true,
        ),
        'request' => array(
            'enableCsrfValidation' => false,
            'csrfTokenName' => 'ftoken',
        ),
       /*
        'user' => array(
                   'class' => 'WebUser',
                   'loginUrl' => array('/site/login'),
                   'allowAutoLogin' => true,
               ),*/
       
        'urlManager' => array(
           /* 'class' => 'frontend.components.UrlManager',*/
            'urlFormat' => 'path',
            'showScriptName' => false,
            /*'vars' => array('page', 'p', 'id', 'q'),*/
            'rules' => array(
                /*
                array(
                                    'class' => 'UrlRule',
                                    'actions' => array()
                                ),*/
                

               /*
                '<lang:\w{2}>/<controller:\w+>' => '<controller>',
                               '<lang:\w{2}>/<controller:\w+>/<id:\d+>' => '<controller>/view',
                               '<lang:\w{2}>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                               '<lang:\w{2}>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',*/
               

                '<controller:\w+>' => '<controller>',
                '<controller:\w+>/<id:\d+>/<title:\w+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/page/<page:\d+>' => '<controller>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                'page/<code:\w+>'=>'page/view',
                'site/message/<message:\w+>'=>'site/message',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
               
                
                
                
            ),
        ),
        'themeManager' => array(
            'basePath' => Yii::getPathOfAlias('frontend'),
            'baseUrl' => Yii::getPathOfAlias('www')
        ),
        'uploader' => array(
            'class' => 'common.components.Uploader',
            'subdirs' => 1
        ),
        /*
        'viewRenderer' => array(
                    'class' => 'frontend.extensions.twig-renderer.ETwigViewRenderer',
        
                    // All parameters below are optional, change them to your needs
                    'fileExtension' => '.twig',
                    'options' => array(
                        'autoescape' => true,
                    ),
                    'extensions' => array(
                    ),
                    'globals' => array(
                        'html' => 'CHtml'
                    ),
                    'functions' => array(
                        't' => 'Yii::t',
                        'file' => 'File::fileLink',
                        'image' => 'File::image',
                        'opt' => 'Option::getOpt',
                        'parseTime' => 'Tool::parseTime',
                    ),
                    'filters' => array(
                        'email' => array('Tool::obfuscateEmailJs', array('is_safe' => array('html'))),
                        'autop' => array('Tool::autop', array('pre_escape' => 'html', 'is_safe' => array('html'))),
                    ),
                ),*/
        
    ),
);

return $config;