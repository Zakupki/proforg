<?php
/** @var $this TagController */
/** @var $model Tag */
/** @var $models Tag[] */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Clone "{title}"', array('{title}' => $model->getDisplayTitle()));
$this->breadcrumbs = array(
	Yii::t('backend', 'Tags') => array('admin'),
	Yii::t('backend', 'Clone'),
);
?>

<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'legend' => $this->pageTitle,
)); ?>
