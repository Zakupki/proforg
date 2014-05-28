<?php
/** @var $this SiteController */
$baseUrl = Yii::app()->baseUrl;
$cs = cs();

$cs->registerScriptFile($baseUrl.'/backend/js/main.js?v=1', CClientScript::POS_END);
$cs->registerCssFile($baseUrl.'/backend/css/main.css?v=1', 'screen');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <?php if(method_exists($this, 'getTitle')) { ?>
        <title><?php echo CHtml::encode(Yii::app()->name
            .' - '.Yii::t('backend', '{title}', array('{title}' => Yii::t('backend', $this->getTitle())))
            .' - '.$this->pageTitle
        ); ?></title>
    <?php } else { ?>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<?php if(!user()->isGuest) {
    ?>
    <?php $this->widget('TbNavbar', array(
        'collapse' => true,
        'brand' => CHtml::encode(Yii::app()->name),
        'brandUrl' => array('/page/admin'),
        'items' => array(
            array(
                'class' => 'TbMenu',
                'items' => array(
                    array('label' => Yii::t('backend', 'Requests'),
                        'url' => array('/request'),
                        'visible' => user()->checkAccess('Request.*'),
                        'active' => $this->getId() === 'request',
                        'active' => in_array($this->getId(), array('request')),
                    ),
                    array('label' => Yii::t('backend', 'Finances'),
                        'url' => array('/finance'),
                        'visible' => user()->checkAccess('Finance.*'),
                        'active' => $this->getId() === 'finance',
                        'active' => in_array($this->getId(), array('finance')),
                    ),
                    array('label' => Yii::t('backend', 'Companies'),
                        'url' => array('/company'),
                        'visible' => user()->checkAccess('Company.*'),
                        'active' => $this->getId() === 'company',
                        'active' => in_array($this->getId(), array('company')),
                    ),
                    array('label' => Yii::t('backend', 'Users'),
                        'url' => array('/user'),
                        'visible' => user()->checkAccess('User.*'),
                        'active' => $this->getId() === 'user',
                        'active' => in_array($this->getId(), array('user')),
                    ),
                    array('label' => Yii::t('backend', 'Cards'),
                        'url' => array('/card'),
                        'visible' => user()->checkAccess('Card.*'),
                        'active' => $this->getId() === 'card',
                        'active' => in_array($this->getId(), array('card')),
                    ),

                   /*array('label' => Yii::t('backend', 'Companies'),
                        'url' => array('#'),
                        'visible' => user()->checkAccess('Company.*'),
                        'active' => in_array($this->getId(), array('company','companygroup','finance')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Companygroups'),
                                'url' => array('/companygroup'),
                                'visible' => user()->checkAccess('Companygroup.*'),
                                'active' => $this->getId() === 'companygroup',
                            ),
                            array('label' => Yii::t('backend', 'Companies'),
                                'url' => array('/company'),
                                'visible' => user()->checkAccess('Company.*'),
                                'active' => $this->getId() === 'company',
                            ),
                            array('label' => Yii::t('backend', 'Finances'),
                                'url' => array('/finance'),
                                'visible' => user()->checkAccess('Finances.*'),
                                'active' => $this->getId() === 'finance',
                            ),
                            array('label' => Yii::t('backend', 'Companygroup Services'),
                                'url' => array('/companygroupService'),
                                'visible' => user()->checkAccess('Companygroup Services.*'),
                                'active' => $this->getId() === 'companygroupService',
                            ),
                           
                        ),
                    ),*/

                    array('label' => Yii::t('backend', 'Login'),
                        'url' => array('/default/login'),
                        'visible' => user()->isGuest
                    ),
                ),
            ),
            array(
                'class' => 'TbMenu',
                'items' => array(
                    '---',
                    array('label' => Yii::t('backend', 'Go to site'), 'url' => '/', 'linkOptions' => array('target' => '_blank')),
                ),
            ),

            '<p class="navbar-text pull-right">'
                .Yii::t('backend', 'Logged in as <strong>{name}</strong>', array('{name}' => user()->getDisplayName()))
                .' <a href="'.$this->createUrl('/site/logout').'">'.Yii::t('backend', '(logout)').'</a></p>',
        ),
    )); ?>
<?php } ?>

<div class="container-fluid">

    <?php echo $content; ?>

    <footer>
    </footer>

</div>
<!-- .container-fluid -->

</body>
</html>