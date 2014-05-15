<?php
/** @var $this CompanygroupServiceController */
/** @var $model CompanygroupService */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Companygroup Services') => array('admin'),
	Yii::t('backend', 'Manage'),
);
?>

<h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>

    <?php $this->widget('backend.components.AdminView', array(
        'model' => $model,
        'columns' => array(
            'id',
            array(
                'name' => 'companygroup_id',
                'value' => '$data->companygroup ? $data->companygroup->title : null',
                'filter' => CHtml::listData(Companygroup::model()->findAll('t.id!=1'), 'id', 'title'),
            ),
            array(
                'name' => 'service_id',
                'value' => '$data->service ? $data->service->title : null',
                'filter' => CHtml::listData(Paysystem::model()->findAll('t.addService=1'), 'id', 'title'),
            ),
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>