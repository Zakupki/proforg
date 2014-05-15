<?php
/**
 * Created by PhpStorm.
 * User: Orange
 * Date: 17.12.13
 * Time: 13:40
 */ class WeekreportCommand extends CConsoleCommand {

    public function run($args)
    {
        $users=User::model()->getWeekAnalyticsUser();
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
                $queue->to_email = 'shevtsova@zakupki-online.com';
                $queue->subject = "Еженедельный отчет по закупкам менеджера";
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
                                                       <p>По итогам прошедшей недели ('.date("d.m.Y",strtotime("-1 week")).'-'.date("d.m.Y").') Вы:</p><br\>
                                                       <p>1.	Провели через систему '.$user['purchase_num'].' сделок на общую сумму закупок '.number_format($user['total'], 3, '.', ' ').' грн.;</p>
                                                       <p>2.	Снижение цен во время торгов составило '.number_format($user['economy_sum'], 3, '.', ' ').' грн.;</p>
                                                       <p>3.	Конкурентная среда по сделкам в среднем составляет '.$user['avg_company_num'].' поставщика. В '.$user['not_concurent'].' из '.$user['purchase_num'].' сделок принял участие только 1-н поставщик.</p><br\>
                                                       <p>Подробнее во вложенном файле.</p>
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

                #PDF
                $pdf = Yii::createComponent('common.extensions.tcpdf.ETcPdf',
                    'L', 'cm', 'A4', true, 'UTF-8');
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor("Lpovar");
                $pdf->SetTitle("Товарный чек");
                $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
                $pdf->setHeaderFont(Array('dejavusans', '', 10));
                $pdf->SetMargins(1, 3, 1);
                $pdf->SetHeaderMargin(0.5);
                $pdf->SetHeaderData(
                    'blank-pdf-logo.jpg',
                    4,
                    'zakupki-online.com',
                    'г. Киев, ул.Воздвиженская, 48, тел: (095) 232 39 68',
                    array(0,0,0),
                    array(103,181,110)
                );
                $pdf->setPrintFooter(false);
                $pdf->AddPage();
                $purchasedata = Purchase::model()->managerPurchases(array('user_id' => $user['user_id']));
                $html = '<html>
<head>
    <title>Zakupki-Online.com</title>

    <style>
        .body{
            width: 100%;
            /*font-family: arial, sans-serif;*/
            font-family: dejavusans;
            font-size: 8px;
        }

        .wrap{
            padding: 0 40px;
        }

        .logo{
            text-align: center;
        }

        table{
            width: 100%;
            font-size: 8px;
        }

        table, td{
            border: none;
        }

        .green-border-bottom{
            border-bottom: 4px solid #67b56e;
        }

        .green-border-top{
            border-top: 4px solid #67b56e;
        }

        .green-border-bottom td,
        .green-border-top td{
            padding: 10px 0;
        }

        .content{
            /*min-height: 320px;*/
        }

        .gray{
            color: #9da5a7;
            font-size: 90%;
        }

    </style>
</head>
<body>
<div class="body">
    <h2>Данные итогам прошедшей недели ('.date("d.m.Y",strtotime("-1 week")).'-'.date("d.m.Y").') по покупателю '.$user['first_name'].' '.$user['last_name'].'</h2>
    <p>1.	Провели через систему '.$user['purchase_num'].' сделок на общую сумму закупок '.number_format($user['total'], 3, '.', ' ').' грн.;</p>
    <p>2.	Снижение цен во время торгов составило '.number_format($user['economy_sum'], 3, '.', ' ').' грн.;</p>
    <p>3.	Конкурентная среда по сделкам в среднем составляет '.$user['avg_company_num'].' поставщика. В '.$user['not_concurent'].' из '.$user['purchase_num'].' сделок принял участие только 1-н поставщик.</p><br\>
    <p>Список Ваших сделок за прошедшую неделю. Так же можете ознакомиться с ними по ссылке <a href="http://zakupki-online.com/report/#/purchases/user/'.$user['user_id'].'">http://zakupki-online.com/report/#/purchases/user/'.$user['user_id'].'</a>:</p>
    <div class="wrap">
        <div class="content">
        <table class="stats-table" border="1">
            <tr bgcolor="#cccccc">
                <th>№</th>
                <th>Дата закрытия</th>
                <th>Организация</th>
                <th>Рынок</th>
                <th>Предмет торгов</th>
                <th>Кто закрыл</th>
                <th>Учасников</th>
                <th>Экономия (<span class="units">%</span>)</th>
                <th>Экономия (<span class="units">грн.</span>)</th>
                <th>Потери (<span class="units">грн.</span>)</th>
                <th>Сумма (<span class="units">грн.</span>)</th>
            </tr>
            ';
            $economy_total=0;
            $lose_total=0;
            $all_total=0;

            foreach($purchasedata['purchases'] as $purchase){
            $economy_total=$economy_total+$purchase['economy_sum'];
            $lose_total=$lose_total+$purchase['lose_total'];
            $all_total=$all_total+$purchase['total'];

            $html.='<tr>
                <td class="nowrap">'.$purchase['id'].'</td>
                <td>'.$purchase['date_closed'].'</td>
                <td>'.$purchase['company_title'].'</td>
                <td>'.$purchase['market_title'].'</td>';
                if(isset($purchasedata['products'][$purchase['id']]))
                    $html.='<td>'.$purchasedata['products'][$purchase['id']].'</td>';
                else
                    $html.='<td></td>';
                $html.='
                <td>'.$purchase['first_name'].' '.$purchase['last_name'].'</td>
                <td>'.$purchase['company_num'].'</td>';
                if($purchase['total']>0)
                    $html.='<td class="bigger">'.round($purchase['economy_sum']/$purchase['total']*100, 2, PHP_ROUND_HALF_UP).'</td>';
                else
                    $html.='<td class="bigger">0</td>';
                $html.='
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['economy_sum'], 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['lose_total'], 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['total'], 3, '.', ' ')).'</td>
            </tr>
            ';
            }
            $html.='
            <tr class="totals">
                <td class="itogogo" colspan="8">Итого:</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($economy_total, 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($lose_total, 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($all_total, 3, '.', ' ')).'</td>
            </tr>
        </table>

        </div>
        <table>
            <tr>
                <td align="right" width="80%">
                    &nbsp;<br>
                    <span class="gray">Координатор проекта Zakupki-online.com</span><br>
                    Шевцова Татьяна
                </td>
                <td align="right" width="20%"><img src="/var/www/newzakupki/newzakupki.reactor.ua/img/blank-pdf-signature.jpg"></td>
            </tr>
        </table>
    </div>
</div>
</body>

</html>
';
                $pdf->writeHTML($html, true, false, false, false, '');
                $file_name="upload/weekreport/userreport/report_".$user['user_id']."_".time().".pdf";
                $pdf->Output("/var/www/newzakupki/newzakupki.reactor.ua/".$file_name."", "F");
                $queue->attachfile=$file_name;
                $queue->save();
            }
        }




        $users1=User::model()->getWeekAnalyticsUserOrg();
        if($users1)
        {
            foreach($users1 as $user){
                if(isset(Yii::app()->controller))
                    $controller = Yii::app()->controller;
                else
                    $controller = new CController('YiiMail');

                $controller->layout='mail';
                $viewPath = Yii::getPathOfAlias('frontend.views/mail/weekreport').'.php';
                $body = $controller->renderInternal($viewPath, array('user'=>$user), true);

                $queue = new EmailQueue();
                $queue->to_email = 'dmitrshevtsova@zakupki-online.com';
                $queue->subject = "Еженедельный отчет по закупкам";
                $queue->from_email = 'support@zakupki-online.com';
                $queue->from_name = 'Zakupki-online';
                $queue->date_published = new CDbExpression('NOW()');

                #PDF
                $pdf = Yii::createComponent('common.extensions.tcpdf.ETcPdf',
                    'L', 'cm', 'A4', true, 'UTF-8');
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor("Lpovar");
                $pdf->SetTitle("Товарный чек");
                $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
                $pdf->setHeaderFont(Array('dejavusans', '', 10));
                $pdf->SetMargins(1, 3, 1);
                $pdf->SetHeaderMargin(0.5);
                $pdf->SetHeaderData(
                    'blank-pdf-logo.jpg',
                    4,
                    'zakupki-online.com',
                    'г. Киев, ул.Воздвиженская, 48, тел: (095) 232 39 68',
                    array(0,0,0),
                    array(103,181,110)
                );
                $pdf->setPrintFooter(false);
                $pdf->AddPage();
                $purchasedata = Purchase::model()->managerOrgPurchases(array('user_id' => $user['user_id']));

                $economy_total=0;
                $lose_total=0;
                $all_total=0;
                foreach($purchasedata['purchases'] as $purchase){
                    $economy_total=$economy_total+$purchase['economy_sum'];
                    $lose_total=$lose_total+$purchase['lose_total'];
                    $all_total=$all_total+$purchase['total'];
                }

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
                                                       <p>По итогам прошедшей недели ('.date("d.m.Y",strtotime("-1 week")).'-'.date("d.m.Y").') по вашим компаниям:</p><br\>
                                                       <p>Провели через систему '.count($purchasedata['purchases']).' сделок на общую сумму закупок '.number_format($all_total, 3, '.', ' ').' грн.;</p>
                                                       <p>Подробнее во вложенном файле.</p>
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


                $html = '<html>
<head>
    <title>Zakupki-Online.com</title>

    <style>
        .body{
            width: 100%;
            /*font-family: arial, sans-serif;*/
            font-family: dejavusans;
            font-size: 8px;
        }

        .wrap{
            padding: 0 40px;
        }

        .logo{
            text-align: center;
        }

        table{
            width: 100%;
            font-size: 8px;
        }

        table, td{
            border: none;
        }

        .green-border-bottom{
            border-bottom: 4px solid #67b56e;
        }

        .green-border-top{
            border-top: 4px solid #67b56e;
        }

        .green-border-bottom td,
        .green-border-top td{
            padding: 10px 0;
        }

        .content{
            /*min-height: 320px;*/
        }

        .gray{
            color: #9da5a7;
            font-size: 90%;
        }

    </style>
</head>
<body>
<div class="body">
    <h2>Данные итогам прошедшей недели ('.date("d.m.Y",strtotime("-1 week")).'-'.date("d.m.Y").') по покупателю '.$user['first_name'].' '.$user['last_name'].'</h2>
    <p>1.	Провели через систему '.count($purchasedata['purchases']).' сделок на общую сумму закупок '.number_format($all_total, 3, '.', ' ').' грн.;</p>
    <p>2.	Снижение цен во время торгов составило '.number_format($economy_total, 3, '.', ' ').' грн.;</p>
    <div class="wrap">
        <div class="content">
        <table class="stats-table" border="1">
            <tr bgcolor="#cccccc">
                <th>№</th>
                <th>Дата закрытия</th>
                <th>Организация</th>
                <th>Рынок</th>
                <th>Предмет торгов</th>
                <th>Кто закрыл</th>
                <th>Учасников</th>
                <th>Экономия (<span class="units">%</span>)</th>
                <th>Экономия (<span class="units">грн.</span>)</th>
                <th>Потери (<span class="units">грн.</span>)</th>
                <th>Сумма (<span class="units">грн.</span>)</th>
            </tr>
            ';
                $economy_total=0;
                $lose_total=0;
                $all_total=0;

                foreach($purchasedata['purchases'] as $purchase){
                    $economy_total=$economy_total+$purchase['economy_sum'];
                    $lose_total=$lose_total+$purchase['lose_total'];
                    $all_total=$all_total+$purchase['total'];

                    $html.='<tr>
                <td class="nowrap">'.$purchase['id'].'</td>
                <td>'.$purchase['date_closed'].'</td>
                <td>'.$purchase['company_title'].'</td>
                <td>'.$purchase['market_title'].'</td>';
                    if(isset($purchasedata['products'][$purchase['id']]))
                        $html.='<td>'.$purchasedata['products'][$purchase['id']].'</td>';
                    else
                        $html.='<td></td>';
                    $html.='
                <td>'.$purchase['first_name'].' '.$purchase['last_name'].'</td>
                <td>'.$purchase['company_num'].'</td>';
                    if($purchase['total']>0)
                        $html.='<td class="bigger">'.round($purchase['economy_sum']/$purchase['total']*100, 2, PHP_ROUND_HALF_UP).'</td>';
                    else
                        $html.='<td class="bigger">0</td>';
                    $html.='
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['economy_sum'], 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['lose_total'], 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['total'], 3, '.', ' ')).'</td>
            </tr>
            ';
                }
                $html.='
            <tr class="totals">
                <td class="itogogo" colspan="8">Итого:</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($economy_total, 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($lose_total, 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($all_total, 3, '.', ' ')).'</td>
            </tr>
        </table>

        </div>
        <table>
            <tr>
                <td align="right" width="80%">
                    &nbsp;<br>
                    <span class="gray">Координатор проекта Zakupki-online.com</span><br>
                    Шевцова Татьяна
                </td>
                <td align="right" width="20%"><img src="/var/www/newzakupki/newzakupki.reactor.ua/img/blank-pdf-signature.jpg"></td>
            </tr>
        </table>
    </div>
</div>
</body>

</html>
';
                $pdf->writeHTML($html, true, false, false, false, '');
                $file_name="upload/weekreport/userreportorg/reportorg_".$user['user_id']."_".time().".pdf";
                $pdf->Output("/var/www/newzakupki/newzakupki.reactor.ua/".$file_name."", "F");
                $queue->attachfile=$file_name;
                $queue->save();
            }
        }





        $users2=User::model()->getEfficiencyAnalyticsUser();
        if($users2)
        {
            foreach($users2 as $user2){
                if(isset(Yii::app()->controller))
                    $controller = Yii::app()->controller;
                else
                    $controller = new CController('YiiMail');

                $controller->layout='mail';
                $viewPath = Yii::getPathOfAlias('frontend.views/mail/weekreport').'.php';
                $body = $controller->renderInternal($viewPath, array('user'=>$user2), true);

                $queue = new EmailQueue();
                $queue->to_email = 'shevtsova@zakupki-online.com';
                $queue->subject = "Еженедельный отчет по потенциалу экономии";
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
                                                       <h2>Добрый день, '.$user2['first_name'].' '.$user2['last_name'].'!</h2>
                                                       <p>По итогам прошедшей недели ('.date("d.m.Y",strtotime("-1 week")).'-'.date("d.m.Y").') в системе Zakupki-online.com по вашим компаниям прошло '.$user2['purchase_num'].' сделок.</p><br\>
                                                       <p>Из которых '.$user2['not_min_purchase'].' сделок были закрыты по не минимальным ценам.</p>
                                                       <p>Потенциал экономии по этим сделкам составил '.$user2['lose_total'].' грн</p>
                                                       <p>Сделки с потенциалом экономии (для просмотра истории торгов перейдите по ссылке <a href="http://zakupki-online.com/report/#/purchases/6/list">http://zakupki-online.com/report/#/purchases/6/list</a>)</p>
                                                       <p>Подробнее во вложенном файле.</p>
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


                #PDF
                $pdf = Yii::createComponent('common.extensions.tcpdf.ETcPdf',
                    'L', 'cm', 'A4', true, 'UTF-8');
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor("Lpovar");
                $pdf->SetTitle("Товарный чек");
                //$pdf->SetSubject("TCPDF Tutorial");
                $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
                //$pdf->SetFont('dejavusans', '', 11);
                $pdf->setHeaderFont(Array('dejavusans', '', 10));
                $pdf->SetMargins(1, 3, 1);
                $pdf->SetHeaderMargin(0.5);
                $pdf->SetHeaderData(
                    'blank-pdf-logo.jpg',
                    4,
                    'zakupki-online.com',
                    'г. Киев, ул.Воздвиженская, 48, тел: (095) 232 39 68',
                    array(0,0,0),
                    array(103,181,110)
                );
                //$pdf->setPrintHeader(true);
                $pdf->setPrintFooter(false);
                //$pdf->AliasNbPages();
                $pdf->AddPage();

                $purchasedata = Purchase::model()->managerPurchasesLose(array('user_id' => $user2['user_id']));
                $html = '<html>
<head>
    <title>Zakupki-Online.com</title>

    <style>
        .body{
            width: 100%;
            /*font-family: arial, sans-serif;*/
            font-family: dejavusans;
            font-size: 8px;
        }

        .wrap{
            padding: 0 40px;
        }

        .logo{
            text-align: center;
        }

        table{
            width: 100%;
            font-size: 8px;
        }

        table, td{
            border: none;
        }

        .green-border-bottom{
            border-bottom: 4px solid #67b56e;
        }

        .green-border-top{
            border-top: 4px solid #67b56e;
        }

        .green-border-bottom td,
        .green-border-top td{
            padding: 10px 0;
        }

        .content{
            /*min-height: 320px;*/
        }

        .gray{
            color: #9da5a7;
            font-size: 90%;
        }

    </style>
</head>
<body>
<div class="body">
    <h2>Данные по итогам прошедшей недели ('.date("d.m.Y",strtotime("-1 week")).'-'.date("d.m.Y").') по компаниям покупателя '.$user2['first_name'].' '.$user2['last_name'].'</h2>
    <p>Прошло '.$user2['purchase_num'].' сделок, из которых '.$user2['not_min_purchase'].' сделок были закрыты по не минимальным ценам.</p>
    <p>Потенциал экономии по этим сделкам составил '.$user2['lose_total'].' грн</p>
    <p>Сделки с потенциалом экономии (для просмотра истории торгов перейдите по ссылке <a href="http://zakupki-online.com/report/#/purchases/6/list">http://zakupki-online.com/report/#/purchases/6/list</a>)</p>

    <div class="wrap">
        <div class="content">
        <table class="stats-table" border="1">
            <tr bgcolor="#cccccc">
                <th>№</th>
                <th>Дата закрытия</th>
                <th>Организация</th>
                <th>Рынок</th>
                <th>Предмет торгов</th>
                <th>Кто закрыл</th>
                <th>Учасников</th>
                <th>Экономия (<span class="units">%</span>)</th>
                <th>Экономия (<span class="units">грн.</span>)</th>
                <th>Потери (<span class="units">грн.</span>)</th>
                <th>Сумма (<span class="units">грн.</span>)</th>
            </tr>
            ';
                $economy_total=0;
                $lose_total=0;
                $all_total=0;

                foreach($purchasedata['purchases'] as $purchase){
                if($purchase['lose_total']>0){

                }else
                    continue;


                    $economy_total=$economy_total+$purchase['economy_sum'];
                    $lose_total=$lose_total+$purchase['lose_total'];
                    $all_total=$all_total+$purchase['total'];

                    $html.='<tr>
                <td class="nowrap">'.$purchase['id'].'</td>
                <td>'.$purchase['date_closed'].'</td>
                <td>'.$purchase['company_title'].'</td>
                <td>'.$purchase['market_title'].'</td>';
                    if(isset($purchasedata['products'][$purchase['id']]))
                        $html.='<td>'.$purchasedata['products'][$purchase['id']].'</td>';
                    else
                        $html.='<td></td>';
                    $html.='
                <td>'.$purchase['first_name'].' '.$purchase['last_name'].'</td>
                <td>'.$purchase['company_num'].'</td>';
                    if($purchase['total']>0)
                        $html.='<td class="bigger">'.round($purchase['economy_sum']/$purchase['total']*100, 2, PHP_ROUND_HALF_UP).'</td>';
                    else
                        $html.='<td class="bigger">0</td>';
                    $html.='
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['economy_sum'], 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['lose_total'], 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($purchase['total'], 3, '.', ' ')).'</td>
            </tr>
            ';
                }
                $html.='
            <tr class="totals">
                <td class="itogogo" colspan="8">Итого:</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($economy_total, 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($lose_total, 3, '.', ' ')).'</td>
                <td class="bigger">'.str_replace(".000", "", (string)number_format($all_total, 3, '.', ' ')).'</td>
            </tr>
        </table>

        </div>
        <table>
            <tr>
                <td align="right" width="80%">
                    &nbsp;<br>
                    <span class="gray">Координатор проекта Zakupki-online.com</span><br>
                    Шевцова Татьяна
                </td>
                <td align="right" width="20%"><img src="/var/www/newzakupki/newzakupki.reactor.ua/img/blank-pdf-signature.jpg"></td>
            </tr>
        </table>
    </div>
</div>
</body>

</html>
';

                $pdf->writeHTML($html, true, false, false, false, '');
                //$pdf->Output($_SERVER['document_root']"/newzakupki2.reactor.ua/upload/pdf/example_002.pdf", "F");
                $file_name="upload/weekreport/userreportlose/reportlose_".$user2['user_id']."_".time().".pdf";
                $pdf->Output("/var/www/newzakupki/newzakupki.reactor.ua/".$file_name."", "F");

                $queue->attachfile=$file_name;



                $queue->save();

            }
        }

    }
}