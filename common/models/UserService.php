<?php
/**
 * This is the model class for table "{{user_service}}".
 *
 * The followings are the available columns in table '{{user_service}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $service_id
 * @property integer $status
 *
 * @method UserService active
 * @method UserService cache($duration = null, $dependency = null, $queryCount = 1)
 * @method UserService indexed($column = 'id')
 * @method UserService language($lang = null)
 * @method UserService select($columns = '*')
 * @method UserService limit($limit, $offset = 0)
 * @method UserService sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Paysystem $service
 */
class UserService extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return UserService the static model class
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
        return '{{user_service}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('user_id, service_id', 'required'),
            array('user_id, service_id, status', 'numerical', 'integerOnly' => true),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('service_id', 'exist', 'className' => 'Service', 'attributeName' => 'id'),
        
            array('id, user_id, service_id, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'service' => array(self::BELONGS_TO, 'Paysystem', 'service_id'),
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
            'service_id' => Yii::t('backend', 'Service'),
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
		$criteria->compare('t.service_id',$this->service_id);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('user', 'service');

        return parent::searchInit($criteria);
    }
}