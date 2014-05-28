<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CardForm extends CFormModel
{
    private $_identity;
    public $user_id;
    public $last_name;
    public $first_name;
    public $name;
    public $number;
    public $expire;
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('user_id, first_name, last_name, name, number, expire,user_id', 'required'),
            array('user_id, number', 'numerical', 'integerOnly' => true),
            array('user_id, first_name, last_name, name, number, expire,user_id', 'safe'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        );
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function save()
    {
        $company = new Card();
        $company->attributes=$this->attributes;
        $company->date_create=new CDbExpression('NOW()');
        if($company->validate()){
            $company->save();
        }
        else
            print_r($company->getErrors());
    }

    public function findByPk($id)
    {
        return;
        /*$model = new CompanyForm;
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
        return $model;*/
    }

}
