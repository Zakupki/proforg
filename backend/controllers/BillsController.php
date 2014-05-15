<?php
class BillsController extends BackController
{
	public function init()
	{
		parent::init();
		$this->setModelName("Payments");
	}

	public function actionAdmin()
	{
		/** @var $model BaseActiveRecord */
		$model = $this->getNewModel('search');
		$model->unsetAttributes(); // clear any default values

		$model->paysystem_id = 1;
		if(isset($_GET[$this->getModelName()]))
			$model->attributes = $_GET[$this->getModelName()];

		$model->restoreGridState();

		$this->render($this->view['admin'], array(
			'model' => $model,
		));
	}

	/**
	 * Disable multiple models.
	 */
	public function actionBulkDisable()
	{
		$this->setBillStatus($_POST['id'], 3);
		$this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
	}

	/**
	 * Enable multiple models.
	 */
	public function actionBulkEnable()
	{
		$this->setBillStatus($_POST['id'], 2);
		$this->redirectAction(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : null);
	}


	protected function setBillStatus($ids, $status = 1, $field = 'id')
	{


		if(Yii::app()->request->isPostRequest && !empty($ids))
		{
			$ids = !is_array($ids) ? (array)$ids : $ids;
			$criteria = new CDbCriteria();
			$criteria->addInCondition($field, $ids);

			$payments = Payments::model()->findAll($criteria);

			foreach($payments as $payment) {
				$payment->status = $status;
				$payment->update(array('status'));

				$bill= $payment->bill;
				if ($status == 2) {
					$bill->status = 2;
					$bill->update(array('status'));
				}
				if ($status == 3) {
					$bill->status = 1;
					$bill->update(array('status'));
				}

			}

		}
		else
		{
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		}
	}

}