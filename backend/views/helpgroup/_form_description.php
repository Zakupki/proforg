
<?php
/** @var $this HelpgroupController */
/** @var $model Helpgroup */
/** @var $models Helpgroup[] */
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
<?php echo $form->dropDownListRow($model, 'page_id', Page::model()->active()->listData()); ?>
<?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
