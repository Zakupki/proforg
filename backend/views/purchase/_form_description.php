<?php
/** @var $this PurchaseController */
/** @var $model Purchase */
/** @var $models Purchase[] */
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
        <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 100)); ?>
        <?php echo $form->dropDownListRow($model, 'purchasestate_id', Purchasestate::model()->listData()); ?>
        <?php echo $form->dropDownListRow($model, 'market_id', CHtml::listData(Market::model()->with('markettype')->findAll(),'id','title','markettype.title')); ?>
        <?php echo $form->dropDownListRow($model, 'company_id', CHtml::listData(Company::model()->with('companygroup')->findAll(),'id','title','companygroup.title')); ?>
        <?php echo $form->dropDownListRow($model, 'user_id', User::model()->listData()); ?>
        <?php echo $form->textFieldRow($model, 'city', array('class' => 'span6', 'maxlength' => 100)); ?>
        <?php echo $form->textFieldRow($model, 'delay', array('class' => 'span2')); ?>
        <?php echo $form->checkBoxRow($model,'dirrect');?>
        <?php echo $form->textAreaRow($model, 'comment', array('rows' => 5, 'cols' => 50, 'class' => 'span9')); ?>
        <?php echo $form->textFieldRow($model, 'date_closed', array('class' => 'span3')); ?>
        <?php $this->widget('backend.extensions.calendar.SCalendar', array(
            'inputField' => CHtml::activeId($model, 'date_closed'),
            'ifFormat' => '%Y-%m-%d %H:%M:%S',
            'showsTime' => true,
            'language' => 'ru-UTF',
        )); ?>
    </div>
    <div class="span6">
        <?
        $CompanyInvite = array_pad((array)$model->companyInvites, 1, new CompanyInvite());
        $CompanyInviteCount = count($CompanyInvite);
        if(isset($CompanyInvite[0]['id'])){
        ?>
        <div data-block="sjstpl" class="control-group control-group-multiple product-info">
            <div class="control-head">
                  <span class="span4 sizeField">
                    <?php echo Yii::t('backend', 'Dirrect Companies'); ?>:
                  </span>
            </div>
                  <?
                    foreach ($CompanyInvite as $i => $item) {
                  ?>
                    <div class="controls controls-multiple-row" style="height: 21px;">
                        <a target="_blank" href="backend.php?r=company/update&id=<?=$item->company_id;?>" class="span6" style="float:left;"><?=$item->company->title;?></a>
                    </div>
                <?}?>
            </div>

        <?}?>
    </div>
</div>
<?php $this->endWidget(); ?>
