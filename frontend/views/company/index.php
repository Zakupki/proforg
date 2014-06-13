<div class="content white">
    <div class="cw clearfix">
        <div class="c main">
            <div class="arrowlabel-l">Сумма долга перед фин.компанией (грн.) </div>
            <div class="bignum"><span class="num"><?=$balance['balance'];?></span><!--<span class="num">3%<a class="help" href="#"></a></span>--></div>
            <div class="actions">
                <a class="btn" href="#">История операций</a>
            </div>
        </div>
        <div class="c side adduser">
            <div class="actions">
                <a class="btn add-user" href="/company/updateuser/">Добавить работника</a>
            </div>
        </div>
    </div>
</div>
<div class="content gray">
    <div class="cw clearfix">
        <table class="data-table">
            <tr>
                <th></th>
                <th>День з/п</th>
                <th>Размер з/п</th>
                <th>Доступно</th>
                <!--<th>Погашено</th>
                <th>Баланс</th>-->
                <th>Всего в году</th>
                <th></th>
            </tr>
            <? foreach($users as $user){?>
            <tr>
                <td><a href="#"><?=$user['first_name'];?> <?=$user['name'];?> <?=$user['last_name'];?></a></td>
                <td class="num"><?=$user['salaryday'];?></td>
                <td class="num"><?=$user['salary'];?></td>
                <td class="num"><?=$user['balance']+$user['salary'];?></td>
                <!--<td class="num gray">0</td>
                <td class="num gray">0</td>-->
                <td class="num gray"><?=$user['yearsalary'];?></td>
                <td class="actions"><a class="btn cross" data-id="<?=$user['id'];?>" data-action="delete" data-url="/company/updateuser"></a></td>
            </tr>
            <?}?>
        </table>
    </div>
</div>