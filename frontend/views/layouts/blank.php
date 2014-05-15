<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?=Option::getOpt('seotitle');?></title>
    <meta name="description" content="<?=Option::getOpt('seodescription');?>">
    <meta name="keywords" content="<?=Option::getOpt('seokeywords');?>">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="/css/main.css?v=<?= time(); ?>">
    <script src="/js/vendor/modernizr-2.6.2.min.js"></script>
    <link rel="shortcut icon" href="/fav.ico" type="image/x-icon"/>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-46941778-1', 'zakupki-online.com');
        ga('send', 'pageview');

    </script>
</head>
<body>
    <?
    $this->Widget('frontend.extensions.highcharts.HighchartsWidget', array(
        'options'=>array(
            'title' => array('text' => 'Fruit Consumption'),
            'xAxis' => array(
                'categories' => array('Apples', 'Bananas', 'Oranges')
            ),
            'yAxis' => array(
                'title' => array('text' => 'Fruit eaten')
            ),
            'series' => array(
                array('name' => 'Jane', 'data' => array(1, 0, 4)),
                array('name' => 'John', 'data' => array(5, 7, 3))
            )
        )
    ));
    ?>
</body>
</html>
