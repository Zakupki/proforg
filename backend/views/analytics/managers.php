<?php
/** @var $this BillsController */
/** @var $model Payments */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
    Yii::t('backend', 'Payments') => array('admin'),
    Yii::t('backend', 'Manage'),
);
?>

    <h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>
<?
$dateisOn = $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'Analytics[date_first]',
        'language' => 'ru',
        'value' => $model->date_first,
        // additional javascript options for the date picker plugin
        'options'=>array(
            'showAnim'=>'fold',
            'dateFormat'=>'yy-mm-dd',
            'changeMonth' => 'true',
            'changeYear'=>'true',
            'constrainInput' => 'false',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;width:70px;',
        ),
        // DONT FORGET TO ADD TRUE this will create the datepicker return as string
    ),true)
    . ' по ' .
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        // 'model'=>$model,
        'name' => 'Analytics[date_last]',
        'language' => 'ru',
        'value' => $model->date_last,
        // additional javascript options for the date picker plugin
        'options'=>array(
            'showAnim'=>'fold',
            'dateFormat'=>'yy-mm-dd',
            'changeMonth' => 'true',
            'changeYear'=>'true',
            'constrainInput' => 'false',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;width:70px',
        ),
        // DONT FORGET TO ADD TRUE this will create the datepicker return as string
    ),true);
?>
<?php $this->widget('backend.components.AdminBillView', array(
    'model' => $model,
    'afterAjaxUpdate'=>"
        function(){
                    jQuery('#Analytics_date_first').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                    jQuery('#Analytics_date_last').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                  }
        ",
    'columns' => array(
        array(
            'name' => 'company_id',
            'value' => '$data->company ? $data->company->title : null',
            'filter' => CHtml::listData(Company::model()->sort('t.title')->with(array(
                        'companygroup',
                        'purchase'=>array(
                            // записи нам не потрібні
                            'select'=>false,
                            // але потрібно вибрати тільки користувачів з опублікованими записами
                            'joinType'=>'INNER JOIN',
                            'condition'=>'purchase.purchasestate_id=4',
                        )))->findAll(), 'id', 'title','companygroup.title'),
        ),
        array(
            'name' => 'user_id',
            'value' => '$data->user ? $data->user->email : null',
            'filter' => CHtml::listData(User::model()->sort('email')->with(array(
                    'purchase'=>array(
                        // записи нам не потрібні
                        'select'=>false,
                        // але потрібно вибрати тільки користувачів з опублікованими записами
                        'joinType'=>'INNER JOIN',
                        'condition'=>'purchase.purchasestate_id=4',
                    )))->findAll(), 'id', 'email'),
        ),
        array(
            'name' => 'purchase_num',
            'value' => '$data->purchase_num ? $data->purchase_num : null',
            'filter' => '',
        ),
        array(
            'name' => 'total',
            'value' => '$data->total ? $data->total : null',
            'filter' => '',
        ),
        array(
            'name' => 'economy_sum',
            'value' => '$data->economy_sum ? $data->economy_sum : null',
            'filter' => '',
        ),
        array(
            'name' => 'avg_company_num',
            'value' => '$data->avg_company_num ? $data->avg_company_num : null',
            'filter' => '',
        ),
        array(
            'name' => 'not_concurent',
            'value' => '$data->not_concurent ? $data->not_concurent : null',
            'filter' => '',
        ),
        array(
            'name'=>'date_closed',
            'filter'=>$dateisOn,
            'value'=>''
        ),
    ),
)); ?>

<?php $this->endWidget(); ?>