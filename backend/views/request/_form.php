<?php
/** @var $this RequestController */
/** @var $model Request */
/** @var $models Request[] */
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

    <?php echo $form->dropDownListRow($model, 'company_id', Company::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'finance_id', Finance::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, 'date_create'),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'value', array('class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'available', array('class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'left', array('class' => 'span9')); ?>

<?php $this->endWidget(); ?>
