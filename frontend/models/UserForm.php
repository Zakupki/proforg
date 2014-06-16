<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserForm extends CFormModel
{
    public $id;
    public $name;
    public $first_name;
    public $last_name;
    public $employer_id;
    public $email;
    public $old_password;
    public $password;
    public $repeat_password;
    public $salary;
    public $salaryday;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {

        $rules[] = array('employer_id,name,first_name,salaryday,salary,last_name', 'required');
        $rules[] = array('email','required','on'=>'create');
        $rules[] = array('password,salary,id', 'safe');
        $rules[] = array('email', 'uniqueemail', 'on'=>'create');
        return $rules;
    }
    public function uniqueemail()
    {
        if (User::model()->find('email="' . $this->email . '"'))
            $this->addError('email', 'Пользователь с таким email уже зарегистрирован');
    }
    public function save()
    {
        Yii::import('common.extensions.yii-mail.*');
        if(isset($this->id)){
            $user=User::model()->findByPk($this->id);
        }else
        $user = new User('create');
        if (!$this->getErrors()) {
            $user->name = $this->name;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->email = $this->email;
            $user->employer_id = $this->employer_id;
            $user->salary = $this->salary;
            $user->salaryday = $this->salaryday;
            $user->usertype_id = 2;
            $password=Yii::app()->epassgen->generate();
            $user->password = $password;
            $user->save();
            if(isset($user->id) && !isset($this->id)){

                $company=Company::model()->findByPk($this->employer_id);

                $body ='
                Добрый день, '.$this->first_name.' '.$this->name.' '.$this->last_name.'<br/><br/>
                Вы были зарегистрированы как сотрудник компании '.$company->title.'.<br/>
                Ваш оклад: '.$this->salary.'<br/>
                День зарплаты: '.$this->salaryday.'<br/>

                Данные для входа:<br/>
                Ваш логин '.$this->email.'<br/>
                Ваш пароль '.$password.'<br/>
                ';
                $message = new YiiMailMessage();
                $message->setBody($body, 'text/html');
                $message->setSubject('Регистрация');
                $message->setTo($user->email);
                $message->setFrom(array(
                    'info@zakupki-online.com' => 'Prod-org.com'
                ));

                Yii::app()->mail->send($message);
            }
            print_r($user->getErrors());
            if (!$user->getErrors())
                return true;
        }
    }

    public function findByLink($id)
    {
        $profile = new UserForm;

        $user=CompanyUser::model()->with('user')->findByPk($id);
        if(isset($user)){
            $profile->user_id=$user->user->id;
            $profile->name=$user->user->name;
            $profile->first_name=$user->user->first_name;
            $profile->position=$user->user->position;
        }
       /* $user = User::model()->findByPk($id);

        $tags = UserTag::model()->with('tag')->findAllByAttributes(array('user_id' => user()->getId()));
        if ($tags) {
            $tags = CHtml::listData($tags, 'tag.id', 'tag.title');
            $profile->tagsids = implode(',', array_keys($tags));
            $profile->tagstitles = implode(',', $tags);

        }
        $phones = Phone::model()->findAllByAttributes(array('user_id' => user()->getId()));
        if ($phones)
            $profile->personalphones = $phones;
        $profile->id = $user->id;
        $profile->name = $user->name;
        $profile->email = $user->email;
        $profile->first_name = $user->first_name;
        $profile->position = $user->position;*/
        return $profile;
    }
    public static function findByPk($id){
        $model = new UserForm;
        $user=User::model()->findByPk($id);
        $model->attributes=$user->attributes;
        return $model;
    }


}
