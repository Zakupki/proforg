<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CompanyForm extends CFormModel
{
    public $region_id;
    public $egrpou;
    public $id;
    public $city_title;
    public $city_id;
    public $title;
    public $markets;
    public $address;
    public $phones;
    public $marketsids;
    public $marketslist;
    public $country_id;
    public $companygroup_id;
    public $account;
    public $mfo;
    public $bank;
    public $billperiod_id;
    public $ndspayer;
    public $withnds;
    public $director;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        $rules[] = array('title,region_id,companygroup_id,country_id,city_title,address', 'required');
        $rules[] = array('phones', 'checkPhones');
        $rules[] = array('region_id,id,companygroup_id,mfo,egrpou,billperiod_id', 'numerical', 'integerOnly' => true, 'min' => 1);
        $rules[] = array('id,account,bank,ndspayer,withnds,director', 'safe');
        $rules[] = array('marketsids', 'checkmarkets');
        return $rules;
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

    public function uniqueegrpou()
    {
        if (Company::model()->find('egrpou="' . $this->egrpou . '"'))
            $this->addError('egrpou', 'Компания с таким кодом ЕГРПОУ');
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

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function save()
    {
        if (!$this->getErrors()) {
            if (user()->getId() > 0) {
                if($this->id<1)
                    $new=true;
                else
                    $new=false;

                if ($this->companygroup_id > 0) {
                        #Создание компании
                        if($this->id>0)
                            $company = Company::model()->findByPk($this->id);
                        else{
                            $company = new Company;
                        }
                        $company->title = $this->title  ;
                        $company->companygroup_id = $this->companygroup_id;
                        if(!$company->companytype_id)
                        $company->companytype_id = 1;
                        $company->address = $this->address;
                        $company->egrpou = $this->egrpou;
                        $company->account = $this->account;
                        $company->mfo = $this->mfo;
                        $company->bank = $this->bank;
                        $company->director = $this->director;
                        $company->billperiod_id = $this->billperiod_id;
                        $company->ndspayer = $this->ndspayer;
                        $company->withnds = $this->withnds;

                        $company->status = 1;
                        if ($this->city_id > 0)
                            $company->city_id = $this->city_id;
                        else {
                            if (!$city = City::model()->findByAttributes(array('title' => $this->city_title)))
                                $city = new City;
                            $city->region_id = $this->region_id;
                            $city->title = $this->city_title;
                            $city->status = 1;
                            $city->save();
                            if($city->getErrors())
                                $this->addErrors($city->getErrors());
                            $company->city_id = $city->id;
                        }
                        $company->save();
                        if($company->getErrors())
                            $this->addErrors($company->getErrors());
                        $this->id=$company->id;
                        #Телефоны компании
                        $savedPhones = array();
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
                                    if($companyphone->getErrors())
                                        $this->addErrors($companyphone->getErrors());
                                    if ($companyphone->id>0)
                                        $savedPhones[$companyphone->id] = $companyphone->id;
                                }
                            }
                        }
                        if (count($savedPhones) > 0)
                            Phone::model()->deleteAll('id NOT IN(' . implode(',', $savedPhones) . ') AND company_id=:company_id', array('company_id' => $company->id));
                        else
                            Phone::model()->deleteAll('user_id=:company_id', array('company_id' =>$company->id));

                        #Рынки компании
                        $oldmarketArr=MarketCompany::model()->findAllByAttributes(array('company_id'=>$company->id));
                        $oldmarketArr = CHtml::listData($oldmarketArr, 'market_id' , 'market_id');
                        $savedmarkets=array();
                        if (isset($this->marketsids)) {
                            $marketsArr = explode(',', $this->marketsids);
                            if (count($marketsArr) > 0)
                                foreach ($marketsArr as $m) {
                                    if (in_array($m, $oldmarketArr)) {
                                        $savedmarkets[$m] = $m;
                                        continue;
                                    }
                                    if ($m > 0) {
                                        $marketcompany = new MarketCompany();
                                        $marketcompany->market_id = $m;
                                        $marketcompany->company_id = $company->id;
                                        $marketcompany->save();
                                        if($marketcompany->getErrors())
                                            $this->addErrors($marketcompany->getErrors());
                                        $savedmarkets[$marketcompany->market_id] = $marketcompany->market_id;
                                    }
                                }
                        }
                         if (count($savedmarkets) > 0)
                             MarketCompany::model()->deleteAll('market_id NOT IN(' . implode(',', $savedmarkets) . ') AND company_id=:company_id', array('company_id' => $company->id));
                         else
                             MarketCompany::model()->deleteAll('user_id=:company_id', array('company_id' =>$company->id));

                    #Пользователь - Компания
                    if ($company->id > 0 && $new) {
                        $companyuser = new CompanyUser;
                        $companyuser->user_id = user()->getId();
                        $companyuser->companyrole_id = 6;
                        $companyuser->company_id = $company->id;
                        $companyuser->status = 1;
                        $companyuser->save();
                        if($companyuser->getErrors())
                            $this->addErrors($companyuser->getErrors());
                    }
                }
            }
            if ($this->getErrors())
                return false;
            else
                return true;
        }
    }

    public function findByPk($id)
    {
        $model = new CompanyForm;
        $company = Company::model()->with('city')->findByPk($id);
        if (isset($company)) {
            $model->attributes = $company->attributes;
            //if($company->city_id>0)
            $city = City::model()->findByPk($company->city_id);
            if ($city->region_id > 0) {
                $model->region_id = $city->region_id;
                $model->city_title = $city->title;
                $region = Region::model()->findByPk($city->region_id);
                $model->country_id = $region->country_id;
            }
            $phones = Phone::model()->findAllByAttributes(array('company_id' => $company->id));
            if ($phones)
                $model->phones = $phones;
            $markets = MarketCompany::model()->with('market')->findAllByAttributes(array('company_id' => $company->id));
            if ($markets) {
                $markets = CHtml::listData($markets, 'market.id', 'market.title');
                $model->marketsids = implode(',', array_keys($markets));
                $model->marketslist = implode(';', $markets);
            }
        }
        return $model;
    }

}
