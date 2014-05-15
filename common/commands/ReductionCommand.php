<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:40
 */ class ReductionCommand extends CConsoleCommand {

    public function run($args)
    {
        //
        echo date('Y-m-d h:i:s');
        echo "\r\n";
        $connection = Yii::app()->db;
        $sql = '
       SELECT
        z_purchase.id,
        z_purchase.date_reduction,
        z_product.check_date,
        z_product.id AS product_id,
        z_product.reductionstate,
        z_product.reductionplace,
        z_product.purchase_id,
        NOW() AS curtime,
        z_purchase.`date_reduction` as redtime,
        MAX(z_offer.reduction_place) AS max_place,
        MIN(z_offer.reduction_place) AS min_place
       FROM z_purchase
       INNER JOIN z_product
         ON z_product.purchase_id=z_purchase.id AND z_product.reductionstate<2
       INNER JOIN z_offer
         ON z_offer.product_id=z_product.id AND z_offer.pid IS NULL AND z_offer.reduction=1
       WHERE z_purchase.purchasestate_id=3 AND z_purchase.`date_reduction`<=DATE_ADD(NOW(),INTERVAL 1 MINUTE)
       GROUP BY z_product.id
       ';
        $command = $connection->createCommand($sql);
        $products = $command->queryAll();
        echo $products[0]['curtime'];
        echo 'iii';
        echo $products[0]['redtime'];

        foreach($products AS $product){
            $sql2 ="
            SELECT
                z_offer.id,
                z_offer.title,
                z_offer.price,
                z_offer.product_id,
                z_offer.reduction_place,
                z_offer.reduction_state,
                z_offer.reduction_pass
            FROM z_offer
            WHERE z_offer.product_id=" . $product['product_id'] . " AND z_offer.pid IS NULL AND z_offer.reduction=1
            ORDER BY z_offer.reduction_place ASC
            ";
            $command = $connection->createCommand($sql2);
            $offers = $command->queryAll();
            $cnt = 0;
            $tcnt = 1;
            $changedstate = 0;
            $reductionplace = 0;
            $dataArr=array();
            $dataNPass=array();
            $dataNPassed=array();

            foreach($offers as $offer){
                if(!$product['reductionstate'] && !$cnt){
                    $productModel=Product::model()->findByPk($product['product_id']);
                    $productModel->check_date=new CDbExpression('NOW()');
                    $productModel->reductionstate=1;
                    $productModel->reductionplace=$offer['reduction_place'];
                    $productModel->save();

                }
                $dataArr[$offer['product_id']][$offer['reduction_place']] = $offer;
                if (!$offer['reduction_pass'] || $offer['reduction_state']==1)
                    $dataNPass[$offer['product_id']][$offer['reduction_place']] = $offer;
                if (!$offer['reduction_pass'])
                    $dataNPassed[$offer['product_id']][$offer['reduction_place']] = $offer;
                $tcnt++;
                $cnt++;
            }
            print_r($dataNPass);

            //if ($product['reductionstate'] == 1) {
            foreach ($dataArr as $product_id => $pr) {

                /*if (count($dataNPass[$product_id]) < 1) {
                    #Закрытие всех
                    $productModel=Product::model()->findByPk($product_id);
                    $productModel->check_date=new CDbExpression('NOW()');
                    $productModel->reductionstate=2;
                    $productModel->save();
                    $sql4 ="
                                SELECT
                                  z_product.*
                                FROM
                                  z_product
                                  INNER JOIN z_purchase
                                    ON z_purchase.id = z_product.`purchase_id`
                                WHERE z_purchase.id = :purchase_id
                                AND z_purchase.purchasestate_id = 3
                                AND z_product.`reductionstate`=1
                                GROUP BY z_purchase.id
                                ";
                    $command = $connection->createCommand($sql4);
                    $command->bindParam(":purchase_id",$product['purchase_id'], PDO::PARAM_INT);

                    if(!$command->queryAll()){
                        $purchase=Purchase::model()->findByPk($product['purchase_id']);
                        $purchase->purchasestate_id=5;
                        $purchase->save();
                    }
                    break;*/
                //} else {
                $cnt = 0;
                $tcnt = 1;
                $change_state = 0;
                $changed_state = 0;
                $reduction_place = 0;
                //print_r($pr);
                //continue;
                foreach ($pr as $k => $of) {

                    #Установка следующего актиныйм
                    if ($change_state == 1) {
                        $offerModel=Offer::model()->findByPk($of['id']);
                        if(!$offerModel->reduction_pass){
                            $offerModel->reduction_state=1;
                            $offerModel->save();

                            $reduction_place = $of['reduction_place'];
                            $changed_state = 1;
                            $change_state = 0;
                        }
                    }

                    #Обновление активного
                    if ($of['reduction_state']) {
                        $sql3 ="
                                SELECT
                                   z_offer.id
                                FROM z_offer
                                WHERE z_offer.pid=" . $of['id'] . "
                                   AND z_offer.date_create>'" . $product['check_date'] . "'
                                LIMIT 0,1
                                ";
                        $command = $connection->createCommand($sql3);
                        $check_left = $command->queryAll();

                        if (!$check_left && !$of['reduction_pass']) {
                            $offerModel=Offer::model()->findByPk($of['id']);
                            $offerModel->reduction_state=0;
                            $offerModel->reduction_pass=1;
                            $offerModel->save();
                        } else{
                            $offerModel=Offer::model()->findByPk($of['id']);
                            $offerModel->reduction_state=0;
                            $offerModel->save();
                        }
                        #Закрытие всех
                        if(count($dataNPass[$product_id])<2){
                            $productModel=Product::model()->findByPk($product_id);
                            $productModel->reductionstate=2;
                            $productModel->save();
                            #Проверка редукционов по продуктам и закрытие
                            if(!Purchase::model()->getActiveReductions($product['purchase_id'])){
                                $purchase=Purchase::model()->findByPk($product['purchase_id']);
                                $purchase->purchasestate_id=5;
                                $purchase->save();
                                $purchaseUsers=Purchase::model()->getClosedReductionUsers($purchase->id);
                                #Отправка писем
                                if($purchaseUsers){
                                    foreach($purchaseUsers as $u){
                                        if(isset(Yii::app()->controller))
                                            $controller = Yii::app()->controller;
                                        else
                                            $controller = new CController('YiiMail');
                                        $controller->layout='mail';
                                        $viewPath = Yii::getPathOfAlias('frontend.views/mail/closed_reduction').'.php';
                                        $body = $controller->renderInternal($viewPath, array('user'=>$u), true);
                                        $queue = new EmailQueue();
                                        $queue->to_email = $u['email'];
                                        $queue->subject = "Завершение редукциона";
                                        $queue->from_email = 'support@zakupki-online.com';
                                        $queue->from_name = 'Zakupki-online';
                                        $queue->date_published = new CDbExpression('NOW()');
                                        $queue->message = $body;
                                        $queue->save();
                                    }
                                }
                            }
                            break;
                        }
                        $change_state = 1;
                    }



                    #Установка первого активным
                    if ($tcnt == count($pr) && !$changed_state) {
                        $keys = array_keys($dataNPassed[$product_id]);
                        $first_key = $keys[0];
                        $first_id = $pr[$first_key]['id'];
                        $reduction_place = $first_key;
                        $offerModel=Offer::model()->findByPk($first_id);
                        $offerModel->reduction_state=1;
                        $offerModel->save();
                    }

                    $tcnt++;
                    $cnt++;



                }
                #Обновление продукта
                $productModel=Product::model()->findByPk($product_id);
                $productModel->check_date=new CDbExpression('NOW()');
                $productModel->reductionplace=$reduction_place;
                $productModel->save();
                //}
            }
            //}


        }

    }
}