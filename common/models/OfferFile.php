<?php
/**
 * This is the model class for table "{{offer_file}}".
 *
 * The followings are the available columns in table '{{offer_file}}':
 * @property integer $id
 * @property integer $offer_id
 * @property integer $file_id
 *
 * @method OfferFile active
 * @method OfferFile cache($duration = null, $dependency = null, $queryCount = 1)
 * @method OfferFile indexed($column = 'id')
 * @method OfferFile language($lang = null)
 * @method OfferFile select($columns = '*')
 * @method OfferFile limit($limit, $offset = 0)
 * @method OfferFile sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Offer $offer
 * @property File $file
 */
class OfferFile extends BaseActiveRecord
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
     * @return OfferFile the static model class
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
        return '{{offer_file}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('offer_id, file_id', 'required'),
            array('offer_id', 'numerical', 'integerOnly' => true),
            array('file_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('offer_id', 'exist', 'className' => 'Offer', 'attributeName' => 'id'),
        
            array('id, offer_id, file_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'offer' => array(self::BELONGS_TO, 'Offer', 'offer_id'),
            'file' => array(self::BELONGS_TO, 'File', 'file_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'offer_id' => Yii::t('backend', 'Offer'),
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
		$criteria->compare('t.offer_id',$this->offer_id);
		$criteria->compare('t.file_id',$this->file_id);

		$criteria->with = array('offer');

        return parent::searchInit($criteria);
    }
}