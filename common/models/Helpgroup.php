<?php
/**
 * This is the model class for table "{{helpgroup}}".
 *
 * The followings are the available columns in table '{{helpgroup}}':
 * @property integer $id
 * @property string $title
 * @property integer $page_id
 * @property integer $sort
 * @property integer $status
 *
 * @method Helpgroup active
 * @method Helpgroup cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Helpgroup indexed($column = 'id')
 * @method Helpgroup language($lang = null)
 * @method Helpgroup select($columns = '*')
 * @method Helpgroup limit($limit, $offset = 0)
 * @method Helpgroup sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Help[] $helps
 * @property Page $page
 */
class Helpgroup extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Helpgroup the static model class
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
        return '{{helpgroup}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, page_id', 'required'),
            array('page_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('page_id', 'exist', 'className' => 'Page', 'attributeName' => 'id'),
        
            array('id, title, page_id, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'helps' => array(self::HAS_MANY, 'Help', 'helpgroup_id'),
            'page' => array(self::BELONGS_TO, 'Page', 'page_id'),
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
            'page_id' => Yii::t('backend', 'Page'),
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
		$criteria->compare('t.page_id',$this->page_id);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('page');

        return parent::searchInit($criteria);
    }
}