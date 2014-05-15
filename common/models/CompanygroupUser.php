<?php
/**
 * This is the model class for table "{{companygroup_user}}".
 *
 * The followings are the available columns in table '{{companygroup_user}}':
 * @property integer $id
 * @property string $title
 * @property integer $companygroup_id
 * @property integer $user_id
 * @property integer $sort
 * @property integer $status
 *
 * @method CompanygroupUser active
 * @method CompanygroupUser cache($duration = null, $dependency = null, $queryCount = 1)
 * @method CompanygroupUser indexed($column = 'id')
 * @method CompanygroupUser language($lang = null)
 * @method CompanygroupUser select($columns = '*')
 * @method CompanygroupUser limit($limit, $offset = 0)
 * @method CompanygroupUser sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Companygroup $companygroup
 * @property User $user
 */
class CompanygroupUser extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return CompanygroupUser the static model class
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
        return '{{companygroup_user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('companygroup_id, user_id', 'required'),
            array('companygroup_id, user_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('companygroup_id', 'exist', 'className' => 'Companygroup', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        
            array('id, title, companygroup_id, user_id, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'companygroup' => array(self::BELONGS_TO, 'Companygroup', 'companygroup_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
            'companygroup_id' => Yii::t('backend', 'Companygroup'),
            'user_id' => Yii::t('backend', 'User'),
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
		$criteria->compare('t.companygroup_id',$this->companygroup_id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('companygroup', 'user');

        return parent::searchInit($criteria);
    }
    public function updateForUser($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size values, use last one
       
        foreach($newData as $item)
                    if((int)$item['user_id']>0)
                    $buff[(int)$item['user_id']] = $item['user_id'];
        $newData = $buff;
        
        if(empty($newData))
            return self::model()->deleteAllByAttributes(array('companygroup_id' => $id));
        
        

        $o = 0;
        $delete = array();

        // update existing product info with new quantities, prices
        /** @var $curData ProductInfo[] */
        $curData = self::model()->findAllByAttributes(array('companygroup_id' => $id));
        foreach($curData as $item)
        {
            if(!isset($newData[$item['user_id']]))
            {
                $delete[] = $item['user_id'];
                continue;
            }

            /*
            if((int)$newData[$item['size']]['quantity'] === (int)$item->quantity && (int)$newData[$item['size']]['price'] === (int)$item->price)
                        {
                            unset($newData[$item['size']]);
                            continue;
                        }*/
            
            if((int)$newData[$item['user_id']]>0){
                //$item->value = (int)$newData[$item['user_id']];
                //$item->update(array('value', ));
                unset($newData[$item['user_id']]);
                ++$o;
            }
        }

        // delete info
        self::model()->deleteAllByAttributes(array('companygroup_id' => $id, 'user_id' => $delete));

        // add new info
        $model = new self();
        foreach($newData as $user_id => $value)
        {
            $model->companygroup_id = $id;
            $model->user_id = $user_id;
            if($model->save(false))
            {
                ++$o;
                $model->id = null;
                $model->setIsNewRecord(true);
            }
        }

        return $o;
    }
}