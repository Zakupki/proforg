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
    public $display_name;
    public $salary;
    public $salaryday;
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
    public function listData($filterKeys = array(), $sort = 'title')
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
            array('email,salary,salaryday', 'required'),
            array('email', 'unique','message'=>'Email уже есть в базе'),
            array('status, deleted, usertype_id, sort, salaryday', 'numerical', 'integerOnly' => true),
            array('login, email', 'length', 'max' => 64),
            array('password', 'length', 'min' => 6),
            array('password', 'required', 'on' => 'create'),
            array('display_name', 'length', 'max' => 64),
            array('last_name, salary, usertype_id, activation_code, retrieve_code, first_name, image_id, detail_text, sort,company_id,finance_id,employer_id,date_create', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('id, login, email, display_name, activation_code, retrieve_code, last_name, first_name, status', 'safe', 'on' => 'search'),
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
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'usertype_id' => array(self::BELONGS_TO, 'Company', 'company_id'),
            'finance' => array(self::BELONGS_TO, 'Finance', 'finance_id'),
            'employer' => array(self::BELONGS_TO, 'Company', 'company_id'),
            //'userUsertypes' => array(self::MANY_MANY, 'Usertype', '{{user_usertype}}(user_id, usertype_id)', 'together' => true),
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
            'status' => Yii::t('backend', 'Status'),
            'detail_text' => Yii::t('backend', 'Description'),
            'sort' => Yii::t('backend', 'Sort'),
            'authItems' => Yii::t('backend', 'Role'),
            'company_id' => Yii::t('backend', 'Company'),
            'finance_id' => Yii::t('backend', 'Finance'),
            'employer_id' => Yii::t('backend', 'Employer'),
            'date_create' => Yii::t('backend', 'Date Register'),
            'address' => Yii::t('backend', 'Address'),
            'city' => Yii::t('backend', 'City'),
            'companies' =>  Yii::t('backend', 'Companies'),
            'deleted' =>  Yii::t('backend', 'Deleted'),
            'salary' =>  Yii::t('backend', 'Salary'),
            'salaryday' =>  Yii::t('backend', 'Salary day'),
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
        $criteria->compare('t.status', $this->status);
		

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

        foreach ($this->attributes as $key => $value)
            if (!$value)
                $this->$key = NULL;
        
        
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
        }
        return $profile;
    }
    public function getBalance($user_id){
        $connection = Yii::app()->db;
        $sql = '
           SELECT
              SUM(`z_request`.value) AS balance
           FROM
              `z_request`
           WHERE z_request.user_id=:user_id
           ';
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $result = $command->queryRow();
        return $result;
    }
    public function getCompanyUsers($employer_id){
        $connection = Yii::app()->db;
        $sql = 'SELECT
                  z_user.id,
                  z_user.`first_name`,
                  z_user.`name`,
                  z_user.`last_name`,
                  z_user.`salaryday`,
                  z_user.`salary`,
                  z_user.`salary`*12 AS yearsalary,
                  SUM(z_request.`value`) AS balance
                FROM
                  z_user
                LEFT JOIN  z_request
                  ON z_request.`user_id`=z_user.id
                WHERE z_user.employer_id=:employer_id
                GROUP BY z_user.id';
        $command = $connection->createCommand($sql);
        $command->bindParam(":employer_id", $employer_id, PDO::PARAM_INT);
        $result = $command->queryAll();
        return $result;
    }

}