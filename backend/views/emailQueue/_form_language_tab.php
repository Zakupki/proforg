<?php
/** @var $this EmailQueueController */
/** @var $model EmailQueue */
/** @var $form ActiveForm */
/** @var $language string */
?>

<?php echo $form->textFieldRow($model, "[{$language}]from_name", array('class' => 'span9', 'maxlength' => 64)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]from_email", array('class' => 'span9', 'maxlength' => 128)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]to_email", array('class' => 'span9', 'maxlength' => 128)); ?>
<?php echo $form->textFieldRow($model, "[{$language}]subject", array('class' => 'span9', 'maxlength' => 255)); ?>
<?php echo $form->textAreaRow($model, "[{$language}]message", array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]max_attempts", array('class' => 'span9')); ?>
<?php echo $form->textFieldRow($model, "[{$language}]attempts", array('class' => 'span9')); ?>
<?php echo $form->checkBoxRow($model, "[{$language}]success"); ?>
<?php echo $form->textFieldRow($model, 'date_published', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_published"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->textFieldRow($model, 'last_attempt', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]last_attempt"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->textFieldRow($model, 'date_sent', array('class' => 'span2')); ?>
    <?php $this->widget('backend.extensions.calendar.SCalendar', array(
        'inputField' => CHtml::activeId($model, "[{$language}]date_sent"),
        'ifFormat' => '%Y-%m-%d %H:%M:%S',
        'showsTime' => true,
        'language' => 'ru-UTF',
    )); ?>
<?php echo $form->hiddenField($model, "[{$language}]language_id", array('value' => $language)); ?>