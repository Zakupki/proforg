<?php
/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $language_id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $display_name
 * @property string $name
 * @property integer $status
 *
 * @method User active
 * @method User cache($duration = null, $dependency = null, $queryCount = 1)
 * @method User indexed($col = 'language_id')
 * @method User limit($limit, $offset = 0)
 *
 * The followings are the available model relations:
 * @property AuthItem[] $authItems
 * @property AuthLog[] $authLogs
 */
class User extends BaseActiveRecord
{
	
	public $old_password;
    public $company;
    public $companyrole;
    public $phone;
    public $companygroup;
    public $city;
    public $address;
	public static function fbUser($authIdentity)
    {
        
        /** @var $user User */
        $user = self::model()->findByAttributes(array(
            'email' => $authIdentity->getEmail()
        ));

        if(!$user)
        {
            $user = new User();
            $user->setAttributes(array(
                'password' => $user->generatePassword(),
                'email' =>  $authIdentity->getEmail(),
                'name' =>  $authIdentity->getName()
            ));
            if(!$user->save())
                return null;
        }

            if(!$user->status)
            return null;

        return $user;
    }
	
		
	/**
   * Returns User model by its email
   * 
   * @param string $email 
   * @access public
   * @return User
   */
	  public function findByEmail($email)
	  {
	    return self::model()->findByAttributes(array('email' => $email));
	  }	
	
	public function afterFind()
    {
    	$this->old_password=$this->password;
	}
		
