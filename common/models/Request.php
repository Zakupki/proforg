<?php
/**
 * This is the model class for table "{{request}}".
 *
 * The followings are the available columns in table '{{request}}':
 * @property integer $id
 * @property integer $company_id
 * @property integer $finance_id
 * @property integer $user_id
 * @property string $date_create
 * @property integer $status
 * @property integer $sort
 * @property double $value
 * @property double $available
 * @property double $left
 *
 * @method Request active
 * @method Request cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Request indexed($column = 'id')
 * @method Request language($lang = null)
 * @method Request select($columns = '*')
 * @method Request limit($limit, $offset = 0)
 * @method Request sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Finance $finance
 * @property User $user
 * @property Company $company
 */
class Request extends BaseActiveRecord
{
    public $title;
    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Request the static model class
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
        return '{{request}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('company_id, finance_id, user_id, date_create, value', 'required'),
            array('company_id, finance_id, user_id, card_id, status, sort, confirm, requesttype_id', 'numerical', 'integerOnly' => true),
            array('value, available, left, commission', 'numerical'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('finance_id', 'exist', 'className' => 'Finance', 'attributeName' => 'id'),
            array('requesttype_id', 'exist', 'className' => 'Requesttype', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('card_id', 'exist', 'className' => 'Card', 'attributeName' => 'id'),
            array('available, left, commission', 'safe'),
            array('id, company_id, finance_id, user_id, date_create, status, sort, value, available, left, commission', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'finance' => array(self::BELONGS_TO, 'Finance', 'finance_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'card' => array(self::BELONGS_TO, 'Card', 'card_id'),
            'requesttype' => array(self::BELONGS_TO, 'Requesttype', 'requesttype_id'),
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
            'finance_id' => Yii::t('backend', 'Finance'),
            'user_id' => Yii::t('backend', 'User'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'value' => Yii::t('backend', 'Value'),
            'available' => Yii::t('backend', 'Available'),
            'left' => Yii::t('backend', 'Left'),
            'confirm' => Yii::t('backend', 'Confirm'),
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
		$criteria->compare('t.finance_id',$this->finance_id);
        $criteria->compare('t.confirm',$this->confirm);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.value',$this->value);
		$criteria->compare('t.available',$this->available);
		$criteria->compare('t.left',$this->left);

		$criteria->with = array('finance', 'user', 'company');

        return parent::searchInit($criteria);
    }
    public function getPrevRequest($params=array()){

        $whereSql='';
        if(isset($params['id']))
            $whereSql=' AND z_request.id<'.$params['id'];

        $connection = Yii::app()->db;
        $sql = 'SELECT
                         z_request.id,
                         0-z_request.value AS value,
                         DATEDIFF(NOW(), z_request.`date_create`) AS datedif,
                          CASE
                            WHEN DATEDIFF(NOW(), z_request.`date_create`)>3
                            THEN 0.3
                            WHEN DATEDIFF(NOW(), z_request.`date_create`)>2
                            THEN 0.15
                            ELSE NULL
                          END AS percent
                        FROM
                          z_request
                        WHERE z_request.`company_id` = :company_id
                          AND z_request.`requesttype_id`=2
                          '.$whereSql.'
                        ORDER BY z_request.id DESC
                        LIMIT 0,1
                          ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        $result = $command->queryRow();
        return $result;
    }
}