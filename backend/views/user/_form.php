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
            <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span4', 'value' => '', 'autocomplete' => 'off')); ?>
            <?php echo $form->textFieldRow($model, 'position', array('class' => 'span4', 'maxlength' => 32)); ?>
            <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
            <?php /*if($model->type == User::ADMIN) {
    	 echo $form->dropDownListRow($model, 'authItems', User::getRoleList(), array(
            'multiple' => 'multiple',
            'key' => 'name',
        ));
	} */
            ?>
            <?php echo $form->checkBoxRow($model, 'subscribe_regular'); ?>
            <?php echo $form->checkBoxRow($model, 'subscribe'); ?>
            <?php echo $form->checkBoxRow($model, 'status'); ?>
        </div>
        <div class="span6">
            <div data-block="sjstpl" class="control-group control-group-multiple product-info">
                <div class="control-head">
                  <span class="span3 sizeField">
                    <?php echo Yii::t('backend', 'Phones'); ?>
                  </span>
                </div>
                <?
                $Phones = array_pad((array)$model->phones, 1, new Phone());
                $PhonesCount = count($Phones);
                foreach ($Phones as $i => $item) {
                    ?>
                    <div class="controls controls-multiple-row">
                    <?php
                    echo $form->textField($item, "[{$i}]phonecode", array(
                        'class' => 'span2',
                        'placeholder' => Yii::t('backend', 'Phone code'),
                    ));
                    ?>
                    <?php
                    echo $form->textField($item, "[{$i}]phone", array(
                        'class' => 'span2',
                        'placeholder' => Yii::t('backend', 'Phone'),
                    ));
                    ?>
                        <div class="arrayControls">
                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                                <?php ?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                                <?php if ($i < $PhonesCount - 1) { ?> style="display: none;"<?php } ?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>
                    </div>
                <?
                }
                ?>
            </div>
            <!--<?php echo $form->dropDownListRow($model, 'userUsertypes', Usertype::model()->listData(), array(
                'size' => 13,
                'multiple' => true,
            )); ?>-->
        </div>
    </div>
<?php $this->endWidget(); ?>