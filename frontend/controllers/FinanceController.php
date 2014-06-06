<?php

class FinanceController extends FrontController
{
    public $finance;
    public $companyname;
    public function init()
    {
        parent::init();
        Yii::import('common.extensions.yii-mail.*');
        if(!isset($this->userData['usertype_id']) || $this->userData['usertype_id']!=4)
        $this->redirect('/');

        if(isset($this->userData['finance_id'])){
            $this->finance=Finance::model()->findByAttributes(array('id'=>$this->userData['finance_id']));
            $this->companyname=$this->finance->title;
        }
    }

    public function actionIndex()
    {
        $requests=Request::model()->findAll();
        $this->render('index',array('requests'=>$requests));
    }
    public function actionCompanies()
    {
        $companies=Company::model()->findAllByAttributes(array('finance_id'=>$this->userData['finance_id']));
        $this->render('companies',array('companies'=>$companies));
    }
    public function actionDeposit()
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
        $this->render('deposit',array('model'=>$model));
    }
    public function actionUpdaterequest()
    {
        if(isset($_POST['delete'])){
            echo CJSON::encode(array('error'=>false,'status'=>'Запрос успешно удален'));
            return;
        }
        if(isset($_POST['confirm'])){
            $request=Request::model()->findByAttributes(array('id'=>$_POST['confirm']));
            $request->confirm=1;
            $request->save();
            echo CJSON::encode(array('error'=>false,'status'=>'Запрос успешно подтвержден'));
            return;
        }
    }

    public function actionUpdatecompany()
    {
        $model = new CompanyForm;
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            die();
        }
        if (isset($_POST['CompanyForm'])) {
            $model->attributes = $_POST['CompanyForm'];
            $model->save();
        }
        $this->render('updatecompany');
    }


}