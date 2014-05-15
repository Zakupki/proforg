<?php

class SiteController extends FrontController
{
    public function init()
    {
        parent::init();
        Yii::import('common.extensions.yii-mail.*');
    }

    public function actionIndex()
    {
       $this->render('index');
    }

    public function actionRegister()
    {

        $this->setPageId(2);

        $model = new RegisterForm;
        // if it is ajax validation request

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['RegisterForm'])) {
            $model->attributes = $_POST['RegisterForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                if ($model->register())
                    $this->redirect(Yii::app()->user->returnUrl);
                else
                    $this->redirect('/site/register/');
            }
        }


        $this->setBodyClass('registration');

        if (isset($_GET['page']))
            $page = $_GET['page'];
        else
            $page = null;
        $user = Yii::app()->user->getData();
        $this->render('register', array('model' => $model, 'user' => $user, 'page' => $page));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionerrorr()
    {
        if($_SERVER['REMOTE_ADDR']!='31.42.52.10')
            $this->redirect('/');
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
            {
                unset($error['traces']);
                if($_SERVER['HTTP_HOST']=='newzakupki2.reactor.ua'){
                    CVarDumper::dump($error,10,true);
                }else{
                    $this->render('error', $error);
                }
            }
        }
    }


    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }
    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout(false);
        unset($_COOKIE['PHPSESSID']);
        $this->redirect(Yii::app()->homeUrl);
    }
}