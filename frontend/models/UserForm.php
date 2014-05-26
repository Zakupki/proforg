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

        $rules[] = array('employer_id,name,first_name,salaryday,salary,last_name,email', 'required');
        $rules[] = array('password,salary', 'safe');
        return $rules;
    }

    public function save()
    {
        $user = new User;
        if (!$this->getErrors()) {
            $user->name = $this->name;
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;
            $user->email = $this->email;
            $user->employer_id = $this->employer_id;
            $user->salary = $this->salary;
            $user->salaryday = $this->salaryday;
            $user->usertype_id = 2;
            $user->password = Yii::app()->epassgen->generate();
            $user->save();
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


}