	public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id',
                ),
                'fileAttributes' => array(
                ),
            ),
            'junction' => array(
                'class' => 'common.components.JunctionBehavior',
                'relations' => array(
                    'userUsertypes' => array(
                        'table' => '{{user_usertype}}',
                        'idColumn' => 'id',
                        'primaryColumn' => 'usertype_id',
                        'secondaryColumn' => 'user_id'
                    ),
                ),
            )
        );
    }
	
     /**
     * Get gender label
     *
     * @param string $gender Gender code (u, m, f)
     * @return string
     */
    public function getGenderLabel($gender)
    {
        $genders = $this->getGenders();
        if(isset($genders[$gender]))
            return $genders[$gender];

        return $gender;
    }

    /**
     * Return genders list
     *
     * @return array
     */
    public function getGenders()
    {
        return array(
            'u' => Yii::t('common', 'Unisex'),
            'm' => Yii::t('common', 'Male'),
            'f' => Yii::t('common', 'Female'),
        );
    }

    /**
     * Update user roles
     *
     * @throws CException
     * @param array $roles Array of auth items
     * @return bool
     */
    public function updateRoles($roles)
    {
        /** @var $am CAuthManager */
        $am = Yii::app()->authManager;
        $actRoles = $am->getAuthAssignments($this->id);
        $availRoles = $am->getAuthItems(2);

        $transaction = $this->dbConnection->beginTransaction();
        try
        {
            //  revoke roles
            foreach($actRoles as $role => $assignment)
            {
                if(in_array($role, $roles))
                    continue;

                $am->revoke($role, $this->id);
            }

            //  assign roles
            foreach($roles as $role)
            {
                if(!isset($availRoles[$role]))
                    continue;

                if(!isset($actRoles[$role]))
                    $am->assign($role, $this->id);
            }
            $transaction->commit();
        }
        catch(CException $e)
        {
            $transaction->rollBack();
            throw new CException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        return true;
    }

    /**
     * Get user display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->email;
    }

    /**
     * List users
     *
     * @param array $filterKeys
     * @return array
     */
    public function listData($filterKeys = array())
    {
        $data = $this;
        if($filterKeys)
            $data = $data->findAllByPk(array('id' => $filterKeys));
        else
            $data = $data->findAll();
        $this->resetScope();

        return CHtml::listData((array)$data, 'id', 'displayName');
    }

    /**
     * Check user password equal entered one
     *
     * @param string $password
     * @return bool
     */
    public function checkPass($password)
    {
        $pwdHasher = new PasswordHash(8, false);
        return $pwdHasher->CheckPassword($password, $this->password, $this->salt);
    }

    /**
     * Get roles list
     * Excluded guest and authenticated
     *
     * @return array
     */
    public static function getRoleList()
    {
        return Rights::getAuthItemSelectOptions(CAuthItem::TYPE_ROLE, array(
            'authenticated', 'guest'
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
     * @return User the static model class
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
        return '{{user}}';
    }

    /**
     * User role field
     *
     * @return array
     */
    public function getRole()
    {
        return Rights::getAssignedRoles($this->id, false);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'unique','message'=>'Email уже есть в базе'),
            array('status, requests_purchase_id, subscribe_regular, subscribe, sort', 'numerical', 'integerOnly' => true),
            array('login, email', 'length', 'max' => 64),
            array('password', 'length', 'min' => 6),
            array('password', 'required', 'on' => 'create'),
            array('display_name', 'length', 'max' => 64),
            array('last_name, activation_code, retrieve_code, first_name, position, image_id, detail_text, sort,company,companyrole,phone,date_create,companygroup,city,address', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('id, login, email, display_name, activation_code, retrieve_code, last_name, first_name, position, status, subscribe_regular, subscrib', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'authItems' => array(self::MANY_MANY, 'AuthItem', '{{auth_assignment}}(userid, itemname)'),
            'authLogs' => array(self::HAS_MANY, 'AuthLog', 'user_id'),
            'purchase' => array(self::HAS_MANY, 'Purchase', 'user_id'),
            'phones' => array(self::HAS_MANY, 'Phone', 'user_id'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'userUsertypes' => array(self::MANY_MANY, 'Usertype', '{{user_usertype}}(user_id, usertype_id)', 'together' => true),
        );
    }

    public function relatedCache()
    {
        return array_merge(parent::relatedCache(), array('Auth'));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'login' => Yii::t('backend', 'Login'),
            'password' => Yii::t('backend', 'Password'),
            'email' => Yii::t('backend', 'Email'),
            'display_name' => Yii::t('backend', 'Display Name'),
            'last_name' => Yii::t('backend', 'Last Name'),
            'first_name' => Yii::t('backend', 'Family Name'),
            'position' => Yii::t('backend', 'Position'),
            'status' => Yii::t('backend', 'Status'),
            'detail_text' => Yii::t('backend', 'Description'),
            'sort' => Yii::t('backend', 'Sort'),
            'subscribe_regular' => Yii::t('backend', 'Subscribe regular'),
            'subscribe' => Yii::t('backend', 'Subscribe'),
            'authItems' => Yii::t('backend', 'Role'),
            'company' => Yii::t('backend', 'Company'),
            'companygroup' => Yii::t('backend', 'Companygroup'),
            'companyrole' => Yii::t('backend', 'Company Role'),
            'phone' => Yii::t('backend', 'Phone'),
            'date_create' => Yii::t('backend', 'Date Register'),
            'address' => Yii::t('backend', 'Address'),
            'city' => Yii::t('backend', 'City')
        );
    }

    /**
     * User password hash string
     *
     * @param string $data Raw password
     * @return string Hash
     */
    public function passHash($password,$salt)
    {
        $pwdHasher = new PasswordHash(8, false);

        return $pwdHasher->hash_password($password,$salt);
    }
    
    public function getSalt()
    {
        $pwdHasher = new PasswordHash(8, false);
        return $pwdHasher->salt();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.login', $this->login, true);
        $criteria->compare('t.password', $this->password, true);
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.display_name', $this->display_name, true);
        $criteria->compare('t.last_name', $this->last_name, true);
        $criteria->compare('t.first_name', $this->first_name, true);
        $criteria->compare('t.position', $this->position, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.subscribe_regular', $this->subscribe_regular);
        $criteria->compare('t.subscribe', $this->subscribe);
		

        return parent::searchInit($criteria);
    }

    /**
     * Sort scope
     *
     * @param string $column Order column
     * @return User
     */
    public function sort($column = 'login')
    {
        return parent::sort($column);
    }

    /**
     * Generate random password
     *
     * @param int $length Length of password
     * @return string
     */
    public static function generatePassword($length = 10)
    {
        $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz0123456789-_';
        $charsetSize = strlen($charset) - 1;

        $password = '';
        foreach(range(1, $length) as $_)
            $password .= $charset{mt_rand(0, $charsetSize)};

        return $password;
    }

    /**
     * Hash password
     *
     * @return bool
     */
    public static function randomPassword() {
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
		    return implode($pass); //turn the array into a string
	}
    protected function beforeSave()
    {
        if(strlen($this->password) > 0 && $this->old_password!=$this->password){
            $salt = $this->getSalt();
            $this->setAttribute('salt', $salt);    
            $passwd = $this->passHash($this->password,$salt);
		}else
            $passwd = $this->id ? $this->findByPk($this->id)->password : null;
        
        
        
        $this->setAttribute('password', $passwd);
        
        

        if(!$this->login)
            $this->login = null;

        if(!$this->display_name)
            $this->display_name = $this->getDisplayName();

        return parent::beforeSave();
    }

    public function findUser($email)
    {
        $profile = new ProfileForm;
        $user = User::model()->findByAttributes(array('email' => $email));
        if (isset($user)) {
            $tags = UserTag::model()->with('tag')->findAllByAttributes(array('user_id' => $user->id));
            if (isset($tags)) {
                $tags = CHtml::listData($tags, 'tag.id', 'tag.title');
                $profile->tagsids = implode(',', array_keys($tags));
                $profile->tagstitles = implode(',', $tags);

            }
            $phones = Phone::model()->findPhones(array('user_id' => $user->id));
            if (isset($phones))
                $profile->personalphones = $phones;
            $profile->id = $user->id;
            $profile->last_name = $user->last_name;
            $profile->email = $user->email;
            $profile->first_name = $user->first_name;
            $profile->position = $user->position;
        }
        return $profile;
    }
    public function getContacts($params=array())
    {
        $take=15;
        $hasmore=0;
        $joinSql='';
        if($params['take']<1)
            $params['take']=$take;
        if($params['start']<1)
            $params['start']=0;
        if($params['market_id']<1)
            $params['market_id']=0;
        if($params['role_id']<1)
            $params['role_id']=0;

        if($params['market_id']>0){
            $joinSql.=' INNER JOIN z_market_company
            ON z_market_company.company_id=z_company_user.company_id AND z_market_company.market_id=:market_id';
        }
        if($params['role_id']>0){
            $joinSql.=' INNER JOIN z_companyrole
          ON z_companyrole.id=z_company_user.companyrole_id AND z_company_user.companyrole_id=:companyrole_id';
        }

        $params['take']++;
        $users=array();
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          z_user.`first_name`,
          z_user.`last_name`,
          z_user.`position`,
          z_company_user.`user_id`
        FROM z_user
        INNER JOIN z_company_user
          ON z_user.id = z_company_user.`user_id`
        '.$joinSql.'
        WHERE z_user.`status` = 1 AND z_company_user.`status` = 1
        group by z_user.id
        LIMIT '. $params['start'].','. $params['take'].'
        ';
        //echo $sql;
        $command = $connection->createCommand($sql);
        if($params['market_id']>0)
            $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        if($params['role_id']>0)
            $command->bindParam(":companyrole_id", $params['role_id'], PDO::PARAM_INT);
        //$command->bindParam(":take", $params['take'], PDO::PARAM_INT);

        $result = $command->queryAll();
        if(count($result)>$take){
            $hasmore=1;
            unset($result[max(array_keys($result))]);
        }

        $userArr=array();
        $userdataArr=array();
        foreach($result as $row){
           $userArr[$row['user_id']]=$row['user_id'];
        }
        if(count($userArr)>0){
            $sql2 = '
            SELECT
              z_company_user.`company_id`,
              z_company_user.`user_id`,
              z_phone.id AS phone_id,
              concat(z_country.phonecode," ",z_phone.phonecode," ",z_phone.phone) AS phone,
              z_market_company.`market_id`,
              z_market.`title` AS market,
              z_company.title as company,
              z_companyrole.title AS `position`
            FROM
              z_company_user
            INNER JOIN z_company
              ON z_company_user.company_id=z_company.id
            INNER JOIN z_companyrole
              ON z_company_user.companyrole_id=z_companyrole.id
            INNER JOIN z_user
              ON z_user.id=z_company_user.user_id
            LEFT JOIN z_phone
              ON z_phone.user_id=z_company_user.`user_id`
            LEFT JOIN z_country
              ON z_country.id=z_phone.country_id
            LEFT JOIN `z_market_company`
              ON z_market_company.`company_id` = z_company_user.`company_id`
            LEFT JOIN z_market
              ON z_market.`id` = z_market_company.`market_id`
            WHERE `z_company_user`.`user_id` IN ('.implode(',',$userArr).')
            ';
            //echo $sql2;
            $command2 = $connection->createCommand($sql2);
            //$command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $result2 = $command2->queryAll();
            $userdataArr=array();
            if ($result2)
                foreach($result2 as $data){
                    $userdataArr[$data['user_id']]['companies'][$data['company_id']]=array('company'=>$data['company'],'position'=>$data['position']);
                    if($data['phone_id']>0)
                        $userdataArr[$data['user_id']]['phones'][$data['phone_id']]=$data['phone'];
                    if($data['market_id']>0)
                        $userdataArr[$data['company_id']]['markets'][$data['market_id']]=$data['market'];
            }
        }
        if (isset($result))
            return array('users'=>$result,'userdata'=>$userdataArr,'hasmore'=>$hasmore);
    }
    public function confirmEmail($activation_code)
    {
        $user=User::model()->findByAttributes(array('activation_code'=>$activation_code,'status'=>0));
        if($user){
            $user->status=1;
            $user->activation_code=null;
            $user->save();

            $_identity = new UserIdentity($user->email,true);
            $_identity->authenticate(true);
            if ($_identity->errorCode === UserIdentity::ERROR_NONE) {
                $duration = 3600 * 24 * 1;
                Yii::app()->user->login($_identity, $duration);

            }
            return true;
        }else{
            return false;
        }
    }

    public function confirmRetrieve($retrieve_code)
    {

        Key::model()->deleteAll('NOW()>DATE_ADD(date_create , INTERVAL 1 HOUR)');

        $key=Key::model()->findByAttributes(array('token'=>$retrieve_code,));
        if($key){
            $newpassword=null;
            $user=User::model()->findByPk($key->user_id);
            $user->password=$newpassword=self::generatePassword();
            $user->save();
            $contr=Yii::app()->controller;
            $contr->layout="mail";
            $body =$contr->render('/mail/newpassword', array('user'=>$user,'password'=>$newpassword), true);
            $queue = new EmailQueue();
            $queue->to_email = trim($user->email);
            $queue->subject = "Новые параметры доступа";
            $queue->from_email = 'support@zakupki-online.com';
            $queue->from_name = 'Zakupki-online';
            $queue->date_published = new CDbExpression('NOW()');
            $queue->message = $body;
            $queue->save();
            $key->delete();
            return true;
        }else{
            return false;
        }
    }

    public function getUsersByMarket($markets){
       $connection = Yii::app()->db;
       $whereSql='WHERE z_user.subscribe_regular=1 AND z_user.status=1 AND z_company.status=1 AND z_companygroup.status=1 ';
       if(count($markets)>0)
       $whereSql.=" AND z_market_company.`market_id` IN(".implode(',',$markets).")";
       $sql = '
           SELECT
          z_user.id,
          z_user.email,
          concat(z_user.first_name," ",z_user.last_name) AS name,
          z_market_company.`market_id`
        FROM
          z_market_company
          INNER JOIN z_company_user
            ON z_company_user.`company_id` = z_market_company.`company_id` AND z_company_user.status=1
          INNER JOIN z_user
            ON z_user.id = z_company_user.`user_id`
          INNER JOIN z_company
            ON z_company.id=z_market_company.`company_id`
          INNER JOIN z_companygroup
            ON z_companygroup.id=z_company.`companygroup_id`
        '.$whereSql.'
        GROUP BY z_user.id,z_market_company.`market_id`
        ORDER BY z_user.id,z_market_company.`market_id`
           ';
       $command = $connection->createCommand($sql);
       //$command->bindParam(":activation_code", $activation_code, PDO::PARAM_STR);
       $result = $command->queryAll();
       return $result;
    }
    public function getGroupAdmins(){
        $connection = Yii::app()->db;
        $sql = '
           SELECT
             z_user.email
           FROM
             `z_companygroup_user`
           INNER JOIN z_user
           ON z_user.id=z_companygroup_user.`user_id`
           WHERE z_user.`status`=1
           GROUP BY z_user.id
           ';
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    public function getUpdates(){
        if(!yii::app()->user->getId())
            return array('user_id'=>0,'planlist'=>0,'requests'=>0,'auctions'=>0,'purchases'=>0,'date_update'=>time());
        $requestnum=0;
        $planlist=0;
        $requests_purchase_id=0;
        $requests_purchase_id=Yii::app()->session['requests_purchase_id'];

        $connection = Yii::app()->db;

        /*if(Yii::app()->cache->get('new_purchase_id')>$requests_purchase_id){*/
            $sql = '
            SELECT company_id FROM z_company_user WHERE z_company_user.status=1 AND z_company_user.user_id=:user_id
            ';
            $command = $connection->createCommand($sql);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $usercompanies = $command->queryColumn();
        /*}*/

        /*if(Yii::app()->cache->get('new_purchase_id')>$requests_purchase_id){*/

            if(count($usercompanies)>0){
            $sql = '
            SELECT
              COUNT(DISTINCT z_product.id) AS cnt
            FROM
              z_purchase
              INNER JOIN z_product
                ON z_product.`purchase_id` = z_purchase.id
              INNER JOIN z_user_market
                ON z_user_market.market_id = z_purchase.market_id
                    AND z_user_market.user_id = :user_id
              LEFT JOIN z_offer
                ON z_offer.`product_id` = z_product.id
                    AND z_offer.`user_id` = :user_id
              LEFT JOIN z_company_invite
                ON z_company_invite.purchase_id = z_purchase.id
                    AND z_company_invite.company_id IN ('.implode(',',$usercompanies).')
            WHERE z_purchase.id > '.intval($requests_purchase_id).'
                    AND z_purchase.company_id NOT IN ('.implode(',',$usercompanies).')
                    AND z_purchase.user_id!=:user_id
                    AND z_offer.id IS NULL
                    AND z_purchase.date_close > NOW()
                    AND (
                        z_purchase.dirrect = 0
                        OR z_company_invite.id > 0
                    )
                    AND z_purchase.purchasestate_id = 2
            ORDER BY z_purchase.date_create DESC';
            $command = $connection->createCommand($sql);
            $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
            $request_result = $command->queryRow();
      /*  }*/
            }
        if(isset($request_result))
            $requestnum=$request_result['cnt'];

        if($requestnum>0)
            $planlist=1;
        return array('user_id'=>yii::app()->user->getId(),'planlist'=>$planlist,'requests'=>$requestnum,'auctions'=>0,'purchases'=>0,'date_update'=>time());
    }
    public function findContacts($params=array()){
        $connection = Yii::app()->db;
        $whereSql='';

        if(!isset($params['market_id']))
            $params['market_id']=2;
        if(!isset($params['companyrole_id']))
            $params['companyrole_id']=4;

        #my companies
        $sql = '
            SELECT company_id FROM z_company_user WHERE z_company_user.status=1 AND z_company_user.user_id=:user_id
            ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", yii::app()->user->getId(), PDO::PARAM_INT);
        $usercompanies = $command->queryColumn();

        #offer users
        $sql = '
            SELECT z_offer.user_id
            FROM z_offer
            INNER JOIN z_product
              ON z_product.id=z_offer.product_id
            WHERE z_product.purchase_id=:purchase_id
            GROUP BY z_offer.user_id
            ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":purchase_id", $params['purchase_id'], PDO::PARAM_INT);
        $offerusers = $command->queryColumn();

        if($offerusers)
            $whereSql.=' AND z_company_user.user_id NOT IN ('.implode(',',$offerusers).')';

        $sql = '
            SELECT
            z_user.id,
            CONCAT(z_user.first_name," ", z_user.last_name) as name,
            z_user.email,
            z_company.title AS company
            FROM z_market_company
            INNER JOIN z_company_user
              ON z_company_user.company_id=z_market_company.company_id AND z_company_user.companyrole_id=:companyrole_id
            INNER JOIN z_company
              ON z_company.id=z_company_user.company_id
            INNER JOIN z_user
              ON z_user.id=z_company_user.user_id
            WHERE z_market_company.market_id=:market_id AND z_market_company.company_id NOT IN ('.implode(',',$usercompanies).')
            '.$whereSql.'
            ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        $command->bindParam(":companyrole_id", $params['companyrole_id'], PDO::PARAM_INT);
        $result = $command->queryAll();
        return $result;
    }
    public function getWeekAnalyticsUser(){
        $connection = Yii::app()->db;
        $sql = '
           SELECT
              COUNT(DISTINCT t.id) AS purchase_num,
              SUM(t.total) AS total,
              SUM(t.economy_sum) AS economy_sum,
              SUM(t.company_num) / COUNT(DISTINCT t.id) AS avg_company_num,
              SUM(t.not_concurent) AS not_concurent,
              user.id AS user_id,
              user.email,
              user.first_name,
              user.last_name
            FROM
              `z_purchase` `t`
              INNER JOIN `z_user` `user`
                ON (`t`.`user_id` = `user`.`id`)
              INNER JOIN `z_company` `company`
                ON (
                  `t`.`company_id` = `company`.`id`
                )
              INNER JOIN z_companygroup_service
                ON z_companygroup_service.companygroup_id=company.companygroup_id AND service_id=7 AND z_companygroup_service.status=1
            WHERE t.date_closed BETWEEN "'.date("Y-m-d",strtotime("-1 week")).' 00:00:00" AND "'.date("Y-m-d").' 00:00:00"
            GROUP BY t.user_id
            ';
        $command = $connection->createCommand($sql);
       /* $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
        $command->bindParam(":companyrole_id", $params['companyrole_id'], PDO::PARAM_INT);*/
        $result = $command->queryAll();
        return $result;
    }
    public function getWeekAnalyticsUserOrg(){
        $connection = Yii::app()->db;
        $sql = '
           SELECT
              z_user.id AS user_id,
              z_user.email,
              z_user.first_name,
              z_user.last_name,
              z_company_user.`company_id`
            FROM
              z_company_user
              INNER JOIN z_company
                ON z_company.id = z_company_user.`company_id`
              INNER JOIN z_companygroup_service
                ON z_companygroup_service.`companygroup_id` = z_company.companygroup_id
                AND z_companygroup_service.`service_id` = 9
                AND z_companygroup_service.`status` = 1
              INNER JOIN z_user
                ON z_user.id = z_company_user.`user_id`
              INNER JOIN z_purchase
                ON z_purchase.company_id = z_company.id
            WHERE z_company_user.`companyrole_id` = 8
              AND z_purchase.date_closed BETWEEN "'.date("Y-m-d",strtotime("-1 week")).' 00:00:00" AND "'.date("Y-m-d").' 00:00:00"
              AND z_purchase.`purchasestate_id` = 4
            GROUP BY z_company_user.`user_id`
        ';
        $command = $connection->createCommand($sql);
        /* $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
         $command->bindParam(":companyrole_id", $params['companyrole_id'], PDO::PARAM_INT);*/
        $result = $command->queryAll();
        return $result;
    }
    public function getEfficiencyAnalyticsUser(){
        $connection = Yii::app()->db;
        $sql = '
           SELECT
              COUNT(z_purchase.id) AS purchase_num,
              SUM(z_purchase.`not_min_purchase`) AS not_min_purchase,
              SUM(z_purchase.lose_total) AS lose_total,
              z_purchase.`closer_id`,
              z_purchase.company_id,
              z_user.id AS `user_id`,
              z_user.`email`,
              z_user.`first_name`,
              z_user.`last_name`,
              z_company.title AS company
            FROM
              z_purchase
              INNER JOIN z_company
                ON z_company.id = z_purchase.`company_id`
              INNER JOIN z_companygroup_service
                ON z_companygroup_service.`companygroup_id` = z_company.companygroup_id
                AND z_companygroup_service.`service_id` = 8
                AND z_companygroup_service.`status` = 1
              INNER JOIN z_company_user
                ON z_company_user.`company_id`= z_company.id AND z_company_user.`companyrole_id`=1
              INNER JOIN z_user
              ON z_user.id=z_company_user.`user_id`
            WHERE z_purchase.`date_closed` BETWEEN "'.date("Y-m-d",strtotime("-1 week")).' 00:00:00" AND "'.date("Y-m-d").' 00:00:00"
            GROUP BY z_user.`id`
            ';
        $command = $connection->createCommand($sql);
        /* $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
         $command->bindParam(":companyrole_id", $params['companyrole_id'], PDO::PARAM_INT);*/
        $result = $command->queryAll();
        return $result;
    }
    public function getMonthReportUser(){
        $connection = Yii::app()->db;
        $sql = '
        SELECT
          SUM(z_purchase.`total`) AS total,
          SUM(z_purchase.`economy_sum`) AS economy_sum,
          SUM(z_purchase.`lose_total`) AS lose_total,
          COUNT(DISTINCT z_purchase.id) AS purchase_num,
          z_company_user.`user_id`,
          z_user.`email`,
          z_user.`first_name`,
          z_user.`last_name`,
          z_company.title AS company
        FROM
          `z_purchase`
          INNER JOIN `z_company_user`
            ON `z_company_user`.`company_id` = z_purchase.`company_id` AND z_company_user.`companyrole_id`=6
          INNER JOIN z_company
            ON z_company.id = z_purchase.`company_id`
          INNER JOIN `z_companygroup_service`
            ON z_companygroup_service.`companygroup_id` = z_company.`companygroup_id`
            AND z_companygroup_service.`service_id` = 9
            AND z_companygroup_service.`status` = 1
          INNER JOIN z_user
              ON z_user.id=z_company_user.`user_id`
        WHERE z_purchase.`date_closed` BETWEEN "'.date("Y-m-d",strtotime("-1 month")).' 00:00:00" AND "'.date("Y-m-d").' 00:00:00"
        GROUP BY z_purchase.`company_id`,
          z_company_user.`user_id`
        ';
        $command = $connection->createCommand($sql);
        /* $command->bindParam(":market_id", $params['market_id'], PDO::PARAM_INT);
         $command->bindParam(":companyrole_id", $params['companyrole_id'], PDO::PARAM_INT);*/
        $result = $command->queryAll();
        return $result;
    }
}