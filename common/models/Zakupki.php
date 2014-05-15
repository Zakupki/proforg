<?php

/**
 * This is the model class for table "{{zakupki}}".
 *
 * The followings are the available columns in table '{{zakupki}}':
 * @property integer $id
 * @property string $title
 * @property integer $market_id
 * @property integer $company_id
 * @property integer $user_id
 * @property integer $lastuser_id
 * @property integer $closer_id
 * @property string $slug
 * @property string $attachment
 * @property string $intro
 * @property string $body
 * @property integer $created_on
 * @property integer $updated_on
 * @property integer $closed_on
 * @property integer $comments_enabled
 * @property string $status_old
 * @property string $date
 * @property string $city
 * @property integer $dney_otsrochki
 * @property integer $delivery_date
 * @property integer $active_until
 * @property string $befor_price
 * @property string $last_price
 * @property string $percent_price_changes
 * @property string $comment
 * @property integer $sort
 * @property integer $status
 *
 * @method Zakupki active
 * @method Zakupki cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Zakupki indexed($column = 'id')
 * @method Zakupki language($lang = null)
 * @method Zakupki select($columns = '*')
 * @method Zakupki limit($limit, $offset = 0)
 * @method Zakupki sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Market $market
 * @property User $closer
 * @property Company $company
 * @property User $lastuser
 */
class Zakupki extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Zakupki the static model class
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
        return '{{zakupki}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('company_id', 'required'),
            array('market_id, company_id, user_id, lastuser_id, closer_id, created_on, updated_on, closed_on, comments_enabled, dney_otsrochki, delivery_date, active_until, sort, status', 'numerical', 'integerOnly' => true),
            array('title, slug, city', 'length', 'max' => 100),
            array('attachment', 'length', 'max' => 255),
            array('status_old', 'length', 'max' => 7),
            array('befor_price, last_price, percent_price_changes', 'length', 'max' => 15),
            array('intro, body, date, comment', 'safe'),
            array('market_id', 'exist', 'className' => 'Market', 'attributeName' => 'id'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('lastuser_id', 'exist', 'className' => 'Lastuser', 'attributeName' => 'id'),
            array('closer_id', 'exist', 'className' => 'Closer', 'attributeName' => 'id'),
        
            array('id, title, market_id, company_id, user_id, lastuser_id, closer_id, slug, attachment, intro, body, created_on, updated_on, closed_on, comments_enabled, status_old, date, city, dney_otsrochki, delivery_date, active_until, befor_price, last_price, percent_price_changes, comment, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'market' => array(self::BELONGS_TO, 'Market', 'market_id'),
            'closer' => array(self::BELONGS_TO, 'User', 'closer_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'lastuser' => array(self::BELONGS_TO, 'User', 'lastuser_id'),
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
            'market_id' => Yii::t('backend', 'Market'),
            'company_id' => Yii::t('backend', 'Company'),
            'user_id' => Yii::t('backend', 'User'),
            'lastuser_id' => Yii::t('backend', 'Lastuser'),
            'closer_id' => Yii::t('backend', 'Closer'),
            'slug' => Yii::t('backend', 'Slug'),
            'attachment' => Yii::t('backend', 'Attachment'),
            'intro' => Yii::t('backend', 'Intro'),
            'body' => Yii::t('backend', 'Body'),
            'created_on' => Yii::t('backend', 'Created On'),
            'updated_on' => Yii::t('backend', 'Updated On'),
            'closed_on' => Yii::t('backend', 'Closed On'),
            'comments_enabled' => Yii::t('backend', 'Comments Enabled'),
            'status_old' => Yii::t('backend', 'Status Old'),
            'date' => Yii::t('backend', 'Date'),
            'city' => Yii::t('backend', 'City'),
            'dney_otsrochki' => Yii::t('backend', 'Dney Otsrochki'),
            'delivery_date' => Yii::t('backend', 'Delivery Date'),
            'active_until' => Yii::t('backend', 'Active Until'),
            'befor_price' => Yii::t('backend', 'Befor Price'),
            'last_price' => Yii::t('backend', 'Last Price'),
            'percent_price_changes' => Yii::t('backend', 'Percent Price Changes'),
            'comment' => Yii::t('backend', 'Comment'),
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
		$criteria->compare('t.market_id',$this->market_id);
		$criteria->compare('t.company_id',$this->company_id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.lastuser_id',$this->lastuser_id);
		$criteria->compare('t.closer_id',$this->closer_id);
		$criteria->compare('t.slug',$this->slug,true);
		$criteria->compare('t.attachment',$this->attachment,true);
		$criteria->compare('t.intro',$this->intro,true);
		$criteria->compare('t.body',$this->body,true);
		$criteria->compare('t.created_on',$this->created_on);
		$criteria->compare('t.updated_on',$this->updated_on);
		$criteria->compare('t.closed_on',$this->closed_on);
		$criteria->compare('t.comments_enabled',$this->comments_enabled);
		$criteria->compare('t.status_old',$this->status_old,true);
		$criteria->compare('t.date',$this->date,true);
		$criteria->compare('t.city',$this->city,true);
		$criteria->compare('t.dney_otsrochki',$this->dney_otsrochki);
		$criteria->compare('t.delivery_date',$this->delivery_date);
		$criteria->compare('t.active_until',$this->active_until);
		$criteria->compare('t.befor_price',$this->befor_price,true);
		$criteria->compare('t.last_price',$this->last_price,true);
		$criteria->compare('t.percent_price_changes',$this->percent_price_changes,true);
		$criteria->compare('t.comment',$this->comment,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('user', 'market', 'closer', 'company', 'lastuser');

        return parent::searchInit($criteria);
    }
}