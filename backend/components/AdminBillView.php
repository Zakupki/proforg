<?php
/**
 * AdminView
 */
class AdminBillView extends AdminView
{


	public $actionButtons = array('status', 'delete');

    /**
     * Prepare CGridView columns
     */
    protected function prepareColumns()
    {
        $columns = array_merge(array(
            CMap::mergeArray(array(
                'class' => 'CButtonColumn',
                'template' => '{view} {delete}',
                'header' => CHtml::dropDownList(
                    'pageSize',
                    Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
                    array(10 => 10, 20 => 20, 50 => 50, 100 => 100, 500 => 500),
                    array(
                        'onchange' => "$.fn.yiiGridView.update('".$this->id."', {data: {pageSize: $(this).val()}})",
                    )),
                'buttons' => array(
                    'view' => array(
                        'url' => "Yii::app()->getController()->createUrl('view', array('{$this->keyField}' => \$data->{$this->keyField}))",
                    ),
                    'delete' => array(
                        'url' => "Yii::app()->getController()->createUrl('delete', array('{$this->keyField}' => \$data->{$this->keyField}))",
                    ),
                )
            ), $this->buttonColumn),

            array(
                'class' => 'CCheckBoxColumn',
                'name' => $this->keyField,
                'checkBoxHtmlOptions' => array(
                    'name' => $this->keyField.'[]'
                ),
            ),
        ), $this->columns);

        if($pos = array_search('status', $columns))
        {
            $columns[$pos] = array(
                'name' => 'status',
                'value' => '$data->status ? Yii::t("backend", "Enabled") : Yii::t("backend", "Disabled")',
                'filter' => array(0 => Yii::t('backend', 'Disabled'), 1 => Yii::t('backend', 'Enabled')),
            );
        }
		if($pos = array_search('main', $columns))
        {
            $columns[$pos] = array(
                'name' => 'main',
                'value' => '$data->main ? Yii::t("backend", "Enabled") : Yii::t("backend", "Disabled")',
                'filter' => array(0 => Yii::t('backend', 'Disabled'), 1 => Yii::t('backend', 'Enabled')),
            );
        }

        if($pos = array_search('language_id', $columns))
        {
            $langList = Language::getList();
            $columns[$pos] = array(
                'name' => 'language_id',
                'filter' => $langList,
                'visible' => count($langList) > 1
            );
        }

        $this->columns = $columns;
    }

	/**
	 * Grid view action buttons
	 */
	protected function renderActions()
	{
		if(empty($this->actionButtons))
			return;

		$ctrl = $this->getController();
		$perm = ucfirst(get_class($this->model)).'.';
		$accessCtrl = Yii::app()->user->checkAccess($perm.'*');
		echo CHtml::openTag('div', array('class' => 'grid-actions'));

		if(in_array('create', $this->actionButtons) && ($accessCtrl || Yii::app()->user->checkAccess($perm.'Create')))
		{
			echo CHtml::link(Yii::t('backend', 'Create'), array('create'), array(
				'class' => 'btn btnCreate'
			));
		}

		if(in_array('status', $this->actionButtons) && ($accessCtrl || Yii::app()->user->checkAccess($perm.'Update')))
		{
			echo CHtml::linkButton("Подтвердить", array(
				'class' => 'btn btnBulk btnEnable',
				'submit' => $ctrl->createUrl('bulkEnable'),
			));
			echo CHtml::linkButton("Отклонить", array(
				'class' => 'btn btnBulk btnDisable',
				'submit' => $ctrl->createUrl('bulkDisable'),
			));
		}

		if(!empty($this->actionButtons))
		{
			foreach($this->actionButtons as $aButt)
			{
				if(in_array($aButt, array('create', 'status', 'delete')))
				{
					continue;
				}

				echo $aButt;
			}
		}

		if(in_array('delete', $this->actionButtons) && ($accessCtrl || Yii::app()->user->checkAccess($perm.'Delete')))
		{
			echo CHtml::linkButton(Yii::t('backend', 'Delete'), array(
				'class' => 'btn btn-danger btnBulk btnDelete',
				'confirm' => Yii::t('backend', 'Are you sure?'),
				'submit' => $ctrl->createUrl('bulkDelete'),
			));
		}

		echo CHtml::closeTag('div');
	}


}