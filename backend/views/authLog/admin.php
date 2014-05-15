<?php
/** @var $this AuthLogController */
/** @var $model AuthLog */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Auth Logs') => array('admin'),
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
                'filter' => CHtml::listData(User::model()->sort('email')->active()->findAll(), 'id', 'email'),
            ),
            array(
                'name' => 'time',
                'value' => 'date("H:i:s d.m.Y",$data->time)',
                'filter' => '',
            ),
            'success',
        ),
    )); ?>

<?php $this->endWidget(); ?>