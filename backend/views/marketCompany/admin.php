<?php
/** @var $this MarketCompanyController */
/** @var $model MarketCompany */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Market Companies') => array('admin'),
	Yii::t('backend', 'Manage'),
);
?>

<h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>

    <?php $gridWidget=$this->widget('backend.components.AdminView', array(
        'model' => $model,
        'columns' => array(
            'id',
            array(
                'name' => 'company_id',
                'value' => '$data->company ? $data->company->title : null',
                'filter' => CHtml::listData(Company::model()->sort('title')->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'market_id',
                'value' => '$data->market ? $data->market->title : null',
                'filter' => CHtml::listData(Market::model()->sort('title')->findAll(), 'id', 'title'),
            ),
            'sort',
            'status',
        ),
    )); ?>
<?php $this->endWidget(); ?>
<? $this->renderExportGridButton($gridWidget,'Экспорт в эксель',array('class'=>'btn btn-success pull-right'));?>