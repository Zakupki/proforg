<?php
/** @var $this CompanyInviteController */
/** @var $model CompanyInvite */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Company Invites') => array('admin'),
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
                'name' => 'company_id',
                'value' => '$data->company ? $data->company->title : null',
                'filter' => CHtml::listData(Company::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'purchase_id',
                'value' => '$data->purchase ? $data->purchase->title : null',
                'filter' => CHtml::listData(Purchase::model()->findAll(), 'id', 'title'),
            ),
            'date_create',
        ),
    )); ?>

<?php $this->endWidget(); ?>