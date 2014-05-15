<?php
/** @var $this CompanyUserController */
/** @var $model CompanyUser */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Company Users') => array('admin'),
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
                'name' => 'user_id',
                'value' => '$data->user ? $data->user->email : null',
                'filter' => CHtml::listData(User::model()->sort('email')->findAll(), 'id', 'email'),
            ),
            array(
                'name' => 'companyrole_id',
                'value' => '$data->companyrole ? $data->companyrole->title : null',
                'filter' => CHtml::listData(Companyrole::model()->findAll(), 'id', 'title'),
            ),
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>