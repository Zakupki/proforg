<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ProfileForm extends CFormModel
{
    public $id;


    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {

        $rules[] = array('password,old_password,repeat_password', 'required');
        $rules[] = array('old_password', 'length', 'min' => 6, 'max' => 12);
        $rules[] = array('password', 'length', 'min' => 6, 'max' => 12);
        $rules[] = array('repeat_password', 'length', 'min' => 6, 'max' => 12);
        $rules[] = array('password', 'compare', 'compareAttribute' => 'repeat_password');
        $rules[] = array('password', 'compare', 'compareAttribute' => 'old_password', 'operator' => '!=');
        $rules[] = array('old_password', 'checkPassword');

        $rules[] = array('id,last_name,first_name', 'required');
        //$rules[] = array('personalphones','type','type'=>'array','allowEmpty'=>false);
        $rules[] = array('personalphones', 'checkpersonalPhones');
        $rules[] = array('position,tagstitles,tagsids,personalphones', 'safe');
        return $rules;
    }

    public function save()
    {
        #ОТПРАВКА EMAIL
        $contr=Yii::app()->controller;
        $contr->layout="mail";
        $body =$contr->render('/mail/invite_email', array('products' => Product::model()->with(array('tag','unit'))->findAllByAttributes(array('purchase_id'=>$purchase->id)), 'purchase'=>$purchase), true);
        $queue = new EmailQueue();
        $queue->to_email = trim($this->to_email);
        $queue->subject = "Приглашение принять участие в торгах";
        $queue->from_email = 'support@zakupki-online.com';
        $queue->from_name = 'Zakupki-online';
        $queue->date_published = new CDbExpression('NOW()');
        $queue->message = $body;
        $queue->save();
    }


}
