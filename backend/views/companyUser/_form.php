<?php
/** @var $this CompanyUserController */
/** @var $model CompanyUser */
/** @var $models CompanyUser[] */
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

    <?php echo $form->dropDownListRow($model, 'company_id',  Company::model()->sort('title ASC')->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->sort('email ASC')->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'companyrole_id', Companyrole::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
