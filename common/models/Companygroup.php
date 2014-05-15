<?php
/**
 * This is the model class for table "{{companygroup}}".
 *
 * The followings are the available columns in table '{{companygroup}}':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $city
 * @property integer $region_id
 * @property integer $sort
 * @property integer $status
 *
 * @method Companygroup active
 * @method Companygroup cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Companygroup indexed($column = 'id')
 * @method Companygroup language($lang = null)
 * @method Companygroup select($columns = '*')
 * @method Companygroup limit($limit, $offset = 0)
 * @method Companygroup sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Company[] $companies
 * @property Region $region
 */
class Companygroup extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Companygroup the static model class
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
        return '{{companygroup}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 256),
            array('id, title, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'companies' => array(self::HAS_MANY, 'Company', 'companygroup_id'),
            'companygroupUsers' => array(self::HAS_MANY, 'CompanygroupUser', 'companygroup_id'),
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
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);
        return parent::searchInit($criteria);
    }
}