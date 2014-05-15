<?php
/** @var $this UserController */
/** @var $model User */
/** @var $form CActiveForm */
?>
<?php
$this->pageTitle = Yii::t('backend', 'Manage');
$this->breadcrumbs = array(
    Yii::t('backend', 'Users') => array('admin'),
    Yii::t('backend', 'Manage'),
);

$this->menu = array(
    array('label' => Yii::t('backend', 'Create user'), 'url' => array('create')),
);

cs()->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
"); ?>

    <h3><?php echo $this->pageTitle; ?></h3>

<?php $this->beginWidget('TbActiveForm', array(
    'id' => 'admin-form',
    'enableAjaxValidation' => false,
)); ?>

<?php $gridWidget=$this->widget('backend.components.AdminView', array(
    'model' => $model,
    'columns' => array(
        'id',
        array(
            'class'=>'CLinkColumn',
            'header'=>'',
            'labelExpression'=>'"Войти"',
            'urlExpression'=>'"/backend.php?r=user/signin&email=".$data->email',
            'linkHtmlOptions'=>array('target'=>'_blank')
        ),
        'email',
        'first_name',
        'last_name',
        'sort',
        'status',
    ),
)); ?>

<?php $this->endWidget(); ?>
<? $this->renderExportGridButton($gridWidget,'Экспорт в эксель',array('class'=>'btn btn-success pull-right'));?>