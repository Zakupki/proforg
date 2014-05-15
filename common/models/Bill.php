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

class Bill extends BaseActiveRecord {
	const STATUS_WAITPAYMENT=1;
	const STATUS_PAID=2;
	const STATUS_PAID_ACTED=3;

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return '{{pay_bills}}';
	}

	public static function getStatuses() {
		return array(
			'' => '',
			Bill::STATUS_WAITPAYMENT => "Ожидает оплаты",
			Bill::STATUS_PAID => "Оплачен",
			Bill::STATUS_PAID_ACTED => "Закрыт актами",
		);
	}

	public function relations() {
		return array(
			'payment'=>array(self::BELONGS_TO, 'Payments', 'payment_id'),
			'acts'=>array(self::HAS_MANY, 'Act', 'bill_id'),
		);
	}

	public static function checkForActs($company_id) {
		$criteria = new CDbCriteria();
		$criteria->compare('payment.company_id', $company_id);
		$criteria->compare('t.status', self::STATUS_PAID);
		$criteria->compare('billperiod_id', 3);
		$criteria->order = 't.date_create ASC';

		$bill = self::model()->with(array('payment'))->find($criteria);
		if ($bill) {
			self::createAct($bill);
		}
	}

	public static function createAct(Bill $bill, $price = "") {

		$act = new Act();

		$phones = Phone::model()->findAllByAttributes(array('company_id' => $bill->payment->company->id));


		$phone = $phones ? ' тел ('.$phones[0]['phonecode'].') '.$phones[0]['phone'] : "";
		$paydata = "";
		if($bill->payment->company->account && $bill->payment->company->bank && $bill->payment->company->mfo)
			$paydata = "Р/р ".$bill->payment->company->account." в ".$bill->payment->company->bank." МФО ".$bill->payment->company->mfo.'<br>';

		$act->bill_id = $bill->id;



		$act->price = ($price) ? (($price > $bill->price) ? $bill->price : $price) : $bill->price;
		$act->our_text = $bill->our_text;
		$act->our_name = Option::getOpt('payment_act_company');
		$act->our_director = Option::getOpt('payment_act_director');
		$act->product_text = Option::getOpt('payment_act_product');
		$act->client_name = $bill->payment->company->title;
		$act->client_text = $bill->payment->company->title.'<br>ЄДРПОУ '.$bill->payment->company->egrpou.$phone.'<br>'.
			$paydata.
			'Адреса: '.($bill->payment->company->cityname ? $bill->payment->company->cityname->title.', ' : '').$bill->payment->company->address;
		$act->director = $bill->payment->company->director;
		$act->save();

		$act->title = sprintf("ОУ-%07d", $act->id);
		$act->update(array('title'));

		$sql = "SELECT SUM(price) FROM {{pay_acts}} WHERE bill_id = ".$bill->id;
		$sumPrice = Yii::app()->db->createCommand($sql)->queryScalar();

		if ($sumPrice >= $bill->price) {
			$bill->status = Bill::STATUS_PAID_ACTED;
			$bill->update(array('status'));
		}


	}


	public function returnStatusHtml() {
		$return = '';
		$statuses = self::getStatuses();
		if(isset($statuses[$this->status]))
			$return = $statuses[$this->status];
		return $return;
	}

	public function getPrices() {
		$prices = array();
		$prices['pdv'] = round($this->price*0.2, 2);
		$prices['no_pdv'] = $this->price - $prices['pdv'];
		$prices['pdv'] = number_format($prices['pdv'], 2, '.', '');
		$prices['no_pdv'] = number_format($prices['no_pdv'], 2, '.', '');
		$prices['price'] = number_format($this->price, 2, '.', '');
		$prices['price_text'] = self::num2str($prices['price']);
		return $prices;
	}

	/**
	 * Возвращает сумму прописью
	 * @author runcore
	 * @uses morph(...)
	 */
	public static function num2str($num) {
		$nul='ноль';
		$ten=array(
			array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
			array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
		);
		$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
		$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
		$unit=array( // Units
			array('копейка' ,'копейки' ,'копеек',	 1),
			array('гривна'   ,'гривны'   ,'гривен'    ,1),
			array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
			array('миллион' ,'миллиона','миллионов' ,0),
			array('миллиард','милиарда','миллиардов',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) $out[]= self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else $out[] = $nul;
		$out[] = self::morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
		$out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}

	/**
	 * Склоняем словоформу
	 * @ author runcore
	 */
	public static function morph($n, $f1, $f2, $f5) {
		$n = abs(intval($n)) % 100;
		if ($n>10 && $n<20) return $f5;
		$n = $n % 10;
		if ($n>1 && $n<5) return $f2;
		if ($n==1) return $f1;
		return $f5;
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Номер счёта',
		);
	}



}