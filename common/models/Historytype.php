<?php
/**
 * This is the model class for table "{{historytype}}".
 *
 * The followings are the available columns in table '{{historytype}}':
 * @property integer $id
 * @property string $name
 *
 * @method Historytype active
 * @method Historytype cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Historytype indexed($column = 'id')
 * @method Historytype language($lang = null)
 * @method Historytype select($columns = '*')
 * @method Historytype limit($limit, $offset = 0)
 * @method Historytype sort($columns = '')
 *
 * The followings are the available model relations:
 * @property History[] $histories
 */
class Historytype extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Historytype the static model class
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
        return '{{historytype}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('name', 'required'),
            array('name', 'length', 'max' => 255),
        
            array('id, name', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'histories' => array(self::HAS_MANY, 'History', 'historytype_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('backend', 'Name'),
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
		$criteria->compare('t.name',$this->name,true);

        return parent::searchInit($criteria);
    }
}