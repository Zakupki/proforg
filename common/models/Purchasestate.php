<?php
/**
 * This is the model class for table "{{purchasestate}}".
 *
 * The followings are the available columns in table '{{purchasestate}}':
 * @property integer $id
 * @property string $title
 *
 * @method Purchasestate active
 * @method Purchasestate cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Purchasestate indexed($column = 'id')
 * @method Purchasestate language($lang = null)
 * @method Purchasestate select($columns = '*')
 * @method Purchasestate limit($limit, $offset = 0)
 * @method Purchasestate sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Purchase[] $purchases
 */
class Purchasestate extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Purchasestate the static model class
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
        return '{{purchasestate}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('title', 'length', 'max' => 255),
        
            array('id, title', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'purchases' => array(self::HAS_MANY, 'Purchase', 'purchasestate_id'),
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

        return parent::searchInit($criteria);
    }
}