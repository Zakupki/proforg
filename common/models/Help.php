<?php
/**
 * This is the model class for table "{{help}}".
 *
 * The followings are the available columns in table '{{help}}':
 * @property integer $id
 * @property string $title
 * @property integer $helpgroup_id
 * @property integer $image_id
 * @property string $detail_text
 * @property integer $sort
 * @property integer $status
 *
 * @method Help active
 * @method Help cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Help indexed($column = 'id')
 * @method Help language($lang = null)
 * @method Help select($columns = '*')
 * @method Help limit($limit, $offset = 0)
 * @method Help sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 * @property Helpgroup $helpgroup
 */
class Help extends BaseActiveRecord
{

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id',
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Help the static model class
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
        return '{{help}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, helpgroup_id', 'required'),
            array('helpgroup_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('image_id, detail_text', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('helpgroup_id', 'exist', 'className' => 'Helpgroup', 'attributeName' => 'id'),
        
            array('id, title, helpgroup_id, image_id, detail_text, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'helpgroup' => array(self::BELONGS_TO, 'Helpgroup', 'helpgroup_id'),
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
            'helpgroup_id' => Yii::t('backend', 'Helpgroup'),
            'image_id' => Yii::t('backend', 'Image'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
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
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.helpgroup_id',$this->helpgroup_id);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('helpgroup');

        return parent::searchInit($criteria);
    }
}