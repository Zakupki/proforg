<?php
class EmailQueueController extends BackController
{
    public function actionBody()
    {
        $mail=EmailQueue::model()->findByPk($_GET['id']);
        echo $mail->message;

    }
}