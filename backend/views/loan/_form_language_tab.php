<?php
/** @var $this LoanController */
/** @var $model Loan */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->dropDownListRow($model, "[{$language}]user_id", array()); ?>
<?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_create"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->textFieldRow($model, "[{$language}]days", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]value", array('class' => 'span9')); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>