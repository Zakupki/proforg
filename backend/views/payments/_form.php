<?php
/** @var $this PaymentsController */
/** @var $model Payments */
/** @var $models Payments[] */
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

    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->active()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'company_id', Company::model()->active()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'paysystem_id', Paysystem::model()->active()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'amount', array('class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'status', array('class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_create'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->textFieldRow($model, 'date_updated', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_updated'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>

<?php $this->endWidget(); ?>
