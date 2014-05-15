<?php
/*
$gridColumns=array(
    'id',
    array('name'=>'Предложение',
          'value'=>'$data->tag->title'),
    array('name'=>'Цена',
        'value'=>'$data->price'),
    array('name'=>'Количество',
        'value'=>'$data->amount'),
);
$gridDataProvider=array();
//$gridDataProvider=Offer::model()->findByAttributes(array('product_id'=>$_GET['id']));
$products=Offer::model()->with('tag')->findAllByAttributes(array('product_id'=>$_GET['id']));
$gridDataProvider= new CArrayDataProvider($products, array('pagination' => array('pageSize' => 9999)));
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $gridDataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));*/
$model=new Offer;
$products=Offer::model()->with('tag')->sort('t.date_create DESC')->findAll('t.pid=:pid OR t.id=:pid',array('pid'=>$_GET['id']));
$this->widget('TbGridView', array(
    'dataProvider' => new CArrayDataProvider($products, array('pagination' => array('pageSize' => 9999))),
    'template' => '{items}',
    'columns' => array(
        array(
            'htmlOptions' => array('nowrap' => 'nowrap'),
            'class' => 'CButtonColumn',
            'template' => '{update} {clone} {delete}',
            'updateButtonUrl' => 'url("offer/update", array("id" => $data->id, "originUrl" => retUrl("#form-paragraphs")))',
            'deleteButtonUrl' => 'url("offer/delete", array("id" => $data->id))',
            'buttons' => array(
                'clone' => array(
                    'label' => Yii::t('cp', 'Clone'),
                    'url' => 'url("paragraph/clone", array("id" => $data->id, "originUrl" => retUrl("#form-paragraphs")))',
                    'imageUrl' => '/backend/img/clone.png',
                    'options' => array(),
                    'visible' => 'true',
                ),
            )
        ),
        /*array(
            'header' => $model->getAttributeLabel('paragraphtype'),
            'name' => 'paragraphtype_id',
            'value' => '$data->paragraphtype->title'
        ),
        array(
            'header' => $model->getAttributeLabel('title'),
            'name' => 'title',
        ),
        array(
            'header' => $model->getAttributeLabel('status'),
            'name' => 'status',
            'value' => '$data->status ? Yii::t("backend", "Enabled") : Yii::t("backend", "Disabled")'
        ),*/
        array(
            'header' => 'Номер',
            'name' => 'id',
        ),
        array(
            'header' => 'Предложение',
            'name' => 'title',
        ),
        array(
            'header' => 'Снижение',
            'name' => 'price_reduce',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
        array(
            'header' => 'Исключить',
            'name' => 'exclude_lose',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
        array(
            'name'=>'Компания',
            'value'=>'$data->company->title',
            'htmlOptions' => array('style' => 'width: 300px')
        ),
        array(
            'name'=>'Пользователь',
            'value'=>'$data->user->email',
            'htmlOptions' => array('style' => 'width: 200px')
        ),
        array(
            'header' => 'Цена',
            'name' => 'price',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
        array(
            'header' => 'Количество',
            'value' => '$data->amount',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
        array(
            'header' => 'Доставка',
            'value' => '$data->delivery',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
        array(
            'header' => 'Отсрочка',
            'value' => '$data->delay',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
        array(
            'header' => 'Дата',
            'name' => 'date_create',
            'htmlOptions' => array('style' => 'width: 140px')
        ),
        array(
            'header' => 'Победа',
            'name' => 'winner',
            'htmlOptions' => array('style' => 'width: 60px')
        ),

    )
)) ?>