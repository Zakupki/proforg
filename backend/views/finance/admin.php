<?php
/** @var $this FinanceController */
/** @var $model Finance */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Finances') => array('admin'),
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
                'name' => 'fincompany_id',
                'value' => '$data->fincompany ? $data->fincompany->title : null',
                'filter' => CHtml::listData(Company::model()->findAll('companytype_id=2'), 'id', 'title'),
            ),
            array(
                'name' => 'company_id',
                'value' => '$data->company ? $data->company->title : null',
                'filter' => CHtml::listData(Company::model()->findAll('companytype_id=1'), 'id', 'title'),
            ),
            'percent',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>