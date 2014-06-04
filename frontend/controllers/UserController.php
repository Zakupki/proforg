<?php

class UserController extends FrontController
{
    public function init()
    {
        parent::init();
        Yii::import('common.extensions.yii-mail.*');
        if(!isset($this->userData['usertype_id']) || $this->userData['usertype_id']!=2)
            $this->redirect('/');
    }

    public function actionIndex()
    {

       $card=Card::model()->findByAttributes(array('user_id'=>yii::app()->user->getId()));
       $user=User::model()->findByPk(yii::app()->user->getId());
       $company=Company::model()->findByPk($user->employer_id);
       $balance=User::model()->getBalance(yii::app()->user->getId());
       $this->render('index',array('user'=>$user,'card'=>$card,'balance'=>$balance,'company'=>$company));
    }
    public function actionCards()
    {
        $cards=Card::model()->findAllByAttributes(array('user_id'=>yii::app()->user->getId()));
        $this->render('cards',array('cards'=>$cards));
    }
    public function actionCardupdate()
    {
        if(isset($_POST['delete'])){
            $card=Card::model()->deleteByPk($_POST['delete']);
            echo CJSON::encode(array('error'=>false,'status'=>'Ваша карта успешно удалена'));
            return;
        }
        if(isset($_POST['major'])){
            $card=Card::model()->findByPk($_POST['major']);
            $card->major=1;
            $card->save();
            echo CJSON::encode(array('error'=>false,'status'=>'Вы успешно изменили основную карту'));
            return;
        }


        $model = new CardForm;
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            die();
        }
        if (isset($_POST['CardForm'])) {
            $model->attributes = $_POST['CardForm'];
            $model->save();
        }
        $this->redirect('/user/cards');
    }
    public function actionRequestupdate()
    {
        $model = new RequestForm;
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            die();
        }
        if (isset($_POST['RequestForm'])) {
            $model->attributes = $_POST['RequestForm'];
            $model->save();
        }
        $this->redirect('/user/?success=1');
    }


}