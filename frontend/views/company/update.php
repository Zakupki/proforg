	<div class="content gray">
		<div class="cw-narrow clearfix">
			<div class="form narrow company-form">
				<div class="login-screen">			
					<h1>Добавить компанию</h1>
					<form method="post" action="/company/update/" name="company-form" class="serverside">
						<input class="txt w280" type="text" name="CompanyForm[title]" id="CompanyForm_title" placeholder="Заголовок">
						<div class="field w280">							
							<select name="CompanyForm[finance]" id="CompanyForm_finance">
								<option>Финансы</option>
								<option value="1">Приват</option>
							</select>
						</div>
						<div class="actions mt20 clearfix">
							<a class="btn submit h60 fr">Сохранить</a>
						</div>
					</form>
				</div>
		</div>
	</div>