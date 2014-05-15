<?php
/** @var $this ProductController */
/** @var $model Product */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Products') => array('admin'),
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
                'name' => 'tagg_id',
                'value' => '$data->tag_id',
            ),
            array(
                'name' => 'tag_id',
                'value' => '$data->tag ? $data->tagtitle : null',
                'filter' => CHtml::listData(Tag::model()->sort('t.title ASC')->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'taggroup_id',
                'value' => '$data->taggrouptitle ? $data->taggrouptitle : null',
                'filter' => CHtml::listData(Taggroup::model()->sort('t.title ASC')->findAll(), 'id', 'title'),
            ),
            array(
                'class'=>'CLinkColumn',
                'labelExpression'=>'"Назначить"',
                'urlExpression'=>'"/backend.php?r=tag/update&id=".$data->tag_id',
                'linkHtmlOptions'=>array('target'=>'_blank')
            ),
            'date_create',
            array(
                'class'=>'CLinkColumn',
                'header'=>'Закупка',
                'labelExpression'=>'$data->purchase_id',
                'urlExpression'=>'"/backend.php?r=purchase/update&id=".$data->purchase_id',
                'linkHtmlOptions'=>array('target'=>'_blank')
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>