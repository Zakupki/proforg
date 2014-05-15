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

class Liqpay extends PaymentSystem {

	public $merchant_id;
	public $signature;
	public $url="https://www.liqpay.com/?do=clickNbuy";
	public $method='card';

    /*public static function workWithCurrency(){
        return array("USD","EUR","GBP","YEN","CAD");
    }*/

//    public function init(){
//        $this->name = 'paypal';
//        return parent::init();
//    }

    public function rules(){
        return array(
            array('merchant_id, signature', 'required'),
            array('email', 'email'),
        );
    }

    public function attributeLabels(){
        return array(
			'merchant_id' => 'Номер мерчанта',
			'signature' => 'Пароль мерчанта'
        );
    }

    public function processRequest(){

		$xml = Yii::app()->request->getPost('operation_xml');
		$sign = Yii::app()->request->getPost('signature');


		$return['id'] = "";

		if (!$xml || !$sign) {
			$return['result'] = 'fail';
			return $return;
		}

//Paysystem::logs($xml.'<br>'.$sign.'<br><br>'.base64_decode($sign).'<br><br>'.base64_decode($xml));
		/*$sign = base64_decode($sign);

		if ($sign != $this->signature) {
			$return['result'] = 'fail';
			return $return;
		}*/

		$xml = base64_decode($xml);

		$data_xml = new SimpleXMLElement($xml);

        $return['id'] = $data_xml->response->order_id;

        if($return['id']) {
            $payment = Payments::model()->findByPk($return['id']);
        }

        if(!$return['id'] || !$payment){
            $return['result'] = 'fail';

            return $return;
        }


        $payment_status = $data_xml->response->ststus;

        if($payment_status == "success"){
            $return['result'] = 'success';
//        } elseif($payment_status == "Pending") {
//            $return['result'] = 'pending';
//            $return['pending_reason'] = getReq('pending_reason');
        } elseif($payment_status == "wait_secure") {
			$return['result'] = 'pending';
		} else {
            $return['result'] = 'fail';
        }

        return $return;
    }

//    public function echoSuccess(){
//        if($_REQUEST["payment"] == 'result'){
//            echo("OK". $_REQUEST["InvId"]."\n");
//            Yii::app()->end();
//        }
//    }

    public function processPayment(Payments $payment){

		$xml='<request>
				  <version>1.2</version>
				  <merchant_id>'.$this->merchant_id.'</merchant_id>
				  <result_url>'.self::getUrlResult().'</result_url>
				  <server_url>'.self::getUrlServer().'</server_url>
				  <order_id>'.$payment->id.'</order_id>
				  <amount>'.$payment->amount.'</amount>
				  <currency>UAH</currency>
				  <description>Poplnenie balansa</description>
				  <default_phone></default_phone>
				  <pay_way>card</pay_way>
				  <goods_id>'.$payment->id.'</goods_id>
			</request>';

		$sign=base64_encode(sha1($this->signature.$xml.$this->signature,1));
		$xml_encoded=base64_encode($xml);

//echo self::getUrlServer(); exit;
        $form = '<div class="content">
        <form action="https://www.liqpay.com/?do=clickNbuy" method="POST" id="paysystem_submit_form">
          <input type="hidden" name="operation_xml" value="'.$xml_encoded.'" />
          <input type="hidden" name="signature" value="'.$sign.'" />
		</form>

		<script>$("#paysystem_submit_form").submit();</script>
		</div>'
		;

        //return $form;
		return array(
			'status' => Paysystem::RESULT_HTML,
			'message' => $form,
		);
    }

    public static function getUrlResult(){
		return CHtml::encode(Yii::app()->controller->createAbsoluteUrl('/account/#/bills'));
    }

    public static function getUrlServer(){
		return CHtml::encode(Yii::app()->controller->createAbsoluteUrl('/account/income',
			array(
				'sys' => 'liqpay',
				'payment' => 'result',
			)));
    }


}