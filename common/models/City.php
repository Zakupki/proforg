<?php
/**
 * This is the model class for table "{{city}}".
 *
 * The followings are the available columns in table '{{city}}':
 * @property integer $id
 * @property integer $region_id
 * @property string $title
 * @property integer $sort
 * @property integer $status
 *
 * @method City active
 * @method City cache($duration = null, $dependency = null, $queryCount = 1)
 * @method City indexed($column = 'id')
 * @method City language($lang = null)
 * @method City select($columns = '*')
 * @method City limit($limit, $offset = 0)
 * @method City sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Region $region
 */
class City extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return City the static model class
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
        return '{{city}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('region_id, title', 'required'),
            array('region_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('region_id', 'exist', 'className' => 'Region', 'attributeName' => 'id'),
        
            array('id, region_id, title, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'region' => array(self::BELONGS_TO, 'Region', 'region_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'region_id' => Yii::t('backend', 'Region'),
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
		$criteria->compare('t.region_id',$this->region_id);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('region');

        return parent::searchInit($criteria);
    }
    public function getRegionCities($id,$title){
        
        
        $title="%$title%";
        $connection=Yii::app()->db;        
        $sql='SELECT id, title as value, title as label from {{city}} WHERE region_id=:region_id AND title LIKE :title';
        $command=$connection->createCommand($sql);
        $command->bindParam(":region_id",$id,PDO::PARAM_INT);
        $command->bindParam(":title",$title,PDO::PARAM_STR);
        return $command->queryAll();
        
        /*
         return self::model()->findAll(array(
                                'condition'=>'region_id=:region_id AND title LIKE :title',
                                'params'=>array(':region_id'=>$id,':title'=>"%$title%"),
                                array()
                 ));*/
        
       
         /*
         return self::model()->findAll(array(
                                 'select'=>'id, t.title as valu',
                                 'condition'=>'region_id=:region_id AND title LIKE :title',
                                 'params'=>array(':region_id'=>$id,':title'=>"%$title%")
                  ));*/
         
    }
}