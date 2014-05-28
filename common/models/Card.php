<?php
/**
 * This is the model class for table "{{card}}".
 *
 * The followings are the available columns in table '{{card}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $name
 * @property integer $number
 * @property string $expire
 * @property integer $ccv
 * @property integer $sort
 * @property integer $status
 *
 * @method Card active
 * @method Card cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Card indexed($column = 'id')
 * @method Card language($lang = null)
 * @method Card select($columns = '*')
 * @method Card limit($limit, $offset = 0)
 * @method Card sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Card extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Card the static model class
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
        return '{{card}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('user_id, first_name, last_name, name, number, expire', 'required'),
            array('user_id, number, ccv, sort, status', 'numerical', 'integerOnly' => true),
            array('first_name, last_name, name', 'length', 'max' => 255),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        
            array('id, user_id, first_name, last_name, name, number, expire, ccv, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => Yii::t('backend', 'User'),
            'first_name' => Yii::t('backend', 'First Name'),
            'last_name' => Yii::t('backend', 'Last Name'),
            'name' => Yii::t('backend', 'Name'),
            'number' => Yii::t('backend', 'Number'),
            'expire' => Yii::t('backend', 'Expire'),
            'ccv' => Yii::t('backend', 'Ccv'),
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
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.first_name',$this->first_name,true);
		$criteria->compare('t.last_name',$this->last_name,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.number',$this->number);
		$criteria->compare('t.expire',$this->expire,true);
		$criteria->compare('t.ccv',$this->ccv);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('user');

        return parent::searchInit($criteria);
    }
}