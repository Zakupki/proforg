<?php
/** @var $this UserController */
/** @var $model User */
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
    <div class="row">
        <div class="span6">
            <?php echo $form->textFieldRow($model, 'first_name', array('class' => 'span4', 'maxlength' => 64)); ?>
            <?php echo $form->textFieldRow($model, 'last_name', array('class' => 'span4', 'maxlength' => 64)); ?>
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4', 'maxlength' => 64)); ?>
            <?php echo $form->dropDownListRow($model, 'usertype_id', Usertype::model()->listData()); ?>
            <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span4', 'value' => '', 'autocomplete' => 'off')); ?>
            <?php echo $form->dropDownListRow($model, 'finance_id', Finance::model()->listData(),array('empty'=>'')); ?>
            <?php echo $form->dropDownListRow($model, 'company_id', Company::model()->listData(),array('empty'=>'')); ?>
            <?php echo $form->dropDownListRow($model, 'employer_id', Company::model()->listData(),array('empty'=>'')); ?>
            <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
            <?php echo $form->checkBoxRow($model, 'deleted'); ?>
            <?php echo $form->checkBoxRow($model, 'status'); ?>
        </div>
        <div class="span6">

        </div>
    </div>
<?php $this->endWidget(); ?>