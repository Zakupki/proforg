<?php
/**
 * This is the model class for table "{{market_company}}".
 *
 * The followings are the available columns in table '{{market_company}}':
 * @property integer $id
 * @property integer $company_id
 * @property integer $market_id
 * @property integer $sort
 * @property integer $status
 *
 * @method MarketCompany active
 * @method MarketCompany cache($duration = null, $dependency = null, $queryCount = 1)
 * @method MarketCompany indexed($column = 'id')
 * @method MarketCompany language($lang = null)
 * @method MarketCompany select($columns = '*')
 * @method MarketCompany limit($limit, $offset = 0)
 * @method MarketCompany sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Company $company
 * @property Market $market
 */
class MarketCompany extends BaseActiveRecord
{

    public $title;
    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return MarketCompany the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{market_company}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('company_id, market_id', 'required'),
            array('company_id, market_id, sort, status', 'numerical', 'integerOnly' => true),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('market_id', 'exist', 'className' => 'Market', 'attributeName' => 'id'),
        
            array('id, company_id, market_id, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
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
            'company_id' => Yii::t('backend', 'Company'),
            'market_id' => Yii::t('backend', 'Market'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'market.title' => Yii::t('backend', 'Market'),
            'company.title' => Yii::t('backend', 'Company'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.company_id',$this->company_id);
		$criteria->compare('t.market_id',$this->market_id);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('company', 'market');

        return parent::searchInit($criteria);
    }
    public function updateForMarket($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size companyrole_ids, use last one

        foreach($newData as $item)
            if((int)$item['market_id']>0)
                $buff[(int)$item['market_id']] = $item['market_id'];
        $newData = $buff;

        if(empty($newData))
            return self::model()->deleteAllByAttributes(array('company_id' => $id));

        $o = 0;
        $delete = array();

        // update existing product info with new quantities, prices
        /** @var $curData ProductInfo[] */
        $curData = self::model()->findAllByAttributes(array('company_id' => $id));
        foreach($curData as $item)
        {
            if(!isset($newData[$item['market_id']]))
            {
                $delete[] = $item['market_id'];
                continue;
            }


            if((int)$newData[$item['market_id']]>0){
                unset($newData[$item['market_id']]);
                ++$o;
            }
        }

        // delete info
        self::model()->deleteAllByAttributes(array('company_id' => $id, 'market_id' => $delete));

        $model = new self();
        foreach($newData as $market_id)
        {
            $model->company_id = $id;
            $model->market_id = $market_id;
            if($model->save(false))
            {
                ++$o;
                $model->id = null;
                $model->setIsNewRecord(true);
            }
        }

        return $o;
    }
}