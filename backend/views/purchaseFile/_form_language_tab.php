<?php
/** @var $this PurchaseFileController */
/** @var $model PurchaseFile */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->dropDownListRow($model, "[{$language}]purchase_id", array()); ?>
<?php echo $form->fileUploadRow($model, "[{$language}]file_id", '_id'); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>