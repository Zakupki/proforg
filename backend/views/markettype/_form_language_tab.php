<?php
/** @var $this MarkettypeController */
/** @var $model Markettype */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 30)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]code", array('class' => 'span9', 'maxlength' => 20)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]description", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>