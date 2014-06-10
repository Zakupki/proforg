<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RequestForm extends CFormModel
{
    private $_identity;
    public $user_id;
    public $available;
    public $value;
    public $company_id;
    public $card_id;
    public $finance_id;
    public $left;
    public $commission;
    public $requesttype_id;
    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('company_id, finance_id, value', 'required'),
            array('company_id, finance_id, user_id, card_id, requesttype_id', 'numerical', 'integerOnly' => true),
            array('value, available, left, commission', 'numerical'),
            array('available, left, commission', 'safe'),
            array('company_id', 'exist', 'className' => 'Company', 'attributeName' => 'id'),
            array('finance_id', 'exist', 'className' => 'Finance', 'attributeName' => 'id'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        );
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function save()
    {
        $company = new Request();
        if ($this->value > 0 && $this->requesttype_id!=3)
            $this->value = -$this->value;
        $company->attributes=$this->attributes;
        $company->date_create=new CDbExpression('NOW()');
        $company->user_id=yii::app()->user->getId();
        if($company->validate()){
            $company->save();
        }
        else
            print_r($company->getErrors());
    }

}
