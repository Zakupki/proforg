         <div class="content register">
            <form action="/site/register/" name="register-form" method="post" class="serverside">
                <h2>Заявка на регистрацию</h2>
                <div class="clearfix">
                    <h4 class="legend company">Компания</h4>
                    <input type="text" class="txt w580"  id="RegisterForm_egrpou" name="RegisterForm[egrpou]"  placeholder="Код ЕГРПОУ">
                    <hr>
                    <input type="text" class="txt w580 required" name="RegisterForm[company_title]" id="RegisterForm_company_title" placeholder="Название">
                    <div class="field w280">
                        <?php echo CHtml::activeDropDownList($model,'country_id',Country::model()->listData(), array('empty'=>'Страна')); ?>
                    </div>
                    <input type="text" class="txt w580" name="RegisterForm[address]" id="RegisterForm_address" placeholder="Адрес компании">
                    <div class="phones company-phones">
                        <div class="field w280">
                            <span class="countrycode">+38</span> <span class="bracket">(</span><input type="text" class="txt area" name="RegisterForm[phones][0][phonecode]" placeholder="Код" maxlength="5"><span class="bracket">)</span><input type="text" class="txt number" name="RegisterForm[phones][0][phone]" placeholder="Телефон" maxlength="7"><input type="hidden" name="RegisterForm[phones][0][countrycode]" value="38">
                        </div>                      
                    </div>
                    <hr>
                </div>
                

                <div class="field w580 ui-front tags-select markets-list">
                    <div class="tags-list clearfix">
                        <label>Выберите один или несколько рынков</label>
                        <input class="ids-input" name="RegisterForm[marketsids]" type="text" value="">
                        <input class="tags-list-input" name="RegisterForm[marketslist]" type="text" value="">
                        <?php echo CHtml::activeDropDownList($model,'markets',CHtml::listData(Market::model()->findAll(),'id','title','markettype.title'),array('class'=>'tags-source'));?>
                    </div>
                </div>

                <hr>
                <div class="field w580 ui-front tags-select company-tags-list">
                    <div class="tags-list clearfix">
                        <label>Тэги</label>
                        <input class="ids-input" name="RegisterForm[tagsids]" type="text" value="">
                        <input class="tags-list-input" name="RegisterForm[tagstitles]" type="text" value="">
                    </div>
                </div>

                <hr>
                <h4 class="legend contact">Контактное лицо</h4>
                <div class="clearfix">
                    <input type="text" class="txt w280" id="RegisterForm_name" name="RegisterForm[first_name]" placeholder="Имя">
                    <input type="text" class="txt w280" id="RegisterForm_first_name" name="RegisterForm[last_name]" placeholder="Фамилия">
                    <input type="text" class="txt w280" id="RegisterForm_position" name="RegisterForm[position]" placeholder="Должность">
                    <div class="field w280">
                        <?php echo CHtml::activeDropDownList($model,'companyrole_id',Companyrole::model()->listData(), array('empty'=>'Роль')); ?>
                    </div>
                </div>
                <div class="phones personal-phones">
                    <div class="field w280">
                        <span class="countrycode">+38</span> <span class="bracket">(</span><input type="text" class="txt area" name="RegisterForm[personalphones][0][phonecode]" placeholder="Код" maxlength="5"><span class="bracket">)</span><input type="text" class="txt number" name="RegisterForm[personalphones][0][phone]" placeholder="Телефон" maxlength="7"><input type="hidden" name="RegisterForm[personalphones][0][countrycode]" value="38">
                    </div>                      
                </div>
                <hr>

                <h4 class="legend profile">Информация для входа</h4>
                <div class="clearfix">
                    <input type="text" class="txt w280" id="RegisterForm_email" name="RegisterForm[email]" placeholder="e-mail (login)" autocomplete="off">
                    <input type="password" class="txt w280" id="RegisterForm_password" name="RegisterForm[password]" placeholder="пароль" autocomplete="off">
                    <input type="password" class="txt w280" id="RegisterForm_repeat_password" name="RegisterForm[repeat_password]" placeholder="подтверждение пароля" autocomplete="off">
                </div>
                <div class="actions clearfix">
                    <a class="btn submit red"><span class="label">Отправить заявку</span></a>
                </div>
            </form>
        </div>
        <div id="company-exists-popup" class="popup-src">
            <div class="wrap">
                <div class="header">Компания уже есть в базе</div>
                <div class="content">
                    <p class="message">Если вы являетесь сотрудником компании, заполните личные данные и ожидайте подтверждения заявки администратором.</p>
                    <div class="actions">
                        <a class="btn red ok">OK</a>
                    </div>
                </div>
                <a class="popup-close"></a>
            </div>
        </div>