<?php
/** @var $this CompanygroupUserController */
/** @var $model CompanygroupUser */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Companygroup Users') => array('admin'),
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
                'filter' => CHtml::listData(Companygroup::model()->sort('title ASC')->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'user_id',
                'value' => '$data->user ? $data->user->email : null',
                'filter' => CHtml::listData(User::model()->sort('email ASC')->findAll(), 'id', 'email'),
            ),
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>