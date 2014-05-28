<div class="content gray">
    <div class="cw-narrow clearfix">
        <div class="form narrow company-form">
            <div class="login-screen">
                <h1>Добавить карту</h1>
                <form method="post" action="/user/cardupdate/" name="user-form" class="serverside">
                    <input type="hidden" name="CardForm[user_id]" value="<?=yii::app()->user->getId();?>"/>
                    <input class="txt w280" type="text" name="CardForm[last_name]" id="CardForm_last_name" placeholder="Фамилия">
                    <input class="txt w280" type="text" name="CardForm[first_name]" id="CardForm_first_name" placeholder="Имя">
                    <input class="txt w280" type="text" name="CardForm[name]" id="CardForm_name" placeholder="Отчество">
                    <input class="txt w280" type="text" name="CardForm[number]" id="CardForm_number" placeholder="Номер">
                    <input class="txt w280" type="text" name="CardForm[expire]" id="CardForm_expire" placeholder="Действительно до">
                    <!--<div class="field w280">
                        <select name="UserForm[finance_id]" id="UserForm_finance">
                            <option>Финансы</option>
                            <option value="1">Приват</option>
                        </select>
                    </div>-->
                    <div class="actions mt20 clearfix">
                        <a class="btn submit h60 fr">Сохранить</a>
                    </div>
                </form>
            </div>
        </div>
    </div>