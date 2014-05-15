<?php
/** @var $this EmailQueueController */
/** @var $model EmailQueue */
/** @var $models EmailQueue[] */
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

    <?php echo $form->textFieldRow($model, 'from_name', array('class' => 'span9', 'maxlength' => 64)); ?>
    <?php echo $form->textFieldRow($model, 'from_email', array('class' => 'span9', 'maxlength' => 128)); ?>
    <?php echo $form->textFieldRow($model, 'to_email', array('class' => 'span9', 'maxlength' => 128)); ?>
    <?php echo $form->textFieldRow($model, 'attachfile', array('class' => 'span9', 'maxlength' => 255)); ?>
    <?php echo $form->textAreaRow($model, 'message', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'max_attempts', array('class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'attempts', array('class' => 'span9')); ?>
    <?php echo $form->checkBoxRow($model, 'success'); ?>
    <?php echo $form->textFieldRow($model, 'date_published', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_published'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->textFieldRow($model, 'last_attempt', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'last_attempt'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->textFieldRow($model, 'date_sent', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_sent'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>

<?php $this->endWidget(); ?>
