<?php
/** @var $this BillsController */
/** @var $model Payments */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->dropDownListRow($model, "[{$language}]user_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]company_id", array()); ?>
<?php echo $form->dropDownListRow($model, "[{$language}]paysystem_id", array()); ?>
<?php echo $form->textFieldRow($model, "[{$language}]amount", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]status", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, 'date_create', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_create"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->textFieldRow($model, 'date_updated', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_updated"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>