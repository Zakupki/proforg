<?php

class CompanyController extends FrontController
{
    public function init()
    {
        parent::init();
        Yii::import('common.extensions.yii-mail.*');
        if(!isset($this->userData['usertype_id']) || $this->userData['usertype_id']!=3)
            $this->redirect('/');
    }

    public function actionIndex()
    {
       $this->render('index');
    }
}