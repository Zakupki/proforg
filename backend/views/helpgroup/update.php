<?php
/** @var $this HelpgroupController */
/** @var $model Helpgroup */
/** @var $models Helpgroup[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Update "{title}"', array('{title}' => $model->getDisplayTitle()));
$this->breadcrumbs = array(
	Yii::t('backend', 'Helpgroups') => array('admin'),
	Yii::t('backend', 'Update'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'help' => $help,
    'helps' => $helps,
    'legend' => $this->pageTitle,
)); ?>
