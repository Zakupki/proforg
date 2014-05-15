<?php
/**
 * This is the model class for table "{{tag}}".
 *
 * The followings are the available columns in table '{{tag}}':
 * @property string $id
 * @property string $title
 * @property integer $sort
 * @property integer $status
 *
 * @method Tag active
 * @method Tag cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Tag indexed($column = 'id')
 * @method Tag language($lang = null)
 * @method Tag select($columns = '*')
 * @method Tag limit($limit, $offset = 0)
 * @method Tag sort($columns = '')
 */
class Tag extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Tag the static model class
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
        return '{{tag}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('sort, status, taggroup_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('id, title, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'taggroup' => array(self::BELONGS_TO, 'Taggroup', 'taggroup_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('backend', 'ID'),
            'title' => Yii::t('backend', 'Title'),
            'sort' => Yii::t('backend', 'Sort'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'taggroup_id' => Yii::t('backend', 'Taggroup'),
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

        $criteria->compare('t.id',$this->id,true);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.sort',$this->sort);
        $criteria->compare('t.date_create',$this->date_create);
		$criteria->compare('t.status',$this->status);
        $criteria->compare('t.taggroup_id',$this->taggroup_id);

        return parent::searchInit($criteria);
    }
    public function getAutotag($title){
        
        
        $title="%$title%";
        $connection=Yii::app()->db;        
        $sql='SELECT id, title as value, title as label from {{tag}} WHERE title LIKE :title GROUP BY title';
        $command=$connection->createCommand($sql);
        $command->bindParam(":title",$title,PDO::PARAM_STR);
        return $command->queryAll();
    }

    public function getTag($title)
    {
        if (strlen(trim(strtolower($title))) > 0) {
            $tagdata = Tag::model()->findByAttributes(array('title' => trim(strtolower($title))));
            if (!isset($tagdata->id)) {
                $tagdata = new Tag;
                $tagdata->title = trim(strtolower($title));
                $tagdata->date_create = date('Y-m-d H:i:s');
                $tagdata->user_id = yii::app()->user->getId();
                $tagdata->save();
            }
            if (isset($tagdata->id))
                return $tagdata->id;
        }
    }
}