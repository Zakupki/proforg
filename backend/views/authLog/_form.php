<?php
/** @var $this AuthLogController */
/** @var $model AuthLog */
/** @var $models AuthLog[] */
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

    <?php echo $form->dropDownListRow($model, 'user_id', array()); ?>
    <?php echo $form->textFieldRow($model, 'email', array('class' => 'span9', 'maxlength' => 32)); ?>
    <?php echo $form->textFieldRow($model, 'ip', array('class' => 'span9', 'maxlength' => 10)); ?>
    <?php echo $form->textFieldRow($model, 'time', array('class' => 'span9', 'maxlength' => 11)); ?>
    <?php echo $form->checkBoxRow($model, 'success'); ?>

<?php $this->endWidget(); ?>
