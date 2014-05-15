<?php
/** @var $this PaymentsController */
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

    <?php $this->widget('backend.components.AdminView', array(
        'model' => $model,
        'columns' => array(
            'id',
            array(
                'name' => 'user_id',
                'value' => '$data->user ? $data->user->email : null',
                'filter' => CHtml::listData(User::model()->findAll(), 'id', 'email'),
            ),
            array(
                'name' => 'company_id',
                'value' => '$data->company ? $data->company->title : null',
                'filter' => CHtml::listData(Company::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'paysystem_id',
                'value' => '$data->paysystem ? $data->paysystem->title : null',
                'filter' => CHtml::listData(Paysystem::model()->findAll(), 'id', 'title'),
            ),
            'amount',
            'status',
            'date_create',
        ),
    )); ?>

<?php $this->endWidget(); ?>