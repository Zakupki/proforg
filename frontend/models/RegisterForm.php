<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
    public $region_id;

    public $last_name;
    public $email;
    public $delivery_addr;
    public $password;
    public $repeat_password;
    public $egrpou;
    public $country_id;
    public $city_title;
    public $city_id;
    public $companyrole_id;
    public $company_id;
    public $company_title;
    public $company;
    public $markets;
    public $address;
    public $position;
    public $first_name;
    public $marketsids;
    public $tagstitles;
    public $tagsids;
    public $phones;
    public $personalphones;


    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        if (isset($_POST['RegisterForm']['company_id'])) {
            $rules[] = array('last_name,first_name,companyrole_id,position,
                         email,password,repeat_password,company_id', 'required');
        } else {
            $rules[] = array('egrpou,company_title,country_id,region_id,city_title,address,
                         last_name,first_name,companyrole_id,position,
                         email,password,repeat_password,', 'required');
            $rules[] = array('egrpou', 'checkEgrpou');
            $rules[] = array('phones', 'checkPhones');
            $rules[] = array('marketsids', 'checkmarkets');
        }
        $rules[] = array('region_id', 'numerical', 'integerOnly' => true, 'min' => 1);
        $rules[] = array('email', 'uniqueemail');
        $rules[] = array('tagsids, tagstitles', 'safe');
        $rules[] = array('personalphones', 'checkpersonalPhones');
        $rules[] = array('password', 'length', 'min' => 6, 'max' => 12);
        $rules[] = array('password', 'compare', 'compareAttribute' => 'repeat_password');
        return $rules;
    }

    public function uniqueemail()
    {
        if (User::model()->find('email="' . $this->email . '"'))
            $this->addError('email', 'Пользователь с таким email уже зарегистрирован');
    }

    public function checkEgrpou()
    {
        $chek_numbers1[1]=array(1,2,3,4,5,6,7);
        $chek_numbers1[2]=array(7,1,2,3,4,5,6);
        $chek_numbers2[1]=array(3,4,5,6,7,8,9);
        $chek_numbers2[2]=array(9,3,4,5,6,7,8);

        $digits = preg_split('//', $this->egrpou, -1, PREG_SPLIT_NO_EMPTY);
        $lastnum=end($digits);
        $type=0;
        if(intval($this->egrpou)<30000000 || intval($this->egrpou)>60000000){
            $type=1;
        }elseif(intval($this->egrpou)>30000000 && intval($this->egrpou)<60000000){
            $type=2;
        }
        $sum1=0;
        //print_r($digits);
        foreach($digits as $k=>$num){
            if(array_key_exists($k,$chek_numbers1[$type])){
                $sum1=$sum1+($num*$chek_numbers1[$type][$k]);
            }
        }
        $koofsum=($sum1 % 11);

        if($koofsum>9){
            $sum1=0;
            foreach($digits as $k=>$num){
                if(array_key_exists($k,$chek_numbers1[$type])){
                    $sum1=$sum1+($num*($chek_numbers1[$type][$k]+2) );
                }
            }
            $koofsum=($sum1 % 11);
        }
        if(intval($koofsum)!=intval($lastnum))
            $this->addError('egrpou','Erdpou error');
    }

    public function checkmarkets()
    {
        $marketArr = explode(',', trim($this->marketsids));
        if (count($marketArr) < 1 || $marketArr[0] < 1)
            $this->addError('marketsids', 'Укажите рынки в которых вы работаете');
    }

    public function checkPhones()
    {
        if (count($this->phones) > 0) {
            foreach ($this->phones as $k => $phone) {
                if (!$phone['phonecode'] || !$phone['phone'] || !$phone['countrycode'])
                    unset($this->phones[$k]);
            }
        }
        if (count($this->phones) < 1)
            $this->addError('phones', 'Укажите контактный телефон');
    }

    public function checkpersonalPhones()
    {
        if (count($this->personalphones) > 0) {
            foreach ($this->personalphones as $k => $phone) {
                if (!$phone['phonecode'] || !$phone['phone'] || !$phone['countrycode'])
                    unset($this->personalphones[$k]);
            }
        }
        if (count($this->personalphones) < 1)
            $this->addError('personalphones', 'Укажите контактный телефон');
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function register()
    {
        $duration = false;

        //print_r($this->attributes);
        //return false;

        if (!$this->getErrors()) {
            $user = new User;
            $user->attributes = $this->attributes;
            $user->activation_code=md5(microtime().$user->email.MD5_KEY . rand());
            $user->status=0;
            $user->save();

            if ($user->id > 0) {

                #ОТПРАВКА EMAIL
                $contr=Yii::app()->controller;
                $contr->layout="mail";
                $body =$contr->render('/mail/register', array('user' => $user, 'password' => $this->password), true);
                $message = new YiiMailMessage();
                $message->setBody($body, 'text/html');
                $message->setSubject('Регистрация');
                $message->setTo($user->email);
                $message->setFrom(array(
                    'info@zakupki-online.com' => 'Zakupki-Online.com'
                ));

                Yii::app()->mail->send($message);

                #Телефоны пользователя
                if (isset($this->personalphones)) {
                    foreach ($this->personalphones as $phone) {
                        if ($phone['phone'] > 0 && $phone['phonecode'] > 0) {
                            $companyphone = new Phone;
                            if ($phone['countrycode'] == 38) {
                                $phone['country_id'] = 1;
                                unset($phone['countrycode']);
                            }
                            $phone['user_id'] = $user->id;
                            $companyphone->attributes = $phone;
                            $companyphone->save();
                            if ($companyphone->getErrors())
                                $this->addErrors($companyphone->getErrors());
                        }
                    }
                }
                #Теги пользователя
                if (isset($this->tagsids)) {
                    $tagArr = explode(',', $this->tagsids);
                    if (isset($this->tagstitles))
                        $tagtitlesArr = explode(',', $this->tagstitles);


                    if (count($tagArr) > 0) {
                        foreach ($tagArr as $k => $m) {
                            if ($m > 0) {
                                $usertag = new UserTag();
                                $usertag->tag_id = $m;
                                $usertag->user_id = $user->id;
                                $usertag->save();
                                if ($usertag->getErrors())
                                    $this->addErrors($usertag->getErrors());
                            } else {
                                if (strlen($tagtitlesArr[$k]) > 0) {
                                    $tag->id = Tag::model()->getTag($tagtitlesArr[$k]);
                                    if (isset($tag->id)) {
                                        $usertag = new UserTag();
                                        $usertag->tag_id = $tag->id;
                                        $usertag->user_id = $user->id;
                                        $usertag->save();
                                        if ($usertag->getErrors())
                                            $this->addErrors($usertag->getErrors());
                                    }
                                }
                            }
                        }
                    }
                }
                if($this->company_id>0)
                $companyexists = Company::model()->findByPk($this->company_id);
                if (!isset($companyexists)) {
                    #Создание группы компании
                    $companygroup = new Companygroup;
                    $companygroup->title = $this->company_title;
                    $companygroup->status = 1;
                    $companygroup->save();
                    if ($companygroup->getErrors())
                        $this->addErrors($companygroup->getErrors());

                    if ($companygroup->id > 0) {

                        #Группа компаний - Юзер
                        $companygroupuser = new CompanygroupUser;
                        $companygroupuser->user_id = $user->id;
                        $companygroupuser->companygroup_id = $companygroup->id;
                        $companygroupuser->status = 1;
                        $companygroupuser->save();
                        if ($companygroupuser->getErrors())
                            $this->addErrors($companygroupuser->getErrors());
                        #Создание компании
                        $company = new Company;
                        $company->title = $this->company_title;
                        $company->companygroup_id = $companygroup->id;
                        $company->companytype_id = 1;
                        $company->address = $this->address;
                        $company->egrpou = $this->egrpou;
                        $company->status = 0;
                        if ($this->city_id > 0)
                            $company->city_id = $this->city_id;
                        else {
                            if (!$city = City::model()->findByAttributes(array('title' => $this->city_title)))
                                $city = new City;
                            $city->region_id = $this->region_id;
                            $city->title = $this->city_title;
                            $city->status = 1;
                            $city->save();
                            if ($city->getErrors())
                                $this->addErrors($city->getErrors());
                            $company->city_id = $city->id;
                        }
                        $company->save();

                        if($company->id){
                            #Подача заявки
                            $contr=Yii::app()->controller;
                            $contr->layout="mail";
                            $body =$contr->render('/mail/register_company', array('user' => $user, 'company'=>$company), true);
                            $message = new YiiMailMessage();
                            $message->setBody($body, 'text/html');
                            $message->setSubject('Регистрация вашей компании');
                            $message->setTo($user->email);
                            $message->setFrom(array(
                                'info@zakupki-online.com' => 'Zakupki-Online.com'
                            ));
                            Yii::app()->mail->send($message);

                            #Подача заявки админу
                            $contr=Yii::app()->controller;
                            $contr->layout="mail";
                            $body =$contr->render('/mail/admin/register_company', array('user' => $user, 'company'=>$company), true);
                            $message = new YiiMailMessage();
                            $message->setBody($body, 'text/html');
                            $message->setSubject('Регистрация новой компании');
                            $message->setTo('support@zakupki-online.com');
                            $message->setFrom(array(
                                'info@zakupki-online.com' => 'Zakupki-Online.com'
                            ));
                            Yii::app()->mail->send($message);

                        }


                        if ($company->getErrors())
                            $this->addErrors($company->getErrors());
                        #Телефоны компании
                        if (isset($this->phones) && isset($company->id)) {
                            foreach ($this->phones as $phone) {
                                if ($phone['phone'] > 0 && $phone['phonecode'] > 0) {
                                    $companyphone = new Phone;
                                    if ($phone['countrycode'] == 38) {
                                        $phone['country_id'] = 1;
                                        unset($phone['countrycode']);
                                    }
                                    $phone['company_id'] = $company->id;
                                    $companyphone->attributes = $phone;
                                    $companyphone->save();
                                    if ($companyphone->getErrors())
                                        $this->addErrors($companyphone->getErrors());
                                }
                            }
                        }
                        #Рынки компании
                        if (isset($this->marketsids)) {
                            $marketsArr = explode(',', $this->marketsids);
                            if (count($marketsArr) > 0)
                                foreach ($marketsArr as $m) {
                                    if ($m > 0) {

                                        $usermarket = new UserMarket();
                                        $usermarket->market_id = $m;
                                        $usermarket->user_id = $user->id;
                                        $usermarket->save();
                                        if ($usermarket->getErrors())
                                            $this->addErrors($usermarket->getErrors());

                                        $marketcompany = new MarketCompany();
                                        $marketcompany->market_id = $m;
                                        $marketcompany->company_id = $company->id;
                                        $marketcompany->save();
                                        if ($marketcompany->getErrors())
                                            $this->addErrors($marketcompany->getErrors());
                                    }
                                }
                        }
                    }
                }else{
                    $groupadmin=CompanygroupUser::model()->with('user')->findByAttributes(array('companygroup_id'=>$companyexists->companygroup_id));
                    if($groupadmin){
                        #Подача заявки
                        $contr=Yii::app()->controller;
                        $contr->layout="mail";
                        $body =$contr->render('/mail/join_company', array('user' => $user, 'company'=>$companyexists,'groupadmin'=>$groupadmin), true);
                        $message = new YiiMailMessage();
                        $message->setBody($body, 'text/html');
                        $message->setSubject('Заявка на присоединение к компании');
                        $message->setTo($user->email);
                        $message->setFrom(array(
                            'info@zakupki-online.com' => 'Zakupki-Online.com'
                        ));
                        Yii::app()->mail->send($message);

                        #Запрос админу
                        $contr=Yii::app()->controller;
                        $contr->layout="mail";
                        $body =$contr->render('/mail/adminjoin_company', array('groupadmin' => $groupadmin, 'aplier' => $user, 'company'=>$companyexists), true);
                        $message = new YiiMailMessage();
                        $message->setBody($body, 'text/html');
                        $message->setSubject('Заявка на присоединение к Вашей компании');
                        $message->setTo($groupadmin->user->email);
                        $message->setFrom(array(
                            'info@zakupki-online.com' => 'Zakupki-Online.com'
                        ));
                        Yii::app()->mail->send($message);
                    }
                }
                #Пользователь - Компания
                if (isset($company->id) && isset($this->companyrole_id) || isset($companyexists->id) && isset($this->companyrole_id)) {
                    $companyuser = new CompanyUser;
                    $companyuser->user_id = $user->id;
                    $companyuser->companyrole_id = $this->companyrole_id;

                    if (isset($companyexists)) {
                        $companyuser->company_id = $companyexists->id;
                        $companyuser->status = 0;
                    } else {
                        $companyuser->company_id = $company->id;
                        $companyuser->status = 1;
                    }
                    $companyuser->save();
                    if ($companyuser->getErrors())
                        $this->addErrors($companyuser->getErrors());
                }
            }

            if ($this->_identity === null) {
                $this->_identity = new UserIdentity($this->email, $this->password);
                $this->_identity->authenticate(true);
            }
            if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
                $duration = 3600 * 24 * 1;
                Yii::app()->user->login($this->_identity, $duration);
                return true;
            }

            if ($this->getErrors())
                return false;
            else
                return true;
        }
    }
}
