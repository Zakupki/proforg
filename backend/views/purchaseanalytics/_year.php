

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>
<?php $this->endWidget(); ?>

<?
$purchasesYear=Purchaseanalytics::model()->newPurchaseStatsYear();
$closedpurchasesYear=Purchaseanalytics::model()->closedPurchaseStatsYear();

$createdArr=array();
$total_created=0;
foreach($purchasesYear AS $row){
    $createdArr[]=array((intval($row['key'])+4*60*60)*1000,intval($row['value']));
    $total_created=$total_created+intval($row['value']);
}

$closedArr=array();
$total_closed=0;
foreach($closedpurchasesYear AS $row){
    $closedArr[]=array((intval($row['key'])+4*60*60)*1000,intval($row['value']));
    $total_closed=$total_closed+intval($row['value']);
}
//CVarDumper::dump($createdArr,10,true);
?>
<div style="width:100%">
    <div style="width:70%; float:left;">
<?
//echo date('Y-m-d h:i:s',1388347200);
$this->widget('bootstrap.widgets.TbHighCharts', array(
    'options'=>array(
        'lang'=>array(
            'downloadJPEG'=>'Скачать JPEG',
            'shortMonths'=>array("Jan1" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"),
            'weekdays'=>array('1','2','3','4','5','6','7')
        ),
        'title' => array('text' => 'Статистика создания/закрытия планов'),
        'xAxis' => array(
            /*'categories' => $createdArrKeys,*/
            'type'=> 'datetime',
        ),
        'yAxis' => array(
            'title' => array('text' => 'Закупки')
        ),
        /*'plotOptions'=>array (
            'series'=>array(
                'pointStart'=>1,
                'pointInterval'=>9
            )
        ),*/
        'series' => array(
            array('name' => 'Новые планы', 'data' => $createdArr),
            array('name' => 'Закрытые планы', 'data' => $closedArr),
            /*array('name' => 'John', 'data' => array(5, 7, 3,5, 7, 3,5, 7, 3))*/
        )
    )
));
?>
    </div>
    <div style="width:30%; float:left;">
<?
$this->widget('bootstrap.widgets.TbHighCharts', array(
    'options'=>array(
        'chart'=>array('type'=>'column'),
        'title' => array('text' => 'Статистика количества планов'),
        'xAxis' => array(
            'type'=>'category',
            'categories' => array('Новые','Закрытые'),
        ),
        'yAxis' => array(
            'title' => array('text' => 'Закупки')
        ),
        /*'plotOptions'=>array (
            'series'=>array(
                'pointStart'=>1,
                'pointInterval'=>9
            )
        ),*/
        'tooltip'=>array(
            'headerFormat'=>''
        ),
        'plotOptions'=>array(
            'series'=>array(
                'borderWidth'=>0,
                'dataLabels'=>array(
                    'enabled'=>true,
                )
            ),
        ),
        'series' => array(
            array('name' => 'Количество планов', 'colorByPoint'=> true, 'data' => array($total_created,$total_closed)),
            /*array('name' => 'John', 'data' => array(5, 7, 3,5, 7, 3,5, 7, 3))*/
        ),
    )
));
?>
    </div>
</div>