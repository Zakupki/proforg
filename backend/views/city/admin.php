<?php
/** @var $this CityController */
/** @var $model City */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Cities') => array('admin'),
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
                'name' => 'region_id',
                'value' => '$data->region ? $data->region->title : null',
                'filter' => CHtml::listData(Region::model()->findAll(), 'id', 'title'),
            ),
            'title',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>