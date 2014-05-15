<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoanForm extends CFormModel
{
    public $value;
    public $days;

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
            array('value,days', 'required'),
            // rememberMe needs to be a boolean
        );
    }
    public function save(){
        $loan=new Loan;
        $loan->days=$this->days;
        $loan->value=$this->value;
        $loan->user_id=yii::app()->user->getId();
        $loan->date_create= new CDbExpression('NOW()');
        $loan->save();
        Yii::app()->session['check_loan']=1;
    }

}
