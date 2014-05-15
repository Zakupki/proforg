<?php
/** @var $this HelpController */
/** @var $model Help */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Helps') => array('admin'),
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
                'name' => 'helpgroup_id',
                'value' => '$data->helpgroup ? $data->helpgroup->title : null',
                'filter' => CHtml::listData(Helpgroup::model()->findAll(), 'id', 'title'),
            ),
            'detail_text',
            'sort',
            'status',
        ),
    )); ?>

<?php $this->endWidget(); ?>