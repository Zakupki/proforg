<?php
class UserController extends BackController
{
    public function behaviors() {
        return array(
            'exportableGrid' => array(
                'class' => 'backend.components.ExportableGridBehavior',
                'filename' => 'Пользователи.csv',
                'csvDelimiter' => ';', //i.e. Excel friendly csv delimiter
            ));
    }
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        if ($this->isExportRequest()) {
            $whereSql='';
            $whereSql2='';
            /*if (isset($_GET['Purchase'])){
                $attributes = $_GET['Purchase'];
                foreach($attributes as $k=>$attr){
                    if($attr && !in_array($k,array('date_first','date_last','date_first2','date_last2','companygroup_id')))
                        $whereSql.=' AND z_purchase.'.$k.'='.$attr;
                    if($k=='companygroup_id' && $attr>0)
                        $whereSql.=' AND company.'.$k.'='.$attr;
                }
            }
            if((isset($_GET['Purchase']['date_first'])) && (isset($_GET['Purchase']['date_last'])))
                if(strlen($_GET['Purchase']['date_first'])>0 && strlen($_GET['Purchase']['date_last']))
                    $whereSql.=' AND z_purchase.date_create BETWEEN "'.$_GET['Purchase']['date_first'].'" AND "'.$_GET['Purchase']['date_last'].'"';
            if((isset($_GET['Purchase']['date_first2'])) && (isset($_GET['Purchase']['date_last2'])))
                if(strlen($_GET['Purchase']['date_first2'])>0 && strlen($_GET['Purchase']['date_last2']))
                    $whereSql.=' AND z_purchase.date_closed BETWEEN "'.$_GET['Purchase']['date_first2'].'" AND "'.$_GET['Purchase']['date_last2'].'"';*/


            /* $sortSql=' ORDER BY z_purchase.date_create DESC';
             if(isset($_GET['Purchase_sort'])){
                 $sortSql=' ORDER BY z_purchase.'.str_replace('.',' ',$_GET['Purchase_sort']);
             }*/
            $sql ='
                SELECT
                  z_user.id,
                  z_user.email,
                  z_user.first_name,
                  z_user.last_name,
                  DATE_FORMAT(z_user.date_create,"%d.%m.%Y %h:%i:%s") AS date_create,
                  z_companyrole.`title` AS companyrole,
                  z_company.title AS company,
                  z_companygroup.title AS companygroup,
                  GROUP_CONCAT(CONCAT(z_phone.`phonecode`," ",z_phone.`phone`)) AS phone,
                  z_city.title AS city,
                  z_company.address,
                  z_user.status
                FROM
                  z_user
                LEFT JOIN z_company_user
                ON z_company_user.`user_id`=z_user.id
                LEFT JOIN z_companyrole
                ON z_companyrole.`id`=z_company_user.`companyrole_id`
                LEFT JOIN z_company
                ON z_company.id=z_company_user.`company_id`
                LEFT JOIN z_companygroup
                ON z_companygroup.id=z_company.`companygroup_id`
                LEFT JOIN z_phone
                ON z_phone.`user_id`=z_user.id
                LEFT JOIN z_city
                ON z_city.`id`=z_company.city_id
                GROUP BY z_phone.`user_id`,z_company.id
                ORDER BY z_user.id DESC
            ';
            //echo $sql;
            //die();
            $data=User::model()->findAllBySql($sql);

            $this->exportCSV($data,array(
                'id',
                'email',
                'first_name',
                'last_name',
                'date_create',
                'companyrole',
                'companygroup',
                'company',
                'phone',
                'city',
                'address',
                'status'
            ));
        }else
            $this->render('admin', array(
                'model' => $model,
            ));
    }
    public function actionClone($id)
    {
        /** @var $model User */
        $model = clone $this->loadModel($id);
        $model->setScenario('create');

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['clone'], array(
            'model' => $model,
            'authItemModel' => isset($model->authItems) ? current($model->authItems) : new AuthItem()
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getNewModel();

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['create'], array(
            'model' => $model,
            'authItemModel' => new AuthItem()
        ));
    }

    public function actionUpdate($id)
    {
        /** @var $model BaseActiveRecord */
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if($this->doAction($model))
        {
            if(isset($_POST['apply']))
            {
                $this->redirectAction($model);
            }
            $this->redirectAction();
        }

        $this->render($this->view['update'], array(
            'model' => $model,
            'authItemModel' => isset($model->authItems[0]) ? $model->authItems[0] : new AuthItem()
        ));
    }
	protected function afterActionDone($model)
    {
        /** @var $model Product */
       
        $model->junction->attach($model);
        $pcData = isset($_POST['User']['userUsertypes']) ? array_unique($_POST['User']['userUsertypes']) : array();
        $model->junction->updateRelated('userUsertypes', $pcData);

         return parent::afterActionDone($model);
    }
    public function actionSignin(){
        $token=Yii::app()->session['logintoken'] = md5('zhl'.date('Ymdhi'));
        $this->redirect('/site/signin?logintoken='.$token.'&email='.$_GET['email']);
    }
}