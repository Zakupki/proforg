<?php
/** @var $this OfferController */
/** @var $model Offer */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
	Yii::t('backend', 'Offers') => array('admin'),
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
            'pid',
            array(
                'name' => 'purchase_id',
                'value' => '$data->product->purchase_id',
            ),
            array(
                'name' => 'tag_id',
                'value' => '$data->tag ? $data->tag->title : null',
                'filter' => CHtml::listData(Tag::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'product_id',
                'value' => '$data->product ? $data->product->tag->title : null',
                'filter' => CHtml::listData(Product::model()->with('tag')->findAll(), 'id', 'tag.title'),
            ),
            array(
                'name' => 'company_id',
                'value' => '$data->company ? $data->company->title : null',
                'filter' => CHtml::listData(Company::model()->findAll(), 'id', 'title'),
            ),
            array(
                'name' => 'user_id',
                'value' => '$data->user ? $data->user->email : null',
                'filter' => CHtml::listData(User::model()->findAll(), 'id', 'email'),
            ),
            'price',
            'amount',
            'delivery',
            'delay',
            'date_create',
            'winner',
            'price_reduce',
            'place',
        ),
    )); ?>

<?php $this->endWidget(); ?>