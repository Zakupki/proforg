<?php
/** @var $this PurchaseFileController */
/** @var $model PurchaseFile */
/** @var $models PurchaseFile[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Clone "{title}"', array('{title}' => $model->getDisplayTitle()));
$this->breadcrumbs = array(
	Yii::t('backend', 'Purchase Files') => array('admin'),
	Yii::t('backend', 'Clone'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
