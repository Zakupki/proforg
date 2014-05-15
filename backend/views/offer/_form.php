<?php
/** @var $this OfferController */
/** @var $model Offer */
/** @var $models Offer[] */
/** @var $form ActiveForm */
?>

<?php $form = $this->beginWidget('backend.components.ActiveForm', array(
    'model' => $model,
    'fieldsetLegend' => $legend,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
)); ?>

    <?php echo $form->dropDownListRow($model, 'tag_id', Tag::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'product_id', CHtml::listData(Product::model()->with('tag')->findAll(),'id','tag.title')); ?>
    <?php echo $form->textFieldRow($model, 'pid', array('class' => 'span2'));?>
    <?php echo $form->dropDownListRow($model, 'company_id', Company::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'price', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'amount', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'delivery'); ?>
    <?php echo $form->textFieldRow($model, 'delay', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_create'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->textAreaRow($model, 'comment', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->checkBoxRow($model, 'winner'); ?>
    <?php echo $form->checkBoxRow($model, 'reduction'); ?>
    <?php echo $form->checkBoxRow($model, 'exclude_lose'); ?>
    <?php echo $form->textFieldRow($model, 'price_reduce', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'place', array('class' => 'span2', 'maxlength' => 12)); ?>
    <?php echo $form->textFieldRow($model, 'totaloffers', array('class' => 'span2', 'maxlength' => 12)); ?>

<?php $this->endWidget(); ?>
