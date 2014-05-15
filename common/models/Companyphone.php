<?php
/**
 * This is the model class for table "{{companyphone}}".
 *
 * The followings are the available columns in table '{{companyphone}}':
 * @property integer $id
 * @property integer $country_id
 * @property integer $phonecode
 * @property integer $phone
 * @property integer $company_id
 *
 * @method Companyphone active
 * @method Companyphone cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Companyphone indexed($column = 'id')
 * @method Companyphone language($lang = null)
 * @method Companyphone select($columns = '*')
 * @method Companyphone limit($limit, $offset = 0)
 * @method Companyphone sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Country $country
 * @property Company $company
 */
class Companyphone extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Companyphone the static model class
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
        return '{{companyphone}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('phonecode, phone, company_id', 'required'),
            array('country_id, phonecode, phone, company_id', 'numerical', 'integerOnly' => true),
            array('country_id', 'exist', 'className' => 'Country', 'attributeName' => 'id'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
        
            array('id, country_id, phonecode, phone, company_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
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

		$criteria->with = array('country', 'company');

        return parent::searchInit($criteria);
    }
}