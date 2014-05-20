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

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {

        if (isset($_POST['ProfileForm']['old_password']) || isset($_POST['ProfileForm']['password']) || isset($_POST['ProfileForm']['repeat_password'])) {

            if (strlen($_POST['ProfileForm']['old_password']) > 0 || strlen($_POST['ProfileForm']['password']) > 0 || strlen($_POST['ProfileForm']['repeat_password']) > 0) {

                $rules[] = array('password,old_password,repeat_password', 'required');

                $rules[] = array('old_password', 'length', 'min' => 6, 'max' => 12);
                $rules[] = array('password', 'length', 'min' => 6, 'max' => 12);
                $rules[] = array('repeat_password', 'length', 'min' => 6, 'max' => 12);

                $rules[] = array('password', 'compare', 'compareAttribute' => 'repeat_password');
                $rules[] = array('password', 'compare', 'compareAttribute' => 'old_password', 'operator' => '!=');
                $rules[] = array('old_password', 'checkPassword');

            }
        }

        $rules[] = array('employer_id,name,first_name,last_name', 'required');
        //$rules[] = array('personalphones','type','type'=>'array','allowEmpty'=>false);
        $rules[] = array('position', 'safe');
        return $rules;
    }

    public function checkPassword()
    {
        $user = User::model()->findByPk(user()->getId());
        if (!$user->checkPass($this->old_password))
            $this->addError('old_password', 'Wrong old password');
    }

    public function checkpersonalPhones($attribute)
    {
        //echo $attribute;
        if (count($this->personalphones) > 0) {
            foreach ($this->personalphones as $k => $phone) {
                if (!$phone['phonecode'] || !$phone['phone'] || !$phone['countrycode'])
                    unset($this->personalphones[$k]);
            }
        }
        if (count($this->personalphones) < 1)
            $this->addError('personalphones', 'Укажите контактный телефон');
    }

    public function save()
    {
        $user = new User;
        print_r($this->getErrors());
        if (!$this->getErrors()) {
            $user->name = $this->name;
            $user->first_name = $this->first_name;
            if (isset($this->password))
                $user->password = $this->password;
            $user->save();
            if (!$user->getErrors())
                return true;
            else
                print_r($user->getErrors());
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
