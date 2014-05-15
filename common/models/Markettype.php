<?php
/**
 * This is the model class for table "{{markettype}}".
 *
 * The followings are the available columns in table '{{markettype}}':
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property string $description
 * @property integer $sort
 * @property integer $status
 *
 * @method Markettype active
 * @method Markettype cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Markettype indexed($column = 'id')
 * @method Markettype language($lang = null)
 * @method Markettype select($columns = '*')
 * @method Markettype limit($limit, $offset = 0)
 * @method Markettype sort($columns = '')
 */
class Markettype extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Markettype the static model class
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
        return '{{markettype}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('code', 'length', 'max' => 20),
            array('description', 'length', 'max' => 255),
        
            array('id, title, code, description, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
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
            'code' => Yii::t('backend', 'Code'),
            'description' => Yii::t('backend', 'Description'),
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
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
}