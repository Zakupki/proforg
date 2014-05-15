<?
$this->widget(
    'bootstrap.widgets.TbDetailView',
    array(
        'data' => array(
            'id' => $model->id,
            'total' => $model->total,
            'economy_sum' => $model->economy_sum,
            'economy_percent' => (isset($model->economy_sum))?round(($model->economy_sum/$model->total)*100, 2, PHP_ROUND_HALF_UP):false,
            'lose_total' => $model->lose_total,
            'not_concurent' => $model->not_concurent,
            'avg_delay' => $model->avg_delay,
        ),
        'attributes' => array(
            array('name' => 'id', 'label' => 'Id'),
            array('name' => 'total', 'label' => 'Сумма закупки'),
            array('name' => 'economy_sum', 'label' => 'Сумма экономии'),
            array('name' => 'economy_percent', 'label' => '% экономии'),
            array('name' => 'lose_total', 'label' => 'Сумма Потерь'),
            array('name' => 'not_concurent', 'label' => 'Не конкурентный'),
            array('name' => 'avg_delay', 'label' => 'Средняя отсрочка'),
        ),
    )
);

if($model->purchasestate_id==4){
echo CHtml::ajaxSubmitButton('Пересчитать аналитику',Yii::app()->createUrl('purchase/recalculate'),
    array(
        'type'=>'POST',
        'data'=> 'js:{"id": '.$model->id.',"btoken":"'.Yii::app()->request->csrfToken.'" }',
        'success'=>'js:function(string){ alert(string); }'
    ),array('class'=>'btn btn-danger',));
}