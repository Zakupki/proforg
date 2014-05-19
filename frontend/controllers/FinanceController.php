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