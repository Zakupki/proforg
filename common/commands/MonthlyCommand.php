<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:40
 */ class MonthlyCommand extends CConsoleCommand {

    public function run($args)
    {
        $users=User::model()->getMonthReportUser();
        if($users)
        {
            foreach($users as $user){
                if(isset(Yii::app()->controller))
                    $controller = Yii::app()->controller;
                else
                    $controller = new CController('YiiMail');

                $controller->layout='mail';
                $viewPath = Yii::getPathOfAlias('frontend.views/mail/weekreport').'.php';
                $body = $controller->renderInternal($viewPath, array('user'=>$user), true);

                $queue = new EmailQueue();
                $queue->to_email = 'dmitriy.bozhok@gmail.com';
                $queue->subject = "Ежемесячный отчет по закупкам";
                $queue->from_email = 'support@zakupki-online.com';
                $queue->from_name = 'Zakupki-online';
                $queue->date_published = new CDbExpression('NOW()');
                $queue->message =
                '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <title>zakupki-online.com</title>
                </head>
                <body style="font-family: arial, sans-serif; font-size: 14px;">
                <table width="580" align="center" cellspacing="0" cellpadding="20" bgcolor="#272a2b">
                    <tr>
                        <td>
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left"><a href="http://zakupki-online.com/" target="_blank"
                                                        style="color: #ffffff !important; text-decoration: none;	font-size: 14px; font-weight: bold;">zakupki-online.com</a>
                                    </td>
                                    <td align="center"><img src="http://zakupki-online.com/img/zakupki-logo-email.gif" alt=""
                                                            width="42" height="42"/></td>
                                    <td align="right"><a style="color: #ffffff !important;	font-size: 14px; font-weight: bold;">+38
                                            044 233 71 45</a></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <table width="100%" cellspacing="0" cellpadding="20" bgcolor="#d4d9de">
                                            <tr>
                                                <td>
                                                       <h2>Добрый день, '.$user['first_name'].' '.$user['last_name'].'!</h2>
                                                       <p>Итоги прошедшего месяца ('.date("m.Y",strtotime("-1 month")).'-'.date("m.Y").') по компании '.$user['company'].':</p><br\>
                                                       <p>1. Общая сумма закупок составила '.$user['total'].' грн.;<p/>
                                                       <p>2. Экономия '.$user['economy_sum'].' грн.;<p/>
                                                       <p>3. Потенциал экономии '.$user['lose_total'].' грн.;<p/>
                                                       <p>4. Сделок в системе '.$user['purchase_num'].';<p/>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                </body>
                </html>'
                ;
                $queue->save();
            }
        }

        $users2=User::model()->getEfficiencyAnalyticsUser();
    }
}