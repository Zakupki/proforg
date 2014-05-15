<?php
/** @var $this CompanygroupServiceController */
/** @var $model CompanygroupService */
/** @var $models CompanygroupService[] */
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

    <?php echo $form->dropDownListRow($model, 'companygroup_id', CHtml::listData(Companygroup::model()->sort('t.title')->findAll('t.id!=1'), 'id', 'title')); ?>
    <?php echo $form->dropDownListRow($model, 'service_id', CHtml::listData(Paysystem::model()->sort('t.title')->findAll('t.addService=1'), 'id', 'title')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
