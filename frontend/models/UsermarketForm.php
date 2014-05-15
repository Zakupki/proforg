<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UsermarketForm extends CFormModel
{
    public $id;
    public $market_id;
    public $markettype_id;


    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {

        $rules[] = array('id,market_id,markettype_id', 'safe');
        return $rules;
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */

    /*public function checkproducts(){
        $this->addError('products','123');
    }*/

    public function save()
    {
        if (!$this->getErrors()) {
            $old_marketsArr = array();
            if ($old_markets = UserMarket::model()->findAllByAttributes(array('user_id' => user()->getId())))
                $old_marketsArr = CHtml::listData($old_markets, 'market_id', 'market_id');
            if (count($this->market_id)) {
                $this->market_id = array_keys($this->market_id);
                foreach ($this->market_id as $m) {
                    if (in_array($m, $old_marketsArr))
                        continue;
                    else
                    $market = new UserMarket;
                    $market->market_id = $m;
                    $market->user_id = user()->getId();
                    $market->save();
                }
                UserMarket::model()->deleteAll('user_id=:user_id AND market_id not in(' . implode(',', $this->market_id) . ')', array('user_id' => user()->getId()));
            } else
                self::deleteUsers();
            return true;
        }
    }

    public function deleteUsers()
    {
        UserMarket::model()->deleteAll('user_id=:user_id', array('user_id' => user()->getId()));
    }

}
