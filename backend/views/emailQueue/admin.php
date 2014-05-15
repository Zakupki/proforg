<?php
/** @var $this EmailQueueController */
/** @var $model EmailQueue */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Email Queues') => array('admin'),
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
            'from_name',
            'to_email',
            'subject',
            'max_attempts',
            'attempts',
            'success',
            array(
                'name'=>'message',
                'type'=>'raw',
                'value'=>'CHtml::link(CHtml::encode("Просмотреть"), "/backend.php?r=emailQueue/body&id=".$data->id,array("target"=>"_blank"))'
                /*'value'=>'<a href="">$data->id</a>',*/
            ),
            'date_published',
            'last_attempt',
            'date_sent',
        ),
    )); ?>

<?php $this->endWidget(); ?>