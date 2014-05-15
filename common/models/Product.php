<?php
/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property integer $id
 * @property string $title
 * @property integer $purchase_id
 * @property integer $tag_id
 * @property integer $unit_id
 * @property string $amount
 * @property string $ed_izm
 * @property string $kol
 * @property string $pickup
 * @property string $comment
 * @property integer $sort
 * @property integer $status
 *
 * @method Product active
 * @method Product cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Product indexed($column = 'id')
 * @method Product language($lang = null)
 * @method Product select($columns = '*')
 * @method Product limit($limit, $offset = 0)
 * @method Product sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Purchase $purchase
 */
class Product extends BaseActiveRecord
{
    public $tagg_id;
    public $taggroup_id;
    public $tagtitle;
    public $taggrouptitle;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Product the static model class
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
        return '{{product}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('purchase_id, tag_id, unit_id, amount', 'required'),
            array('purchase_id, tag_id, unit_id, taggroup_id, tagg_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('amount', 'length', 'max' => 16),
            array('ed_izm', 'length', 'max' => 20),
            array('kol', 'length', 'max' => 15),
            array('pickup', 'length', 'max' => 5),
            array('comment', 'length', 'max' => 256),
            array('purchase_id', 'exist', 'className' => 'Purchase', 'attributeName' => 'id'),
            array('tag_id', 'exist', 'className' => 'Tag', 'attributeName' => 'id'),
            array('unit_id', 'exist', 'className' => 'Unit', 'attributeName' => 'id'),

            array('id, title, purchase_id, tag_id, unit_id, amount, ed_izm, kol, pickup, comment, sort, status, tagg_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'purchase' => array(self::BELONGS_TO, 'Purchase', 'purchase_id'),
            'tag' => array(self::BELONGS_TO, 'Tag', 'tag_id'),
            'unit' => array(self::BELONGS_TO, 'Unit', 'unit_id'),
            'offers' => array(self::HAS_MANY, 'Offer', 'product_id'),
            'taggroup'=>array(self::BELONGS_TO,'Taggroup',array('tag_id'=>'taggroup_id'),'through'=>'tag'),
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
            'purchase_id' => Yii::t('backend', 'Purchase'),
            'tag_id' => Yii::t('backend', 'Tag'),
            'tagg_id' => Yii::t('backend', 'Tag'),
            'taggroup_id' => Yii::t('backend', 'Taggroup'),
            'unit_id' => Yii::t('backend', 'Unit'),
            'amount' => Yii::t('backend', 'Amount'),
            'ed_izm' => Yii::t('backend', 'Ed Izm'),
            'kol' => Yii::t('backend', 'Kol'),
            'pickup' => Yii::t('backend', 'Pickup'),
            'comment' => Yii::t('backend', 'Comment'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'date_create' => Yii::t('backend', 'Date Create'),
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
        $criteria->compare('tag.title', $this->title, true);
        $criteria->compare('tag.taggroup_id', $this->taggroup_id);
        $criteria->compare('t.purchase_id', $this->purchase_id);
        $criteria->compare('t.tag_id', $this->tag_id);
        $criteria->compare('t.tag_id', $this->tagg_id);
        $criteria->compare('t.unit_id', $this->unit_id);
        $criteria->compare('t.amount', $this->amount, true);
        $criteria->compare('t.ed_izm', $this->ed_izm, true);
        $criteria->compare('t.kol', $this->kol, true);
        $criteria->compare('t.pickup', $this->pickup, true);
        $criteria->compare('t.comment', $this->comment, true);
        $criteria->compare('t.sort', $this->sort);
        $criteria->compare('t.status', $this->status);

        $criteria->select = 't.id,
        t.tag_id,
        t.purchase_id,
        t.date_create,
        tag.title AS tagtitle,
        taggroup.title AS taggrouptitle';

        $criteria->join = 'LEFT JOIN z_tag tag ON tag.id = t.tag_id ';
        $criteria->join .= 'LEFT JOIN z_taggroup taggroup ON taggroup.id = tag.taggroup_id';

        //$criteria->with = array('purchase','tag','taggroup');

        return parent::searchInit($criteria);
    }

    public function getProductRequests($params)
    {
        $connection = Yii::app()->db;
        $where = '';
        $select = '';
        $take = Yii::app()->params['take'] + 1;
        $start = 0;
        $result['last'] = false;
        if (isset($params['market_id']))
            $where .= ' AND z_market.id=' . $params['market_id'];
        if (isset($params['company_id']))
            $where .= ' AND z_purchase.company_id=' . $params['company_id'];
        if (isset($params['search']))
            $where .= ' AND z_tag.title LIKE "%'.$params['search'].'%"';
        if (isset($params['purchase_id'])){
            $where .= ' AND z_purchase.id>' . intval($params['purchase_id']).'';
            //$select.=' if(z_purchase.id>'.$params['purchase_id'].',1,0) AS new_purchase, ';
            $select.='1 AS new_purchase, ';
        }
        else{
            $requests_purchase_id=Yii::app()->session['requests_purchase_id'];
            $select.='if(z_purchase.id>'.intval($requests_purchase_id).' AND '.intval($requests_purchase_id).'>0,1,null) AS new_purchase, ';
        }
        if (isset($params['start']))
            $start = $params['start'];

        $sql = '
        SELECT company_id FROM z_company_user WHERE z_company_user.status=1 AND z_company_user.user_id=:user_id
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $usercompanies = $command->queryColumn();
        if(!$usercompanies)
            return;

        $sql = '
        SELECT
          z_purchase.id,
          z_purchase.date_create,
          '.$select.'
          unix_timestamp(z_purchase.date_create) unix_time,
          z_purchase.date_close,
          z_purchase.date_deliver,
          z_purchase.dirrect,
          z_purchase.address,
          z_product.id AS product_id,
          z_product.amount,
          z_product.minprice,
          z_product.deliverminprice,
          z_purchase.delay,
          z_purchase.comment,
          z_tag.title,
          z_company.title AS company,
          z_city.title AS city,
          z_market.title AS market,
          z_unit.title AS unit,
          z_unit.title2 AS unit2,
          z_unit.title3 AS unit3,
          z_user.first_name,
          z_user.name,
          z_user.last_name
        FROM z_product
        INNER JOIN z_purchase
        	ON z_purchase.id=z_product.purchase_id
        INNER JOIN z_tag
        	ON z_tag.id=z_product.tag_id
        INNER JOIN z_market
        	ON z_market.id=z_purchase.market_id
        INNER JOIN z_company
        	ON z_company.id=z_purchase.company_id
       	INNER JOIN z_city
        	ON z_city.id=z_company.city_id
        INNER JOIN z_unit
        	ON z_unit.id=z_product.unit_id
        INNER JOIN z_user
          ON z_user.id=z_purchase.user_id
        LEFT JOIN z_company_invite
          ON z_company_invite.purchase_id=z_purchase.id AND z_company_invite.company_id IN('.implode(',',$usercompanies).')
        LEFT JOIN z_offer
          ON z_offer.product_id=z_product.id AND z_offer.user_id=:user_id
        INNER JOIN z_user_market
          ON z_user_market.market_id=z_purchase.market_id AND z_user_market.user_id=:user_id
        WHERE z_offer.id IS NULL AND z_purchase.date_close>NOW() ' . $where . ' AND z_company.id NOT IN ('.implode(',',$usercompanies).')
        AND (z_purchase.dirrect=0 OR z_company_invite.id>0)
        AND z_purchase.purchasestate_id=2
        ORDER BY z_purchase.date_create DESC
        LIMIT ' . $start . ',' . $take;
        /*if(yii::app()->user->getId()==2027)
        echo $sql;*/
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        //$command->bindParam(":strt",$start,PDO::PARAM_INT);
        /*$command->bindParam(":take",$take,PDO::PARAM_INT);*/
        $purchases = $command->queryAll();

        if (count($purchases) < $take)
            $result['last'] = true;
        else
            unset($purchases[count($purchases) - 1]);
        $pArr = array();
        if (count($purchases) > 0)
            foreach ($purchases as $p)
                $pArr[$p['id']] = $p['id'];
        if (count($pArr) > 0) {

            #sellers
            $sql = '
            SELECT
              z_product.id,
              COUNT(DISTINCT z_offer.user_id) AS sellers
            FROM z_offer
            INNER JOIN z_product
                ON z_product.id=z_offer.product_id
            WHERE z_product.purchase_id in(' . implode(',', $pArr) . ')
            GROUP BY z_product.id
            ';
            $command = $connection->createCommand($sql);
            //$command->bindParam(":user_id",yii::app()->user->getId(),PDO::PARAM_INT);
            $sellers = $command->queryAll();
            $sellerArr = array();
            foreach ($sellers as $s) {
                $sellerArr[$s['id']] = $s;
            }
            $result['sellers'] = $sellerArr;

            #files
            $sql = '
            SELECT
            z_file.id,
            z_file.file,
            z_file.path,
            z_purchase_file.purchase_id
            FROM z_purchase_file
            INNER JOIN z_file
                ON z_file.id=z_purchase_file.file_id
            WHERE z_purchase_file.purchase_id in(' . implode(',', $pArr) . ')
            ';
            $command = $connection->createCommand($sql);
            //$command->bindParam(":user_id",yii::app()->user->getId(),PDO::PARAM_INT);
            $files = $command->queryAll();
            $fileArr = array();
            foreach ($files as $s) {
                $fileArr[$s['purchase_id']][$s['id']] = array('path' => $s['path'], 'file' => $s['file']);
            }
            $result['files'] = $fileArr;
        }

        $result['products'] = $purchases;
        return $result;
    }
    public function findMinPrices($product_id){
        $connection = Yii::app()->db;
        $sql = '
            SELECT
                MIN(if(z_offer.delivery,z_offer.price,null)) AS minprice_delivery,
                MIN(if(z_offer.delivery,null,z_offer.price)) AS minprice
            FROM z_offer
            WHERE z_offer.product_id=:product_id
            ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":product_id",$product_id,PDO::PARAM_INT);
        return $command->queryRow();
    }
}