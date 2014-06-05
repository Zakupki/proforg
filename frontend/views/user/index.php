    <div class="content white">
        <div class="cw clearfix">
            <div class="c main">
                <div class="arrowlabel-l">Доступно (грн)</div>
                <div class="bignum available-display"><?=$balance['balance']+$user->salary;?></div>
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
            <form class="withdraw-form serverside" method="post" action="/user/requestupdate" name="request-form">
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
                    <input type="hidden" class="rest-input" name="RequestForm[left]" value="">
                    <input type="hidden" class="balance-input" name="RequestForm[balance]" value="<?=$balance['balance'];?>">
                    <input type="hidden" class="salary-input" name="RequestForm[salary]" value="<?=$user->salary;?>">
                    <input type="hidden" class="salary-input" name="RequestForm[company_id]" value="<?=$company->id;?>">
                    <? if(isset($card->id)){?>
                    <input type="hidden" class="salary-input" name="RequestForm[card_id]" value="<?=$card->id;?>">
                    <?}?>
                    <input type="hidden" class="salary-input" name="RequestForm[finance_id]" value="<?=$company->finance_id;?>">
                    <input type="hidden" class="days-input" name="RequestForm[days]" value="25">
                    <input type="hidden" class="percentfee-input" name="RequestForm[percentfee]" value="3">
                    <input type="hidden" class="percentcredit-input" name="RequestForm[percentcredit]" value="0.3">
                    <div class="sum-area">
                        <input type="text" name="RequestForm[value]" value="" placeholder="0">
                        <span class="units">грн</span>
                    </div>
                    <div class="row fee clearfix">
                        <div class="c label">Комиссия</div>
                        <a class="help" href="#"></a>
                        <div class="c value"></div>
                        <input type="hidden" class="fee-input" name="RequestForm[commission]" value="">
                    </div>
                    <div class="row usersum clearfix">
                        <div class="c label">Вы получите</div>
                        <a class="help" href="#"></a>
                        <div class="c value"></div>
                        <input type="hidden" class="usersum-input" name="RequestForm[usersum]" value="">
                    </div>
                    <div class="actions">
                        <a class="btn submit" href="#">Получить на карту</a>
                    </div>
                </div>
            </form>
        </div>
    </div>