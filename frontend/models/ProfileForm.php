<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ProfileForm extends CFormModel
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
    /*public $familyname;
    public $delivery_addr;
    public $password;
    public $repeat_password;
    public $company;
    public $markets;
    public $address;
    public $position;
    public $marketsids;
    public $phones;
    public $personalphones;*/


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

        $rules[] = array('id,last_name,first_name', 'required');
        //$rules[] = array('personalphones','type','type'=>'array','allowEmpty'=>false);
        $rules[] = array('personalphones', 'checkpersonalPhones');
        $rules[] = array('position,tagstitles,tagsids,personalphones', 'safe');
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

        if (!$this->getErrors()) {

            $user = User::model()->findByPk(user()->getId());
            $user->last_name = $this->last_name;
            $user->first_name = $this->first_name;
            $user->position = $this->position;
            if (isset($this->password))
                $user->password = $this->password;
            $user->save();

            #Теги пользователя
            $oldtagArr = $savedTags = array();
            if ($oldtags = UserTag::model()->findAllByAttributes(array('user_id' => user()->getId())))
                $oldtagArr = CHtml::listData($oldtags, 'tag_id', 'tag_id');
            if (isset($this->tagsids)) {

                $tagArr = explode(',', $this->tagsids);

                if (isset($this->tagstitles))
                    $tagtitlesArr = explode(',', $this->tagstitles);

                if (count($tagArr) > 0) {

                    foreach ($tagArr as $k => $m) {

                        if (in_array($m, $oldtagArr)) {
                            $savedTags[$m] = $m;
                            continue;
                        }

                        if ($m > 0) {
                            $usertag = new UserTag();
                            $usertag->tag_id = $m;
                            $usertag->user_id = $user->id;
                            $usertag->save();
                            $savedTags[$usertag->tag_id] = $usertag->tag_id;
                        } else {
                            if (strlen($tagtitlesArr[$k]) > 0) {
                                $tag = Tag::model()->findByAttributes(array('title' => strtolower($tagtitlesArr[$k])));
                                if (!$tag) {
                                    $tag = new Tag;
                                    $tag->title = $tagtitlesArr[$k];
                                    $tag->user_id = $user->id;
                                    $tag->save();
                                }
                                if (isset($tag->id)) {
                                    $usertag = new UserTag();
                                    $usertag->tag_id = $tag->id;
                                    $usertag->user_id = $user->id;
                                    $usertag->save();
                                    $savedTags[$usertag->tag_id] = $usertag->tag_id;
                                }
                            }
                        }

                    }
                }

            }
            if (count($savedTags) > 0)
                UserTag::model()->deleteAll('tag_id NOT IN(' . implode(',', $savedTags) . ') AND user_id=:user_id', array('user_id' => user()->getId()));
            else
                UserTag::model()->deleteAll('user_id=:user_id', array('user_id' => user()->getId()));


            #Телефоны пользователя
            $savedPhones = array();
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
                        $companyphone->attributes = $phone;
                        $companyphone->save();

                        if ($companyphone->id > 0)
                            $savedPhones[$companyphone->id] = $companyphone->id;
                    }
                }
            }
            if (count($savedPhones) > 0)
                Phone::model()->deleteAll('id NOT IN(' . implode(',', $savedPhones) . ') AND user_id=:user_id', array('user_id' => user()->getId()));
            else
                Phone::model()->deleteAll('user_id=:user_id', array('user_id' => user()->getId()));

            if (!$user->getErrors())
                return true;
        }
    }

    public function findByPk($id)
    {
        $profile = new ProfileForm;
        $user = User::model()->findByPk($id);

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
        $profile->last_name = $user->last_name;
        $profile->email = $user->email;
        $profile->first_name = $user->first_name;
        $profile->position = $user->position;
        return $profile;
    }


}
