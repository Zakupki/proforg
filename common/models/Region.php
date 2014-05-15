<?php
/**
 * This is the model class for table "{{region}}".
 *
 * The followings are the available columns in table '{{region}}':
 * @property integer $id
 * @property integer $country_id
 * @property string $title
 * @property integer $sort
 * @property integer $status
 *
 * @method Region active
 * @method Region cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Region indexed($column = 'id')
 * @method Region language($lang = null)
 * @method Region select($columns = '*')
 * @method Region limit($limit, $offset = 0)
 * @method Region sort($columns = '')
 *
 * The followings are the available model relations:
 * @property City[] $cities
 * @property Companygroup[] $companygroups
 * @property Country $country
 */
class Region extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Region the static model class
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
        return '{{region}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('country_id, title', 'required'),
            array('country_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 55),
            array('country_id', 'exist', 'className' => 'Country', 'attributeName' => 'id'),
        
            array('id, country_id, title, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'cities' => array(self::HAS_MANY, 'City', 'region_id'),
            'companygroups' => array(self::HAS_MANY, 'Companygroup', 'region_id'),
            'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'country_id' => Yii::t('backend', 'Country'),
            'title' => Yii::t('backend', 'Title'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
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
		$criteria->compare('t.country_id',$this->country_id);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('country');

        return parent::searchInit($criteria);
    }
    public function getCountryRegions($id){
                      
        
         return self::model()->findAll(array(
                        'select'=>'id, title',
                        'condition'=>'country_id=:country_id',
                        'params'=>array(':country_id'=>$id)
         ));
    }
   
}