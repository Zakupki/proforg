<?php
/** @var $this OfferController */
/** @var $model Offer */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->dropDownListRow($model, "[{$language}]tag_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]product_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]company_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]user_id", array()); ?>
<?php echo $form->textFieldRow($model, "[{$language}]price", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]amount", array('class' => 'span9')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]delivery"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]delay", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_create"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->textFieldRow($model, "[{$language}]title", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textAreaRow($model, "[{$language}]comment", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]winner"); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]reduction"); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]reduction_place"); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]reduction_state"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]reduction_level", array('class' => 'span9')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]reduction_pass"); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]reduction_passed"); ?>
<?php echo $form->textFieldRow($model, "[{$language}]price_reduce", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]place", array('class' => 'span9', 'maxlength' => 12)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]totaloffers", array('class' => 'span9', 'maxlength' => 12)); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>