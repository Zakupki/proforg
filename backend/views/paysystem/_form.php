<?php
/** @var $this PaysystemController */
/** @var $model Paysystem */
/** @var $models Paysystem[] */
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
    <?php echo $form->textFieldRow($model, 'price', array('class' => 'span9')); ?>
    <?php echo $form->textAreaRow($model, 'description', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php //echo $form->textFieldRow($model, 'model_name', array('class' => 'span9', 'maxlength' => 100)); ?>
    <?php //echo $form->textAreaRow($model, 'settings', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
