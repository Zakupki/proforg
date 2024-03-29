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
            'status',
            'sort',
            'date_create',
            array(
                'name' => 'finance_id',
                'value' => '$data->finance ? $data->finance->title : null',
                'filter' => CHtml::listData(Finance::model()->findAll(), 'id', 'title'),
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>