    <div class="content white">
        <div class="cw clearfix">
            <div class="c main">
                <div class="arrowlabel-l">Доступно (грн)</div>
                <div class="bignum"><?=$balance['balance']+$user->salary;?></div>
                <div class="actions">
                    <a class="btn" href="#">Подробнее</a>
                </div>
            </div>
            <div class="c side wallet">
                <? if(isset($card)){?>
                <div class="arrowlabel-r gray">Ваш карточный счёт</div>
                <div class="name"><?=$card->last_name;?> <?=$card->first_name;?> <?=$card->name;?></div>
                <div class="cc">
                    <span class="ccnum"><?=substr($card->number, -4);?></span>
                </div>
                <?}?>
                <div class="actions">
                    <a class="btn" href="/user/cards/">Мои карты</a>
                    <a class="btn plus" href="/user/cardupdate/">+</a>
                </div>
            </div>
        </div>
    </div>
    <div class="content gray">
        <div class="cw clearfix">
            <form class="withdraw-form serverside" name="request-form">
                <div class="c main">
                    <div class="slider"></div>
                    <div class="text">Он уже не мог снова сесть за бумаги от волнения и ожидания и стал бродить по кабинету из угла в угол. Князь остановился перевести дух. Он ужасно скоро говорил. Он был бледен и задыхался. Все переглядывались; но наконец старичок откровенно рассмеялся. Князь N. вынул лорнет и, не отрываясь, рассматривал князя. Немчик-поэт выполз из угла и подвинулся поближе к столу, улыбаясь зловещею улыбкой.</div>
                    <div class="support">
                        Поддержка (8.00 - 20.00)
                        <div class="phone">8 050 5176012</div>
                    </div>
                </div>
                <div class="c side">
                    <input type="hidden" class="available-input" name="RequestForm[available]" value="<?=$balance['balance']+$user->salary;?>">
                    <div class="sum-area">
                        <input type="text" name="RequestForm[value]" value="" placeholder="0">
                        <span class="units">грн</span>
                    </div>
                    <div class="row fee clearfix">
                        <div class="c label">Комиссия</div>
                        <a class="help" href="#"></a>
                        <div class="c value">-23</div>
                        <input type="hidden" class="fee-input" name="RequestForm[fee]" value="-23">
                    </div>
                    <div class="row rest clearfix">
                        <div class="c label">Остаток</div>
                        <a class="help" href="#"></a>
                        <div class="c value">3456</div>
                        <input type="hidden" class="rest-input" name="RequestForm[rest]" value="3456">
                    </div>
                    <div class="actions">
                        <a class="btn submit" href="#">Получить на карту</a>
                    </div>
                </div>
            </form>
        </div>
    </div>