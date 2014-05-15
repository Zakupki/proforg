<?php
/** @var $this OfferFileController */
/** @var $model OfferFile */
/** @var $models OfferFile[] */
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

    <?php echo $form->dropDownListRow($model, 'offer_id', array()); ?>
    <?php echo $form->fileUploadRow($model, 'file_id', 'file'); ?>

<?php $this->endWidget(); ?>
