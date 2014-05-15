<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CompanyUserForm extends CFormModel
{
    public $id;
    public $last_name;
    public $first_name;
    public $email;
    public $position;
    public $old_password;
    public $password;
    public $repeat_password;
    public $tagstitles;
    public $tagsids;
    public $personalphones;
    public $companyuser_id;
    public $companyrole_id;
    public $company_id;
    public $companytitle;


    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        //echo $this->scenario;
        $rules[] = array('last_name,first_name,company_id,email,companyrole_id', 'required', 'on'=>'create');
        $rules[] = array('id,companyrole_id', 'required', 'on'=>'update');
        $rules[] = array('personalphones', 'checkpersonalPhones', 'on'=>'crate');
        $rules[] = array('email', 'uniqueemail','on'=>'create');
        $rules[] = array('companyuser_id,company_id,position,tagstitles,tagsids,personalphones', 'safe');
        return $rules;
    }

    public function checkPassword()
    {
        $user = User::model()->findByPk(user()->getId());
        if (!$user->checkPass($this->old_password))
            $this->addError('old_password', 'Wrong old password');
    }
    public function uniqueemail()
    {
        if(User::model()->find('email="'.$this->email.'"'))
            $this->addError('email','Пользователь с таким email уже зарегистрирован');
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

        if (!$this->getErrors()) {

                if($this->scenario=="update"){

                }elseif($this->scenario=="create"){

                    if(!isset($this->id)){
                        $user = new User;
                        $user->last_name = $this->last_name;
                        $user->email = $this->email;
                        $user->password = Yii::app()->epassgen->generate();
                        $user->first_name = $this->first_name;
                        $user->position = $this->position;
                        $user->save();
                        if($user->getErrors())
                            $this->addErrors($user->getErrors());
                        if(isset($user->id)){
                            $this->id=$user->id;
                            #Теги пользователя
                            if (isset($this->tagsids)) {
                                $tagArr = explode(',', $this->tagsids);
                                if (isset($this->tagstitles))
                                    $tagtitlesArr = explode(',', $this->tagstitles);
                                if (count($tagArr) > 0) {
                                    foreach ($tagArr as $k => $m) {
                                        if ($m > 0) {
                                            $usertag = new UserTag();
                                            $usertag->tag_id = $m;
                                            $usertag->user_id = $user->id;
                                            $usertag->save();
                                            $savedTags[$usertag->tag_id] = $usertag->tag_id;
                                        } else {
                                            if (strlen($tagtitlesArr[$k]) > 0) {

                                                $tag->id=Tag::model()->getTag(strtolower($tagtitlesArr[$k]));
                                                if (isset($tag->id)) {
                                                    $usertag = new UserTag();
                                                    $usertag->tag_id = $tag->id;
                                                    $usertag->user_id = $user->id;
                                                    $usertag->save();
                                                    if($usertag->getErrors())
                                                        $this->addErrors($usertag->getErrors());
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            #Телефоны пользователя
                            if (isset($this->personalphones)) {
                                foreach ($this->personalphones as $phone) {
                                    if ($phone['phone'] > 0 && $phone['phonecode'] > 0) {
                                        if ($phone['id'] > 0) {
                                            $companyphone = Phone::model()->findByPk($phone['id']);
                                        } else {
                                            $companyphone = new Phone;
                                        }
                                        if ($phone['countrycode'] == 38) {
                                            $phone['country_id'] = 1;
                                            unset($phone['countrycode']);
                                        }
                                        $phone['user_id'] = $user->id;
                                        unset($phone['countrycode']);
                                        $companyphone->attributes = $phone;
                                        $companyphone->save();
                                        if($companyphone->getErrors())
                                            $this->addErrors($companyphone->getErrors());
                                    }
                                }
                            }
                        }
                    }
                }

                if(isset($this->companyuser_id))
                    $companyuser=CompanyUser::model()->findByPk($this->companyuser_id);
                else {
                    $companyuser=new CompanyUser;
                    $companyuser->company_id=$this->company_id;
                }
                if(isset($user->id)){
                    $companyuser->user_id=$this->id;
                    $companyuser->status=1;
                }
                if(isset($this->id)){
                    $companyuser->user_id=$this->id;
                    //$companyuser->company_id=$this->company_id;
                    $companyuser->status=1;
                }
                $companyuser->companyrole_id=$this->companyrole_id;
                $companyuser->save();
                if($companyuser->getErrors())
                  $this->addErrors($companyuser->getErrors());

            if (!$this->getErrors())
                return true;
            else
                print_r($this->getErrors());
        }
    }

    public function findByLink($id)
    {
        $profile = new CompanyUserForm;
        $user = CompanyUser::model()->with('user', 'company')->findByPk($id);
        if (isset($user)) {
            $profile->companyuser_id = $id;
            $profile->id = $user->user->id;
            $profile->last_name = $user->user->last_name;
            $profile->email = $user->user->email;
            $profile->first_name = $user->user->first_name;
            $profile->position = $user->user->position;
            $profile->companyrole_id = $user->companyrole_id;
            $profile->companytitle = $user->company->title;

            $tags = UserTag::model()->with('tag')->findAllByAttributes(array('user_id' => $user->user->id));
            if ($tags) {
                $tags = CHtml::listData($tags, 'tag.id', 'tag.title');
                $profile->tagsids = implode(',', array_keys($tags));
                $profile->tagstitles = implode(',', $tags);
            }
            $phones = Phone::model()->findAllByAttributes(array('user_id' => $user->user->id));
            if ($phones)
                $profile->personalphones = $phones;

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
