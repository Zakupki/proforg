<?php
/** @var $this RequestController */
/** @var $model Request */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Requests') => array('admin'),
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
                'name' => 'company_id',
                'value' => '$data->company ? $data->company->title : null',
                'filter' => CHtml::listData(Company::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'finance_id',
                'value' => '$data->finance ? $data->finance->title : null',
                'filter' => CHtml::listData(Finance::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'user_id',
                'value' => '$data->user ? $data->user->title : null',
                'filter' => CHtml::listData(User::model()->findAll(), 'id', 'title'),
            ),
            'date_create',
            'status',
            'sort',
            'value',
            'available',
            'left',
        ),
    )); ?>

<?php $this->endWidget(); ?>