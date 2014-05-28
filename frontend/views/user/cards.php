    <div class="content gray">
        <div class="cw-narrow clearfix">
            <h1>Мои карты</h1>
            <div class="form narrow forgot-form">
                <form>
                    <div class="cc-list">
                        <? foreach($cards as $card){?>
                        <div class="cc clearfix">
                            <a class="btn cross"></a>
                            <div class="ccnum"><?=substr($card->number, -4);?></div>
                            <i class="check"></i>
                        </div>
                        <?}?>
                    </div>
                    <div class="actions clearfix">
                        <a class="btn submit fr">Добавить карту</a>
                    </div>
                    <div class="bottom-actions">
                        <a href="#">Вернуться в кабинет</a>
                    </div>
                </form>
            </div>
        </div>
    </div>