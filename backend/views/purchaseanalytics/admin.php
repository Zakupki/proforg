<?php
/** @var $this PurchaseController */
/** @var $model Purchase */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Purchase Analytics');
$this->breadcrumbs = array(
	Yii::t('backend', 'Analytics'),
	Yii::t('backend', 'Purchase Analytics'),
);
?>
    <ul id="form" class="nav nav-pills">
        <li class="active"><a href="#form-description" data-toggle="pill">Дни</a></li>
        <li><a href="#form-auctions" data-toggle="pill">Месяцы</a></li>
    </ul>
    <div class="tab-content">
        <div id="form-description" class="tab-pane fade in active">
            <?php
            $this->renderPartial('_month', array(
            'model'=>$model,
            'data'=>$data
            ));
            ?>
       </div>
        <div id="form-auctions" class="tab-pane fade">

            <div style="width:100%"></div>
            <?php
            /*$this->renderPartial('_year', array(

            ));*/
            ?>
        </div>
    </div>

