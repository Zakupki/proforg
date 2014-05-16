<?php

class FinanceController extends FrontController
{
    public function init()
    {
        parent::init();
        Yii::import('common.extensions.yii-mail.*');
    }

    public function actionIndex()
    {
        $requests=Request::model()->findAll();
        $this->render('index',array('requests'=>$requests));
    }


}