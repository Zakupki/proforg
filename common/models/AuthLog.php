<?php

/**
 * This is the model class for table "{{auth_item}}".
 *
 * The followings are the available columns in table '{{auth_item}}':
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 *
 * The followings are the available model relations:
 * @property User[] $rvUsers
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren1
 * @property Option[] $options
 * @property Rights $rights
 */
class AuthLog extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuthItem the static model class
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
		return '{{auth_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email,user_id', 'required'),
			array('success', 'numerical', 'integerOnly'=>true),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('email', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('email', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			/*'rvUsers' => array(self::MANY_MANY, 'User', '{{auth_assignment}}(itemname, userid)'),
			'authItemChildren' => array(self::HAS_MANY, 'AuthItemChild', 'child'),
			'authItemChildren1' => array(self::HAS_MANY, 'AuthItemChild', 'parent'),
			'options' => array(self::HAS_MANY, 'Option', 'role'),
			'rights' => array(self::HAS_ONE, 'Rights', 'itemname'),*/
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'user_id' => Yii::t('backend', 'User'),
            'email' => Yii::t('backend', 'Email'),
            'time' => Yii::t('backend', 'Time'),
            'success' => Yii::t('backend', 'Success'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('email',$this->email,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}