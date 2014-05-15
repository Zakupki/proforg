<?php
/** @var $this OfferFileController */
/** @var $model OfferFile */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Offer Files') => array('admin'),
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
                'name' => 'offer_id',
                'value' => '$data->offer ? $data->offer->title : null',
                'filter' => CHtml::listData(Offer::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'file_id',
                'value' => '$data->file ? $data->file->title : null',
                'filter' => CHtml::listData(File::model()->findAll(), 'id', 'title'),
            ),
        ),
    )); ?>

<?php $this->endWidget(); ?>