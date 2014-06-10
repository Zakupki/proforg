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
       $balance=Company::model()->getBalance($this->userData['company_id']);
       if(isset($this->userData['company_id']))
       $users=User::model()->getCompanyUsers($this->userData['company_id']);
       $this->render('index',array('users'=>$users,'balance'=>$balance));
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
    public function actionTest(){

        Company::model()->payCompanyPercents();
        return;
        $combal=Company::model()->getComBal(1);
        print_r($combal);
        echo '<br/>';
        if($combal['balance']<0){
            $combal['balance'] = 0 - $combal['balance'];
            $tempbal=0;
            $curid=null;
            $reqIds=array();
            $debtleft=$combal['balance'];
            $totalpercents=0;
            for (; ; ) {
                $bal=Request::model()->getPrevRequest(array('company_id'=>$combal['company_id'],'id'=>$curid));
                if(!isset($bal) || $combal['balance']<$tempbal){
                    break;
                }
                else {
                    $curid=$bal['id'];
                    $tempbal=$tempbal+(0-$bal['value']);
                }

                if($debtleft>(0-$bal['value'])){
                    echo $bal['id'].' __ '.(0-$bal['value']).' __ '.(0-$bal['value']).' __ '.$bal['percent'].' __ '.(((0-$bal['value'])/100)*$bal['percent']).'<br/>';
                    $debtleft=$debtleft-(0-$bal['value']);
                    if($bal['percent']>0){
                        $totalpercents=$totalpercents+(((0-$bal['value'])/100)*$bal['percent']);
                    }

                }
                else{
                    echo $bal['id'].' __ '.(0-$bal['value']).' __ '.($debtleft).' __ '.$bal['percent'].' __ '.($debtleft/100*$bal['percent']).'<br/>';
                    if($bal['percent']>0){
                        $totalpercents=$totalpercents+($debtleft/100*$bal['percent']);
                    }
                }
            }
            if($totalpercents>0){
                $newrequest=new Request;
                $newrequest->requesttype_id=4;
                $newrequest->company_id=$combal['company_id'];
                $newrequest->finance_id=$combal['finance_id'];
                $newrequest->user_id=2;
                $newrequest->date_create=new CDbExpression('NOW()');
                $newrequest->value=-$totalpercents;
                $newrequest->confirm=1;
                //$newrequest->save();

            }
            echo $totalpercents;
        }

    }
}