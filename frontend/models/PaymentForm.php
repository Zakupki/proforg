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

class PaymentForm extends CFormModel {
	public $paysystem_id;
	public $company_id;
	public $amount;


	public function rules()	{
		return array(
			array('paysystem_id, company_id, amount', 'required'),
			array('paysystem_id', 'numerical','integerOnly' => true),
			array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
			array('amount', 'numerical', 'min' => 0.01),
			array('amount', 'match', 'pattern' => '/^\d{1,8}(\.\d{1,2})?$/'),
		);
	}

	public function attributeLabels() {
		return array(
			'company_id' => 'Компания',
			'amount' => 'Сумма',
		);
	}
}