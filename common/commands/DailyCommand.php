<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:40
 */ class DailyCommand extends CConsoleCommand {
    public function run($args)
    {
       /* $contr=Yii::app()->controller;
        $contr->layout="mail";*/
        $products=Purchase::model()->getNewPurchases();
        $productArr=array();
        foreach($products as $product){
            $productArr[$product['market_id']][$product['id']]=$product;
        }
        $userArr=array();
        if(count($productArr)>0){
            $users=User::model()->getUsersByMarket(array_keys($productArr));
            foreach($users as $u){
                $userArr[$u['id']]['email']=$u['email'];
                $userArr[$u['id']]['name']=$u['name'];
                $userArr[$u['id']]['markets'][$u['market_id']]=$u['market_id'];
            }
        }

        foreach($userArr as $user){
            if(isset(Yii::app()->controller))
            $controller = Yii::app()->controller;
            else
            $controller = new CController('YiiMail');
            $controller->layout='mail';
            $viewPath = Yii::getPathOfAlias('frontend.views/mail/new_purchases').'.php';
            $body = $controller->renderInternal($viewPath, array('products'=>$productArr,'user'=>$user), true);
            $queue = new EmailQueue();
            $queue->to_email = $user['email'];
            $queue->subject = "Новые планы закупок";
            $queue->from_email = 'support@zakupki-online.com';
            $queue->from_name = 'Zakupki-online';
            $queue->date_published = new CDbExpression('NOW()');
            $queue->message = $body;
            $queue->save();

        }
    }
}