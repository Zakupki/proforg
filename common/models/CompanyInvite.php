<?php
/**
 * This is the model class for table "{{company_invite}}".
 *
 * The followings are the available columns in table '{{company_invite}}':
 * @property integer $id
 * @property integer $company_id
 * @property integer $purchase_id
 * @property string $date_create
 *
 * @method CompanyInvite active
 * @method CompanyInvite cache($duration = null, $dependency = null, $queryCount = 1)
 * @method CompanyInvite indexed($column = 'id')
 * @method CompanyInvite language($lang = null)
 * @method CompanyInvite select($columns = '*')
 * @method CompanyInvite limit($limit, $offset = 0)
 * @method CompanyInvite sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Company $company
 * @property Purchase $purchase
 */
class CompanyInvite extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return CompanyInvite the static model class
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
        return '{{company_invite}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('company_id, purchase_id, date_create', 'required'),
            array('company_id, purchase_id', 'numerical', 'integerOnly' => true),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('purchase_id', 'exist', 'className' => 'Purchase', 'attributeName' => 'id'),
        
            array('id, company_id, purchase_id, date_create', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'purchase' => array(self::BELONGS_TO, 'Purchase', 'purchase_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'company_id' => Yii::t('backend', 'Company'),
            'purchase_id' => Yii::t('backend', 'Purchase'),
            'date_create' => Yii::t('backend', 'Date Create'),
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
		$criteria->compare('t.company_id',$this->company_id);
		$criteria->compare('t.purchase_id',$this->purchase_id);
		$criteria->compare('t.date_create',$this->date_create,true);

		$criteria->with = array('company', 'purchase');

        return parent::searchInit($criteria);
    }
}