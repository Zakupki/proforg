	<div class="content gray">
		<div class="cw-narrow clearfix">
			<div class="form narrow company-form">
				<div class="login-screen">
                    <h1>
                        <?if(isset($model->id)){?>
                            Добавить работника
                        <?}else{?>
                            Редактирование пользователя
                        <?}?>
                    </h1>
					<form method="post" action="/company/updateuser/" name="user-form" class="serverside">
                        <? if(isset($model->id)){?>
                        <input type="hidden" name="UserForm[id]" value="<?=$model->id;?>"/>
                        <?}?>
						<input type="hidden" name="UserForm[employer_id]" value="<?=$this->userData['company_id'];?>"/>

                        <?php echo CHtml::activeTextField($model,'last_name', array('class' => 'txt w280', 'placeholder' => 'Фамилия')); ?>
                        <?php echo CHtml::activeTextField($model,'first_name', array('class' => 'txt w280', 'placeholder' => 'Имя')); ?>
                        <?php echo CHtml::activeTextField($model,'name', array('class' => 'txt w280', 'placeholder' => 'Отчество')); ?>
                        <? if(!isset($model->id)){?>
                            <?php echo CHtml::activeTextField($model,'email', array('class' => 'txt w280', 'placeholder' => 'Email')); ?>
                        <?}?>
                        <?php echo CHtml::activeTextField($model,'salary', array('class' => 'txt w280', 'placeholder' => 'Зарплата')); ?>
                        <?php echo CHtml::activeTextField($model,'salaryday', array('class' => 'txt w280', 'placeholder' => 'День зврплаты')); ?>

                        <!--<input class="txt w280" type="text" name="UserForm[last_name]" id="UserForm_last_name" placeholder="Фамилия">
                        <input class="txt w280" type="text" name="UserForm[first_name]" id="UserForm_first_name" placeholder="Имя">
                        <input class="txt w280" type="text" name="UserForm[name]" id="UserForm_name" placeholder="Отчество">
                        <input class="txt w280" type="text" name="UserForm[email]" id="UserForm_email" placeholder="Email">
                        <input class="txt w280" type="text" name="UserForm[salary]" id="UserForm_salary" placeholder="Зарплата">
                        <input class="txt w280" type="text" name="UserForm[salaryday]" id="UserForm_salaryday" min="1" max="31" placeholder="День зврплаты">-->
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