<?php
/**
 * This is the model class for table "{{offer}}".
 *
 * The followings are the available columns in table '{{offer}}':
 * @property integer $id
 * @property string $title
 * @property integer $product_id
 * @property integer $user_id
 * @property double $price
 * @property integer $delivery
 * @property integer $delay
 * @property string $date_create
 *
 * @method Offer active
 * @method Offer cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Offer indexed($column = 'id')
 * @method Offer language($lang = null)
 * @method Offer select($columns = '*')
 * @method Offer limit($limit, $offset = 0)
 * @method Offer sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property User $user
 */
class Offer extends BaseActiveRecord
{

    public $purchase_id;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Offer the static model class
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
        return '{{offer}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('product_id, user_id, price, tag_id', 'required'),
            array('reduction_pass, reduction_passed, reduction_level, product_id, winner, reduction, user_id, delivery, delay, tag_id, exclude_lose, delivered, offerpayed, creditpayed, payment', 'numerical', 'integerOnly' => true),
            array('price,credit_percent,amount', 'numerical'),
            array('title', 'length', 'max' => 255),
            array('product_id', 'exist', 'className' => 'Product', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('comment,price_reduce,pid', 'safe'),
            array('purchase_id, price_reduce, reduction_level, reduction_pass, reduction_passed, id, pid, title, product_id, user_id, tag_id, price, comment, amount, delivery, delay, date_create, exclude_lose', 'safe', 'on' => 'search'),
            array('pid', 'default', 'setOnEmpty' => true, 'value' => null),
        ));
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'tag' => array(self::BELONGS_TO, 'Tag', 'tag_id'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pid' => 'PID',
            'tag_id' => Yii::t('backend', 'Title'),
            'product_id' => Yii::t('backend', 'Product'),
            'purchase_id' => Yii::t('backend', 'Purchase'),
            'user_id' => Yii::t('backend', 'User'),
            'company_id' => Yii::t('backend', 'Company'),
            'amount' => Yii::t('backend', 'Amount'),
            'price' => Yii::t('backend', 'Price'),
            'price_reduce' => Yii::t('backend', 'Price reduce'),
            'place' => Yii::t('backend', 'Place'),
            'delivery' => Yii::t('backend', 'Delivery'),
            'delay' => Yii::t('backend', 'Delay'),
            'winner' => Yii::t('backend', 'Winner'),
            'reduction' => Yii::t('backend', 'Reduction'),
            'date_create' => Yii::t('backend', 'Date Create'),
            'comment' => Yii::t('backend', 'Comment'),
            'exclude_lose' => Yii::t('backend', 'Exclude Lose'),
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
        $criteria->compare('t.pid', $this->pid);
        //$criteria->compare('t.tag', $this->tag_id);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.product_id', $this->product_id);
        $criteria->compare('product.purchase_id', $this->purchase_id);
        $criteria->compare('t.user_id', $this->user_id);
        $criteria->compare('t.price', $this->price);
        $criteria->compare('t.amount', $this->price);
        $criteria->compare('t.delivery', $this->delivery);
        $criteria->compare('t.delay', $this->delay);
        $criteria->compare('t.comment', $this->comment);
        $criteria->compare('t.winner', $this->winner);
        $criteria->compare('t.reduction', $this->reduction);
        $criteria->compare('t.date_create', $this->date_create, true);

        $criteria->with = array('product', 'user', 'tag');

        return parent::searchInit($criteria);
    }

    public function findMyOffers($param = array())
    {
        $offers = '';
        $WHERE = '';
        if (isset($param['product_id']) > 0) {
            $WHERE .= ' AND z_product.id=:product_id';
        }
        if (isset($param['market_id']) > 0) {
            $WHERE .= ' AND z_market.id=:market_id';
        }
        if (isset($param['company_id']) > 0) {
            $WHERE .= ' AND z_company.id=:company_id';
        }
        if (isset($param['tag_id']) > 0) {
            $WHERE .= ' AND z_tag.id=:tag_id';
        }
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_product.id,
          z_tag.title,
          z_market.title AS market,
          z_company.title AS company,
          z_city.title AS city,
          z_product.amount,
          z_product.purchase_id,
          z_product.id AS product_id,
          z_purchase.address,
          z_purchase.comment,
          z_purchase.date_create,
          z_purchase.date_deliver,
          z_purchase.date_close,
          z_purchase.delay,
          z_purchase.company_id,
          z_purchase.usecredit,
          z_purchase.creditpercent,
          IF(z_purchase.date_close<NOW(),5,z_purchase.purchasestate_id) AS purchasestate_id,
          z_unit.title AS unit,
          z_unit.title2 AS unit2,
          z_unit.title3 AS unit3,
          /*MIN(if(offers.delivery,offers.price,null)) AS minprice,
          MIN(if(offers.delivery,null,offers.price)) AS minprice_delivery,*/
          z_product.minprice AS minprice_delivery,
          z_product.deliverminprice AS minprice,
          z_user.first_name,
          z_user.name,
          z_user.last_name,
          z_finance.fincompany_id
        FROM z_product
        INNER JOIN z_purchase
            ON z_purchase.id=z_product.purchase_id
        INNER JOIN z_user
            ON z_user.id=z_purchase.user_id
        INNER JOIN z_offer
            ON z_offer.product_id=z_product.id AND z_offer.user_id=:user_id
        LEFT JOIN z_offer as offers
            ON offers.product_id=z_offer.product_id
        LEFT JOIN z_tag
            ON z_tag.id=z_product.tag_id
        LEFT JOIN z_market
            ON z_market.id=z_purchase.market_id
        LEFT JOIN z_company
            ON z_company.id=z_purchase.company_id
        LEFT JOIN z_finance
            ON z_finance.company_id=z_company.id
        LEFT JOIN z_city
            ON z_city.id=z_company.city_id
        LEFT JOIN z_unit
            ON z_unit.id=z_product.unit_id
        WHERE z_purchase.purchasestate_id IN (2,5) /*AND z_purchase.date_close>NOW()*/ ' . $WHERE . '
        GROUP BY z_product.id
        ORDER by z_purchase.purchasestate_id ASC, z_purchase.date_create DESC
        ';
        $command = $connection->createCommand($sql);
        if (isset($param['product_id']) > 0)
            $command->bindParam(":product_id", $param['product_id'], PDO::PARAM_INT);
        if (isset($param['market_id']) > 0)
            $command->bindParam(":market_id", $param['market_id'], PDO::PARAM_INT);
        if (isset($param['company_id']) > 0)
            $command->bindParam(":company_id", $param['company_id'], PDO::PARAM_INT);
        if (isset($param['tag_id']) > 0)
            $command->bindParam(":tag_id", $param['tag_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $products = $command->queryAll();
        if (count($products) > 0) {
            $result['products'] = $products;
            foreach ($products as $p)
                $productsArr[$p['id']] = $p['id'];
            $sql = '
           SELECT 
			IF(offer.id,offer.id,z_offer.id) AS id,
			IF(offer.id,offer.pid,z_offer.id) AS pid,
			z_offer.id AS ofid,
			IF(offer.id,offer.product_id,z_offer.product_id) AS product_id,
			IF(offer.id,offer.price,z_offer.price) AS price,
			IF(offer.id,offer.delivery,z_offer.delivery) AS delivery,
			IF(offer.id,offer.amount,z_offer.amount) AS amount,
			IF(offer.id,offer.delay,z_offer.delay) AS delay,
			IF(offer.id,offer.date_create,z_offer.date_create) AS date_create,
			IF(offer.id,offer.place,z_offer.place) AS place,
			IF(offer.id,offer.totaloffers,z_offer.totaloffers) AS totaloffers,
			IF(offer.id,offer.fincompany_id,z_offer.fincompany_id) AS fincompany_id,
			IF(offer.id,offer.credit_percent,z_offer.credit_percent) AS credit_percent,
			IF(offer.id,offer.comment,z_offer.comment) AS comment,
			IF(tag.id,tag.id,z_tag.id) AS tag_id,
            IF(tag.id,tag.title,z_tag.title) AS title
			FROM
			  z_offer 
			LEFT JOIN z_offer offer
			  ON offer.pid=z_offer.id AND offer.`id`=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`ofid`)
			LEFT JOIN z_tag
              ON z_tag.id=z_offer.tag_id
            LEFT JOIN z_tag tag
              ON tag.id=offer.tag_id
            WHERE z_offer.pid IS NULL AND z_offer.user_id=:user_id AND z_offer.product_id IN(' . implode(',', $productsArr) . ')
			ORDER BY z_offer.date_create ASC
            ';
            //echo $sql;
            $command = $connection->createCommand($sql);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $offerslist = $command->queryAll();
            if (count($offerslist) > 0) {
                foreach ($offerslist as $of){
                    $offers[$of['product_id']][$of['id']] = $of;
                    $offerFileArr[$of['pid']]=$of['pid'];
                }
                $result['offers'] = $offers;

                if(count($offerFileArr)>0){
                    $sql_file='
                    SELECT
                    z_offer_file.id,
                    z_offer_file.offer_id,
                    z_offer_file.file_id,
                    z_file.file AS file_name,
                    concat(z_file.path,"/",z_file.file) AS file
                    FROM
                    z_offer_file
                    INNER JOIN z_file
                    ON z_file.id=z_offer_file.file_id
                    WHERE z_offer_file.offer_id in('.implode(",",$offerFileArr).')
                    ';
                    $command = $connection->createCommand($sql_file);
                    $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
                    $filelist = $command->queryAll();
                    $offerFiles=array();
                    if($filelist)
                    foreach($filelist as $file)
                    $offerFiles[$file['offer_id']][$file['file_id']]=array('file'=>$file['file'],'file_name'=>$file['file_name']);
                    $result['offerfiles'] = $offerFiles;
                }
            }
        }
        if (isset($result))
            return $result;
    }

    public function findMyReductions($param = array())
    {
        $offers = '';
        $WHERE = '';
        if (isset($param['product_id']) > 0) {
            $WHERE .= ' AND z_product.id=:product_id';
        }
        if (isset($param['market_id']) > 0) {
            $WHERE .= ' AND z_market.id=:market_id';
        }
        if (isset($param['company_id']) > 0) {
            $WHERE .= ' AND z_company.id=:company_id';
        }
        if (isset($param['tag_id']) > 0) {
            $WHERE .= ' AND z_tag.id=:tag_id';
        }


        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_product.id,
          z_tag.title,
          z_market.title AS market,
          z_company.title AS company,
          z_city.title AS city,
          z_product.id AS product_id,
          z_product.amount,
          z_product.purchase_id,
          z_product.comment,
          z_purchase.date_create,
          z_purchase.date_deliver,
          z_purchase.date_close,
          TIME_TO_SEC(TIMEDIFF(z_purchase.date_reduction,NOW())) AS date_reduction,
          TIME_TO_SEC(TIMEDIFF(z_product.check_date,NOW())) AS check_date_seconds,
          z_purchase.delay,
          z_purchase.purchasestate_id,
          z_unit.title AS unit,
          z_unit.title2 AS unit2,
          z_unit.title3 AS unit3,
          MIN(if(z_offer.id,offers.price,offers.price)) AS minprice,
          z_product.check_date,
          z_product.reductionstate
        FROM z_product
        INNER JOIN z_purchase
            ON z_purchase.id=z_product.purchase_id
        INNER JOIN z_offer
            ON z_offer.product_id=z_product.id AND z_offer.user_id=:user_id AND z_offer.reduction=1 AND z_offer.reduction_pass=0
        LEFT JOIN z_offer as offers
            ON offers.product_id=z_offer.product_id AND z_offer.id=offers.pid
        LEFT JOIN z_tag
            ON z_tag.id=z_product.tag_id
        LEFT JOIN z_market
            ON z_market.id=z_purchase.market_id
        LEFT JOIN z_company
            ON z_company.id=z_purchase.company_id
        LEFT JOIN z_city
            ON z_city.id=z_company.city_id
        LEFT JOIN z_unit
            ON z_unit.id=z_product.unit_id
        WHERE z_purchase.purchasestate_id=3 ' . $WHERE . '
        GROUP BY z_product.id
        ORDER by z_purchase.purchasestate_id DESC
        ';
        $command = $connection->createCommand($sql);
        if (isset($param['product_id']) > 0)
            $command->bindParam(":product_id", $param['product_id'], PDO::PARAM_INT);
        if (isset($param['market_id']) > 0)
            $command->bindParam(":market_id", $param['market_id'], PDO::PARAM_INT);
        if (isset($param['company_id']) > 0)
            $command->bindParam(":company_id", $param['company_id'], PDO::PARAM_INT);
        if (isset($param['tag_id']) > 0)
            $command->bindParam(":tag_id", $param['tag_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $products = $command->queryAll();

        if (count($products) > 0) {
            $result['products'] = $products;
            foreach ($products as $p)
                $productsArr[$p['id']] = $p['id'];
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
			IF(offer.id,offer.date_create,z_offer.date_create) AS date_create,
			IF(offer.id,offer.user_id,z_offer.user_id) AS user_id,
			IF(offer.id,offer.place,z_offer.place) AS place,
			IF(offer.id,offer.totaloffers,z_offer.totaloffers) AS totaloffers,
			IF(offer.id,offer.reduction_level,z_offer.reduction_level) AS reduction_level,
			IF(offer.id,offer.company_id,z_offer.company_id) AS company_id,
			IF(offer.id,offer.price_reduce,z_offer.price_reduce) AS price_reduce,
			IF(tag.id,tag.id,z_tag.id) AS tag_id,
            IF(tag.id,tag.title,z_tag.title) AS title,
            z_offer.reduction_state,
            z_offer.reduction_pass,
            z_offer.reduction_passed,
            z_offer.reduction_place
			FROM
			  z_offer
			LEFT JOIN z_offer offer
			  ON offer.pid=z_offer.id AND offer.`id`=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`ofid`)
			LEFT JOIN z_tag
              ON z_tag.id=z_offer.tag_id
            LEFT JOIN z_tag tag
              ON tag.id=offer.tag_id
			WHERE z_offer.pid IS NULL AND z_offer.reduction=1 AND z_offer.product_id IN(' . implode(',', $productsArr) . ')
			ORDER BY z_offer.reduction_place ASC
            ';
            //echo $sql;
            //echo implode(',',$productsArr);
            $command = $connection->createCommand($sql);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $offerslist = $command->queryAll();
            if (count($offerslist) > 0) {
                foreach ($offerslist as $of)
                    $offers[$of['product_id']][$of['id']] = $of;
                $result['offers'] = $offers;
            }
            //print_r($offers);
        }
        if (isset($result))
            return $result;
    }

    public function findMyReduction($product_id = null)
    {
        $offers = '';
        $WHERE = '';
        if ($product_id > 0) {
            $WHERE = 'AND z_product.id=:product_id';
        }
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_product.id,
          z_tag.title,
          z_market.title AS market,
          z_company.title AS company,
          z_city.title AS city,
          z_product.id AS product_id,
          z_product.amount,
          z_product.purchase_id,
          z_product.comment,
          z_purchase.date_create,
          z_purchase.date_deliver,
          z_purchase.date_close,
          z_purchase.delay,
          z_purchase.purchasestate_id,
          TIME_TO_SEC(TIMEDIFF(z_purchase.date_reduction,NOW())) AS date_reduction,
          TIME_TO_SEC(TIMEDIFF(z_product.check_date,NOW())) AS check_date_seconds,
          z_unit.title AS unit,
          z_unit.title2 AS unit2,
          z_unit.title3 AS unit3,
          MIN(offers.price) AS minprice,
          z_product.check_date,
          z_product.reductionstate,
          UNIX_TIMESTAMP(z_product.check_date) as seconds_left
        FROM z_product
        INNER JOIN z_purchase
            ON z_purchase.id=z_product.purchase_id
        INNER JOIN z_offer
            ON z_offer.product_id=z_product.id AND z_offer.user_id=:user_id AND z_offer.reduction=1
        LEFT JOIN z_offer as offers
            ON offers.product_id=z_offer.product_id
        LEFT JOIN z_tag
            ON z_tag.id=z_product.tag_id
        LEFT JOIN z_market
            ON z_market.id=z_purchase.market_id
        LEFT JOIN z_company
            ON z_company.id=z_purchase.company_id
        LEFT JOIN z_city
            ON z_city.id=z_company.city_id
        LEFT JOIN z_unit
            ON z_unit.id=z_product.unit_id
        WHERE z_purchase.purchasestate_id=3 ' . $WHERE . ' AND z_offer.reduction_pass=0
        GROUP BY z_product.id
        ORDER by z_purchase.purchasestate_id ASC
        ';
        //echo $sql;
        $command = $connection->createCommand($sql);
        if ($product_id > 0)
            $command->bindParam(":product_id", $product_id, PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $products = $command->queryAll();

        //print_r($products);

        if (count($products) > 0) {
            $result['products'] = $products;
            foreach ($products as $p)
                $productsArr[$p['id']] = $p['id'];
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
			IF(offer.id,offer.date_create,z_offer.date_create) AS date_create,
			IF(offer.id,offer.user_id,z_offer.user_id) AS user_id,
			IF(offer.id,offer.place,z_offer.place) AS place,
			IF(offer.id,offer.totaloffers,z_offer.totaloffers) AS totaloffers,
			IF(offer.id,offer.reduction_level,z_offer.reduction_level) AS reduction_level,
			IF(offer.id,offer.company_id,z_offer.company_id) AS company_id,
			IF(offer.id,offer.price_reduce,z_offer.price_reduce) AS price_reduce,
			IF(tag.id,tag.id,z_tag.id) AS tag_id,
            IF(tag.id,tag.title,z_tag.title) AS title,
            z_offer.reduction_state,
            z_offer.reduction_pass,
            z_offer.reduction_passed,
            z_offer.reduction_place
			FROM
			  z_offer
			LEFT JOIN z_offer offer
			  ON offer.pid=z_offer.id AND offer.`id`=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`ofid`)
			LEFT JOIN z_tag
              ON z_tag.id=z_offer.tag_id
            LEFT JOIN z_tag tag
              ON tag.id=offer.tag_id
			WHERE z_offer.pid IS NULL AND z_offer.reduction=1 AND z_offer.product_id IN(' . implode(',', $productsArr) . ')
			ORDER BY z_offer.reduction_place ASC
            ';
            //echo implode(',',$productsArr);
            $command = $connection->createCommand($sql);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $offerslist = $command->queryAll();
            if (count($offerslist) > 0) {
                foreach ($offerslist as $of)
                    $offers[$of['product_id']][$of['id']] = $of;
                $result['offers'] = $offers;
            }
            //print_r($offers);
        }
        if (isset($result))
            return $result;
    }

    public function viewMyReduction($purchase_id = null)
    {
        $offers = '';
        $WHERE = '';
        if ($purchase_id > 0) {
            $WHERE = 'AND z_purchase.id=:purchase_id';
        }
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_product.id,
          z_tag.title,
          z_market.title AS market,
          z_company.title AS company,
          z_city.title AS city,
          z_product.id AS product_id,
          z_product.amount,
          z_product.purchase_id,
          z_product.comment,
          z_purchase.date_create,
          z_purchase.date_deliver,
          z_purchase.date_close,
          z_purchase.delay,
          z_purchase.purchasestate_id,
          z_unit.title AS unit,
          z_unit.title2 AS unit2,
          z_unit.title3 AS unit3,
          MIN(if(z_offer.id,offers.price,offers.price)) AS minprice,
          z_product.check_date,
          TIME_TO_SEC(TIMEDIFF(z_purchase.date_reduction,NOW())) AS date_reduction,
          TIME_TO_SEC(TIMEDIFF(DATE_ADD(z_product.check_date, INTERVAL 5 MINUTE),NOW())) AS check_date_left,
          z_purchase.date_reduction as date_reduction2
        FROM z_product
        INNER JOIN z_purchase
            ON z_purchase.id=z_product.purchase_id
        INNER JOIN z_offer
            ON z_offer.product_id=z_product.id AND z_offer.reduction=1
        LEFT JOIN z_offer as offers
            ON offers.product_id=z_offer.product_id  AND z_offer.id=offers.pid
        LEFT JOIN z_tag
            ON z_tag.id=z_product.tag_id
        LEFT JOIN z_market
            ON z_market.id=z_purchase.market_id
        LEFT JOIN z_company
            ON z_company.id=z_purchase.company_id
        LEFT JOIN z_city
            ON z_city.id=z_company.city_id
        LEFT JOIN z_unit
            ON z_unit.id=z_product.unit_id
        WHERE z_purchase.purchasestate_id=3 AND z_purchase.user_id=:user_id ' . $WHERE . '
        GROUP BY z_product.id
        ORDER by z_purchase.purchasestate_id DESC
        ';
        //echo $sql;
        $command = $connection->createCommand($sql);
        if ($purchase_id > 0)
            $command->bindParam(":purchase_id", $purchase_id, PDO::PARAM_INT);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $products = $command->queryAll();

        //print_r($products);

        if (count($products) > 0) {
            $result['products'] = $products;
            foreach ($products as $p)
                $productsArr[$p['id']] = $p['id'];
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
			IF(offer.id,offer.date_create,z_offer.date_create) AS date_create,
			IF(offer.id,offer.user_id,z_offer.user_id) AS user_id,
			IF(offer.id,offer.place,z_offer.place) AS place,
			IF(offer.id,offer.totaloffers,z_offer.totaloffers) AS totaloffers,
			IF(offer.id,offer.reduction_level,z_offer.reduction_level) AS reduction_level,
			IF(offer.id,offer.company_id,z_offer.company_id) AS company_id,
			IF(tag.id,tag.id,z_tag.id) AS tag_id,
            IF(tag.id,tag.title,z_tag.title) AS title,
            z_offer.reduction_state,
            z_offer.reduction_pass,
            z_offer.reduction_passed,
            z_offer.reduction_place,
            IF(offer.id,offer.price_reduce,z_offer.price_reduce) AS price_reduce,
            z_company.title AS company,
            z_city.title AS city
			FROM
			  z_offer
			LEFT JOIN z_company
			  ON z_company.id=z_offer.company_id
			LEFT JOIN z_city
			  ON z_city.id=z_company.city_id
			LEFT JOIN z_offer offer
			  ON offer.pid=z_offer.id AND offer.`id`=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`ofid`)
			LEFT JOIN z_tag
              ON z_tag.id=z_offer.tag_id
            LEFT JOIN z_tag tag
              ON tag.id=offer.tag_id
			WHERE z_offer.pid IS NULL AND z_offer.reduction=1 AND z_offer.product_id IN(' . implode(',', $productsArr) . ')
			ORDER BY z_offer.reduction_place ASC
            ';
            //echo $sql;
            //echo implode(',',$productsArr);
            $command = $connection->createCommand($sql);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $offerslist = $command->queryAll();
            if (count($offerslist) > 0) {
                foreach ($offerslist as $of)
                    $offers[$of['product_id']][$of['id']] = $of;
                $result['offers'] = $offers;
            }
            //print_r($offers);
        }
        if (isset($result))
            return $result;
    }

    public function resetOfferPlaces($params = array())
    {
        if (isset($params['product_id'])) {
            $command = Yii::app()->db->createCommand('CALL resetOfferPlaces(:product_id)');
            $command->bindParam(":product_id", $params['product_id'], PDO::PARAM_INT);
            $command->execute();

            Offer::model()->sendNewPlaces(array('product_id'=>$params['product_id']));

        }
    }

    public function newOfferPlace($params = array())
    {
        $connection = Yii::app()->db;
        $whereSql = '';
        if (isset($params['pid']))
            $whereSql .= ' AND z_offer.id!=:pid';
        if (isset($params['delivery']))
            $whereSql .= ' AND z_offer.delivery=:delivery';
        if (isset($params['price']))
            $having = ' HAVING price<:price';

        $sql = '
          SELECT
          COUNT(z_offer.id) as cnt,
          IF(offer.id, offer.id, z_offer.id) AS id,
          IF(offer.id, offer.pid, z_offer.id) AS pid,
          z_offer.id AS ofid,
          IF(
            offer.id,
            offer.product_id,
            z_offer.product_id
          ) AS product_id,
          SUM(IF(
          IF(
            offer.id,
            offer.price,
            z_offer.price
          )<:price,1,0)) AS place,
          IF(
            offer.id,
            offer.delivery,
            z_offer.delivery
          ) AS delivery
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
          LEFT JOIN z_tag
            ON z_tag.id = z_offer.tag_id
          LEFT JOIN z_tag tag
            ON tag.id = offer.tag_id
        WHERE z_offer.pid IS NULL
          AND z_offer.product_id = :product_id
          ' . $whereSql . '
        GROUP BY z_offer.`product_id`
        ORDER BY z_offer.date_create ASC
        ';
        $command = $connection->createCommand($sql);

        //$command->bindParam(":product_id", yii::app()->user->getId(), PDO::PARAM_INT);
        if (isset($params['pid']))
            $command->bindParam(":pid", $params['pid'], PDO::PARAM_INT);
        if (isset($params['delivery']))
            $command->bindParam(":delivery", $params['delivery'], PDO::PARAM_INT);
        $command->bindParam(":product_id", $params['product_id'], PDO::PARAM_INT);
        if (isset($params['price']))
            $command->bindParam(":price", $params['price'], PDO::PARAM_INT);
        $result = $command->queryRow();
        return $result;
    }
    public function sendNewPlaces($params=array()){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
			IF(offer.id,offer.id,z_offer.id) AS id,
			IF(offer.id,offer.pid,z_offer.id) AS pid,
			IF(offer.user_id,offer.user_id,z_offer.user_id) AS user_id,
			z_user.email,
			z_user.first_name,
			z_user.last_name,
			z_offer.id AS ofid,
			z_product.purchase_id,
			IF(offer.id,offer.product_id,z_offer.product_id) AS product_id,
			IF(offer.id,offer.price,z_offer.price) AS price,
			IF(offer.id,offer.delivery,z_offer.delivery) AS delivery,
			IF(offer.id,offer.amount,z_offer.amount) AS amount,
			IF(offer.id,offer.delay,z_offer.delay) AS delay,
			IF(offer.id,offer.place,z_offer.place) AS place,
			IF(offer.id,offer.totaloffers,z_offer.totaloffers) AS totaloffers,
			IF(tag.id,tag.id,z_tag.id) AS tag_id,
			IF(tag.id,tag.title,z_tag.title) AS title,
			z_market.title AS market
			FROM
			  z_offer
			INNER JOIN z_user
			ON z_user.id=`user_id` AND z_user.id!=:user_id
			INNER JOIN z_product
			ON z_product.id=`product_id`
			INNER JOIN z_purchase
			ON z_purchase.id=z_product.purchase_id
			INNER JOIN z_market
			ON z_market.id=z_purchase.market_id
			LEFT JOIN z_offer offer
			  ON offer.pid=z_offer.id AND offer.`id`=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`ofid`)
			LEFT JOIN z_tag
              ON z_tag.id=z_offer.tag_id
            LEFT JOIN z_tag tag
              ON tag.id=offer.tag_id
			WHERE z_offer.pid IS NULL AND z_offer.product_id=:product_id
			ORDER BY z_offer.date_create ASC
	    ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $command->bindParam(":product_id", $params['product_id'], PDO::PARAM_INT);
        $result = $command->queryAll();
        $usersArr=array();
        foreach($result as $offer){
            $usersArr[$offer['user_id']]['user_id']=$offer['user_id'];
            $usersArr[$offer['user_id']]['email']=$offer['email'];
            $usersArr[$offer['user_id']]['name']=$offer['first_name'].' '.$offer['last_name'];
            $usersArr[$offer['user_id']]['offers'][$offer['id']]=array(
                'id'=>$offer['id'],'place'=>$offer['place'],'totaloffers'=>$offer['totaloffers'],'title'=>$offer['title'],'market'=>$offer['market'],'purchase_id'=>$offer['purchase_id'],'product_id'=>$offer['product_id']);
        }
        if(count($usersArr)>0){
            foreach($usersArr as $user){
                $contr=Yii::app()->controller;
                $contr->layout="mail";
                $body=$contr->render('/mail/new_offer',array('user'=>$user),true);
                $queue = new EmailQueue();
                $queue->to_email = $user['email'];
                $queue->subject = "Изменения процента сравнения с рынком";
                $queue->from_email = 'support@zakupki-online.com';
                $queue->from_name = 'Zakupki-online';
                $queue->date_published = new CDbExpression('NOW()');
                $queue->message = $body;
                $queue->save();
            }
        }
        //return $usersArr;
    }
    public function getMyDeals(){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_offer.id AS offer_id,
          z_offer.`product_id`,
          z_offer.delivered,
          z_offer.offerpayed,
          z_offer.creditpayed,
          z_offer.`price`*z_offer.`amount` AS `total`,
          z_offer.`credit_percent`*z_offer.`amount` AS `offer_total`,
          (z_offer.`price`-z_offer.`credit_percent`)*z_offer.`amount` AS `credit_total`,
          z_product.`purchase_id`,
          z_purchase.`date_closed`,
          z_purchase.`date_deliver`,
          DATE_ADD(z_purchase.`date_deliver`, INTERVAL z_offer.delay DAY) AS `date_offerpay`,
          DATE_ADD(z_purchase.`date_deliver`, INTERVAL z_purchase.delay DAY) AS `date_creditpay`,
          DATE_ADD(z_purchase.`date_deliver`, INTERVAL z_offer.delay DAY) AS `date`,
          z_company.title AS company,
          z_city.title AS city,
          z_user.first_name,
          z_user.last_name
        FROM
          z_offer
          INNER JOIN z_product
            ON z_product.id = z_offer.`product_id`
          INNER JOIN z_purchase
            ON z_purchase.id=z_product.`purchase_id`
          INNER JOIN z_company_user
            ON z_company_user.`company_id`=z_purchase.`company_id`
          INNER JOIN z_company
            ON z_company.id=z_offer.company_id
          LEFT JOIN z_finance
            ON z_finance.`company_id`=z_purchase.`company_id`
          INNER JOIN z_city
            ON z_city.id=z_company.city_id
          INNER JOIN z_user
            ON z_user.id=z_offer.user_id
        WHERE z_offer.`fincompany_id` > 0
        AND z_company_user.`user_id`=:user_id
        AND z_purchase.`purchasestate_id`=4
        AND z_purchase.delay>z_offer.delay
        AND z_offer.winner=1
        AND z_offer.delay>0
        GROUP BY z_offer.id
        ORDER BY z_purchase.`date_deliver` ASC


        /*SELECT
          z_offer.id AS offer_id,
          z_offer.`product_id`,
          z_offer.delivered,
          z_offer.offerpayed,
          z_offer.creditpayed,
          z_offer.`price`*z_offer.`amount` AS `total`,
          z_offer.`credit_percent`*z_offer.`amount` AS `offer_total`,
          (z_offer.`price`-z_offer.`credit_percent`)*z_offer.`amount` AS `credit_total`,
          z_product.`purchase_id`,
          z_purchase.`date_closed`,
          z_purchase.`date_deliver`,
          DATE_ADD(z_purchase.`date_deliver`, INTERVAL z_offer.delay DAY) AS `date_offerpay`,
          DATE_ADD(z_purchase.`date_deliver`, INTERVAL z_purchase.delay DAY) AS `date_creditpay`,
          DATE_ADD(z_purchase.`date_deliver`, INTERVAL z_offer.delay DAY) AS `date`,
          z_company.title AS company,
          z_city.title AS city,
          z_user.first_name,
          z_user.last_name
        FROM
          z_offer
          INNER JOIN z_product
            ON z_product.id=z_offer.`product_id`
          INNER JOIN z_purchase
            ON z_purchase.id=z_product.`purchase_id`
          INNER JOIN z_company_user
            ON z_company_user=z_purchase.company_id
          INNER JOIN z_company
            ON z_company.id=z_offer.company_id
          INNER JOIN z_city
            ON z_city.id=z_company.city_id
          INNER JOIN z_user
            ON z_user.id=z_offer.user_id
          INNER JOIN z_company_user
          ON z_company_user.user_id=z_user.id
        WHERE z_purchase.`purchasestate_id`=4
        AND z_purchase.`user_id`=:user_id AND z_purchase.delay>z_offer.delay AND z_offer.delay>0
        ORDER BY z_purchase.`date_deliver` ASC*/
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        if($result)
            return $result;
    }
    /*public function beforeSave(){
        $product=Product::model()->with('purchase')->findByPk($this->product_id);
        if($product->purchase->delay<$this->delay){
            $finance=Finance::model()->findByAttributes(array('company_id'=>$this->company_id));
            if($finance->fincompany_id){
                $this->fincompany_id=$finance->fincompany_id;
                $this->credit_percent=$finance->percent;
            }
        }
        $this->fincompany_id=2035;
        parent::beforeSave();
    }*/
}