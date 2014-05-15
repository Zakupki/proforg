<?php
/** @var $this PurchaseController */
/** @var $model Purchase */
/** @var $models Purchase[] */
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

    <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 100)); ?>
    <?php echo $form->dropDownListRow($model, 'purchasestate_id', Purchasestate::model()->listData()); ?>
    <?php echo $form->dropDownListRow($model, 'market_id', CHtml::listData(Market::model()->with('markettype')->findAll(),'id','title','markettype.title')); ?>
    <?php echo $form->dropDownListRow($model, 'company_id', CHtml::listData(Company::model()->with('companygroup')->findAll(),'id','title','companygroup.title')); ?>
    <?php echo $form->dropDownListRow($model, 'user_id', User::model()->listData()); ?>
    <?php echo $form->textFieldRow($model, 'city', array('class' => 'span6', 'maxlength' => 100)); ?>
    <?php echo $form->textFieldRow($model, 'delay', array('class' => 'span2')); ?>
    <?php echo $form->textAreaRow($model, 'comment', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php $this->endWidget(); ?>
