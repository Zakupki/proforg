<?php
/** @var $this MarketController */
/** @var $model Market */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Markets') => array('admin'),
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
                'name' => 'markettype_id',
                'value' => '$data->markettype ? $data->markettype->title : null',
                'filter' => CHtml::listData(Markettype::model()->findAll(), 'id', 'title'),
            ),
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>