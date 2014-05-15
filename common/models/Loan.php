<?php
/**
 * This is the model class for table "{{loan}}".
 *
 * The followings are the available columns in table '{{loan}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $date_create
 * @property integer $days
 * @property integer $value
 *
 * @method Loan active
 * @method Loan cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Loan indexed($column = 'id')
 * @method Loan language($lang = null)
 * @method Loan select($columns = '*')
 * @method Loan limit($limit, $offset = 0)
 * @method Loan sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Loan extends BaseActiveRecord
{
    public $title;
    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Loan the static model class
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
        return '{{loan}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('user_id, date_create, days, value', 'required'),
            array('user_id, days, value', 'numerical', 'integerOnly' => true),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        
            array('id, user_id, date_create, days, value', 'safe', 'on' => 'search'),
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
            'date_create' => Yii::t('backend', 'Date Create'),
            'days' => Yii::t('backend', 'Days'),
            'value' => Yii::t('backend', 'Value'),
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
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.days',$this->days);
		$criteria->compare('t.value',$this->value);

		$criteria->with = array('user');

        return parent::searchInit($criteria);
    }
}