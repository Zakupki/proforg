<?php
/**
 * This is the model class for table "{{country}}".
 *
 * The followings are the available columns in table '{{country}}':
 * @property integer $id
 * @property string $title
 * @property integer $sort
 * @property integer $status
 *
 * @method Country active
 * @method Country cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Country indexed($column = 'id')
 * @method Country language($lang = null)
 * @method Country select($columns = '*')
 * @method Country limit($limit, $offset = 0)
 * @method Country sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Region[] $regions
 */
class Service extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Country the static model class
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
        return '{{paysystem}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('id, title, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        /*return array(
            'regions' => array(self::HAS_MANY, 'Region', 'country_id'),
        );*/
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => Yii::t('backend', 'Title'),
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
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
    public function getAdditionalServices(){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          `z_paysystem`.id,
          `z_paysystem`.title
        FROM
          `z_paysystem`
        WHERE `z_paysystem`.`addService`=1 AND z_paysystem.`status`=1
        ';
        $command = $connection->createCommand($sql);
        /* $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
         $command->bindParam(":companyrole_id", $params['companyrole_id'], PDO::PARAM_INT);*/
        $result = $command->queryAll();
        return $result;
    }
}