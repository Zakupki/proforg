<?php
/**
 * This is the model class for table "{{purchase_file}}".
 *
 * The followings are the available columns in table '{{purchase_file}}':
 * @property integer $id
 * @property integer $purchase_id
 * @property integer $file_id
 *
 * @method PurchaseFile active
 * @method PurchaseFile cache($duration = null, $dependency = null, $queryCount = 1)
 * @method PurchaseFile indexed($column = 'id')
 * @method PurchaseFile language($lang = null)
 * @method PurchaseFile select($columns = '*')
 * @method PurchaseFile limit($limit, $offset = 0)
 * @method PurchaseFile sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $file
 * @property Purchase $purchase
 */
class PurchaseFile extends BaseActiveRecord
{

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                ),
                'fileAttributes' => array(
                    'file_id',
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return PurchaseFile the static model class
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
        return '{{purchase_file}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('purchase_id, file_id', 'required'),
            array('purchase_id', 'numerical', 'integerOnly' => true),
            array('file_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('purchase_id', 'exist', 'className' => 'Purchase', 'attributeName' => 'id'),
        
            array('id, purchase_id, file_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'file' => array(self::BELONGS_TO, 'File', 'file_id'),
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
            'file_id' => Yii::t('backend', 'File'),
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
		$criteria->compare('t.file_id',$this->file_id);

		$criteria->with = array('purchase');

        return parent::searchInit($criteria);
    }
}