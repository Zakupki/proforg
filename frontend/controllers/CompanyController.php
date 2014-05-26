<?php

class CompanyController extends FrontController
{
    public $company;
    public $companyname;
    public function init()
    {
        parent::init();
        Yii::import('common.extensions.yii-mail.*');
        if(!isset($this->userData['usertype_id']) || $this->userData['usertype_id']!=3)
            $this->redirect('/');

        if(isset($this->userData['company_id'])){
            $this->company=Company::model()->findByAttributes(array('id'=>$this->userData['company_id']));
            $this->companyname=$this->company->title;
        }

    }

    public function actionIndex()
    {
       $users=array();
       if(isset($this->userData['company_id']))
       $users=User::model()->findAllByAttributes(array('employer_id'=>$this->userData['company_id']));
       $this->render('index',array('users'=>$users));
    }
    public function actionUpdateuser()
    {
        $model = new UserForm;
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            die();
        }
        if (isset($_POST['UserForm'])) {
            $model->attributes = $_POST['UserForm'];
            $model->save();
        }
        $this->render('updateuser');
    }
}