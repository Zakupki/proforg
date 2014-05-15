<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:40
 */ class AutocloseCommand extends CConsoleCommand {

    public function run($args)
    {
        $products=Purchase::model()->getOverdues();
        if($products)
        {
            foreach($products as $user){
                if(isset(Yii::app()->controller))
                    $controller = Yii::app()->controller;
                else
                    $controller = new CController('YiiMail');
                $controller->layout='mail';
                $viewPath = Yii::getPathOfAlias('frontend.views/mail/autoclose').'.php';
                $body = $controller->renderInternal($viewPath, array('user'=>$user), true);
                $queue = new EmailQueue();
                $queue->to_email = $user['email'];
                $queue->subject = "Простроченные торги";
                $queue->from_email = 'support@zakupki-online.com';
                $queue->from_name = 'Zakupki-online';
                $queue->date_published = new CDbExpression('NOW()');
                $queue->message = $body;
                $queue->save();
            }
        }
        Purchase::model()->updateAll(array('purchasestate_id'=>5), 'date_close < NOW() AND purchasestate_id = 2');
    }
}