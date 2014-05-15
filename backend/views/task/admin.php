<?php
/** @var $this PurchaseController */
/** @var $model Purchase */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Purchases') => array('admin'),
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
            'name' => 'Purchase[date_first]',
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
            'name' => 'Purchase[date_last]',
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
    <?
    $dateisOn2 = $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'Purchase[date_first2]',
        'language' => 'ru',
        'value' => $model->date_first2,
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
    .' по '.
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        // 'model'=>$model,
        'name' => 'Purchase[date_last2]',
        'language' => 'ru',
        'value' => $model->date_last2,
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


<?php $gridWidget=$this->widget('backend.components.AdminView', array(
        'model' => $model,
        'afterAjaxUpdate'=>"
        function(){
                    jQuery('#Purchase_date_first').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                    jQuery('#Purchase_date_last').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                    jQuery('#Purchase_date_first2').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                    jQuery('#Purchase_date_last2').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['ru'], {'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true','constrainInput':'false'}));
                  }
        ",
        'columns' => array(
            'id',
            array(
                'name' => 'company_id',
                'value' => '$data->company ? $data->company->title : null',
                'filter' => CHtml::listData(Company::model()->with('companygroup')->sort('companygroup.title,t.title')->findAll('companygroup.id!=1'), 'id', 'title','companygroup.title'),
            ),
            array(
                'name' => 'operator_id',
                'value' => '$data->operator_id ? $data->operator->email : null',
                'filter' => CHtml::listData(User::model()->sort('email')->findAll(), 'id', 'email'),
            ),
            array(
                'name'=>'date_create',
                'filter'=>$dateisOn,
                'value'=>'$data->date_create'
            ),
            array(
                'name'=>'date_closed',
                'filter'=>$dateisOn2,
                'value'=>'$data->date_closed'
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>
<? $this->renderExportGridButton($gridWidget,'Экспорт в эксель',array('class'=>'btn btn-success pull-right'));?>