<?php
/**
 * This is the model class for table "{{taggroup}}".
 *
 * The followings are the available columns in table '{{taggroup}}':
 * @property integer $id
 * @property string $title
 * @property integer $sort
 *
 * @method Taggroup active
 * @method Taggroup cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Taggroup indexed($column = 'id')
 * @method Taggroup language($lang = null)
 * @method Taggroup select($columns = '*')
 * @method Taggroup limit($limit, $offset = 0)
 * @method Taggroup sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Tag[] $tags
 */
class Taggroup extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Taggroup the static model class
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
        return '{{taggroup}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('sort, disposition, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
        
            array('id, title, sort, status, disposition', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'tags' => array(self::HAS_MANY, 'Tag', 'taggroup_id'),
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
            'disposition' => Yii::t('backend', 'Disposition'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
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
        $criteria->compare('t.disposition',$this->disposition);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
}