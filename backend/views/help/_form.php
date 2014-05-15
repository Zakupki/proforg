<?php
/** @var $this HelpController */
/** @var $model Help */
/** @var $models Help[] */
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


    <?php
    if(isset($_GET['originUrl']))
        echo CHtml::hiddenField('returnUrl', $_GET['originUrl']); ?>
    <?    echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
    <?php
    if(isset($model->helpgroup_id))
        CHtml::hiddenField('helpgroup_id', $model->helpgroup_id);
    else
        echo $form->dropDownListRow($model, 'helpgroup_id', array());
    ?>
    <?php echo $form->fileUploadRow($model, 'image_id', 'image'); ?>
    <?php echo $form->textAreaRow($model, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
    <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
    <?php echo $form->checkBoxRow($model, 'status'); ?>

<?php $this->endWidget(); ?>
