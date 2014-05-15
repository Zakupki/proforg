<?php
/**
 * This is the model class for table "{{phone}}".
 *
 * The followings are the available columns in table '{{phone}}':
 * @property integer $id
 * @property integer $country_id
 * @property integer $phonecode
 * @property integer $phone
 * @property integer $company_id
 * @property integer $user_id
 *
 * @method Phone active
 * @method Phone cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Phone indexed($column = 'id')
 * @method Phone language($lang = null)
 * @method Phone select($columns = '*')
 * @method Phone limit($limit, $offset = 0)
 * @method Phone sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Country $country
 * @property Company $company
 */
class Phone extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Phone the static model class
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
        return '{{phone}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('phonecode, phone', 'required'),
            array('country_id, phonecode, phone, company_id, user_id', 'numerical', 'integerOnly' => true),
            array('country_id', 'exist', 'className' => 'Country', 'attributeName' => 'id'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            //array('countrycode', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        
            array('id, country_id, phonecode, phone, company_id, user_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'country_id' => Yii::t('backend', 'Country'),
            'phonecode' => Yii::t('backend', 'Phonecode'),
            'phone' => Yii::t('backend', 'Phone'),
            'company_id' => Yii::t('backend', 'Company'),
            'user_id' => Yii::t('backend', 'User'),
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
		$criteria->compare('t.country_id',$this->country_id);
		$criteria->compare('t.phonecode',$this->phonecode);
		$criteria->compare('t.phone',$this->phone);
		$criteria->compare('t.company_id',$this->company_id);
		$criteria->compare('t.user_id',$this->user_id);

		$criteria->with = array('user', 'country', 'company');

        return parent::searchInit($criteria);
    }
    public function findPhones($params)
    {
        $connection = Yii::app()->db;
        $sql = '
        SELECT
        t.*,
        c.phonecode AS countrycode
        FROM {{phone}} t
        INNER JOIN {{country}} c
            ON c.id=t.country_id
        WHERE t.user_id=:user_id';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", $params['user_id'], PDO::PARAM_INT);
        return $command->queryAll();
    }
}