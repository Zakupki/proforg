<?php
/** @var $this PostController */
/** @var $form ActiveForm */
/** @var $model Category */
/** @var $models Category[] */
/** @var $meals Category[] */
/** @var $postModel Post */
/** @var $postModels Post[] */
/** @var $languages array */
?>
<div class="row-fluid">



    <?php $form2 = $this->beginWidget('backend.components.ActiveForm', array(
        'id' => 'help-form',
        'action' => array('help/create'),
        'model' => $help,
        'fieldsetLegend' => Yii::t('backend', 'Add'),
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'afterValidate' => 'js:formAfterValidate',
        ),
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
        ),
        'formActions' => array(
            'add' => CHtml::submitButton(Yii::t('backend', 'Add'), array(
                'class' => 'btn btn-primary',
                'name' => 'save',
            ))
        )
    )); ?>

    <?
    echo CHtml::hiddenField('Help[helpgroup_id]', $model->id);
    echo CHtml::hiddenField('returnUrl', retUrl('#form-helps'));
    echo $form2->textFieldRow($help, 'title', array('class' => 'span9', 'maxlength' => 255));
    echo $form2->fileUploadRow($help, 'image_id', 'image');
    echo $form2->textAreaRow($help, 'detail_text', array('rows' => 5, 'cols' => 50, 'class' => 'span9'));
    echo $form2->textFieldRow($help, 'sort', array('class' => 'span2'));
    echo $form2->checkBoxRow($help, 'status');
    ?>
    <?php $this->endWidget(); ?>

</div>
<div class="row-fluid">
    <?php

        //$assetsDir = dirname(__FILE__).'/../assets'; /*Assume that you have a folder named assets inside the protected folder used to store the images */
        $this->widget('TbGridView', array(
        'dataProvider' => new CArrayDataProvider($helps, array('pagination' => array('pageSize' => 9999))),
        'template' => '{items}',
        'columns' => array(
            array(
                'htmlOptions' => array('nowrap' => 'nowrap'),
                'class' => 'CButtonColumn',
                'template' => '{update} {clone} {delete}',
                'updateButtonUrl' => 'url("help/update", array("id" => $data->id, "originUrl" => retUrl("#form-helps")))',
                'deleteButtonUrl' => 'url("help/delete", array("id" => $data->id))',
                'buttons' => array(
                    'clone' => array(
                        'label' => Yii::t('cp', 'Clone'),
                        'url' => 'url("help/clone", array("id" => $data->id, "originUrl" => retUrl("#form-helps")))',
                        'imageUrl' => '/backend/img/clone.png',
                        'options' => array(),
                        'visible' => 'true',
                    ),
                )
            ),
            array(
                'header' => $model->getAttributeLabel('helps'),
                'name' => 'helpgroup_id',
                'value' => '$data->helpgroup->title'
            ),
            array(
                'header' => $model->getAttributeLabel('title'),
                'name' => 'title',
            ),
            array(
                'header' => $model->getAttributeLabel('sort'),
                'name' => 'sort',
            ),
            array(
                'header' => $model->getAttributeLabel('status'),
                'name' => 'status',
                'value' => '$data->status ? Yii::t("backend", "Enabled") : Yii::t("backend", "Disabled")'
            ),
        )
    )) ?>
</div>