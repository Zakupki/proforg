<?php
/**********************************************************************************************
*                            CMS Open Real Estate
*                              -----------------
*	version				:	%TAG%
*	copyright			:	(c) %YEAR% Monoray
*	website				:	http://www.monoray.ru/
*	contact us			:	http://www.monoray.ru/contact
*
* This file is part of CMS Open Real Estate
*
* Open Real Estate is free software. This work is licensed under a GNU GPL.
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
***********************************************************************************************/

class Payments extends BaseActiveRecord {
	const STATUS_WAITPAYMENT=1;
	const STATUS_PAYMENTCOMPLETE=2;
	const STATUS_DECLINED=3;
	const STATUS_WAITOFFLINE = 4;
	const STATUS_PENDING = 5;
    public $paysystem_title;
	public $balance;
    public $title;
	public $bill_search;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{payments}}';
	}

	public function rules() {
		return array(
			array('amount, status', 'required'),
			array('amount, status, paysystem_id, user_id, company_id, purchase_id', 'numerical'),
			array('id, amount, status, company_id, paysystem_id, bill_search', 'safe', 'on' => 'search'),
			array('company_id', 'exists', 'className' => 'Company', 'attributeName' => 'id'),
			array('paysystem_id', 'exists', 'className' => 'Paysystem', 'attributeName' => 'id'),
		);
	}

	public function relations() {
		return array(
            'paysystem' => array(self::BELONGS_TO, 'Paysystem', 'paysystem_id'),
            'purchase_id' => array(self::BELONGS_TO, 'Purchase', 'purchase_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
			'bill'=>array(self::HAS_ONE, 'Bill', 'payment_id'),


		);
	}

	public function attributeLabels() {
		return array(
			'id' => 'ID платежа',
			'amount' => 'Сумма',
			'status' => 'Статус',
			'date_create' => 'Дата платежа',
			'user_id' => 'Пользователь',
			'company_id' => 'Компания',
            'purchase_id'=>  Yii::t('backend', 'Purchase'),
            'paysystem_id' => Yii::t('backend', 'Paysystems'),

		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare($this->getTableAlias().'.id', $this->id);
		$criteria->compare($this->getTableAlias().'.amount', $this->amount, true);
		$criteria->compare($this->getTableAlias().'.status', $this->status);
		$criteria->compare('paysystem.title', $this->paysystem_title, true);
		$criteria->compare($this->getTableAlias().'.paysystem_id', $this->paysystem_id);
		$criteria->compare($this->getTableAlias().'.company_id', $this->company_id);
		$criteria->compare($this->getTableAlias().'.user_id', $this->user_id);
		$criteria->compare('bill.title', $this->bill_search, true);

		$criteria->with = array('user', 'paysystem', 'bill');

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => $this->getTableAlias().'.date_create DESC',
			),
			'pagination' => array(
				'pageSize' => param('adminPaginationPageSize', 20),
			),
		));
	}

	public function behaviors(){
		return array(
			'AutoTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'date_create',
				'updateAttribute' => 'date_updated',
			),
		);
	}

	public static function getStatuses() {
		return array(
			'' => '',
			Payments::STATUS_WAITPAYMENT => "Ожидание платежа",
			Payments::STATUS_PAYMENTCOMPLETE => "Платёж выполнен",
			Payments::STATUS_DECLINED => "Платёж отклонён",
			Payments::STATUS_WAITOFFLINE => "Ожидается подтверждение оффлайн-платежа",
            Payments::STATUS_PENDING => "Ожидается подтверждение"
		);
	}

	public function returnStatusHtml() {
		$return = '';
		$statuses = self::getStatuses();
		if(isset($statuses[$this->status]))
			$return = $statuses[$this->status];
		return $return;
	}

    public static function getCountWait(){
        $sql = "SELECT COUNT(id) FROM {{payments}} WHERE status IN (".self::STATUS_WAITOFFLINE.", ".self::STATUS_WAITPAYMENT.")";
        return (int) Yii::app()->db->createCommand($sql)->queryScalar();
    }

	public static function makePayment($paysystem_id, $company_id, $purchase_id) {

		$payment = new Payments();
		$payment->paysystem_id = $paysystem_id;
		$payment->company_id = $company_id;
        $payment->purchase_id = $purchase_id;
		$payment->user_id = Yii::app()->user->id;
		$payment->amount = -(Paysystem::model()->getPrice($paysystem_id));
		$payment->status = Payments::STATUS_PAYMENTCOMPLETE;
        $result=$payment->save();
        if($result && isset(Yii::app()->session['major_company'])){
            $major_company=Yii::app()->session['major_company'];
            $major_company['balance']=self::companyBalance($company_id);
            Yii::app()->session['major_company']=$major_company;
        }

		if ((float)$major_company['balance'] <= 0)
			Bill::checkForActs($company_id);

		return ;
	}

	public static function companyBalance($company_id) {
		$criteria = new CDbCriteria;
		$criteria->select='SUM(amount) as balance';
		$criteria->compare('status', Payments::STATUS_PAYMENTCOMPLETE);
		$criteria->compare('company_id', $company_id);
		$balance = Payments::model()->find($criteria);
        return number_format($balance->balance, 2, '.', '');
	}

	public function complete() {

		if($this->paid_id != PaidServices::ID_ADD_FUNDS){
			$paidOption = $this->paidOption;

            $interval = 'INTERVAL '.$paidOption->duration_days.' DAY';
			$dateEnd = new CDbExpression('NOW() + ' . $interval);

			PaidServices::applyToApartment($this->apartment_id, $this->paid_id, $dateEnd, $interval);
		}else{
			$user = User::model()->findByPk($this->user_id);
			if(!$user){
				throw new CHttpException('Not user with ID ' . $this->user_id);
			}
			$user->addToBalance($this->amount);
		}

		$this->status = Payments::STATUS_PAYMENTCOMPLETE;
		$this->update('status');

		return true;
	}
}