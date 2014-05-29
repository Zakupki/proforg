    <div class="content gray">
        <div class="cw-narrow clearfix">
            <h1>Мои карты</h1>
            <div class="form narrow cards-form">
                <form>
                    <div class="cc-list">
                        <? foreach($cards as $card){?>
                        <div class="cc clearfix">
                            <a class="btn cross"></a>
                            <div class="ccnum"><?=substr($card->number, -4);?></div>
                            <input type="radio" value="<?=$card->number?>" name="activeCard" checked>
                        </div>
                        <?}?>
                    </div>
                    <div class="actions default clearfix">
                        <a class="btn submit fr">Добавить карту</a>
                    </div>
                    <div class="actions delete clearfix">
                        <a class="btn cancel fl">Отменить</a>
                        <a class="btn ok fr">Удалить карту</a>
                    </div>
                    <div class="bottom-actions">
                        <a href="#">Вернуться в кабинет</a>
                    </div>
                </form>
            </div>
        </div>
    </div>