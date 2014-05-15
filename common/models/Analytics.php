<?php
/**
 * This is the model class for table "{{company}}".
 *
 * The followings are the available columns in table '{{company}}':
 * @property integer $id
 * @property string $title
 * @property integer $companygroup_id
 * @property string $description
 * @property string $city
 * @property integer $region_id
 * @property integer $sort
 * @property integer $status
 *
 * @method Company active
 * @method Company cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Company indexed($column = 'id')
 * @method Company language($lang = null)
 * @method Company select($columns = '*')
 * @method Company limit($limit, $offset = 0)
 * @method Company sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Companygroup $companygroup
 * @property Region $region
 */
class Analytics extends BaseActiveRecord
{
    public $purchase_num;
    public $avg_company_num;
    public $date_first;
    public $date_last;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Company the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
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
            array('user_id, company_id', 'required'),
            array('user_id, company_id', 'numerical', 'integerOnly' => true),
            /*    array('title', 'length', 'max' => 255),
                //array('city', 'length', 'max' => 128),
                array('description, account, bank, ndspayer, withnds, director', 'safe'),
                array('companygroup_id', 'exist', 'className' => 'Companygroup', 'attributeName' => 'id'),
                array('city_id', 'exist', 'className' => 'City', 'attributeName' => 'id'),
            */
            array('user_id, id, purchase_num, economy_sum, not_concurent, company_id, date_closed', 'safe'),
            array('user_id, id, purchase_num, economy_sum, not_concurent, company_id, date_first, date_last, date_closed', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            /*'companyUsers' => array(self::HAS_MANY, 'CompanyUser', 'company_id', 'with' => 'user'),
            'companyMarkets' => array(self::HAS_MANY, 'MarketCompany', 'company_id', 'with' => 'market'),
            'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'cityname' => array(self::BELONGS_TO, 'City', 'city_id'),
            'companytype' => array(self::BELONGS_TO, 'Companytype', 'companytype_id'),
            'region' => array(self::BELONGS_TO, 'Region', 'region_id'),*/
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => Yii::t('backend', 'Title'),
            'user_id' => Yii::t('backend', 'User'),
            'company_id' => Yii::t('backend', 'Company'),
            'purchase_num' => Yii::t('backend', 'Purchase Number'),
            'total' => Yii::t('backend', 'Total'),
            'economy_sum' => Yii::t('backend', 'Economy'),
            'avg_company_num' => Yii::t('backend', 'Average Company Number'),
            'not_concurent' => Yii::t('backend', 'Not Concurent'),
            'date_closed' => Yii::t('backend', 'Date Closed'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        if((isset($this->date_first) && trim($this->date_first) != "") && (isset($this->date_last) && trim($this->date_last) != ""))
            $criteria->addBetweenCondition('t.date_closed', ''.$this->date_first.'', ''.$this->date_last.'');

        $criteria->compare('t.id', $this->id);
        /*$criteria->compare('t.title', $this->title, true);*/
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.company_id', $this->company_id);
        $criteria->select='
        COUNT(DISTINCT t.id) AS purchase_num,
        SUM(t.total) AS total,
        SUM(t.economy_sum) AS economy_sum,
        SUM(t.company_num)/COUNT(DISTINCT t.id) AS avg_company_num,
        SUM(t.not_concurent) AS not_concurent,
        t.user_id
        ';
        $criteria->join='
              INNER JOIN z_company
                ON z_company.id=t.company_id
              INNER JOIN z_companygroup_service
                ON z_companygroup_service.companygroup_id=z_company.companygroup_id AND service_id=7 AND z_companygroup_service.status=1
        ';
        $criteria->group='t.user_id';
        /*$criteria->compare('t.companygroup_id', $this->companygroup_id);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.city_id', $this->city_id, true);
        $criteria->compare('t.companytype_id',$this->companytype_id);
        $criteria->compare('t.egrpou', $this->egrpou);
        $criteria->compare('t.sort', $this->sort);
        $criteria->compare('t.status', $this->status);*/

        $criteria->with = array('user','company');

        return parent::searchInit($criteria);
    }
}