<?php
/** @var $this FinanceController */
/** @var $model Finance */
/** @var $models Finance[] */
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
    <?php echo $form->checkBoxRow($model, 'status'); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_create'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>

<?php $this->endWidget(); ?>
