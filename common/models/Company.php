<?php
/**
 * This is the model class for table "{{company}}".
 *
 * The followings are the available columns in table '{{company}}':
 * @property integer $id
 * @property string $title
 * @property integer $companygroup_id
 * @property string $description
 * @property string $city
 * @property integer $region_id
 * @property integer $sort
 * @property integer $status
 *
 * @method Company active
 * @method Company cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Company indexed($column = 'id')
 * @method Company language($lang = null)
 * @method Company select($columns = '*')
 * @method Company limit($limit, $offset = 0)
 * @method Company sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Companygroup $companygroup
 * @property Region $region
 */
class Company extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Company the static model class
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
        return '{{company}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, companytype_id, companygroup_id, sort, egrpou, city_id', 'required'),
            array('companygroup_id, sort, status, mfo, billperiod_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            //array('city', 'length', 'max' => 128),
            array('description, account, bank, ndspayer, withnds, director, date_create', 'safe'),
            array('companygroup_id', 'exist', 'className' => 'Companygroup', 'attributeName' => 'id'),
            array('city_id', 'exist', 'className' => 'City', 'attributeName' => 'id'),

            array('id, title, companygroup_id, description, city_id, sort, status, egrpou, companytype_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'companygroup' => array(self::BELONGS_TO, 'Companygroup', 'companygroup_id'),
            'companyUsers' => array(self::HAS_MANY, 'CompanyUser', 'company_id', 'with' => 'user'),
            'companyMarkets' => array(self::HAS_MANY, 'MarketCompany', 'company_id', 'with' => 'market'),
            'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'cityname' => array(self::BELONGS_TO, 'City', 'city_id'),
            'companytype' => array(self::BELONGS_TO, 'Companytype', 'companytype_id'),
            'purchase' => array(self::HAS_MANY, 'Purchase', 'company_id'),
            //'region' => array(self::BELONGS_TO, 'Region', 'region_id'),
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
            'description' => Yii::t('backend', 'Description'),
            'city_id' => Yii::t('backend', 'City'),
            'companytype_id' => Yii::t('backend', 'Companytype'),
            'egrpou' => Yii::t('backend', 'Egrpou'),
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
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.companygroup_id', $this->companygroup_id);
        $criteria->compare('t.description', $this->description, true);
        $criteria->compare('t.city_id', $this->city_id, true);
        $criteria->compare('t.companytype_id',$this->companytype_id);
        $criteria->compare('t.egrpou', $this->egrpou);
        $criteria->compare('t.sort', $this->sort);
        $criteria->compare('t.status', $this->status);

        $criteria->with = array('companygroup');

        return parent::searchInit($criteria);
    }

    public function findRegisterByEgrpou($id)
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand('
        SELECT
            t.id AS RegisterForm_company_id,
            t.title AS RegisterForm_company_title,
            region.country_id AS RegisterForm_country_id,
            country.title AS RegisterForm_country_title,
            region.id AS RegisterForm_region_id,
            region.title AS RegisterForm_region_title,
            t.city_id AS RegisterForm_city_id,
            city.title AS RegisterForm_city_title,
            t.address AS RegisterForm_address
        FROM {{company}} AS t
        INNER JOIN z_city AS city
            ON city.id=t.city_id
        INNER JOIN z_region AS region
            ON region.id=city.region_id
        INNER JOIN z_country AS country
            ON country.id=region.country_id
        WHERE t.egrpou=:id');
        $command->bindParam(":id", $id, PDO::PARAM_INT);
        $row = $command->queryRow();
        if ($row)
            $command = $connection->createCommand('
        SELECT
        t.id,
        t.phonecode,
        t.phone,
        z_country.phonecode AS countrycode
        FROM z_phone as t
        INNER JOIN z_country
        ON z_country.id=t.country_id
        WHERE
        t.company_id=' . $row['RegisterForm_company_id'] . '
        ');
        $phones = $command->queryAll();
        if ($phones)
            $row['RegisterForm_phones'] = $phones;


        if ($row)
            $command = $connection->createCommand('
        SELECT
        market.id,
        market.title
        FROM z_market_company as t
        INNER JOIN z_market as market
        ON market.id=t.market_id
        WHERE
        t.company_id=' . $row['RegisterForm_company_id'] . '
        ');

        $markets = $command->queryAll();
        if ($markets)
            $row['RegisterForm_markets'] = $markets;

        return $row;
    }

    public function getAutocompany($title)
    {
        $title = "%$title%";
        $connection = Yii::app()->db;
        $sql = 'SELECT id, title AS value, title AS label FROM {{company}} WHERE title LIKE :title';
        $command = $connection->createCommand($sql);
        $command->bindParam(":title", $title, PDO::PARAM_STR);
        return $command->queryAll();
    }

    public function getRequestCompanies($title,$pageid)
    {
        $connection = Yii::app()->db;

        $title = "%$title%";

        if($pageid==4){
            $sql = '
              SELECT company_id FROM z_company_user WHERE z_company_user.status=1 AND z_company_user.user_id=:user_id
            ';
            $command = $connection->createCommand($sql);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $usercompanies = $command->queryColumn();

            $sql = 'SELECT
              z_company.id,
              z_company.`title` AS value,
              z_company.`title` AS label
            FROM
              z_purchase
              INNER JOIN z_company
                ON z_company.id = z_purchase.company_id
              INNER JOIN z_product
                ON z_product.purchase_id=z_purchase.id
              LEFT JOIN z_offer
                ON z_offer.product_id=z_product.id AND z_offer.user_id=:user_id
              INNER JOIN z_user_market
                ON z_user_market.market_id=z_purchase.market_id AND z_user_market.user_id=:user_id
              LEFT JOIN z_company_invite
                ON z_company_invite.purchase_id=z_purchase.id AND z_company_invite.company_id IN('.implode(',',$usercompanies).')
            WHERE z_purchase.date_close > NOW()
              AND z_purchase.purchasestate_id = 2
              AND z_offer.id IS NULL
              AND z_company.id NOT IN ('.implode(',',$usercompanies).')
              AND (z_purchase.dirrect=0 OR z_company_invite.id>0)
              AND z_company.`title` LIKE :title
            GROUP BY z_company.id
            ORDER BY z_company.title';
        }elseif($pageid==6){
            $sql = 'SELECT
              z_company.id,
              z_company.`title` AS value,
              z_company.`title` AS label
            FROM
              z_offer
              INNER JOIN z_product
                ON z_product.`id` = z_offer.product_id
              INNER JOIN z_purchase
                ON z_purchase.id = z_product.`purchase_id`
              INNER JOIN z_company
                ON z_company.id = z_purchase.`company_id`
            WHERE z_offer.user_id = :user_id
              AND z_purchase.date_close > NOW()
              AND z_purchase.`purchasestate_id` IN (2,3,5)
              AND z_company.`title` LIKE :title
            GROUP BY z_company.id
            ORDER BY z_company.title';
        }elseif($pageid==5){
            $sql = 'SELECT
              company.id,
              company.`title` AS value,
              company.`title` AS label
            FROM
              z_company_user
              INNER JOIN z_company
                ON z_company.id = z_company_user.`company_id`
              INNER JOIN z_company AS company
                ON company.`companygroup_id` = z_company.companygroup_id
              INNER JOIN z_purchase
                ON z_purchase.`company_id` = company.id
            WHERE z_company_user.`user_id` = :user_id AND z_purchase.`purchasestate_id`!=4
            AND company.`title` LIKE :title
            GROUP BY company.id
            ORDER BY company.title';
        }
            $command = $connection->createCommand($sql);
            $command->bindParam(":title", $title, PDO::PARAM_STR);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            return array_merge(array(-1=>array('id'=>-1,'value'=>'Все компании','label'=>'Все компании')),$command->queryAll());

    }

    public function getExternalCompany($title)
    {
        if (strlen(trim(strtolower($title))) > 0) {
            $comdata = Company::model()->findByAttributes(array('title' => trim(strtolower($title)), 'external' => 1));
            if (!isset($comdata->id)) {
                $comdata = new Company;
                $comdata->title = trim(strtolower($title));
                $comdata->description = trim(strtolower($title));
                $comdata->companygroup_id = 1;
                $comdata->city_id = 1;
                $comdata->status = 1;
                $comdata->external = 1;
                $comdata->egrpou = 0;
                $comdata->save();
                if ($comdata->getErrors()) {
                    print_r($comdata->getErrors());
                }
            }
            if (isset($comdata->id))
                return $comdata->id;
        }
    }

    public function getMyCompanies()
    {
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_company.id,
          z_company.title,
          z_city.title AS city
        FROM
          z_company_user
          INNER JOIN z_company
            ON z_company.id = z_company_user.`company_id`
          INNER JOIN z_city
          ON z_city.`id`=z_company.`city_id`
        WHERE z_company_user.`user_id` = :user_id
        ORDER BY z_company.title
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        return $command->queryAll();
    }

    public function getCompaniesTree()
    {
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_companygroup.id AS companygroup_id,
          z_companygroup.title AS companygroup_title,
          z_company.id,
          z_company.title,
          z_user.id AS user_id,
          IF(
            z_companygroup_user.`user_id`,
            1,
            0
          ) AS group_owner,
          company_user.status,
          z_user.first_name,
          z_user.name,
          z_user.last_name,
          z_user.date_create,
          company_user.id AS companyuser_id
        FROM
          z_company_user
          INNER JOIN z_company
            ON z_company.id = z_company_user.`company_id`
          INNER JOIN z_companygroup
            ON z_companygroup.id = `z_company`.`companygroup_id`
          INNER JOIN z_company_user company_user
            ON company_user.`company_id` = z_company.id
          INNER JOIN z_user
            ON z_user.id = company_user.`user_id`
          LEFT JOIN `z_companygroup_user`
            ON z_companygroup_user.`companygroup_id` = z_companygroup.id
            AND z_companygroup_user.`user_id` = :user_id
        WHERE z_company_user.user_id = :user_id
        AND z_companygroup.status=1
        ORDER BY z_company.title ASC,z_user.date_create
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        $return = null;

        foreach ($result as $row) {
            $return[$row['companygroup_id']]['data'] = array('id' => $row['companygroup_id'], 'title' => $row['companygroup_title'], 'owner' => $row['group_owner']);
            $return[$row['companygroup_id']]['companies'][$row['id']]['data'] = array('id' => $row['id'], 'title' => $row['title']);
            $return[$row['companygroup_id']]['companies'][$row['id']]['users'][$row['user_id']] = array('id' => $row['user_id'], 'status' => $row['status'], 'date_create' => $row['date_create'], 'first_name' => $row['first_name'], 'name' => $row['name'], 'last_name' => $row['last_name'], 'companyuser_id' => $row['companyuser_id']);
        }

        return $return;
    }

    public function getProfileCompanies()
    {
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_companygroup.title AS companygroup_title,
          z_company_user.`company_id`,
          z_company.title AS company_title,
          z_city.title AS city,
          ifnull(sum(z_payments.amount),0) AS balance
        FROM
          z_company_user
          INNER JOIN z_company
            ON z_company.id = z_company_user.`company_id`
          INNER JOIN z_city
            ON z_city.id=z_company.city_id
          INNER JOIN `z_companygroup`
            ON `z_companygroup`.id = z_company.companygroup_id
          LEFT JOIN z_payments
            ON z_payments.company_id=z_company_user.`company_id` AND z_payments.status=2
        WHERE z_company_user.`user_id`=:user_id AND z_company_user.`status`=1 AND z_company_user.major=0 AND z_companygroup.status=1 AND z_company.status=1
        GROUP BY z_company_user.`company_id`
        ORDER BY z_companygroup.title ASC, z_company.title ASC
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $result = $command->queryAll();
        return $result;
    }
    public function findMarketCompanies($market_id){
        $connection = Yii::app()->db;
        $sql = '
        SELECT DISTINCT
          z_market_company.`company_id`,
          z_company.title AS company_title,
          z_company.`companygroup_id`,
          z_companygroup.title AS companygroup_title
        FROM
          `z_market_company`
          INNER JOIN z_company
            ON z_company.id = z_market_company.company_id
          INNER JOIN z_companygroup
            ON z_companygroup.`id` = z_company.`companygroup_id`
        WHERE z_market_company.`market_id` = :market_id
        ORDER BY z_companygroup.title ASC, z_company.title ASC
        ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":market_id", $market_id, PDO::PARAM_INT);
        $result = $command->queryAll();
        $data=array();
        //echo count($result);
        foreach($result AS $c){
            $data[$c['companygroup_id']]['id']=$c['companygroup_id'];
            $data[$c['companygroup_id']]['title']=$c['companygroup_title'];
            $data[$c['companygroup_id']]['companies'][$c['company_id']]=array('id'=>$c['company_id'],'title'=>$c['company_title']);
        }
        //CVarDumper::dump($data,10,true);

        return $data;
    }
}