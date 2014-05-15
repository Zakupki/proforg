<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:40
 */ class MailerCommand extends CConsoleCommand {
    public function run($args)
    {
        //Yii::setPathOfAlias('mynamespace', '/var/www/common/mynamespace/');

       // Yii::import('common.extensions.yii-mail.*');
       // Yii::import('extensions.yii-mail.*');

        $criteria = new CDbCriteria(array(
            'condition' => 'success=:success AND attempts < max_attempts',
            'params' => array(
                ':success' => 0,
            ),
        ));

        $queueList = EmailQueue::model()->findAll($criteria);

        /* @var $queueItem EmailQueue */
        foreach ($queueList as $queueItem)
        {
            $validator = new CEmailValidator;
            if(!$validator->validateValue(trim($queueItem->to_email,'\'')))
            {
                continue;
            }

            $message = new YiiMailMessage();
            $message->setTo(trim($queueItem->to_email,'\''));
            $message->setFrom(array($queueItem->from_email => $queueItem->from_name));
            $message->setSubject($queueItem->subject);

            if(isset($queueItem->attachfile)){
                $swiftAttachment = Swift_Attachment::fromPath('/var/www/newzakupki/newzakupki.reactor.ua/'.$queueItem->attachfile);
                $message->attach($swiftAttachment);

            }

            /*if($queueItem->to_email=='dmitriy.bozhok@gmail.com'){
                $swiftAttachment = Swift_Attachment::fromPath('/var/www/newzakupki/newzakupki.reactor.ua/upload/Zakupki_dlya_adminov.docx');
                $message->attach($swiftAttachment);

            }*/

            $message->setBody($queueItem->message, 'text/html');


            if ($this->sendEmail($message))
            {
                $queueItem->attempts = $queueItem->attempts + 1;
                $queueItem->success = 1;
                $queueItem->last_attempt = new CDbExpression('NOW()');
                $queueItem->date_sent = new CDbExpression('NOW()');

                $queueItem->save();
            }
            else
            {
                $queueItem->attempts = $queueItem->attempts + 1;
                $queueItem->last_attempt = new CDbExpression('NOW()');

                $queueItem->save();
            }
        }
    }

    /**
     * Sends an email to the user.
     * This methods expects a complete message that includes to, from, subject, and body
     *
     * @param YiiMailMessage $message the message to be sent to the user
     * @return boolean returns true if the message was sent successfully or false if unsuccessful
     */
    private function sendEmail(YiiMailMessage $message)
    {
        $sendStatus = false;

        if (Yii::app()->mail->send($message) > 0)
            $sendStatus = true;

        return $sendStatus;
    }
}