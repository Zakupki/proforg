<?php

class SiteController extends BackController
{
    public function actionAdmin()
    {
        $this->redirect(array('/purchase'));
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->redirect(array('/purchase'));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout(false);
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionInit()
    {
        $sql = Yii::app()->db->createCommand();
        $sql->select()
            ->from('{{user}}')
            ->order('id')
            ->limit(1, 0);

        if($data = $sql->queryRow())
        {
            echo 'There are already users found.<br />';
            $userId = $data['id'];
        }
        else
        {
            $sql->reset();

            $pwdHasher = new PasswordHash(8, false);
            $password = $pwdHasher->HashPassword('123456');

            $res = $sql->insert('{{user}}', array(
                'login' => 'admin',
                'password' => $password,
                'email' => 'admin@localhost.com',
                'name' => 'admin',
            ));

            if($res)
                echo 'User generated.<br />';
            $userId = app()->db->getLastInsertID();
        }

        $sql->reset();
        app()->db->createCommand('SET AUTOCOMMIT=0')->execute();
        $sql->truncateTable('{{auth_item}}');
        echo 'Auth items truncated.<br />';
        $sql->reset();
        $res = $sql->insert('{{auth_item}}', array(
            'name' => 'admin',
            'type' => 2,
        ));

        if($res)
            echo 'Auth item added.<br />';

        $sql->truncateTable('{{auth_assignment}}');
        echo 'Auth assignment truncated.<br />';
        $sql->reset();
        $res = $sql->insert('{{auth_assignment}}', array(
            'itemname' => 'admin',
            'userid' => $userId,
        ));

        if($res)
            echo 'User role assigned.<br />';

        $sql->reset();
        $sql->truncateTable('{{auth_log}}');
        echo 'Auth log truncated.<br />';
        app()->db->createCommand('SET AUTOCOMMIT=1')->execute();
    }

    public function actionPurgeCache()
    {
        if(Yii::app()->cache)
            Yii::app()->cache->flush();
    }
    public function actionResetreduce()
    {
        die();
        $connection = Yii::app()->db;
        $sql ="
        SELECT
          z_offer.id AS `mpid`,
          z_offer.price,
          z_offer.amount,
          offer.id,
          offer.price,
          offer.amount,
          IF(z_offer.price>offer.price,((z_offer.price-offer.price)*offer.amount)/(offer.amount*offer.price)*100,0) AS price_reduce
        FROM
          z_offer
        INNER JOIN z_offer offer
        ON offer.pid=z_offer.id AND offer.id=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`mpid`)
        WHERE z_offer.pid IS NULL
        ";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        if($result)
            foreach($result as $row){
                echo $row['id'].' - '.$row['price_reduce'];
                echo '<br>';
                $offer=Offer::model()->findByPk($row['id']);
                $offer->price_reduce=$row['price_reduce'];
                $offer->save();
            }
    }
    public function actionReclosepurchases(){
        $purchases=Purchase::model()->findAllByAttributes(array('purchasestate_id'=>'4'));
        foreach($purchases as $purchase){
            //if($purchase['id']==11313){
            echo ($purchase['id']);
            //echo time();
            $command = Yii::app()->db->createCommand('CALL closePurchase('.$purchase['id'].')');
            $command->execute();
            echo '<br>';
            //}
        }
    }
    public function actionResetofferplaces(){
        $connection = Yii::app()->db;
        $sql ="
        SELECT
          z_product.id
        FROM
          z_purchase
        INNER JOIN z_product
          ON z_product.`purchase_id`=z_purchase.id
        INNER JOIN z_offer
        ON z_offer.`product_id`=z_product.id
        WHERE z_purchase.`purchasestate_id` != 4
        group by z_product.id
        /*limit 0,1*/
        ";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        if($result)
            foreach($result as $row){
                echo $row['id'];
                echo "<br>";

                $command = Yii::app()->db->createCommand('CALL resetOfferPlaces(:product_id)');
                $command->bindParam(":product_id", $row['id'], PDO::PARAM_INT);
                $command->execute();
            }
    }
    
}