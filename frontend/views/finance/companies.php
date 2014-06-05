    <div class="content gray">
        <div class="cw clearfix">
            <table class="data-table">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Доступно</th>
                    <th>Запрос</th>
                    <th>Остаток</th>
                    <th></th>
                </tr>
                <?
                if(isset($companies))
                foreach($companies as $com){?>
                <tr>
                    <td><a href="#"><?=$com->title;?></a></td>
                    <td><a href="#"></a></td>
                    <td class="num gray"></td>
                    <td class="num"></td>
                    <td class="num gray"></td>
                    <td class="gray"><?=str_replace(',',' /',Yii::app()->dateFormatter->formatDateTime($com->date_create, 'short', 'short')); ?></td>
                    <td class="actions"><a class="btn cross" data-id="<?=$com->id;?>" data-action="delete" data-url="/finance/updatecompany"></a></a></td>
                </tr>
                <?}?>
            </table>
            <div class="actions mt20 clearfix">
                <a class="btn fr" href="/finance/updatecompany">Добавить компанию</a>
                <a class="btn fr" href="/finance/companies">Все компании</a>
            </div>
        </div>
    </div>