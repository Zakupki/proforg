<?php
/**
 * This is the model class for table "{{purchase}}".
 *
 * @method Purchase active
 * @method Purchase cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Purchase indexed($column = 'id')
 * @method Purchase language($lang = null)
 * @method Purchase select($columns = '*')
 * @method Purchase limit($limit, $offset = 0)
 * @method Purchase sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Market $market
 * @property User $user
 * @property User $closer
 * @property Company $company
 * @property User $lastuser
 */
class Purchaseanalytics extends BaseActiveRecord
{

    public $date_first;
    public $date_last;
    public $companygroup_id;

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Purchase the static model class
     */
    public static function model($className=__CLASS__)
    {

        return parent::model($className);
    }
    public function init(){
        $this->date_first=date('Y-m-d',mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
        $this->date_last=date('Y-m-d');
        parent::init();
    }
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{purchase}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('date_first,date_last', 'required'),
            array('date_create, date_closed, date_first, date_last, company_id, companygroup_id', 'safe'),
            array('date_first', 'check_date'),
            array('date_last', 'check_date'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function check_date()
    {
       /* if(strlen($this->date_first)<10)
            $this->date_first='2014-01-31';
        if(strlen($this->date_last)<10)
            $this->date_last='2014-01-31';*/
    }

    public function relations()
    {
        return array(
            'market' => array(self::BELONGS_TO, 'Market', 'market_id'),

        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'date_create' => Yii::t('backend', 'Date'),
            'company_id' => Yii::t('backend', 'Company'),
            'companygroup_id' => Yii::t('backend', 'Companygroup'),

        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function getstats()
    {
        #Новые планы
        $data['new_purchases']=array();
        $data['new_purchases_total']=0;
            $new_purchases=$this->newPurchaseStats();
            foreach($new_purchases AS $row){
                $data['new_purchases'][]=array((intval($row['key'])+4*60*60)*1000,intval($row['value']));
                $data['new_purchases_total']=$data['new_purchases_total']+intval($row['value']);
            }

        #Закрытые планы
        $data['closed_purchases']=array();
        $data['closed_purchases_total']=0;
        $data['not_concurent']=0;
        $data['not_min_purchase']=0;
            $closed_purchases=$this->closedPurchaseStats();
            foreach($closed_purchases AS $row){
                $data['closed_purchases'][]=array((intval($row['key'])+4*60*60)*1000,intval($row['value']));
                $data['closed_purchases_total']=$data['closed_purchases_total']+intval($row['value']);
                $data['not_concurent']=$data['not_concurent']+intval($row['not_concurent']);
                $data['not_min_purchase']=$data['not_min_purchase']+intval($row['not_min_purchase']);
            }
        #редукционы
        $data['reductions']=$this->closedReductionStats();
        #Деньги
        $data['money']=array();
        $data['money_total']=0;
        $data['economy']=array();
        $data['economy_total']=0;
        $data['lose']=array();
        $data['lose_total']=0;
        $moneydata=$this->moneyStats();

        foreach($moneydata AS $row){
            $data['money'][]=array((intval($row['key'])+4*60*60)*1000,intval($row['total']));
            $data['money_total']=$data['money_total']+intval($row['total']);

            $data['economy'][]=array((intval($row['key'])+4*60*60)*1000,intval($row['economy_sum']));
            $data['economy_total']=$data['economy_total']+intval($row['economy_sum']);

            $data['lose'][]=array((intval($row['key'])+4*60*60)*1000,intval($row['lose_total']));
            $data['lose_total']=$data['lose_total']+intval($row['lose_total']);
        }

        return $data;
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id',$this->id);
		$criteria->with = array('market', 'user', 'closer', 'company', 'lastuser');
        return parent::searchInit($criteria);
    }

    public function newPurchaseStats(){
        $whereSql='';
        if($this->company_id>0){
            $whereSql.=' AND z_purchase.company_id='.$this->company_id;
        }
        if($this->companygroup_id>0){
            $whereSql.=' AND z_company.companygroup_id='.$this->companygroup_id;
        }

        $connection = Yii::app()->db;
        $sql ='
        SELECT
          UNIX_TIMESTAMP(DATE_FORMAT(z_purchase.date_create, "%Y-%m-%d 00:00:00")) AS `key`,
          COUNT(z_purchase.id) AS `value`
        FROM
          z_purchase
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
        WHERE 1=1 '.$whereSql.' AND z_purchase.date_create BETWEEN "'.$this->date_first.' 00:00:00"
          AND "'.$this->date_last.' 00:00:00"
          GROUP BY DATE_FORMAT(
            z_purchase.date_create,
            "%Y%m%d"
          )
        ';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    public function closedPurchaseStats(){
        $whereSql='';
        if($this->company_id>0){
            $whereSql.=' AND z_purchase.company_id='.$this->company_id;
        }
        if($this->companygroup_id>0){
            $whereSql.=' AND z_company.companygroup_id='.$this->companygroup_id;
        }
        $connection = Yii::app()->db;
        $sql ='
        SELECT
          UNIX_TIMESTAMP(DATE_FORMAT(z_purchase.date_closed, "%Y-%m-%d 00:00:00")) AS `key`,
          COUNT(z_purchase.id) AS `value`,
          SUM(z_purchase.not_min_purchase) AS `not_min_purchase`,
          SUM(z_purchase.not_concurent) AS `not_concurent`
        FROM
          z_purchase
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
       WHERE 1=1 '.$whereSql.' AND z_purchase.date_closed BETWEEN "'.$this->date_first.' 00:00:00"
          AND "'.$this->date_last.' 00:00:00" AND z_purchase.purchasestate_id=4
          GROUP BY DATE_FORMAT(
            z_purchase.date_closed,
            "%Y%m%d"
          )
        ';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    public function closedReductionStats(){
        $whereSql='';
        if($this->company_id>0){
            $whereSql.=' AND z_purchase.company_id='.$this->company_id;
        }
        if($this->companygroup_id>0){
            $whereSql.=' AND z_company.companygroup_id='.$this->companygroup_id;
        }
        $connection = Yii::app()->db;
        $sql ='
        SELECT
          COUNT(distinct z_purchase.id) AS `value`
        FROM
          z_purchase
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
        INNER JOIN z_product
          ON z_product.purchase_id=z_purchase.id AND z_product.reductionstate=2
       WHERE 1=1 '.$whereSql.' AND z_purchase.date_closed BETWEEN "'.$this->date_first.' 00:00:00"
          AND "'.$this->date_last.' 00:00:00" AND z_purchase.purchasestate_id=4
        ';
        $command = $connection->createCommand($sql);
        $result = $command->queryColumn();
        return intval($result[0]);
    }
    public function newPurchaseStatsYear(){
        $connection = Yii::app()->db;
        $sql ='
        SELECT
          UNIX_TIMESTAMP(DATE_FORMAT(z_purchase.date_create, "%Y-%m-01 00:00:00")) AS `key`,
          COUNT(id) AS `value`
        FROM
          z_purchase
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
        WHERE z_purchase.date_create BETWEEN DATE_ADD(DATE_FORMAT(NOW(), "%Y-%m-31 00:00:00"), INTERVAL - 1 YEAR)
          AND DATE_FORMAT(NOW(), "%Y-%m-31 00:00:00")
          GROUP BY DATE_FORMAT(
            z_purchase.date_create,
            "%Y%m"
          )
        ';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    public function closedPurchaseStatsYear(){
        $connection = Yii::app()->db;
        $sql ='
        SELECT
          UNIX_TIMESTAMP(DATE_FORMAT(z_purchase.date_closed, "%Y-%m-01 00:00:00")) AS `key`,
          COUNT(id) AS `value`
        FROM
          z_purchase
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
        WHERE z_purchase.date_closed BETWEEN DATE_ADD(DATE_FORMAT(NOW(), "%Y-%m-31 00:00:00"), INTERVAL - 1 YEAR)
          AND "'.$this->date_last.' 00:00:00" AND z_purchase.purchasestate_id=4
          GROUP BY DATE_FORMAT(
            z_purchase.date_closed,
            "%Y%m"
          )
        ';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    public function moneyStats(){
        $whereSql='';
        if($this->company_id>0){
            $whereSql=' AND z_purchase.company_id='.$this->company_id;
        }
        if($this->companygroup_id>0){
            $whereSql.=' AND z_company.companygroup_id='.$this->companygroup_id;
        }
        $connection = Yii::app()->db;
        $sql ='
        SELECT
          UNIX_TIMESTAMP(DATE_FORMAT(z_purchase.date_close, "%Y-%m-%d 00:00:00")) AS `key`,
          SUM(z_purchase.`total`) as total,
          SUM(z_purchase.`economy_sum`) as economy_sum,
          SUM(z_purchase.`lose_total`) as lose_total
        FROM
          z_purchase
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
        WHERE 1=1 '.$whereSql.' AND z_purchase.`purchasestate_id`=4
        AND z_purchase.date_close BETWEEN "'.$this->date_first.' 00:00:00"
        AND DATE_FORMAT(NOW(), "%Y-%m-%d 00:00:00")
        GROUP BY DATE_FORMAT(
                    z_purchase.date_close,
                    "%Y%m%d"
                  )

                ';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
}