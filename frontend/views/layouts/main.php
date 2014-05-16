<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" href="/css/main.css">

    <script src="/js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>

<div id="top">
    <div class="cw clearfix">
        <?if(Yii::app()->user->getId()){?>
        <div class="l">
            <div class="b profile company">Название компании</div>
        </div>
        <div class="r">
                <a class="b" href="/site/logout/">Выход</a>
        </div>
        <?}?>
        <a class="logo" href="#"><img src="/img/logo.png"></a>
    </div>
</div>

<div id="main">
    <?=$content;?>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>

<script src="/js/plugins.js"></script>
<script src="/js/main.js"></script>
</body>
</html>
