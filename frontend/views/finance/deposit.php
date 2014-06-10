	<div class="content gray">
		<div class="cw-narrow clearfix">
			<div class="form narrow company-form">
				<div class="login-screen">			
					<h1>Внести средства</h1>
					<form method="post" action="/finance/deposit/" name="company-form" class="serverside">
                        <input class="txt w280" type="hidden" name="RequestForm[requesttype_id]" value="3">
                        <input class="txt w280" type="hidden" name="RequestForm[finance_id]" value="<?=$finance_id;?>">
						<input class="txt w280" type="text" name="RequestForm[value]" id="RequestForm_value" placeholder="Сумма">
						<div class="field w280">
                            <?php echo CHtml::activeDropDownList($model,'company_id',Company::model()->listData(), array('empty'=>'Компания')); ?>

							<!--<select name="RequestForm[company_id]" id="RequestForm_company_id">
								<option>Ком</option>
								<option value="1">Приват</option>
							</select>-->
						</div>
						<div class="actions mt20 clearfix">
							<a class="btn submit h60 fr">Сохранить</a>
						</div>
					</form>
				</div>
		</div>
	</div>