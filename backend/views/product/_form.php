<?php
/** @var $this ProductController */
/** @var $model Product */
/** @var $models Product[] */
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

    <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
    <?php echo $form->dropDownListRow($model, 'purchase_id', Purchase::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'ed_izm', array('class' => 'span9', 'maxlength' => 20)); ?>
    <?php echo $form->textFieldRow($model, 'kol', array('class' => 'span9', 'maxlength' => 15)); ?>
    <?php echo $form->textFieldRow($model, 'pickup', array('class' => 'span9', 'maxlength' => 5)); ?>
    <?php echo $form->textFieldRow($model, 'comment', array('class' => 'span9', 'maxlength' => 256)); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
