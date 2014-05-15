<?php
/**
 * This is the model class for table "{{user_market}}".
 *
 * The followings are the available columns in table '{{user_market}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $market_id
 *
 * @method UserMarket active
 * @method UserMarket cache($duration = null, $dependency = null, $queryCount = 1)
 * @method UserMarket indexed($column = 'id')
 * @method UserMarket language($lang = null)
 * @method UserMarket select($columns = '*')
 * @method UserMarket limit($limit, $offset = 0)
 * @method UserMarket sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Market $market
 * @property User $user
 */
class UserMarket extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserMarket the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user_market}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('user_id, market_id', 'required'),
            array('user_id, market_id', 'numerical', 'integerOnly' => true),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('market_id', 'exist', 'className' => 'Market', 'attributeName' => 'id'),

            array('id, user_id, market_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'market' => array(self::BELONGS_TO, 'Market', 'market_id'),
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
            'user_id' => Yii::t('backend', 'User'),
            'market_id' => Yii::t('backend', 'Market'),
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
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.market_id', $this->market_id);

        $criteria->with = array('market', 'user');

        return parent::searchInit($criteria);
    }

    public function getAllMyMarkets()
    {
        $connection = Yii::app()->db;
        $sql = '
        SELECT
         z_market.id,
         z_market.title,
         z_market.markettype_id,
         z_markettype.title AS type,
         if(z_user_market.id,1,null) AS checked
        FROM z_market
        INNER JOIN z_markettype
          ON z_markettype.id=z_market.markettype_id
        LEFT JOIN z_user_market
        ON z_user_market.market_id=z_market.id AND z_user_market.user_id=:user_id
        ORDER BY z_markettype.sort ASC,z_markettype.title ASC,z_market.title ASC
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $markets = $command->queryAll();

        $marketdata = array();
        foreach ($markets as $m) {
            $marketdata[$m['markettype_id']]['title'] = $m['type'];
            if ($m['checked'])
                $marketdata[$m['markettype_id']]['checked'] = 1;
            $marketdata[$m['markettype_id']]['data'][$m['id']] = array('title' => $m['title'], 'checked' => $m['checked']);
        }
        return $marketdata;

    }


}