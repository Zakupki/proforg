<?php
/**
 * This is the model class for table "{{requesttype}}".
 *
 * The followings are the available columns in table '{{requesttype}}':
 * @property integer $id
 * @property string $title
 * @property integer $sort
 * @property integer $status
 *
 * @method Requesttype active
 * @method Requesttype cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Requesttype indexed($column = 'id')
 * @method Requesttype language($lang = null)
 * @method Requesttype select($columns = '*')
 * @method Requesttype limit($limit, $offset = 0)
 * @method Requesttype sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Request[] $requests
 */
class Requesttype extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Requesttype the static model class
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
        return '{{requesttype}}';
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
            'requests' => array(self::HAS_MANY, 'Request', 'requesttype_id'),
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