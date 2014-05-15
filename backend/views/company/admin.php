<?php
/** @var $this CompanyController */
/** @var $model Company */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Companies') => array('admin'),
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
            'title',
            array(
                'name' => 'companygroup_id',
                'value' => '$data->companygroup ? $data->companygroup->title : null',
                'filter' => CHtml::listData(Companygroup::model()->findAll(), 'id', 'title'),
            ),
            'date_create',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>