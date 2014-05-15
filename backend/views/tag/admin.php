<?php
/** @var $this TagController */
/** @var $model Tag */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Tags') => array('admin'),
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
                'name' => 'taggroup_id',
                'value' => '$data->taggroup ? $data->taggroup->title : null',
                'filter' => CHtml::listData(Taggroup::model()->findAll(), 'id', 'title'),
            ),
            'date_create',
        ),
    )); ?>

<?php $this->endWidget(); ?>