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
       $user=User::model()->findByPk(yii::app()->user->getId());
       $this->render('index',array('user'=>$user));
    }


}