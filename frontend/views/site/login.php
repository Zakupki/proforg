	<div class="content gray">
		<div class="cw-narrow clearfix">
			<div class="form narrow login-form">
				<div class="login-screen">			
					<h1>Вход</h1>
					<form method="post" action="/site/login/" name="login-form" class="serverside">
						<input class="txt w280" type="email" name="LoginForm[email]" id="LoginForm_email" placeholder="e-mail">
						<input class="txt w280" type="password" name="LoginForm[password]" id="LoginForm_password" placeholder="пароль">
						<div class="actions clearfix">
							<div class="remember fl"><label>Запомнить <input type="checkbox" value="1" name="LoginForm[rememberMe]" id="LoginForm_rememberMe"></label></div>
							<a class="btn submit h60 fr">Вход</a>
						</div>
						<div class="bottom-actions">
							<a class="forgot">Я забыл пароль</a>
						</div>
					</form>
				</div>
				<div class="forgot-screen">
					<h1>Я забыл пароль</h1>
					<div class="form narrow forgot-form">
						<form method="post" action="/site/forgot/" name="forgot-form" class="serverside">
							<input type="email" class="txt w280" placeholder="e-mail" name="ForgotForm[email]" id="ForgotForm_email">
							<div class="text">Укажите Ваш e-mail и<br> мы вышлем новый пароль</div>
							<div class="actions clearfix">
								<a class="btn submit h60 fr">Выслать</a>
							</div>
							<div class="bottom-actions">
								<a class="backtologin">Я вспомнил пароль!</a>
							</div>
						</form>					
				</div>
			</div>
			<div class="emailsent-screen">
				<h1>Готово!</h1>
				<div class="text">Если введённый e-mail зарегистрирован в системе, на него были высланы инструкции по восстановлению пароля.</div>
				<div class="bottom-actions">
					<a class="backtologin">Назад ко входу</a>
				</div>
			</div>
		</div>
	</div>