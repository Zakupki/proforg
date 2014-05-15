<?php
/**
 * This is the model class for table "{{purchase}}".
 *
 * The followings are the available columns in table '{{purchase}}':
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
 * @property integer $delay
 * @property integer $delivery_date
 * @property integer $active_until
 * @property string $befor_price
 * @property string $last_price
 * @property string $percent_price_changes
 * @property string $comment
 * @property integer $sort
 * @property integer $status
 *
 * @method Purchase active
 * @method Purchase cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Purchase indexed($column = 'id')
 * @method Purchase language($lang = null)
 * @method Purchase select($columns = '*')
 * @method Purchase limit($limit, $offset = 0)
 * @method Purchase sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Market $market
 * @property User $user
 * @property User $closer
 * @property Company $company
 * @property User $lastuser
 */
class Purchasereport extends BaseActiveRecord
{

    public $purchase_id;
    public $date_create;
    public $company;
    public $companygroup;
    public $product;
    public $markettype;
    public $market;
    public $address;
    public $unit;
    public $amount;
    public $purchasestate;
    public $date_deliver;
    public $date_closed;
    public $date_close;
    public $comment;
    public $close_text;
    public $supplier;
    public $offer_product;
    public $offer_amount;
    public $offer_price;
    public $offer_total;
    public $delay;
    public $delivery;
    public $offer_delay;
    public $winner;
    public $offer_comment;
    public $offer_id;
    public $offer_place;
    public $offertype;

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Purchase the static model class
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
        return '{{purchase}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('company_id, user_id, market_id,purchasestate_id','required'),
            array('reduction_level,market_id, dirrect,  purchasestate_id, company_id, user_id, lastuser_id, closer_id, created_on, updated_on, closed_on, comments_enabled, delay, active_until, sort, status', 'numerical', 'integerOnly' => true),
            array('title, address, slug, city', 'length', 'max' => 100),
            array('attachment', 'length', 'max' => 255),
            array('status_old', 'length', 'max' => 7),
            array('befor_price, last_price, percent_price_changes', 'length', 'max' => 15),
            array('intro, emails, body, date, delivery_date, close_text, comment, product, unit, companygroup, offertype', 'safe'),
            array('market_id', 'exist', 'className' => 'Market', 'attributeName' => 'id'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('lastuser_id', 'exist', 'className' => 'Lastuser', 'attributeName' => 'id'),
            array('closer_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('id, title, emails, dirrect, market_id, company_id, user_id, lastuser_id, closer_id, slug, attachment, intro, body, created_on, updated_on, closed_on, comments_enabled, status_old, date, city, delay, delivery_date, active_until, befor_price, last_price, percent_price_changes, close_text, sort, status, purchasestate_id', 'safe', 'on' => 'search'),
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
            'purchasestate' => array(self::BELONGS_TO, 'Purchasestate', 'purchasestate_id'),
            'tag' => array(self::BELONGS_TO, 'Tag', 'tag_id'),
            'closer' => array(self::BELONGS_TO, 'User', 'closer_id'),
            'products' => array(self::HAS_MANY, 'Product', 'purchase_id'),
            'purchaseFiles' => array(self::HAS_MANY, 'PurchaseFile', array('purchase_id' => 'id')),
            'closer' => array(self::BELONGS_TO, 'User', 'closer_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'companycity'=>array(self::HAS_ONE,'City',array('city_id'=>'id'),'through'=>'company'),
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
            'comment' => Yii::t('backend', 'Comment'),
            'purchasestate_id' => Yii::t('backend', 'Purchasestate'),
            'lastuser_id' => Yii::t('backend', 'Lastuser'),
            'closer_id' => Yii::t('backend', 'Closer'),
            'slug' => Yii::t('backend', 'Slug'),
            'attachment' => Yii::t('backend', 'Attachment'),
            'intro' => Yii::t('backend', 'Intro'),
            'body' => Yii::t('backend', 'Body'),
            'created_on' => Yii::t('backend', 'Created On'),
            'updated_on' => Yii::t('backend', 'Updated On'),
            'closed_on' => Yii::t('backend', 'Closed On'),
            'close_texts_enabled' => Yii::t('backend', 'Comments Enabled'),
            'status_old' => Yii::t('backend', 'Status Old'),
            'date' => Yii::t('backend', 'Date'),
            'city' => Yii::t('backend', 'City'),
            'delay' => Yii::t('backend', 'Delay'),
            'delivery_date' => Yii::t('backend', 'Delivery Date'),
            'active_until' => Yii::t('backend', 'Active Until'),
            'befor_price' => Yii::t('backend', 'Befor Price'),
            'last_price' => Yii::t('backend', 'Last Price'),
            'percent_price_changes' => Yii::t('backend', 'Percent Price Changes'),
            'close_text' => Yii::t('backend', 'close_text'),
            'address' => Yii::t('backend', 'Address'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'dirrect' => Yii::t('backend', 'Dirrect'),
            'emails' => Yii::t('backend', 'Emails'),
            'market' => Yii::t('backend', 'Market'),
            'markettype' => Yii::t('backend', 'Markettype'),
            'company' => Yii::t('backend', 'Company'),
            'companygroup' => Yii::t('backend', 'Companygroup'),
            'product' => Yii::t('backend', 'Product'),
            'unit' => Yii::t('backend', 'Unit'),
            'amount' => Yii::t('backend', 'Аmount'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'purchasestate' => Yii::t('backend', 'Purchasestate'),
            'date_deliver' => Yii::t('backend', 'Date Deliver'),
            'date_close' => Yii::t('backend', 'Date Close'),
            'date_closed' => Yii::t('backend', 'Date Closed'),
            'close_text' => Yii::t('backend', 'Close Text'),
            'offer_product' => Yii::t('backend', 'Offer'),
            'supplier'=>Yii::t('backend', 'Supplier'),
            'offer_amount'=>Yii::t('backend', 'Offer Amount'),
            'offer_price'=>Yii::t('backend', 'Price'),
            'offer_total'=>Yii::t('backend', 'Total'),
            'delivery'=>Yii::t('backend', 'Delivery'),
            'offer_delay'=>Yii::t('backend', 'Delay'),
            'winner'=>Yii::t('backend', 'Winner'),
            'offer_comment'=>Yii::t('backend', 'Offer Comment'),
            'offer_id' => Yii::t('backend', 'Offer Id'),
            'purchase_id' => Yii::t('backend', 'Purchase Id'),
            'offer_place' => Yii::t('backend', 'Offer Place'),
            'offertype' => Yii::t('backend', 'Supplier Type'),


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
        $criteria->compare('t.purchasestate_id',$this->purchasestate_id);
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
		$criteria->compare('t.delay',$this->delay);
		$criteria->compare('t.delivery_date',$this->delivery_date,true);
		$criteria->compare('t.active_until',$this->active_until);
		$criteria->compare('t.befor_price',$this->befor_price,true);
		$criteria->compare('t.last_price',$this->last_price,true);
		$criteria->compare('t.percent_price_changes',$this->percent_price_changes,true);
        $criteria->compare('t.close_text', $this->close_text, true);
        $criteria->compare('t.emails', $this->emails, true);
        $criteria->compare('t.state',$this->purchasestate_id);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);
        $criteria->compare('t.dirrect',$this->dirrect);

		$criteria->with = array('market', 'user', 'closer', 'company', 'lastuser');

        return parent::searchInit($criteria);
    }

    public function getMyPurchases($param=array()){
        $connection=Yii::app()->db;
        $Where='';
        if(isset($param['purchase_id']))
            $Where .=' AND z_purchase.id=:purchase_id';
        if(isset($param['own']))
            $Where .=' AND z_purchase.user_id=:user_id';
        if (isset($param['market_id']))
            $Where .= ' AND z_purchase.market_id=:market_id';
        if (isset($param['company_id']))
            $Where .= ' AND z_purchase.company_id=:company_id';
        $sql='
        SELECT
          z_purchase.id,
          z_purchase.date_create,
          z_purchase.date_close,
          z_purchase.date_deliver,
          z_purchase.date_reduction,
          z_purchase.delay,
          z_purchase.dirrect,
          z_purchase.comment,
          z_purchase.purchasestate_id,
          z_purchase.`user_id`,
          z_market.title AS market,
          z_user.first_name,
          z_user.name,
          z_user.last_name,
          company.title AS company,
          z_city.title AS city
        FROM
          z_company_user
          INNER JOIN z_company
            ON z_company.id = z_company_user.`company_id`
          INNER JOIN z_company company
            ON company.`companygroup_id` = z_company.`companygroup_id`
          INNER JOIN z_city
            ON z_city.id=company.city_id
          INNER JOIN z_purchase
            ON z_purchase.`company_id` = company.`id`
          INNER JOIN z_user
            ON z_user.id = z_purchase.user_id
          LEFT JOIN z_market
            ON z_market.id = z_purchase.market_id
        WHERE z_company_user.user_id=:user_id AND z_purchase.purchasestate_id NOT IN(1,4) ' . $Where . '
        GROUP BY z_purchase.id
        ORDER BY z_purchase.date_create DESC
        ';
        $command=$connection->createCommand($sql);
        $command->bindParam(":user_id",yii::app()->user->getId(),PDO::PARAM_INT);
        if(isset($param['purchase_id']))
            $command->bindParam(":purchase_id", $param['purchase_id'], PDO::PARAM_INT);
        if (isset($param['market_id']))
            $command->bindParam(":market_id", $param['market_id'], PDO::PARAM_INT);
        if (isset($param['company_id']))
            $command->bindParam(":company_id", $param['company_id'], PDO::PARAM_INT);
        $purchases=$command->queryAll();

        if(count($purchases)>0){
            $result['purchases']=$purchases;
            foreach($purchases as $p)
                $pArr[$p['id']]=$p['id'];

            if(count($pArr)>0){
                $sql='
                SELECT
                  count(distinct z_offer.user_id) AS sellers,
                  z_product.`purchase_id` AS id
                FROM
                  z_product
                  INNER JOIN z_offer
                    ON z_offer.`product_id` = z_product.id
                WHERE z_product.purchase_id in('.implode(',',$pArr).')
                GROUP BY z_product.`purchase_id`
                ';
                $command=$connection->createCommand($sql);
                //$command->bindParam(":user_id",yii::app()->user->getId(),PDO::PARAM_INT);
                $sellers=$command->queryAll();
                $sellerArr=array();
                foreach($sellers as $s){
                    $sellerArr[$s['id']]=$s;
                }
                $result['sellers']=$sellerArr;
            }

            $sql='
            SELECT
              z_product.id,
              z_product.purchase_id,
              z_product.amount,
              z_tag.title,
              z_unit.title as unit,
              z_unit.title2 as unit2,
              z_unit.title3 as unit3
            FROM z_product
            LEFT JOIN z_unit
              ON z_unit.id=z_product.unit_id
            LEFT JOIN z_tag
              ON z_tag.id=z_product.tag_id
            WHERE z_product.purchase_id IN('.implode(',',$pArr).')
            ORDER BY z_product.date_create
            ';

                $command=$connection->createCommand($sql);
                $productdata=$command->queryAll();
                if(count($productdata)>0){
                foreach($productdata as $pr){
                    $prodArr[$pr['id']]=$pr['id'];
                    $products[$pr['purchase_id']][$pr['id']]=$pr;
                }
                    $result['products']=$products;
                    if(count($prodArr)>0){
                        $sql='
                        SELECT 
						IF(offer.id,offer.id,z_offer.id) AS id,
						IF(offer.id,offer.pid,z_offer.id) AS pid,
						z_offer.id AS `ofid`,
						IF(offer.id,offer.product_id,z_offer.product_id) AS product_id,
						IF(offer.id,offer.price,z_offer.price) AS price,
						IF(offer.id,offer.delivery,z_offer.delivery) AS delivery,
						IF(offer.id,offer.amount,z_offer.amount) AS amount,
						IF(offer.id,offer.delay,z_offer.delay) AS delay,
						IF(offer.id,offer.winner,z_offer.winner) AS winner,
						z_offer.reduction,
						IF(offer.id,offer.comment,z_offer.comment) AS comment,
						IF(offer.price_reduce>0,offer.price_reduce,0) AS price_reduce,
						z_tag.title,
						z_company.egrpou,
						z_company.id AS company_id,
						z_company.companygroup_id,
						z_company.title AS company,
						z_company.address,
						z_city.title AS city,
						z_country.title AS country,
						z_user.id AS user_id,
						z_user.first_name,
                        z_user.name,
                        z_user.last_name,
                        z_user.position
						FROM
						  z_offer
						INNER JOIN z_user
						ON z_user.id=z_offer.user_id
						LEFT JOIN z_offer offer
						  ON offer.pid=z_offer.id AND offer.`id`=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`ofid`)
						LEFT JOIN z_tag
			              ON z_tag.id=z_offer.tag_id
						INNER JOIN z_company
                          ON z_company.id=z_offer.company_id
                        LEFT JOIN z_city
                          ON z_city.id=z_company.city_id
                        LEFT JOIN z_region
                          ON z_region.id=z_city.region_id
                        LEFT JOIN z_country
                          ON z_country.id=z_region.country_id
                        WHERE z_offer.pid IS NULL AND z_offer.product_id IN('.implode(',',$prodArr).')
                        ';
                        $command=$connection->createCommand($sql);
                        $offerdata=$command->queryAll();
                        $companyArr = array();
                        $userArr = array();
                        if(count($offerdata)>0){
                            foreach($offerdata as $of){
                                $offers[$of['product_id']][$of['id']]=$of;
                                $companyArr[$of['company_id']] = $of['company_id'];
                                $userArr[$of['user_id']] = $of['user_id'];
                            }
                            $result['offers']=$offers;
                        }

                        if (count($companyArr) > 0 && count($userArr)>0) {
                            $sql = '
                                SELECT
                                  z_phone.id,
                                  z_phone.user_id,
                                  z_phone.company_id,
                                  concat(z_country.phonecode," ",z_phone.phonecode," ",z_phone.phone) AS phone
                                FROM z_company_user
                                INNER JOIN z_phone
                                  ON z_phone.user_id=z_company_user.user_id OR z_phone.company_id=z_company_user.company_id
                                INNER JOIN z_country
                                  ON z_country.id=z_phone.country_id
                                WHERE z_company_user.user_id IN (' . implode(',', $userArr) . ')
                                  AND z_company_user.company_id IN (' . implode(',', $companyArr) . ')
                                  AND z_company_user.status=1
                            ';
                            $command = $connection->createCommand($sql);
                            $phonedata = $command->queryAll();
                            $phonesArr = array();
                            foreach ($phonedata as $ph) {
                                if ($ph['user_id'] > 0)
                                    $phonesArr['users'][$ph['user_id']]['phones'][$ph['id']] = $ph['phone'];
                                if ($ph['company_id'] > 0)
                                    $phonesArr['companies'][$ph['company_id']]['phones'][$ph['id']] = $ph['phone'];
                            }
                            if (count($phonesArr) > 0)
                                $result['phones'] = $phonesArr;

                        }

                    }
                }
        }
        if(isset($result))
        return $result;
    }

    public function getClosedPurchase($param = array())
    {
        $connection = Yii::app()->db;
        $Where = '';
        if (isset($param['purchase_id']))
            $Where = ' AND z_purchase.id=:purchase_id';
        $sql = '
        SELECT
          z_purchase.id,
          z_purchase.date_create,
          z_purchase.date_close,
          z_purchase.date_closed,
          z_purchase.date_deliver,
          z_purchase.delay,
          z_purchase.close_text,
          z_purchase.comment,
          z_purchase.purchasestate_id,
          z_market.title AS market,
          z_company.title AS company,
          z_user.first_name,
          z_user.name,
          z_user.last_name
        FROM z_purchase
        INNER JOIN z_user
          ON z_user.id=z_purchase.user_id
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
        LEFT JOIN z_market
          ON z_market.id=z_purchase.market_id
        WHERE 1=1 /*AND z_purchase.purchasestate_id NOT IN(4)*/ ' . $Where . '
        ORDER BY z_purchase.date_create DESC
        ';
        //echo $sql;
        $command = $connection->createCommand($sql);
        //$command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        if (isset($param['purchase_id']))
            $command->bindParam(":purchase_id", $param['purchase_id'], PDO::PARAM_INT);
        $purchases = $command->queryAll();

        if (count($purchases) > 0) {
            $result['purchases'] = $purchases;
            foreach ($purchases as $p)
                $pArr[$p['id']] = $p['id'];


            if (count($pArr) > 0) {
                $sql = '
                SELECT
                  DISTINCT(z_offer.user_id) AS sellers,
                  z_product.`purchase_id` AS id
                FROM
                  z_product
                  INNER JOIN z_offer
                    ON z_offer.`product_id` = z_product.id
                WHERE z_product.purchase_id in(' . implode(',', $pArr) . ')
                GROUP BY z_product.`purchase_id`
                ';
                $command = $connection->createCommand($sql);
                //$command->bindParam(":user_id",yii::app()->user->getId(),PDO::PARAM_INT);
                $sellers = $command->queryAll();
                $sellerArr = array();
                foreach ($sellers as $s) {
                    $sellerArr[$s['id']] = $s;
                }
                $result['sellers'] = $sellerArr;
            }


            $sql = '
            SELECT
              z_product.id,
              z_product.purchase_id,
              z_product.amount,
              z_tag.title,
              z_unit.title as unit,
              z_unit.title2 as unit2,
              z_unit.title3 as unit3
            FROM z_product
            LEFT JOIN z_unit
              ON z_unit.id=z_product.unit_id
            LEFT JOIN z_tag
              ON z_tag.id=z_product.tag_id
            WHERE z_product.purchase_id IN(' . implode(',', $pArr) . ')
            ORDER BY z_product.date_create
            ';

            $command = $connection->createCommand($sql);
            $productdata = $command->queryAll();
            if (count($productdata) > 0) {
                foreach ($productdata as $pr) {
                    $prodArr[$pr['id']] = $pr['id'];
                    $products[$pr['purchase_id']][$pr['id']] = $pr;
                }
                $result['products'] = $products;
                if (count($prodArr) > 0) {
                    $sql = '
                        SELECT
						IF(offer.id,offer.id,z_offer.id) AS id,
						IF(offer.id,offer.pid,z_offer.id) AS pid,
						z_offer.id AS `ofid`,
						IF(offer.id,offer.product_id,z_offer.product_id) AS product_id,
						IF(offer.id,offer.price,z_offer.price) AS price,
						IF(offer.id,offer.delivery,z_offer.delivery) AS delivery,
						IF(offer.id,offer.amount,z_offer.amount) AS amount,
						IF(offer.id,offer.delay,z_offer.delay) AS delay,
						IF(offer.id,offer.winner,z_offer.winner) AS winner,
						IF(offer.id,offer.reduction,z_offer.reduction) AS reduction,
						IF(offer.id,offer.comment,z_offer.comment) AS comment,
						IF(offer.price_reduce>0,offer.price_reduce,0) AS price_reduce,
						z_tag.title,
						z_company.id AS company_id,
						z_company.title AS company,
						z_company.address,
						z_company.companygroup_id,
						z_user.id AS user_id,
						z_user.first_name,
						z_user.name,
                        z_user.last_name,
                        z_user.position,
                        if(z_company_user.id,1,0) AS visible,
                        z_country.title AS country,
                        z_city.title AS city
						FROM
						  z_offer
						INNER JOIN z_user
						ON z_user.id=z_offer.user_id
						LEFT JOIN z_offer offer
						  ON offer.pid=z_offer.id AND offer.`id`=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`ofid`)
						LEFT JOIN z_tag
			              ON z_tag.id=z_offer.tag_id
						LEFT JOIN z_company
                          ON z_company.id=z_offer.company_id
                        LEFT JOIN z_city
                          ON z_city.id=z_company.city_id
                        LEFT JOIN z_region
                          ON z_region.id=z_city.region_id
                        LEFT JOIN z_country
                          ON z_country.id=z_region.country_id
                        LEFT JOIN z_company_user
                          ON z_company_user.company_id=z_offer.company_id AND z_company_user.user_id=:user_id AND z_company_user.status=1
                        WHERE z_offer.pid IS NULL AND z_offer.product_id IN(' . implode(',', $prodArr) . ')
                        ';
                    $command = $connection->createCommand($sql);
                    $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
                    $offerdata = $command->queryAll();
                    if (count($offerdata) > 0) {
                        foreach ($offerdata as $of) {
                            $offers[$of['product_id']][$of['id']] = $of;
                        }
                        $result['offers'] = $offers;
                    }

                }
            }
        }
        if (isset($result))
            return $result;
    }

    public function companyAnalitycs($params=array())
    {
        $whereSql='';
        if($params['market_id']>0)
            $whereSql.=' AND z_purchase.market_id=:market_id';
        if($params['company_id']>0)
            $whereSql.=' AND z_purchase.company_id=:company_id';
        if(isset($params['month_range']) && isset($params['year'])){
            $mArr=explode(',',$params['month_range']);
            if(count($mArr)>1){
                $dateStart=$params['year'].'-'.$mArr[0].'-31 00:00:00';
                $dateEnd=$params['year'].'-'.$mArr[1].'-31 00:00:00';
            }
            $whereSql.=' AND z_purchase.date_closed BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"';

        }
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          sum(z_purchase.total) AS total,
          count(distinct z_purchase.id) AS purchase_num,
          sum(z_purchase.not_concurent) AS not_concurent,
          sum(z_purchase.company_num) AS company_num,
          sum(z_purchase.company_num) AS company_num,
          sum(z_purchase.maxtotal) AS maxtotal,
          sum(z_purchase.mintotal) AS mintotal,
          SUM(IF(z_purchase.economy_sum>0,z_purchase.economy_sum,0)) AS economy_sum,
          SUM(IF(z_purchase.economy_sum>0,z_purchase.total,0)) AS economy_sum_total,
          sum(z_purchase.not_min_purchase) AS not_min_purchase,
          sum(if(z_purchase.not_min_purchase,z_purchase.lose_total,0)) AS lose_total,
          sum(if(z_purchase.not_min_purchase,z_purchase.total,0)) AS total_with_lose,
          AVG(z_purchase.avg_delay) AS avg_delay,
          100-(sum(z_purchase.mintotal)/sum(z_purchase.maxtotal)*100) AS price_corridor
        FROM z_purchase
        INNER JOIN z_company_user
        ON z_company_user.company_id=z_purchase.company_id AND z_company_user.user_id=:user_id
        /*AND z_purchase.purchasestate_id>1*/
        WHERE z_purchase.purchasestate_id=4 '.$whereSql.'
        ';
        /*if($_SERVER['REMOTE_ADDR']=='195.177.72.222')
            echo $sql;*/
        $command = $connection->createCommand($sql);
        if($params['market_id']>0)
            $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        if($params['company_id']>0)
            $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryRow();
        //CVarDumper::dump($result, 10, true);
        return $result;
    }
    public function companyTypeAnalitycs($params=array())
    {
        $whereSql='';
        $data=array();
        if($params['market_id']>0)
            $whereSql.=' AND z_purchase.market_id=:market_id';
        if($params['company_id']>0)
            $whereSql.=' AND z_purchase.company_id=:company_id';
        if(isset($params['month_range']) && isset($params['year'])){
            $mArr=explode(',',$params['month_range']);
            if(count($mArr)>1){
                $dateStart=$params['year'].'-'.$mArr[0].'-31 00:00:00';
                $dateEnd=$params['year'].'-'.$mArr[1].'-31 00:00:00';
            }
            $whereSql.=' AND z_purchase.date_closed BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"';

        }
        if($params['reporttype_id']==2)
            $whereSql.=' AND z_purchase.economy_sum>0';
        if($params['reporttype_id']==4)
            $whereSql.=' AND z_purchase.not_concurent=1';
        if($params['reporttype_id']==5)
            $whereSql.=' AND z_purchase.avg_delay>0';
        if($params['reporttype_id']==6)
            $whereSql.=' AND z_purchase.not_min_purchase=1';
        if($params['reporttype_id']==10 && $params['user_id'])
            $whereSql.=' AND z_purchase.user_id=:user_id2';

        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_purchase.id,
          z_purchase.date_closed,
          z_purchase.total,
          z_purchase.economy_sum,
          z_purchase.economy_sum_total,
          z_purchase.lose_total,
          z_market.title AS market_title,
          z_company.title AS company_title,
          z_user.name,
          z_user.first_name,
          z_user.last_name
        FROM z_purchase
        INNER JOIN z_company
          ON z_company.id=z_purchase.company_id
        INNER JOIN z_user
          ON z_user.id=z_purchase.user_id
        INNER JOIN z_market
          ON z_market.id=z_purchase.market_id
        INNER JOIN z_company_user
          ON z_company_user.company_id=z_purchase.company_id AND z_company_user.user_id=:user_id
        WHERE z_purchase.purchasestate_id=4 '.$whereSql.'
        ORDER BY z_purchase.date_closed DESC
        ';
         /*if($_SERVER['REMOTE_ADDR']=='195.177.72.222')
            echo $sql;*/
        $command = $connection->createCommand($sql);
        if($params['market_id']>0)
            $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        if($params['company_id']>0)
            $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        if($params['reporttype_id']==10 && $params['user_id'])
            $command->bindParam(":user_id2", $params['user_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        $purchaseidArr=array();
        foreach($result as $pur)
            $purchaseidArr[$pur['id']]=$pur['id'];
        $productArr=array();
        if(count($purchaseidArr)>0){
            $sql = 'SELECT
              z_product.`purchase_id`,
              GROUP_CONCAT(DISTINCT z_tag.title ORDER BY z_tag.title ASC SEPARATOR ", ") AS title
            FROM
              z_product
              INNER JOIN z_purchase
                ON z_purchase.id = z_product.`purchase_id`
              INNER JOIN z_tag
                ON z_tag.id = z_product.`tag_id`
             WHERE z_product.`purchase_id` IN ('.implode(',',$purchaseidArr).')
             GROUP BY z_purchase.id';
            $command = $connection->createCommand($sql);
            $product_result = $command->queryAll();
            foreach($product_result as $pr){
                $productArr[$pr['purchase_id']]=$pr['title'];
            }
        }
        $data['purchases']=$result;
        $data['products']=$productArr;
        return $data;
    }
    public function usersAnalitycs($params=array())
    {
        $whereSql='';
        if($params['market_id']>0)
            $whereSql.=' AND z_purchase.market_id=:market_id';
        if($params['company_id']>0)
            $whereSql.=' AND z_purchase.company_id=:company_id';
        if(isset($params['month_range']) && isset($params['year'])){
            $mArr=explode(',',$params['month_range']);
            if(count($mArr)>1){
                $dateStart=$params['year'].'-'.$mArr[0].'-31 00:00:00';
                $dateEnd=$params['year'].'-'.$mArr[1].'-31 00:00:00';
            }
            $whereSql.=' AND z_purchase.date_closed BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"';

        }

        $connection = Yii::app()->db;
        $sql = '
        SELECT
          sum(z_purchase.total) AS total,
          count(distinct z_purchase.id) AS purchase_num,
          sum(z_purchase.not_concurent) AS not_concurent,
          sum(z_purchase.company_num) AS company_num,
          sum(z_purchase.company_num) AS company_num,
          sum(z_purchase.maxtotal) AS maxtotal,
          sum(z_purchase.mintotal) AS mintotal,
          sum(z_purchase.economy_sum) AS economy_sum,
          sum(z_purchase.economy_sum_total) AS economy_sum_total,
          sum(z_purchase.not_min_purchase) AS not_min_purchase,
          sum(z_purchase.lose_total) AS lose_total,
          sum(z_purchase.total_with_lose) AS total_with_lose,
          AVG(z_purchase.avg_delay) AS avg_delay,
          100-(sum(z_purchase.mintotal)/sum(z_purchase.maxtotal)*100) AS price_corridor,
          z_user.first_name,
          z_user.name,
          z_user.last_name,
          z_user.id AS user_id
        FROM z_purchase
        INNER JOIN z_company_user
        ON z_company_user.company_id=z_purchase.company_id AND z_company_user.user_id=:user_id
        INNER JOIN z_user
        ON z_user.id=z_purchase.user_id
        /*AND z_purchase.purchasestate_id>1*/
        WHERE z_purchase.purchasestate_id=4 '.$whereSql.'
        GROUP BY z_user.id
        ';
        $command = $connection->createCommand($sql);
        if($params['market_id']>0)
            $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        if($params['company_id']>0)
            $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        //CVarDumper::dump($result, 10, true);
        return $result;
    }

    public function getGraph($params = array())
    {
        $whereSql = '';
        if ($params['market_id'] > 0)
            $whereSql .= ' AND z_purchase.market_id=:market_id';
        if ($params['company_id'] > 0)
            $whereSql .= ' AND z_purchase.company_id=:company_id';
        if (isset($params['month_range']) && isset($params['year'])) {
            $mArr = explode(',', $params['month_range']);
            if (count($mArr) > 1) {
                $dateStart = $params['year'] . '-' . $mArr[0] . '-01 00:00:00';
                $dateEnd = $params['year'] . '-' . $mArr[1] . '-31 00:00:00';
            }
            $whereSql .= ' AND z_purchase.date_closed BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';

        }
        $connection = Yii::app()->db;

        if ($params['reporttype_id'] == 1) {
            $sql = '
            SELECT
              UNIX_TIMESTAMP(
                DATE_ADD(
                  DATE_FORMAT(
                    z_purchase.`date_closed`,
                    "%Y-%m-01 00:00:00"
                  ),
                  INTERVAL 3 HOUR
                )
              ) * 1000 AS `dt`,
              SUM(z_purchase.total) AS value
            FROM
              z_purchase
            INNER JOIN z_company_user
                ON z_company_user.company_id = z_purchase.company_id
                AND z_company_user.user_id = :user_id
            WHERE z_purchase.`purchasestate_id` = 4 ' . $whereSql . '
            GROUP BY dt
            ORDER BY z_purchase.`date_closed` ASC
            ';
        } elseif ($params['reporttype_id'] == 2) {
            $sql = '
            SELECT
              UNIX_TIMESTAMP(
                DATE_ADD(
                  DATE_FORMAT(
                    z_purchase.`date_closed`,
                    "%Y-%m-01 00:00:00"
                  ),
                  INTERVAL 3 HOUR
                )
              ) * 1000 AS `dt`,
              if(SUM(z_purchase.economy_sum),SUM(z_purchase.economy_sum),0) AS value
            FROM
              z_purchase
            INNER JOIN z_company_user
                ON z_company_user.company_id = z_purchase.company_id
                AND z_company_user.user_id = :user_id
            WHERE z_purchase.`purchasestate_id` = 4 ' . $whereSql . '
            GROUP BY dt
            ORDER BY z_purchase.`date_closed` ASC
            ';
        } elseif ($params['reporttype_id'] == 3) {
            $sql = '
            SELECT
              UNIX_TIMESTAMP(
                  DATE_ADD(
                    DATE_FORMAT(
                      z_purchase.`date_closed`,
                      "%Y-%m-01 00:00:00"
                    ),
                    INTERVAL 3 HOUR
                  )
              ) * 1000 AS `dt`,
              ROUND(SUM(z_purchase.company_num)/COUNT(DISTINCT z_purchase.id),2) AS value
            FROM
              z_purchase
              INNER JOIN z_company_user
                ON z_company_user.company_id = z_purchase.company_id
                AND z_company_user.user_id = :user_id
            WHERE z_purchase.`purchasestate_id` = 4 ' . $whereSql . '
            GROUP BY `dt`
            ORDER BY z_purchase.`date_closed` ASC
            ';

        } elseif ($params['reporttype_id'] == 4) {
            $sql = '
             SELECT
              UNIX_TIMESTAMP(
                DATE_ADD(
                  DATE_FORMAT(
                    z_purchase.`date_closed`,
                    "%Y-%m-01 00:00:00"
                  ),
                  INTERVAL 3 HOUR
                )
              ) * 1000 AS `dt`,
              COUNT(z_purchase.not_concurent) AS value
            FROM
              z_purchase
            INNER JOIN z_company_user
                ON z_company_user.company_id = z_purchase.company_id
                AND z_company_user.user_id = :user_id
            WHERE z_purchase.`purchasestate_id` = 4 AND z_purchase.not_concurent=1 ' . $whereSql . '
            GROUP BY dt
            ORDER BY z_purchase.`date_closed` ASC
            ';
        } elseif ($params['reporttype_id'] == 5) {
            $sql = '
             SELECT
              UNIX_TIMESTAMP(
                DATE_ADD(
                  DATE_FORMAT(
                    z_purchase.`date_closed`,
                    "%Y-%m-01 00:00:00"
                  ),
                  INTERVAL 3 HOUR
                )
              ) * 1000 AS `dt`,
             round(sum(z_purchase.avg_delay)/count(distinct z_purchase.id)) AS value
            FROM
              z_purchase
            INNER JOIN z_company_user
                ON z_company_user.company_id = z_purchase.company_id
                AND z_company_user.user_id = :user_id
            WHERE z_purchase.`purchasestate_id` = 4' . $whereSql . '
            GROUP BY dt
            ORDER BY z_purchase.`date_closed` ASC
            ';
        } elseif ($params['reporttype_id'] == 6) {
            $sql = '
             SELECT
              UNIX_TIMESTAMP(
                DATE_ADD(
                  DATE_FORMAT(
                    z_purchase.`date_closed`,
                    "%Y-%m-01 00:00:00"
                  ),
                  INTERVAL 3 HOUR
                )
              ) * 1000 AS `dt`,
              sum(z_purchase.not_min_purchase) AS value
            FROM
              z_purchase
            INNER JOIN z_company_user
                ON z_company_user.company_id = z_purchase.company_id
                AND z_company_user.user_id = :user_id
            WHERE z_purchase.`purchasestate_id` = 4 AND z_purchase.not_min_purchase>0 ' . $whereSql . '
            GROUP BY dt
            ORDER BY z_purchase.`date_closed` ASC
            ';
        } elseif ($params['reporttype_id'] == 1111) {
            $sql = '
            SELECT
              UNIX_TIMESTAMP(
                DATE_ADD(
                  DATE_FORMAT(
                    z_purchase.`date_closed`,
                    "%Y-%m-%d 00:00:00"
                  ),
                  INTERVAL 2 HOUR
                )
              ) * 1000 AS `dt`,
              COUNT(z_purchase.id) AS value
            FROM
              z_purchase
            INNER JOIN z_company_user
                ON z_company_user.company_id = z_purchase.company_id
                AND z_company_user.user_id = :user_id
            WHERE z_purchase.`purchasestate_id` = 4 ' . $whereSql . '
            GROUP BY dt
            ORDER BY z_purchase.`date_closed` ASC
            ';
        }
        $command = $connection->createCommand($sql);
        if ($params['market_id'] > 0)
            $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        if ($params['company_id'] > 0)
            $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        return $result;
    }

    public function companySaleAnalitycs($params = array())
    {
        $whereSql = '';
        if ($params['market_id'] > 0)
            $whereSql .= ' AND z_purchase.market_id=:market_id';
        if ($params['company_id'] > 0)
            $whereSql .= ' AND z_purchase.company_id=:company_id';
        if (isset($params['month_range']) && isset($params['year'])) {
            $mArr = explode(',', $params['month_range']);
            if (count($mArr) > 1) {
                $dateStart = $params['year'] . '-' . $mArr[0] . '-31 00:00:00';
                $dateEnd = $params['year'] . '-' . $mArr[1] . '-31 00:00:00';
            }
            $whereSql .= ' AND z_purchase.date_closed BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';

        }
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          1 AS gr,
          z_offer.id AS `ofid`,
          offer.id,
          COUNT(DISTINCT z_offer.id) AS total_offers,
	      SUM(IF(
            offer.winner,
            offer.winner,
            z_offer.winner
          )) AS winn_offers,
          COUNT(DISTINCT z_purchase.`company_id`) AS total_companies,
          COUNT(DISTINCT z_offer.`user_id`) AS managers,
          COUNT(DISTINCT IF(IF(offer.winner,offer.winner,z_offer.winner),z_purchase.company_id,NULL)) AS my_companies,
          SUM(z_purchase.`total`) AS total_taken,
          IF(
            offer.winner,
            offer.winner,
            z_offer.winner
          ) AS winner,
          SUM(
            IF(
              IF(
                offer.winner,
                offer.winner,
                z_offer.winner
              ),
              IF(
                offer.winner,
                offer.price * offer.amount,
                z_offer.price * z_offer.amount
              ),
              0
            )
          ) AS total
        FROM
          z_company_user
          INNER JOIN z_offer
            ON z_offer.`company_id` = z_company_user.`company_id`
            AND z_offer.pid IS NULL
          INNER JOIN z_product
            ON z_product.id = z_offer.`product_id`
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.`purchase_id`
          LEFT JOIN z_offer offer
            ON offer.pid = z_offer.id
            AND offer.`id` =
            (SELECT
              MAX(id)
            FROM
              z_offer
            WHERE z_offer.pid = `ofid`)
        WHERE z_company_user.`user_id` = :user_id ' . $whereSql . '
        GROUP BY `gr`
        ';
        //echo $sql;
        $command = $connection->createCommand($sql);
        if ($params['market_id'] > 0)
            $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        if ($params['company_id'] > 0)
            $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryRow();
        //CVarDumper::dump($result, 10, true);
        return $result;
    }

    public function usersSalesAnalitycs($params = array())
    {
        $whereSql = '';
        if ($params['market_id'] > 0)
            $whereSql .= ' AND z_purchase.market_id=:market_id';
        if ($params['company_id'] > 0)
            $whereSql .= ' AND z_offer.company_id=:company_id';
        if (isset($params['month_range']) && isset($params['year'])) {
            $mArr = explode(',', $params['month_range']);
            if (count($mArr) > 1) {
                $dateStart = $params['year'] . '-' . $mArr[0] . '-31 00:00:00';
                $dateEnd = $params['year'] . '-' . $mArr[1] . '-31 00:00:00';
            }
            $whereSql .= ' AND z_purchase.date_closed BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';

        }

        $connection = Yii::app()->db;
        $sql = '
         SELECT
          1 AS gr,
          z_offer.id AS `ofid`,
          z_offer.user_id,
          offer.id,
          COUNT(DISTINCT z_purchase.id) AS total_purchases,
          COUNT(DISTINCT z_offer.id) AS total_offers,
          SUM(
            IF(
              offer.winner,
              offer.winner,
              z_offer.winner
            )
          ) AS winn_offers,
          COUNT(
            DISTINCT z_purchase.`company_id`
          ) AS total_companies,
          COUNT(DISTINCT z_offer.`user_id`) AS managers,
          COUNT(
            DISTINCT IF(
              IF(
                offer.winner,
                offer.winner,
                z_offer.winner
              ),
              z_purchase.company_id,
              NULL
            )
          ) AS my_companies,
          SUM(z_purchase.`total`) AS total_taken,
          IF(
            offer.winner,
            offer.winner,
            z_offer.winner
          ) AS winner,
          SUM(
            IF(
              IF(
                offer.winner,
                offer.winner,
                z_offer.winner
              ),
              IF(
                offer.winner,
                offer.price * offer.amount,
                z_offer.price * z_offer.amount
              ),
              0
            )
          ) AS total,
          z_user.first_name,
          z_user.name,
          z_user.last_name
        FROM
          z_company_user
          INNER JOIN z_offer
            ON z_offer.`company_id` = z_company_user.`company_id`
            AND z_offer.pid IS NULL
          INNER JOIN z_user
            ON z_user.id = z_offer.`user_id`
          INNER JOIN z_product
            ON z_product.id = z_offer.`product_id`
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.`purchase_id`
          LEFT JOIN z_offer offer
            ON offer.pid = z_offer.id
            AND offer.`id` =
            (SELECT
              MAX(id)
            FROM
              z_offer
            WHERE z_offer.pid = `ofid`)
        WHERE z_company_user.`user_id` = :user_id
          ' . $whereSql . '
        GROUP BY `z_offer`.user_id
        ';
        $command = $connection->createCommand($sql);
        if ($params['market_id'] > 0)
            $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        if ($params['company_id'] > 0)
            $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        //CVarDumper::dump($result, 10, true);
        return $result;
    }

    public function getMyPurchaseFilters()
    {
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_purchase.company_id,
          company.title AS company_title,
          z_market.id AS market_id,
          z_market.title AS market,
          z_market.`markettype_id`,
          z_markettype.`title` AS markettype_title
        FROM
        z_company_user
        INNER JOIN z_company
          ON z_company.id=z_company_user.company_id
        INNER JOIN z_company company
          ON z_company.companygroup_id=company.companygroup_id
        INNER JOIN z_purchase
          ON z_purchase.company_id=company.id
        INNER JOIN z_market
          ON z_market.id = z_purchase.market_id
        INNER JOIN `z_markettype`
          ON z_markettype.id=z_market.`markettype_id`
        WHERE z_company_user.user_id=:user_id AND z_purchase.purchasestate_id NOT IN(1,4)
        ';

        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        $data['markets'] = array();
        $data['companies'] = array();
        foreach ($result as $row) {
            $data['markets'][$row['markettype_id']]['data'][$row['market_id']] = array('checked' => 1, 'title' => $row['market']);
            $data['markets'][$row['markettype_id']]['checked'] = 1;
            $data['markets'][$row['markettype_id']]['title'] = $row['markettype_title'];
            $data['companies'][$row['company_id']] = $row['company_title'];
        }

        return $data;
    }

    public function companySaleTypeAnalitycs($params = array())
    {

        $whereSql = '';
        if ($params['market_id'] > 0)
            $whereSql .= ' AND z_purchase.market_id=:market_id';
        if ($params['company_id'] > 0)
            $whereSql .= ' AND z_purchase.company_id=:company_id';
        if ($params['reporttype_id'] == 10 && $params['user_id'] > 0)
            $whereSql .= ' AND z_offer.user_id=:user_id2';
        if (isset($params['month_range']) && isset($params['year'])) {
            $mArr = explode(',', $params['month_range']);
            if (count($mArr) > 1) {
                $dateStart = $params['year'] . '-' . $mArr[0] . '-31 00:00:00';
                $dateEnd = $params['year'] . '-' . $mArr[1] . '-31 00:00:00';
            }
            $whereSql .= ' AND z_purchase.date_closed BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';

        }
        /*if($params['reporttype_id']==2)
            $whereSql.=' AND z_purchase.economy_sum>0';
        if($params['reporttype_id']==4)
            $whereSql.=' AND z_purchase.not_concurent=1';
        if($params['reporttype_id']==5)
            $whereSql.=' AND z_purchase.avg_delay>0';
        if($params['reporttype_id']==6)
            $whereSql.=' AND z_purchase.not_min_purchase=1';*/
        $connection = Yii::app()->db;


        if (in_array($params['reporttype_id'],array(1,2,10,3))){
            $sql = '
            SELECT
              z_offer.id AS `ofid`,
              z_purchase.id AS purchase_id,
              z_purchase.date_closed,
              z_company.title AS company_title,
              IF(offer.winner OR z_offer.winner,IF(
                 offer.id,
                 offer.price * offer.amount,
                 z_offer.price * z_offer.amount
              ),0) AS total,
              z_user.first_name,
              z_user.last_name,
              IF(
                offer.id,
                offer.winner,
                z_offer.winner
              ) AS winner
            FROM
              z_offer
              LEFT JOIN z_offer offer
                ON offer.pid = z_offer.id
                AND offer.`id` =
                (SELECT
                  MAX(id)
                FROM
                  z_offer
                WHERE z_offer.pid = `ofid`)
              INNER JOIN z_user
                ON z_user.id = z_offer.`user_id`
              INNER JOIN z_product
                ON z_product.id = z_offer.`product_id`
              INNER JOIN z_purchase
                ON z_purchase.id = z_product.`purchase_id`
              INNER JOIN z_company
                ON z_company.id = z_purchase.company_id
            WHERE z_offer.pid IS NULL
              AND z_offer.user_id = :user_id
              ' . $whereSql . '
            ';
            $command = $connection->createCommand($sql);
            if ($params['market_id'] > 0)
                $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
            if ($params['company_id'] > 0)
                $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
            if ($params['reporttype_id'] == 10 && $params['user_id'])
                $command->bindParam(":user_id2", $params['user_id'], PDO::PARAM_INT);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $result = $command->queryAll();
        }else{
            $sql = '
            SELECT
              z_purchase.id AS purchase_id,
              z_purchase.date_closed,
              z_company.title AS company_title,
              z_user.first_name,
              z_user.name,
              z_user.last_name,
              IF(
                offer.winner,
                offer.winner,
                z_offer.winner
              ) AS winner,
              SUM(
                IF(
                  IF(
                    offer.winner,
                    offer.winner,
                    z_offer.winner
                  ),
                  IF(
                    offer.winner,
                    offer.price * offer.amount,
                    z_offer.price * z_offer.amount
                  ),
                  0
                )
              ) AS total,

              1 AS gr,
              z_offer.id AS `ofid`,
              z_offer.user_id,
              offer.id,
              COUNT(DISTINCT z_purchase.id) AS total_purchases,
              COUNT(DISTINCT z_offer.id) AS total_offers,
              SUM(
                IF(
                  offer.winner,
                  offer.winner,
                  z_offer.winner
                )
              ) AS winn_offers,
              COUNT(
                DISTINCT z_purchase.`company_id`
              ) AS total_companies,
              COUNT(DISTINCT z_offer.`user_id`) AS managers,
              COUNT(
                DISTINCT IF(
                  IF(
                    offer.winner,
                    offer.winner,
                    z_offer.winner
                  ),
                  z_purchase.company_id,
                  NULL
                )
              ) AS my_companies,
              SUM(z_purchase.`total`) AS total_taken
            FROM
              z_company_user
              INNER JOIN z_offer
                ON z_offer.`company_id` = z_company_user.`company_id`
                AND z_offer.pid IS NULL
              INNER JOIN z_user
                ON z_user.id = z_offer.`user_id`
              INNER JOIN z_product
                ON z_product.id = z_offer.`product_id`
              INNER JOIN z_purchase
                ON z_purchase.id = z_product.`purchase_id`
              INNER JOIN z_company
               ON z_company.id=z_purchase.company_id
              LEFT JOIN z_offer offer
                ON offer.pid = z_offer.id
                AND offer.`id` =
                (SELECT
                  MAX(id)
                FROM
                  z_offer
                WHERE z_offer.pid = `ofid`)
            WHERE z_company_user.`user_id` = :user_id
              ' . $whereSql . '
            GROUP BY z_purchase.id
            ';
            //echo $sql;
            $command = $connection->createCommand($sql);
            if ($params['market_id'] > 0)
                $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
            if ($params['company_id'] > 0)
                $command->bindParam(":company_id", $params['company_id'], PDO::PARAM_INT);
            if ($params['reporttype_id'] == 10 && $params['user_id'])
                $command->bindParam(":user_id2", $params['user_id'], PDO::PARAM_INT);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $result = $command->queryAll();
        }
        return $result;
    }

    public function getHistory($params = array())
    {
        $connection = Yii::app()->db;
        $whereSql = '';
        $whereSql2 = '';
        if ($params['purchase_id']){
            $whereSql .= ' AND z_history.purchase_id=:purchase_id';
            $whereSql2 .= ' AND z_product.purchase_id=:purchase_id';
        }
        $sql = "
        (SELECT
          CONCAT('h', z_history.id) AS id,
          z_history.historytype_id,
          CASE z_history.`historytype_id`
            WHEN 1 THEN CONCAT(z_company.title,' создал торги №',z_history.`purchase_id`)
            WHEN 2 THEN CONCAT(z_company.title,' внес изменения в торги')
            WHEN 3 THEN CONCAT(z_company.title,' закрыл торги')
            WHEN 4 THEN CONCAT(z_company.title,' открыл редукцион')
            WHEN 5 THEN CONCAT(z_company.title,' закрыл редукцион')
          END AS txt,
          DATE_FORMAT(z_history.date_create,'%d.%m.%Y') AS `date`,
          DATE_FORMAT(z_history.date_create,'%h:%i:%s') AS `time`,
          CONCAT(z_user.`first_name`,' ',z_user.`last_name`) AS manager,
          z_history.date_create
        FROM
          z_history
        INNER JOIN z_user
          ON z_user.id = z_history.user_id
        INNER JOIN z_company
          ON z_company.id=z_history.`company_id`
        WHERE 1=1 /*z_purchase.user_id=:user_id*/ " . $whereSql . ")
        UNION
        (SELECT
          CONCAT('o', z_offer.id) AS id,
          6 AS historytype_id,
          CONCAT(CAST(z_company.title AS CHAR(255)),IF(z_offer.pid,' обновил свое предложение: ',' сделал новое предложение: '),CAST(z_tag.title AS CHAR(255)),' по цене ', z_offer.price,' в количестве ', z_offer.amount,' ',z_unit.`title`,IF(z_offer.`delivery`,' с доставкой ',' без доставки ')) AS txt,
          DATE_FORMAT(z_offer.date_create,'%d.%m.%Y') AS `date`,
          DATE_FORMAT(z_offer.date_create,'%h:%i:%s') AS `time`,
          CONCAT(z_user.`first_name`,' ',z_user.`last_name`) AS manager,
          z_offer.date_create
        FROM
          z_offer
          INNER JOIN z_user
            ON z_user.id = z_offer.user_id
          INNER JOIN z_company
            ON z_company.id = z_offer.company_id
          INNER JOIN z_tag
            ON z_tag.id = z_offer.tag_id
          INNER JOIN z_product
            ON z_product.id = z_offer.`product_id`
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.`purchase_id`
          INNER JOIN z_unit
            ON z_unit.`id` = z_product.`unit_id`
          WHERE 1=1 /*z_purchase.user_id=:user_id*/ " . $whereSql2 . ")
          ORDER BY `date_create` DESC
        ";
        $command = $connection->createCommand($sql);
        if ($params['purchase_id'] > 0)
            $command->bindParam(":purchase_id", $params['purchase_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        //print_r($result);
        return $result;
    }

    public function getNewPurchases($params = array())
    {
        $today=date('Y-m-d');
        $yesterday=date('Y-m-d', strtotime(' -1 day'));
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_tag.title AS product,
          z_product.id,
          z_product.amount,
          z_unit.title2 as unit,
          z_purchase.address,
          z_purchase.market_id,
          z_product.id as product_id
        FROM
          z_purchase
        INNER JOIN z_product
          ON z_product.`purchase_id` = z_purchase.id
        INNER JOIN z_tag
          ON z_tag.`id` = z_product.`tag_id`
        INNER JOIN z_unit
          ON z_unit.`id` = z_product.`unit_id`
        WHERE z_purchase.`purchasestate_id` = 2
          AND z_purchase.`date_close` > NOW()
          AND z_purchase.date_create BETWEEN "'.$yesterday.' 00:00:00" AND "'.$today.' 00:00:00"
        ORDER BY z_tag.title ASC';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    public function InviteToReduction($data,$date_reduction){
        $idArr=array();
        foreach($data as $offer){
            if($offer['reduction']==1 && $offer['pid']>0)
                $idArr[$offer['pid']]=$offer['pid'];
        }
        if(count($idArr)>0){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_user.email,
          z_user.first_name,
          z_user.last_name,
          z_product.purchase_id,
          z_company.title AS company,
          z_tag.title AS product,
          z_purchase.date_reduction
        FROM
          `z_offer`
        INNER JOIN z_user
          ON z_user.id = z_offer.`user_id`
        INNER JOIN z_product
          ON z_product.id = z_offer.`product_id`
        INNER JOIN z_tag
          ON z_tag.id=z_product.tag_id
        INNER JOIN z_purchase
          ON z_purchase.id = z_product.`purchase_id`
        INNER JOIN z_company
          ON z_company.id = z_purchase.`company_id`
        WHERE z_offer.id IN ('.implode(',',$idArr).')
          AND z_offer.`reduction` = 1';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
            if($result){
                $contr=Yii::app()->controller;
                $contr->layout="mail";
                foreach($result as $user){
                    $body =$contr->render('/mail/invite_reduction_email', array('user'=>$user,'date'=>$date_reduction), true);
                    $queue = new EmailQueue();
                    $queue->to_email = trim($user['email']);
                    $queue->subject = "Приглашение в редукцион";
                    $queue->from_email = 'support@zakupki-online.com';
                    $queue->from_name = 'Zakupki-online';
                    $queue->date_published = new CDbExpression('NOW()');
                    $queue->message = $body;
                    $queue->save();
                }
            }
        }
    }
    public function closeInform($param=array()){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          IF(offer.id, offer.id, z_offer.id) AS id,
          IF(offer.id, offer.pid, z_offer.id) AS pid,
          z_offer.id AS `ofid`,
          IF(offer.id,offer.product_id,z_offer.product_id) AS product_id,
          IF(offer.id,offer.price,z_offer.price) AS price,
          IF(offer.id,offer.delivery,z_offer.delivery) AS delivery,
          IF(offer.id,offer.amount,z_offer.amount) AS amount,
          IF(offer.id,offer.delay,z_offer.delay) AS delay,
          IF(offer.id,offer.winner,z_offer.winner) AS winner,
          z_tag.title,
          z_company.id AS company_id,
          z_company.title AS company,
          z_user.email,
          z_user.id AS user_id,
          z_user.first_name,
          z_user.last_name,
          company.title AS buyer,
          z_product.`purchase_id`,
          buyer_user.email AS buyer_email,
          buyer_user.first_name AS buyer_first_name,
          buyer_user.last_name AS buyer_last_name,
          z_unit.title AS unit
        FROM
          z_offer
          INNER JOIN z_user
            ON z_user.id = z_offer.user_id
          INNER JOIN z_product
            ON z_product.id = z_offer.`product_id`
          INNER JOIN z_unit
            ON z_unit.id=z_product.unit_id
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.`purchase_id`
          INNER JOIN z_user buyer_user
            ON buyer_user.id=z_purchase.user_id
          INNER JOIN z_company company
            ON company.id=z_purchase.company_id
          LEFT JOIN z_offer offer
            ON offer.pid = z_offer.id AND offer.`id` = (SELECT MAX(id) FROM z_offer WHERE z_offer.pid = `ofid`)
          LEFT JOIN z_tag
            ON z_tag.id = z_offer.tag_id
          LEFT JOIN z_company
            ON z_company.id = z_offer.company_id
        WHERE z_offer.pid IS NULL
                AND z_purchase.id = :purchase_id';
        $command = $connection->createCommand($sql);
        $command->bindParam(":purchase_id", $param['purchase_id'], PDO::PARAM_INT);
        $result = $command->queryAll();
        $data=array();
        $winners=array();
        foreach($result as $offer){
            if($offer['winner']){
                $data['winners'][$offer['user_id']]=array(
                    'user_id'=>$offer['user_id'],
                    'email'=>$offer['email'],
                    'first_name'=>$offer['first_name'],
                    'last_name'=>$offer['last_name'],
                    'buyer'=>$offer['buyer'],
                    'purchase_id'=>$offer['purchase_id'],
                    'winner'=>$offer['winner'],
                    'buyer_email'=>$offer['buyer_email'],
                    'buyer_first_name'=>$offer['buyer_first_name'],
                    'buyer_last_name'=>$offer['buyer_last_name']);
            }else{
                $data['loosers'][$offer['user_id']]=array(
                    'user_id'=>$offer['user_id'],
                    'email'=>$offer['email'],
                    'first_name'=>$offer['first_name'],
                    'last_name'=>$offer['last_name'],
                    'buyer'=>$offer['buyer'],
                    'purchase_id'=>$offer['purchase_id'],
                    'winner'=>$offer['winner'],
                    'buyer_email'=>$offer['buyer_email'],
                    'buyer_first_name'=>$offer['buyer_first_name'],
                    'buyer_last_name'=>$offer['buyer_last_name']);
            }
            if($offer['winner'])
            $winners[$offer['purchase_id']][$offer['id']]=array('title'=>$offer['title'],'price'=>$offer['price'],'amount'=>$offer['amount'],'unit'=>$offer['unit']);
            $data[$offer['user_id']]['offers'][$offer['id']]=array('title'=>$offer['title'],'price'=>$offer['price'],'amount'=>$offer['amount'],'unit'=>$offer['unit'], 'winner'=>$offer['winner']);
        }

        $contr=Yii::app()->controller;
        $contr->layout="mail";
        if(isset($data['winners']))
        foreach($data['winners'] as $user){
            if($user['winner']){
                $body =$contr->render('/mail/purchase_winner', array('user'=>$user,'offers'=>$data[$user['user_id']]['offers']), true);
                $queue = new EmailQueue();
                $queue->to_email = trim($user['email']);
                $queue->subject = "Завершение торгов: победа";
                $queue->from_email = 'support@zakupki-online.com';
                $queue->from_name = 'Zakupki-online';
                $queue->date_published = new CDbExpression('NOW()');
                $queue->message = $body;
                $queue->save();
            }
        }
        if(isset($data['loosers']))
        foreach($data['loosers'] as $user){
                if(!isset($data['winners'][$user['user_id']])){
                    $body =$contr->render('/mail/purchase_looser', array('user'=>$user,'offers'=>$data[$user['user_id']]['offers'],'winners'=>$winners), true);
                    $queue = new EmailQueue();
                    $queue->to_email = trim($user['email']);
                    $queue->subject = "Завершение торгов";
                    $queue->from_email = 'support@zakupki-online.com';
                    $queue->from_name = 'Zakupki-online';
                    $queue->date_published = new CDbExpression('NOW()');
                    $queue->message = $body;
                    $queue->save();
                }
        }
    }
    public function getClosedReductionUsers($id){
        $connection = Yii::app()->db;
        $sql = 'SELECT
          z_user.email,
          z_user.first_name,
          z_user.last_name,
          z_product.`purchase_id`,
          z_company.title AS company
        FROM
          z_offer
          INNER JOIN z_product
            ON z_product.id = z_offer.`product_id`
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.`purchase_id`
          INNER JOIN z_company
            ON `z_company`.id = z_purchase.`company_id`
          INNER JOIN z_user
            ON z_user.id = z_offer.user_id
        WHERE z_offer.`reduction` = 1
                AND z_offer.`pid` IS NULL
                AND z_product.`reductionstate` = 2
                AND z_purchase.purchasestate_id= 5
                AND z_product.`purchase_id` = :purchase_id
        GROUP BY z_user.id';
        $command = $connection->createCommand($sql);
        $command->bindParam(":purchase_id", $id, PDO::PARAM_INT);
        $result = $command->queryAll();
        if($result)
            return $result;
    }
    public function getActiveReductions($id){
        $connection = Yii::app()->db;
        $sql ="
          SELECT
            z_product.*
          FROM
            z_product
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.`purchase_id`
          WHERE z_purchase.id = :purchase_id
            AND z_purchase.purchasestate_id = 3
            AND z_product.`reductionstate`=1
          GROUP BY z_purchase.id
          ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":purchase_id",$id, PDO::PARAM_INT);
        $result = $command->queryRow();
        if($result)
            return $result;
    }
    public function getOverdues(){
        $connection = Yii::app()->db;
        $sql ="
        SELECT
          z_purchase.user_id,
          z_user.email,
          z_user.first_name,
          z_user.last_name,
          z_purchase.id AS purchase_id,
          z_purchase.date_close,
          z_market.title AS market
        FROM
          z_purchase
        INNER JOIN z_user
          ON z_user.id=z_purchase.user_id AND z_user.subscribe_regular=1 AND z_user.status=1
        INNER JOIN z_market
        ON z_market.id=z_purchase.market_id
        WHERE z_purchase.`date_close` < NOW()
          AND z_purchase.purchasestate_id = 2
          ";
        $command = $connection->createCommand($sql);
        //$command->bindParam(":purchase_id",$id, PDO::PARAM_INT);
        $result = $command->queryAll();
        $userArr=array();
        if($result)
        {
            foreach($result as $row){
                $userArr[$row['user_id']]['email']=$row['email'];
                $userArr[$row['user_id']]['first_name']=$row['first_name'];
                $userArr[$row['user_id']]['last_name']=$row['last_name'];
                $userArr[$row['user_id']]['purchases'][$row['purchase_id']]=array('market'=>$row['market'],'date_close'=>$row['date_close']);
            }
        }
        if(count($userArr)>0)
            return $userArr;
    }
    public function checkProductActive($params=array()){
        $connection = Yii::app()->db;
        $sql ="
        SELECT
          z_purchase.id
        FROM
          z_product
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.purchase_id
        WHERE z_product.id = :product_id
          AND z_purchase.`purchasestate_id` IN (2, 3)
          AND z_purchase.`date_close` > NOW()
        ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":product_id",$params['product_id'], PDO::PARAM_INT);
        $result = $command->queryRow();
        if($result)
            return true;
        else
            return false;
    }
    public function selectMax(){
        $connection = Yii::app()->db;
        $sql ="
        SELECT
          max(z_purchase.id) AS id
        FROM
          z_purchase
        WHERE z_purchase.`purchasestate_id` IN (2)
          AND z_purchase.`date_close` > NOW()
        ";
        $command = $connection->createCommand($sql);
        $result = $command->queryRow();
        if($result)
            return $result['id'];
    }
    protected function afterSave(){
      /* if(self::getisNewRecord() && $this->purchasestate_id==2){
            Yii::app()->cache->set('new_purchase_id', $this->id);
       }*/
        parent::afterSave();
    }
    public function getAdminPurchaseProducts($purchase_id){
        $data=array();
        $connection = Yii::app()->db;
        $sql ="
        SELECT
          z_product.id,
          z_product.amount,
          z_tag.title,
          z_unit.title as unit
        FROM
          z_product
        INNER JOIN z_tag
          ON z_tag.id=z_product.tag_id
        INNER JOIN z_unit
        ON z_unit.id=z_product.unit_id
        WHERE z_product.purchase_id=:purchase_id
        ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":purchase_id",$purchase_id, PDO::PARAM_INT);
        $result = $command->queryAll();
        $data['products']=$result;

        $productIds=array();
        foreach($result as $product)
            $productIds[$product['id']]=$product['id'];

        $sql = '
        SELECT
          IF(offer.id, offer.id, z_offer.id) AS id,
          IF(offer.id, offer.pid, z_offer.id) AS pid,
          z_offer.id AS `ofid`,
          IF(offer.id,offer.product_id,z_offer.product_id) AS product_id,
          IF(offer.id,offer.price,z_offer.price) AS price,
          IF(offer.id,offer.delivery,z_offer.delivery) AS delivery,
          IF(offer.id,offer.amount,z_offer.amount) AS amount,
          IF(offer.id,offer.delay,z_offer.delay) AS delay,
          IF(offer.id,offer.winner,z_offer.winner) AS winner,
          z_tag.title,
          z_company.id AS company_id,
          z_company.title AS company,
          z_user.email,
          z_user.id AS user_id,
          z_user.first_name,
          z_user.last_name,
          company.title AS buyer,
          z_product.`purchase_id`,
          buyer_user.email AS buyer_email,
          buyer_user.first_name AS buyer_first_name,
          buyer_user.last_name AS buyer_last_name,
          z_unit.title2 AS unit
        FROM
          z_offer
          INNER JOIN z_user
            ON z_user.id = z_offer.user_id
          INNER JOIN z_product
            ON z_product.id = z_offer.`product_id`
          INNER JOIN z_unit
            ON z_unit.id=z_product.unit_id
          INNER JOIN z_purchase
            ON z_purchase.id = z_product.`purchase_id`
          INNER JOIN z_user buyer_user
            ON buyer_user.id=z_purchase.user_id
          INNER JOIN z_company company
            ON company.id=z_purchase.company_id
          LEFT JOIN z_offer offer
            ON offer.pid = z_offer.id AND offer.`id` = (SELECT MAX(id) FROM z_offer WHERE z_offer.pid = `ofid`)
          LEFT JOIN z_tag
            ON z_tag.id = z_offer.tag_id
          LEFT JOIN z_company
            ON z_company.id = z_offer.company_id
        WHERE z_offer.pid IS NULL
                AND z_product.id in('.implode(',',$productIds).')';
        $command = $connection->createCommand($sql);
        $result2 = $command->queryAll();
        $offers=array();
        foreach($result2 as $offer){
            $offers[$offer['product_id']][$offer['id']]=$offer;
        }
        $data['offers']=$offers;


        if($data)
            return $data;
    }

}