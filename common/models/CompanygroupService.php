<?php
/**
 * This is the model class for table "{{companygroup_service}}".
 *
 * The followings are the available columns in table '{{companygroup_service}}':
 * @property integer $id
 * @property integer $companygroup_id
 * @property integer $service_id
 * @property integer $status
 *
 * @method CompanygroupService active
 * @method CompanygroupService cache($duration = null, $dependency = null, $queryCount = 1)
 * @method CompanygroupService indexed($column = 'id')
 * @method CompanygroupService language($lang = null)
 * @method CompanygroupService select($columns = '*')
 * @method CompanygroupService limit($limit, $offset = 0)
 * @method CompanygroupService sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Paysystem $service
 * @property Companygroup $companygroup
 */
class CompanygroupService extends BaseActiveRecord
{
    public $title;
    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return CompanygroupService the static model class
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
        return '{{companygroup_service}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('companygroup_id, service_id', 'required'),
            array('companygroup_id, service_id, status', 'numerical', 'integerOnly' => true),
            array('companygroup_id', 'exist', 'className' => 'Companygroup', 'attributeName' => 'id'),
            array('service_id', 'exist', 'className' => 'Paysystem', 'attributeName' => 'id'),
        
            array('id, companygroup_id, service_id, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'service' => array(self::BELONGS_TO, 'Paysystem', 'service_id'),
            'companygroup' => array(self::BELONGS_TO, 'Companygroup', 'companygroup_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'companygroup_id' => Yii::t('backend', 'Companygroup'),
            'service_id' => Yii::t('backend', 'Service'),
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
		$criteria->compare('t.companygroup_id',$this->companygroup_id);
		$criteria->compare('t.service_id',$this->service_id);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('service', 'companygroup');

        return parent::searchInit($criteria);
    }
    public function CheckAccess(){
        $connection = Yii::app()->db;
        $sql = '
            SELECT
              z_companygroup_service.`service_id`
            FROM
              z_company_user
              INNER JOIN z_company
                ON z_company.id = z_company_user.`company_id`
              INNER JOIN z_companygroup_service
                ON z_companygroup_service.`companygroup_id` = z_company.`companygroup_id`
            WHERE z_company_user.`user_id` = :user_id
            GROUP BY z_companygroup_service.`service_id`
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $data = $command->queryColumn();
        return $data;
    }
}