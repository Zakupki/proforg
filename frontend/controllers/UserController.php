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
       $balance=User::model()->getBalance(yii::app()->user->getId());
       $this->render('index',array('user'=>$user,'card'=>$card,'balance'=>$balance));
    }
    public function actionCards()
    {
        $cards=Card::model()->findAllByAttributes(array('user_id'=>yii::app()->user->getId()));
        $this->render('cards',array('cards'=>$cards));
    }
    public function actionCardupdate()
    {
        $model = new CardForm;
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            die();
        }
        if (isset($_POST['CardForm'])) {
            $model->attributes = $_POST['CardForm'];
            $model->save();
        }
        $this->render('cardupdate');
    }


}