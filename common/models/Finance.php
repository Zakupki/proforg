<?php
/**
 * This is the model class for table "{{finance}}".
 *
 * The followings are the available columns in table '{{finance}}':
 * @property integer $id
 * @property integer $fincompany_id
 * @property integer $company_id
 * @property double $percent
 * @property string $date_create
 * @property integer $sort
 * @property integer $status
 *
 * @method Finance active
 * @method Finance cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Finance indexed($column = 'id')
 * @method Finance language($lang = null)
 * @method Finance select($columns = '*')
 * @method Finance limit($limit, $offset = 0)
 * @method Finance sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Company $company
 * @property Company $fincompany
 */
class Finance extends BaseActiveRecord
{
    public $title;

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Finance the static model class
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
        return '{{finance}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('fincompany_id, company_id, percent', 'required'),
            array('fincompany_id, company_id, sort, status', 'numerical', 'integerOnly' => true),
            array('percent', 'numerical'),
            array('fincompany_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
        
            array('id, fincompany_id, company_id, percent, date_create, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'fincompany' => array(self::BELONGS_TO, 'Company', 'fincompany_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'fincompany_id' => Yii::t('backend', 'Fincompany'),
            'company_id' => Yii::t('backend', 'Company'),
            'percent' => Yii::t('backend', 'Percent'),
            'date_create' => Yii::t('backend', 'Date Crete'),
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
		$criteria->compare('t.fincompany_id',$this->fincompany_id);
		$criteria->compare('t.company_id',$this->company_id);
		$criteria->compare('t.percent',$this->percent);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('company', 'fincompany');

        return parent::searchInit($criteria);
    }
    public function getCredit($params){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_finance.`percent`
        FROM
          z_finance
        WHERE z_finance.`status`=1 AND z_finance.`company_id`=:company_id
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        $result = $command->queryRow();
        return $result;
    }
}