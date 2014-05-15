<ul id="form" class="nav nav-pills">
    <li class="active"><a href="#form-description" data-toggle="pill"><?php echo Yii::t('backend', 'Description'); ?></a></li>
    <?php if($model->getIsNewRecord()) { ?>
        <li class="disabled">
            <a href="#" title="<?php echo Yii::t('backend', 'Save page first'); ?>"
               rel="tooltip"><?php echo Yii::t('backend', 'Helps'); ?></a>
        </li>
    <?php } else { ?>
        <li><a href="#form-helps" data-toggle="pill"><?php echo Yii::t('backend', 'Helps'); ?></a></li>
    <?php } ?>
</ul>
<div class="tab-content">
    <div id="form-description" class="tab-pane fade in active">
        <?php $this->renderPartial('_form_description', array(
            'model' => $model,
            'legend' => $legend,
        )); ?>
    </div>
    <div id="form-helps" class="tab-pane fade">
        <?php if(!$model->getIsNewRecord()) { ?>
            <?php

            $this->renderPartial('_form_helps', array(
                'model' => $model,
                'help' => $help,
                'helps' => $helps
            ));
            ?>
        <?php } ?>
    </div>
</div>