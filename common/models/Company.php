<?php
/**
 * This is the model class for table "{{company}}".
 *
 * The followings are the available columns in table '{{company}}':
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 * @property string $date_create
 * @property integer $finance_id
 *
 * @method Company active
 * @method Company cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Company indexed($column = 'id')
 * @method Company language($lang = null)
 * @method Company select($columns = '*')
 * @method Company limit($limit, $offset = 0)
 * @method Company sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Finance $finance
 * @property User[] $users
 */
class Company extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Company the static model class
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
        return '{{company}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, date_create, finance_id', 'required'),
            array('status, sort, finance_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('finance_id', 'exist', 'className' => 'Finance', 'attributeName' => 'id'),
        
            array('id, title, status, sort, date_create, finance_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'finance' => array(self::BELONGS_TO, 'Finance', 'finance_id'),
            'users' => array(self::HAS_MANY, 'User', 'company_id'),
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
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'finance_id' => Yii::t('backend', 'Finance'),
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
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.finance_id',$this->finance_id);

		$criteria->with = array('finance');

        return parent::searchInit($criteria);
    }
    public function getBalance($company_id)
    {
        $connection = Yii::app()->db;
        $sql = 'SELECT
                  SUM(z_request.value) AS balance,
                  z_request.*
                FROM
                  z_request
                WHERE z_request.`company_id` = :company_id
                  AND z_request.`requesttype_id` IN (2, 3)';
        $command = $connection->createCommand($sql);
        $command->bindParam(":company_id", $company_id, PDO::PARAM_INT);
        $result = $command->queryRow();
        return $result;
    }

    public function getComBal($company_id){
        $connection = Yii::app()->db;
        $sql = 'SELECT
                          SUM(z_request.value) AS balance,
                          SUM(z_request.value) AS balance,
                          z_request.`company_id`,
                          z_request.`finance_id`/*,
                          z_request.**/
                        FROM
                          z_request
                        WHERE z_request.`company_id` = :company_id
                          AND z_request.`requesttype_id` IN (2,3,4)';
        $command = $connection->createCommand($sql);
        $command->bindParam(":company_id", $company_id, PDO::PARAM_INT);
        $result = $command->queryRow();
        return $result;
    }
}