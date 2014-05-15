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

    <?php echo $form->dropDownListRow($model, 'fincompany_id', CHtml::listData(Company::model()->with('companygroup')->sort('companygroup.title,t.title')->findAll('companytype_id=2'),'id','title','companygroup.title')); ?>
    <?php echo $form->dropDownListRow($model, 'company_id', CHtml::listData(Company::model()->with('companygroup')->sort('companygroup.title,t.title')->findAll('companytype_id=1'), 'id', 'title','companygroup.title')); ?>
    <?php echo $form->textFieldRow($model, 'percent', array('class' => 'span2')); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
