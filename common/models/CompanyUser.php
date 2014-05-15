<?php
/**
 * This is the model class for table "{{company_user}}".
 *
 * The followings are the available columns in table '{{company_user}}':
 * @property integer $id
 * @property string $title
 * @property integer $company_id
 * @property integer $user_id
 * @property integer $sort
 * @property integer $status
 *
 * @method CompanyUser active
 * @method CompanyUser cache($duration = null, $dependency = null, $queryCount = 1)
 * @method CompanyUser indexed($column = 'id')
 * @method CompanyUser language($lang = null)
 * @method CompanyUser select($columns = '*')
 * @method CompanyUser limit($limit, $offset = 0)
 * @method CompanyUser sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Company $company
 */
class CompanyUser extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return CompanyUser the static model class
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
        return '{{company_user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('company_id, companyrole_id, user_id', 'required'),
            array('company_id, user_id, sort, status, major', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        
            array('id, title, companyrole_id, company_id, user_id, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id', 'with' => 'city'),
            'companyrole' => array(self::BELONGS_TO, 'Companyrole', 'companyrole_id'),
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
            'company_id' => Yii::t('backend', 'Company'),
            'companyrole_id' => Yii::t('backend', 'Companyrole'),
            'user_id' => Yii::t('backend', 'User'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'major' => Yii::t('backend', 'Major'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.company_id',$this->company_id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('user', 'company');

        return parent::searchInit($criteria);
    }
    public function updateForUser($id, $newData = array())
     {
        $buff = array();
        // rid of possibly duplicated size companyrole_ids, use last one

        foreach($newData as $item)
                    if((int)$item['companyrole_id']>0)
                    $buff[(int)$item['user_id']] = array('companyrole_id'=>$item['companyrole_id'],'status'=>$item['status']);

        $newData = $buff;

        if(empty($newData))
            return self::model()->deleteAllByAttributes(array('company_id' => $id));



        $o = 0;
        $delete = array();

        // update existing product info with new quantities, prices
        /** @var $curData ProductInfo[] */
        $curData = self::model()->findAllByAttributes(array('company_id' => $id));
        foreach($curData as $item)
        {
            if(!isset($newData[$item['user_id']]))
            {
                $delete[] = $item['user_id'];
                continue;
            }

            /*
            if((int)$newData[$item['size']]['companyrole_id'] === (int)$item->companyrole_id && (int)$newData[$item['size']]['price'] === (int)$item->price)
                        {
                            unset($newData[$item['size']]);
                            continue;
                        }*/

            if((int)$newData[$item['user_id']]>0){
                $item->companyrole_id = (int)$newData[$item['user_id']]['companyrole_id'];
                $item->status = (int)$newData[$item['user_id']]['status'];
                $item->update(array('companyrole_id','status' ));
                unset($newData[$item['user_id']]);
                ++$o;
            }
        }

        // delete info
        self::model()->deleteAllByAttributes(array('company_id' => $id, 'user_id' => $delete));

        // add new info
        $model = new self();
        foreach($newData as $user_id => $userdata)
        {
            $model->company_id = $id;
            $model->user_id = $user_id;
            $model->companyrole_id = $userdata['companyrole_id'];
            $model->status = $userdata['status'];
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