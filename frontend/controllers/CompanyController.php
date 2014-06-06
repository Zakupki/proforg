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
       $users=User::model()->getCompanyUsers($this->userData['company_id']);
       $this->render('index',array('users'=>$users));
    }
    public function actionUpdateuser()
    {
        if(isset($_POST['delete'])){

            User::model()->deleteByPk($_POST['delete']);
            /*$card=Card::model()->deleteByPk($_POST['delete']);
            $major=Card::model()->findByAttributes(array('user_id'=>yii::app()->user->getId(),'major'=>1));
            if(!$major){
                $oldcard=Card::model()->findByAttributes(array('user_id'=>yii::app()->user->getId()));
                if($oldcard){
                    $oldcard->major=1;
                    $oldcard->save();
                }
            }*/
            echo CJSON::encode(array('error'=>false,'status'=>'Сотрудник успешно удален'));
            return;
        }

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