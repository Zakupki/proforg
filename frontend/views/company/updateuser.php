	<div class="content gray">
		<div class="cw-narrow clearfix">
			<div class="form narrow company-form">
				<div class="login-screen">			
					<h1>Добавить работника</h1>
					<form method="post" action="/company/updateuser/" name="user-form" class="serverside">
						<input type="hidden" name="UserForm[employer_id]" value="<?=$this->userData['company_id'];?>"/>
                        <input class="txt w280" type="text" name="UserForm[last_name]" id="UserForm_last_name" placeholder="Фамилия">
                        <input class="txt w280" type="text" name="UserForm[first_name]" id="UserForm_first_name" placeholder="Имя">
                        <input class="txt w280" type="text" name="UserForm[name]" id="UserForm_name" placeholder="Отчество">
                        <input class="txt w280" type="text" name="UserForm[email]" id="UserForm_email" placeholder="Email">
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