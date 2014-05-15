<?php
class FeedbackForm extends MailForm
{
    public $name;
    public $email;
    public $to;
    public $message;

    public function rules()
    {
        return array(
            array('name, message', 'required'),
            array('to', 'exist', 'className' => 'Shop', 'attributeName' => 'pid', 'criteria' => array(
                'condition' => 'status = 1'
            )),
            array('email', 'safe'),
        );
    }

    public function send($opts = array(), $lang = null)
    {
        /** @var Shop $shop */
        //$shop = Shop::model()->language()->active()->pid($this->to)->find();
        //if($shop)
        $opts['to'] = "dmitriy.bozhok@gmail.com";

        return parent::send($opts, $lang);
    }
}