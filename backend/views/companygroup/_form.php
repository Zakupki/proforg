<?php
/** @var $this CompanygroupController */
/** @var $model Companygroup */
/** @var $models Companygroup[] */
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
        <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 256)); ?>
        <?php echo $form->textFieldRow($model, 'sort', array('class' => 'span2')); ?>
        <?php echo $form->checkBoxRow($model, 'status'); ?>
    </div>
    <div class="span6">
        <div data-block="sjstpl" class="control-group control-group-multiple product-info">
            <div class="control-head">
                    <span class="span3 sizeField">
                        <?php echo Yii::t('backend', 'User'); ?>
                    </span>
            </div>
            <!--<label for="User_0_user_id" class="control-label"><?php echo Yii::t('backend', 'User'); ?></label>-->

            <?
            $CompanygroupUser = array_pad((array)$model->companygroupUsers, 1, new CompanygroupUser());
            $CompanygroupUserCount = count($CompanygroupUser);
            $userlist = User::model()->sort('t.email ASC')->listData();
            ?>
            <?php foreach ($CompanygroupUser as $i => $item) {
                /** @var $item ProductInfo */
                if (isset($item->user_id)) {
                    ?>
                    <div class="controls controls-multiple-row">
                        <a target="_blank" href="backend.php?r=user/update&id=<?= $item->user_id; ?>"
                           class="span5"><?= $item->user->email; ?></a>
                        <input name="CompanygroupUser[<?= $i; ?>][user_id]" type="hidden"
                               value="<?= $item->user_id; ?>"/>

                        <div class="arrayControls">

                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                                <?php /*if($CompanygroupUserCount == 1) { ?> style="display: none;"<?php } */ ?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                                <?php if ($i < $CompanygroupUserCount - 1) { ?> style="display: none;"<?php } ?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>

                        <?php echo $form->error($item, "[{$i}]user_id"); ?>
                        <?php echo $form->error($item, "[{$i}]value"); ?>
                    </div>
                <? } else { ?>
                    <div class="controls controls-multiple-row">
                        <? //CVarDumper::dump($item->user->id,10,true); ?>
                        <?php echo $form->dropDownList($item, "[{$i}]user_id", $userlist, array(
                            'class' => 'span5 validate-unique',
                            'placeholder' => Yii::t('backend', 'User'),
                            'empty' => ''
                        )); ?>
                        <div class="arrayControls">
                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                                <?php /*if($CompanyUserCount == 1) { ?> style="display: none;"<?php } */ ?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                                <?php if ($i < $CompanygroupUserCount - 1) { ?> style="display: none;"<?php } ?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>
                    </div>
                <?
                }
            } ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/html" id="sjstpl">
    <?php echo $form->dropDownList($CompanygroupUser[0], "[<%=idx%>]user_id", $userlist, array(
        'value' => '',
        'encode' => false,
        'class' => 'span5 validate-unique',
        'placeholder' => Yii::t('backend', 'User'),
        'empty'=>''
    )); ?>


    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>
    <?php echo $form->error($CompanygroupUser[0], '[<%=idx%>]user_id', array('encode' => false)); ?>
</script>

<?php cs()->registerScriptFile('/backend/js/simple_js_templating.js', CClientScript::POS_END); ?>
