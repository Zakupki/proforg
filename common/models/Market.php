<?php
/**
 * This is the model class for table "{{market}}".
 *
 * The followings are the available columns in table '{{market}}':
 * @property integer $id
 * @property string $title
 * @property integer $markettype_id
 * @property string $code
 * @property string $description
 * @property integer $sort
 * @property integer $status
 *
 * @method Market active
 * @method Market cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Market indexed($column = 'id')
 * @method Market language($lang = null)
 * @method Market select($columns = '*')
 * @method Market limit($limit, $offset = 0)
 * @method Market sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Markettype $markettype
 */
class Market extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Market the static model class
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
        return '{{market}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, markettype_id', 'required'),
            array('markettype_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title, code', 'length', 'max' => 100),
            array('description', 'safe'),
            array('markettype_id', 'exist', 'className' => 'Markettype', 'attributeName' => 'id'),
        
            array('id, title, markettype_id, code, description, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'markettype' => array(self::BELONGS_TO, 'Markettype', 'markettype_id'),
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
            'markettype_id' => Yii::t('backend', 'Markettype'),
            'code' => Yii::t('backend', 'Code'),
            'description' => Yii::t('backend', 'Description'),
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
		$criteria->compare('t.markettype_id',$this->markettype_id);
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('markettype');

        return parent::searchInit($criteria);
    }
    public function getAutocomplete(){
        header('Content-Type: application/json; charset=utf-8');
        $sql='SELECT id,title as value,title as label from z_market';
        $data=app()->db->createCommand($sql)->queryAll();
        echo json_encode($data);
        Yii::app()->end();
    }
    public function getMarkettype($markettype_id){
        header('Content-Type: application/json; charset=utf-8');
        $sql='SELECT id,title as value,title as label from z_market WHERE z_market.markettype_id=:markettype_id';
        $command=app()->db->createCommand($sql);
        $command->bindParam(":markettype_id",$markettype_id,PDO::PARAM_INT);
        $data=$command->queryAll();
        echo json_encode($data);
        Yii::app()->end();
    }
}