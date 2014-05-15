<?php
/** @var $this CompanyController */
/** @var $model Company */
/** @var $models Company[] */
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
));

$userlist = User::model()->listData();
$companyrolelist = Companyrole::model()->listData();

?>
<div class="row">
    <div class="span6">
        <?php echo $form->textFieldRow($model, 'title', array('class' => 'span9', 'maxlength' => 255)); ?>
        <?php echo $form->dropDownListRow($model, 'companygroup_id', Companygroup::model()->listData()); ?>
        <?php echo $form->dropDownListRow($model, 'companytype_id', Companytype::model()->listData()); ?>
        <?php echo $form->dropDownListRow($model, 'city_id', CHtml::listData(City::model()->with('region')->sort('region.title ASC,t.title ASC')->findAll(), 'id', 'title', 'region.title')); ?>
        <?php echo $form->textFieldRow($model, 'egrpou', array('class' => 'span9', 'maxlength' => 128)); ?>
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
            <?
            $CompanyUser = array_pad((array)$model->companyUsers, 1, new CompanyUser());
            $CompanyUserCount = count($CompanyUser);
            $userlist = User::model()->sort('t.email ASC')->listData();
            $companyrolelist = Companyrole::model()->sort()->listData();

            foreach ($CompanyUser as $i => $item) {
                if (isset($item->user_id)) {
                    ?>
                    <div class="controls controls-multiple-row">
                        <? //CVarDumper::dump($item->user->id,10,true); ?>
                        <a target="_blank" href="backend.php?r=user/update&id=<?= $item->user_id; ?>"
                           class="span5"><?= $item->user->email; ?></a>
                        <input name="CompanyUser[<?= $i; ?>][user_id]" type="hidden" value="<?= $item->user_id; ?>"/>

                        <?php echo $form->dropDownList($item, "[{$i}]companyrole_id", $companyrolelist, array(
                            'class' => 'span3 validate-unique',
                            'placeholder' => Yii::t('backend', 'Companyrole'),
                            'empty' => ''
                        )); ?>
                        <?php echo $form->dropDownList($item, "[{$i}]status", array(0=>'Отключено',1=>'Включено'), array(
                            'class' => 'span3 validate-unique',
                            'placeholder' => Yii::t('backend', 'Status')
                        )); ?>
                        <div class="arrayControls">
                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                                <?php /*if($CompanyUserCount == 1) { ?> style="display: none;"<?php } */ ?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                                <?php if ($i < $CompanyUserCount - 1) { ?> style="display: none;"<?php } ?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>
                    </div>
                <? } else { ?>
                    <div class="controls controls-multiple-row">
                        <? //CVarDumper::dump($item->user->id,10,true); ?>
                        <?php echo $form->dropDownList($item, "[{$i}]user_id", $userlist, array(
                            'class' => 'span8 validate-unique',
                            'placeholder' => Yii::t('backend', 'User'),
                            'empty' => ''
                        )); ?>
                        <?php echo $form->dropDownList($item, "[{$i}]companyrole_id", $companyrolelist, array(
                            'class' => 'span3 validate-unique',
                            'placeholder' => Yii::t('backend', 'Companyrole'),
                            'empty' => ''
                        )); ?>
                        <?php echo $form->dropDownList($item, "[{$i}]status", array(0=>'Отключено',1=>'Включено'), array(
                            'class' => 'span3 validate-unique',
                            'placeholder' => Yii::t('backend', 'Status')
                        )); ?>
                        <div class="ar
                        <div class="arrayControls">
                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                                <?php /*if($CompanyUserCount == 1) { ?> style="display: none;"<?php } */ ?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                                <?php if ($i < $CompanyUserCount - 1) { ?> style="display: none;"<?php } ?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>
                    </div>
                <?
                }
            }?>
        </div>
        <div data-block="sjstpl2" class="control-group control-group-multiple product-info">
            <div class="control-head">
                  <span class="span3 sizeField">
                    <?php echo Yii::t('backend', 'Market'); ?>
                  </span>
            </div>
            <?
            $MarketCompany = array_pad((array)$model->companyMarkets, 1, new MarketCompany());
            $MarketCompanyCount = count($MarketCompany);
            $marketlist = CHtml::listData(Market::model()->with('markettype')->sort('markettype.title ASC,t.title ASC')->findAll(),'id','title','markettype.title');

            foreach ($MarketCompany as $i => $item) {
                if (isset($item->market_id)) {
                    ?>
                    <div class="controls controls-multiple-row">
                        <? //CVarDumper::dump($item->user->id,10,true); ?>
                        <a target="_blank" href="backend.php?r=market/update&id=<?= $item->market_id; ?>"
                           class="span5"><?= $item->market->title; ?></a>
                        <input name="MarketCompany[<?= $i; ?>][market_id]" type="hidden" value="<?= $item->market_id; ?>"/>

                        <div class="arrayControls">
                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                               <?php /*if($CompanyUserCount == 1) { ?> style="display: none;"<?php } */ ?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                               <?php if ($i < $MarketCompanyCount - 1) { ?> style="display: none;"<?php } ?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>
                    </div>
                <? } else { ?>
                    <div class="controls controls-multiple-row">
                        <?php echo $form->dropDownList($item, "[{$i}]market_id", $marketlist, array(
                            'class' => 'span5 validate-unique',
                            'placeholder' => Yii::t('backend', 'User'),
                            'empty' => ''
                        )); ?>
                        <div class="arrayControls">
                            <a href="#" class="btn btn-mini btnRemoveArrayRow"
                                <?php /*if($CompanyUserCount == 1) { ?> style="display: none;"<?php } */ ?>>-</a>
                            <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow"
                                <?php if ($i < $MarketCompanyCount - 1) { ?> style="display: none;"<?php } ?>
                               data-array-last="<?php echo $i; ?>">+</a>
                        </div>
                    </div>
                <?
                }
            }?>


        </div>
    </div>


</div>
<?php $this->endWidget(); ?>
<script type="text/html" id="sjstpl">
    <?php echo $form->dropDownList($CompanyUser[0], "[<%=idx%>]user_id", $userlist, array(
        'value' => '',
        'encode' => false,
        'class' => 'span5 validate-unique',
        'placeholder' => Yii::t('backend', 'User'),
        'empty'=>''
    )); ?>
    <?php echo $form->dropDownList($CompanyUser[0], "[<%=idx%>]companyrole_id", $companyrolelist, array(
        'value' => '',
        'encode' => false,
        'class' => 'span3',
        'placeholder' => Yii::t('backend', 'Companyrole'),
        'empty'=>''
    )); ?>
    <?php echo $form->dropDownList($CompanyUser[0], "[<%=idx%>]status", array(0=>'Отключено',1=>'Включено'), array(
        'value' => '',
        'encode' => false,
        'class' => 'span3',
        'placeholder' => Yii::t('backend', 'Status')
    )); ?>

    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>
</script>

<script type="text/html" id="sjstpl2">
    <?php echo $form->dropDownList($MarketCompany[0], "[<%=idx%>]market_id", $marketlist, array(
        'value' => '',
        'encode' => false,
        'class' => 'span5 validate-unique',
        'placeholder' => Yii::t('backend', 'Market'),
        'empty'=>''
    )); ?>
    <div class="arrayControls">
        <a href="#" class="btn btn-mini btnArrayControl btnRemoveArrayRow">-</a>
        <a href="#" class="btn btn-mini btnArrayControl btnAddArrayRow" data-array-last="<%=idx%>">+</a>
    </div>
</script>
<?php cs()->registerScriptFile('/backend/js/simple_js_templating.js', CClientScript::POS_END); ?>
