<?php
/**
 * This is the model class for table "{{companyrole}}".
 *
 * The followings are the available columns in table '{{companyrole}}':
 * @property integer $id
 * @property string $title
 * @property integer $sort
 * @property integer $status
 *
 * @method Companyrole active
 * @method Companyrole cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Companyrole indexed($column = 'id')
 * @method Companyrole language($lang = null)
 * @method Companyrole select($columns = '*')
 * @method Companyrole limit($limit, $offset = 0)
 * @method Companyrole sort($columns = '')
 *
 * The followings are the available model relations:
 * @property CompanyUser[] $companyUsers
 */
class Companyrole extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Companyrole the static model class
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
        return '{{companyrole}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
        
            array('id, title, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'companyUsers' => array(self::HAS_MANY, 'CompanyUser', 'companyrole_id'),
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