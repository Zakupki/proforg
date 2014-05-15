<?php
/**
 * This is the model class for table "{{email_queue}}".
 *
 * The followings are the available columns in table '{{email_queue}}':
 * @property integer $id
 * @property string $from_name
 * @property string $from_email
 * @property string $to_email
 * @property string $subject
 * @property string $message
 * @property integer $max_attempts
 * @property integer $attempts
 * @property integer $success
 * @property string $date_published
 * @property string $last_attempt
 * @property string $date_sent
 */
class EmailQueue extends BaseActiveRecord
{
    public $title;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EmailQueue the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{email_queue}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('from_email, to_email, subject, message', 'required'),
            array('max_attempts, attempts, success', 'numerical', 'integerOnly' => true),
            array('from_name', 'length', 'max' => 64),
            array('from_email, to_email', 'length', 'max' => 128),
            array('subject', 'length', 'max' => 255),
            array('date_published, attachfile, last_attempt, date_sent', 'safe'),

            array('id, from_name, from_email, to_email, subject, message, max_attempts, attempts, success, date_published, last_attempt, date_sent', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'from_name' => 'From Name',
            'from_email' => 'From email',
            'to_email' => 'To email',
            'subject' => 'Subject',
            'message' => 'Message',
            'max_attempts' => 'Max Attempts',
            'attempts' => 'Attempts',
            'success' => 'Success',
            'date_published' => 'Date Published',
            'last_attempt' => 'Last Attempt',
            'date_sent' => 'Date Sent',
            'attachfile'=> 'Attach'
        );
    }
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id',$this->id);
        $criteria->compare('t.to_email',$this->to_email,true);

        return parent::searchInit($criteria);
    }
}
?>