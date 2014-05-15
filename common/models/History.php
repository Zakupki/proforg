<?php
/**
 * This is the model class for table "{{history}}".
 *
 * The followings are the available columns in table '{{history}}':
 * @property integer $id
 * @property integer $purchase_id
 * @property string $date_create
 * @property integer $company_id
 * @property integer $historytype_id
 *
 * @method History active
 * @method History cache($duration = null, $dependency = null, $queryCount = 1)
 * @method History indexed($column = 'id')
 * @method History language($lang = null)
 * @method History select($columns = '*')
 * @method History limit($limit, $offset = 0)
 * @method History sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Company $company
 * @property Historytype $historytype
 * @property Purchase $purchase
 */
class History extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return History the static model class
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
        return '{{history}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('purchase_id, date_create, company_id', 'required'),
            array('purchase_id, company_id, historytype_id', 'numerical', 'integerOnly' => true),
            array('purchase_id', 'exist', 'className' => 'Purchase', 'attributeName' => 'id'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('historytype_id', 'exist', 'className' => 'Historytype', 'attributeName' => 'id'),
        
            array('id, purchase_id, date_create, company_id, historytype_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'historytype' => array(self::BELONGS_TO, 'Historytype', 'historytype_id'),
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
            'purchase_id' => Yii::t('backend', 'Purchase'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'company_id' => Yii::t('backend', 'Company'),
            'historytype_id' => Yii::t('backend', 'Historytype'),
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
		$criteria->compare('t.purchase_id',$this->purchase_id);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.company_id',$this->company_id);
		$criteria->compare('t.historytype_id',$this->historytype_id);

		$criteria->with = array('company', 'historytype', 'purchase');

        return parent::searchInit($criteria);
    }
}