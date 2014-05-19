    <div class="content gray">
        <div class="cw clearfix">
            <table class="data-table">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Доступно</th>
                    <th>Запрос</th>
                    <th>Остаток</th>
                    <th></th>
                </tr>
                <?
                if(isset($requests))
                foreach($requests as $req){?>
                <tr>
                    <td><a class="btn check" href="#"></a></td>
                    <td class="gray"><?=str_replace(',',' /',Yii::app()->dateFormatter->formatDateTime($req->date_create, 'short', 'short')); ?></td>
                    <td><a href="#"><?=$req->company->title;?></a></td>
                    <td><a href="#"><?=$req->user->last_name;?> <?=$req->user->first_name;?></a></td>
                    <td class="num gray"><?=$req->available;?></td>
                    <td class="num"><?=$req->value;?></td>
                    <td class="num gray"><?=$req->left;?></td>
                    <td class="actions"><a class="btn cross" href="#"></a></td>
                </tr>
                <?}?>
            </table>
            <div class="actions mt20 clearfix">
                <a class="btn fr" href="/finance/updatecompany">Добавить компанию</a>
            </div>
        </div>
    </div>