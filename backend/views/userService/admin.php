<?php
/** @var $this UserServiceController */
/** @var $model UserService */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'User Services') => array('admin'),
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
                'value' => '$data->user ? $data->user->title : null',
                'filter' => CHtml::listData(User::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'service_id',
                'value' => '$data->service ? $data->service->title : null',
                'filter' => CHtml::listData(Paysystem::model()->findAllByAttributes(array('addService'=>1)), 'id', 'title'),
            ),
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>