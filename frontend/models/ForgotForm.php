<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ForgotForm extends CFormModel
{
    public $email;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('email', 'required'),
            // rememberMe needs to be a boolean
            array('email','email'),
        );
    }

    public function retrieve()
    {
        $user=User::model()->findByAttributes(array('email'=>$this->email));
        if($user){
            $user->retrieve_code=md5(microtime().$user->email.MD5_KEY.rand());
            $user->save();
            $key=new Key;
            $key->token=$user->retrieve_code;
            $key->user_id=$user->id;
            $key->date_create=new CDbExpression('NOW()');
            $key->save();
            $contr=Yii::app()->controller;
            $contr->layout="mail";
            $body =$contr->render('/mail/retrieve', array('user'=>$user), true);
            $queue = new EmailQueue();
            $queue->to_email = trim($this->email);
            $queue->subject = "Восстановление пароля";
            $queue->from_email = 'support@zakupki-online.com';
            $queue->from_name = 'Zakupki-online';
            $queue->date_published = new CDbExpression('NOW()');
            $queue->message = $body;
            $queue->save();

        }
    }
}
