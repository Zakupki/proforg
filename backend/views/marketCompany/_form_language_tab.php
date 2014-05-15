<?php
/** @var $this MarketCompanyController */
/** @var $model MarketCompany */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->dropDownListRow($model, "[{$language}]company_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]market_id", array()); ?>
<?php echo $form->textFieldRow($model, "[{$language}]sort", array('class' => 'span2')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]status"); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>