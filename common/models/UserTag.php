<?php
/**
 * This is the model class for table "{{user_tag}}".
 *
 * The followings are the available columns in table '{{user_tag}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $tag_id
 *
 * @method UserTag active
 * @method UserTag cache($duration = null, $dependency = null, $queryCount = 1)
 * @method UserTag indexed($column = 'id')
 * @method UserTag language($lang = null)
 * @method UserTag select($columns = '*')
 * @method UserTag limit($limit, $offset = 0)
 * @method UserTag sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Tag $tag
 */
class UserTag extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return UserTag the static model class
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
        return '{{user_tag}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('user_id, tag_id', 'required'),
            array('user_id, tag_id', 'numerical', 'integerOnly' => true),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('tag_id', 'exist', 'className' => 'Tag', 'attributeName' => 'id'),
        
            array('id, user_id, tag_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'tag' => array(self::BELONGS_TO, 'Tag', 'tag_id'),
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
            'tag_id' => Yii::t('backend', 'Tag'),
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
		$criteria->compare('t.tag_id',$this->tag_id);

		$criteria->with = array('user', 'tag');

        return parent::searchInit($criteria);
    }
}