<?php
/**********************************************************************************************
*                            CMS Open Real Estate
*                              -----------------
*	version				:	%TAG%
*	copyright			:	(c) %YEAR% Monoray
*	website				:	http://www.monoray.ru/
*	contact us			:	http://www.monoray.ru/contact
*
* This file is part of CMS Open Real Estate
*
* Open Real Estate is free software. This work is licensed under a GNU GPL.
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
***********************************************************************************************/

class Paysystem extends BaseActiveRecord {

	const STATUS_ACTIVE=1;
	const STATUS_INACTIVE=0;
	const MODE_REAL=1;
	const MODE_TEST=0;

	const ID_BALANCE = 4;

	const RESULT_ERROR = 1;
	const RESULT_OK = 2;
	const RESULT_NOTICE = 3;
	const RESULT_HTML = 4;

	public $payModel = null;
	public $payModelName = null;
	public $viewName = null;

	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return '{{paysystem}}';
	}

	public function rules(){
		return array(
			array('title, status', 'required'),
            array('price', 'safe'),
        );
	}



	/*public function scopes(){
		return array(
			'active' => array('condition' => 'active='.self::STATUS_ACTIVE)
		);
	}*/

	protected function afterFind(){
		// создаем зависимые модели
		$this->createPayModel();

		return parent::afterFind();
	}

	protected function beforeSave(){
		$settings = array();
		foreach($this->payModel->attributes as $key => $value) {
			$settings[$key] = $value;
		}
		// Сохраняем аттрибуты зависимой модели (настройки платежки)
		$this->settings = CJSON::encode($settings);

		return parent::beforeSave();
	}

	public function attributeLabels(){
		return array(
			'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
			'title' => Yii::t('backend', 'Title'),
			'description' => Yii::t('backend', 'Description'),
		);
	}

	public function createPayModel(){
		if($this->model_name && !$this->payModel){
			$this->payModelName = ucfirst($this->model_name);
			$this->payModel = new $this->payModelName;

			$this->viewName = $this->model_name;
			$this->payModel->attributes = CJSON::decode($this->settings, true);
		}
		return $this->payModel;
	}

	public static function getPaysystems($all = null){
		if($all){
			$models = Paysystem::model()->findAll();
		} else {
			$models = Paysystem::model()->findAll(array('condition' => 'status = '.Paysystem::STATUS_ACTIVE.' AND forPayment = 1'));
		}

		return $models;
	}

    public static function logs($mVal) {
		$file = fopen(File::basePath() . '/upload/logs.txt', 'a+');
		$sLogs = date("d.m.y H:i : ") . var_export($mVal, true) . "\n";
		fwrite($file, $sLogs);
		fclose($file);
	}

    public function getPrice($id){
        $model=self::findByPk($id);
        return str_replace('.00','',$model->price);
    }



	public function search() {
		$criteria = new CDbCriteria;

        if(!isset($_GET['Page_sort'])){
            $_GET['Page_sort'] = 'sort';
            $_GET['Purchase_sort'] = 'sort.asc';
        }

		$criteria->compare($this->getTableAlias().'.id', $this->id);
		$criteria->compare($this->getTableAlias().'.title', $this->title, true);


		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => param('adminPaginationPageSize', 20),
			),
		));
	}
    public function getAdditionalServices(){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          `z_paysystem`.id,
          `z_paysystem`.title
        FROM
          `z_paysystem`
        WHERE `z_paysystem`.`addService`=1 AND z_paysystem.`status`=1
        ';
        $command = $connection->createCommand($sql);
        /* $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
         $command->bindParam(":companyrole_id", $params['companyrole_id'], PDO::PARAM_INT);*/
        $result = $command->queryAll();
        return $result;
    }
}