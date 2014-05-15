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
                   array('label' => Yii::t('backend', 'Companies'),
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
                    ),
                    array('label' => Yii::t('backend', 'Purchases'),
                        'url' => array('#'),
                        'visible' => user()->checkAccess('Purchase.*'),
                        'active' => in_array($this->getId(), array('purchase','product')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Purchases'),
                                'url' => array('/purchase'),
                                'visible' => user()->checkAccess('Purchase.*'),
                                'active' => $this->getId() === 'purchase',
                            ),
                            array('label' => Yii::t('backend', 'Products'),
                                'url' => array('/product'),
                                'visible' => user()->checkAccess('Product.*'),
                                'active' => $this->getId() === 'product',
                            ),
                            array('label' => Yii::t('backend', 'Offers'),
                                'url' => array('/offer'),
                                'visible' => user()->checkAccess('Offer.*'),
                                'active' => $this->getId() === 'offer',
                            ),
                        ),
                    ),
                   /* array('label' => Yii::t('backend', 'Purchases'),
                        'url' => array('/purchase'),
                        'visible' => user()->checkAccess('Purchase.*'),
                        'active' => $this->getId() === 'purchase',
                    ),*/
                    array('label' => Yii::t('backend', 'Users'),
                        'url' => array('#'),
                        'visible' => user()->checkAccess('User.*'),
                        'active' => in_array($this->getId(), array('user','usertype','companygroupUser')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Usertype'),
                                'url' => array('/usertype'),
                                'visible' => user()->checkAccess('Usertype.*'),
                                'active' => $this->getId() === 'usertype',
                            ),
                            array('label' => Yii::t('backend', 'Users'),
                                'url' => array('/user'),
                                'visible' => user()->checkAccess('User.*'),
                                'active' => $this->getId() === 'user',
                            ),
                              array('label' => Yii::t('backend', 'Companygroup Users'),
                                'url' => array('/companygroupUser'),
                                'visible' => user()->checkAccess('CompanygroupUser.*'),
                                'active' => $this->getId() === 'companygroupUser',
                            ),
                            array('label' => Yii::t('backend', 'Company Roles'),
                                'url' => array('/companyrole'),
                                'visible' => user()->checkAccess('Companyroles.*'),
                                'active' => $this->getId() === 'companyrole',
                            ),
                            array('label' => Yii::t('backend', 'Company Users'),
                                'url' => array('/companyUser'),
                                'visible' => user()->checkAccess('CompanyUser.*'),
                                'active' => $this->getId() === 'companyUser',
                            ),
                            array('label' => Yii::t('backend', 'Auth Log'),
                                'url' => array('/authLog'),
                                'visible' => user()->checkAccess('AutLog.*'),
                                'active' => $this->getId() === 'authLog',
                            ),
                           
                        ),
                    ),
                    array('label' => Yii::t('backend', 'System'),
                        'url' => '#',
                        'visible' => user()->checkAccess('User.*'),
                        'active' => in_array($this->getId(), array('option', 'language', 'file', 'rights','region','page','helpgroup','taggroup','tag')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Common'),
                                'url' => array('options/optionGroup', 'group' => 'Common'),
                                'visible' => user()->checkAccess('Options.*'),
                                'active' => $this->getId() === 'options' && request()->getQuery('group') === 'Common',
                            ),
                            array('label' => Yii::t('backend', 'Loan'),
                                'url' => array('/loan'),
                                'visible' => user()->checkAccess('Loan.*'),
                                'active' => $this->getId() === 'loan',
                            ),
                            array('label' => Yii::t('backend', 'Options'),
                                'url' => array('/option'),
                                'visible' => user()->checkAccess('Option.*'),
                                'active' => $this->getId() === 'option',
                            ),
                            array('label' => Yii::t('backend', 'Mail'),
                                'url' => array('/emailQueue'),
                                'visible' => user()->checkAccess('EmailQueue.*'),
                                'active' => $this->getId() === 'emailQueue',
                            ),
                            array('label' => Yii::t('backend', 'Units'),
                                'url' => array('/unit'),
                                'visible' => user()->checkAccess('Unit.*'),
                                'active' => $this->getId() === 'unit',
                            ),
                            array('label' => Yii::t('backend', 'Pages'),
                                'url' => array('/page'),
                                'visible' => user()->checkAccess('Page.*'),
                                'active' => $this->getId() === 'page',
                            ),
                            array('label' => Yii::t('backend', 'Helpgroups'),
                                'url' => array('/helpgroup'),
                                'visible' => user()->checkAccess('Helpgroup.*'),
                                'active' => $this->getId() === 'helpgroup',
                            ),
                            array('label' => Yii::t('backend', 'Files'),
                                'url' => array('/file'),
                                'visible' => user()->checkAccess(Rights::module()->superuserName),
                                'active' => $this->getId() === 'file',
                            ),
                            array('label' => Yii::t('backend', 'Regions'),
                                'url' => array('/region'),
                                'visible' => user()->checkAccess('Region.*'),
                                'active' => $this->getId() === 'region',
                            ),
                            array('label' => Yii::t('backend', 'City'),
                                'url' => array('/city'),
                                'visible' => user()->checkAccess('City.*'),
                                'active' => $this->getId() === 'city',
                            ),
                            array('label' => Yii::t('backend', 'Paysystems'),
                                'url' => array('/paysystem'),
                                'visible' => user()->checkAccess('Paysystems.*'),
                                'active' => $this->getId() === 'paysystem',
                            ),
                            array('label' => Yii::t('backend', 'Payments'),
                                'url' => array('/payments'),
                                'visible' => user()->checkAccess('Payments.*'),
                                'active' => $this->getId() === 'payments',
                            ),
                            array('label' => Yii::t('backend', 'Bills'),
                                'url' => array('/bills'),
                                'visible' => user()->checkAccess('Bills.*'),
                                'active' => $this->getId() === 'bills',
                            ),
                            array('label' => Yii::t('backend', 'Taggroups'),
                                'url' => array('/taggroup'),
                                'visible' => user()->checkAccess('Taggroups.*'),
                                'active' => $this->getId() === 'taggroup',
                            ),
                            array('label' => Yii::t('backend', 'Tags'),
                                'url' => array('/tag'),
                                'visible' => user()->checkAccess('Tags.*'),
                                'active' => $this->getId() === 'tag',
                            ),
                            array('label' => Yii::t('backend', 'Markets'),
                                'url' => array('#'),
                                'visible' => user()->checkAccess('Markettype.*'),
                                'active' => in_array($this->getId(), array('markettype','market','marketCompany')),
                                'items' => array(
                                    array('label' => Yii::t('backend', 'Markettypes'),
                                        'url' => array('/markettype'),
                                        'visible' => user()->checkAccess('Markettype.*'),
                                        'active' => $this->getId() === 'markettype',
                                    ),
                                    array('label' => Yii::t('backend', 'Markets'),
                                        'url' => array('/market'),
                                        'visible' => user()->checkAccess('Markettypes.*'),
                                        'active' => $this->getId() === 'market',
                                    ),
                                    array('label' => Yii::t('backend', 'Market Companies'),
                                        'url' => array('/marketCompany'),
                                        'visible' => user()->checkAccess('MarketCompany.*'),
                                        'active' => $this->getId() === 'marketCompany',
                                    ),

                                ),
                            ),
                        )
                    ),
                    array('label' => Yii::t('backend', 'Analytics'),
                        'url' => array('#'),
                        'visible' => user()->checkAccess('Analytics.*'),
                        'active' => in_array($this->getId(), array('purchaseanalytics','analytics')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Purchase Analytics'),
                                'url' => array('/purchaseanalytics'),
                                'visible' => user()->checkAccess('Purchaseanalytics.*'),
                                'active' => $this->getId() === 'purchaseanalytics',
                            ),
                            array('label' => Yii::t('backend', 'Disposition'),
                                'url' => array('/disposition'),
                                'visible' => user()->checkAccess('Disposition.*'),
                                'active' => $this->getId() === 'disposition',
                            ),
                            array('label' => Yii::t('backend', 'Analytics'),
                                'url' => array('analytics/managers'),
                                'visible' => user()->checkAccess('Analytics.*'),
                                'active' => $this->getId() === 'analytics',
                            ),
                        ),
                    ),
                    array('label' => Yii::t('backend', 'Callcenter'),
                        'url' => array('#'),
                        'visible' => user()->checkAccess('Callcenter.*'),
                        'active' => in_array($this->getId(), array('task')),
                        'items' => array(
                            array('label' => Yii::t('backend', 'Task'),
                                'url' => array('/task'),
                                'visible' => user()->checkAccess('Task.*'),
                                'active' => $this->getId() === 'task',
                            ),

                        ),
                    ),

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