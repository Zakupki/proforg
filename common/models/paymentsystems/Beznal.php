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

class Beznal extends PaymentSystem {



	public function rules(){
		return array(
		);
	}

	public function attributeLabels(){
		return array(
		);
	}

	public function processPayment(Payments $payment){
		$payment->status = Payments::STATUS_WAITOFFLINE;
		$payment->update(array('status'));

		$bill = new Bill();

		$bill->payment_id = $payment->id;

		$bill->our_text = Option::getOpt('payment_our_data');
		$bill->client_text = $payment->company->title;
		$bill->product_text = Option::getOpt('payment_our_product');
		$bill->price = $payment->amount;
		$bill->date_end = new CDbExpression('NOW() + INTERVAL 5 DAY');
		$bill->status = 1;
		$bill->billperiod_id = $payment->company->billperiod_id;

		if ($payment->company_id && yii::app()->user->getId() > 0) {
			$model = CompanyUser::model()->with('company')->findByAttributes(array('user_id' => yii::app()->user->getId(), 'company_id' => $payment->company_id));
			if ($model->id && !$model->major) {
				CompanyUser::model()->updateAll(array('major' => 0, 'user_id=:user_id AND company_id!=:company_id AND major=1', array(':user_id' => yii::app()->user->getId(), ':company_id' => $payment->company_id)));
				$model->major = 1;
				if ($model->save()) {
					$city=City::model()->findByPk($model->company->city_id);
					if($city)
						$city_title=$city->title;
					else
						$city_title='';
					Yii::app()->session['major_company'] = array('id' => $model->company_id, 'title' => $model->company->title, 'city' => $city_title, 'balance'=>Payments::model()->companyBalance($model->company_id));
				}
			}
		}

		$bill->save();

		$bill->title = sprintf("ЗА-%07d", $bill->id);

		$bill->update(array('title'));


		return array(
			'status' => "",
			'message' => 'Оплатите пожалуйста выставленный счёт
				<script>document.location.href="'.CHtml::encode(Yii::app()->controller->createAbsoluteUrl('/account/#/bills')).'";window.location.reload(true);</script>'
		);
	}
}