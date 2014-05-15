<?php
    $form = $this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>true,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
        'class'=>'form-horizontal'
    ),
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'afterValidate' => 'js:formAfterValidate',
    ),
    //'focus'=>array($model,'date_create'),
));

$dateisOn = $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        'name' => 'Purchaseanalytics[date_first]',
        'language' => 'ru',
        'value' => $model->date_first,
        // additional javascript options for the date picker plugin
        'options'=>array(
            'showAnim'=>'fold',
            'dateFormat'=>'yy-mm-dd',
            'changeMonth' => 'true',
            'changeYear'=>'true',
            'constrainInput' => 'false',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;width:70px;',
        ),
        // DONT FORGET TO ADD TRUE this will create the datepicker return as string
    ),true)
    . ' по ' .
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        // 'model'=>$model,
        'name' => 'Purchaseanalytics[date_last]',
        'language' => 'ru',
        'value' => $model->date_last,
        // additional javascript options for the date picker plugin
        'options'=>array(
            'showAnim'=>'fold',
            'dateFormat'=>'yy-mm-dd',
            'changeMonth' => 'true',
            'changeYear'=>'true',
            'constrainInput' => 'false',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;width:70px',
        ),
        // DONT FORGET TO ADD TRUE this will create the datepicker return as string
    ),true);

?>
<fieldset>
    <legend>Аналитика по дням</legend>
    <div class="control-group ">
        <?php echo $form->labelEx($model,'date_create',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $dateisOn; ?>
        </div>
    </div>
    <div class="control-group ">
        <?php echo $form->labelEx($model,'companygroup_id',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model,'companygroup_id', CHtml::listData(Companygroup::model()->sort('title')->findAll('t.id!=1'),'id','title'),array('empty'=>'Все организации')); ?>
        </div>
    </div>
    <div class="control-group ">
        <?php echo $form->labelEx($model,'company_id',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model,'company_id', CHtml::listData(Company::model()->with('companygroup')->sort('companygroup.title, t.title')->findAll('companygroup.id!=1'),'id','title','companygroup.title'),array('empty'=>'Все компании')); ?>
        </div>
    </div>
    <div class="form-actions">
        <input class="btn btn-primary" name="save" type="submit" value="Поиск">
    </div>
    <div class="control-group ">
        <div style="width:100%">
            <div style="width:60%; float:left;">
            <?
            $this->widget('bootstrap.widgets.TbHighCharts', array(
                'options'=>array(
                    'title' => array('text' => 'Статистика создания/закрытия планов'),
                    'xAxis' => array(
                        'type'=> 'datetime',
                    ),
                    'yAxis' => array(
                        'title' => array('text' => 'Закупки')
                    ),
                    'series' => array(
                        array('name' => 'Новые планы', 'data' => $data['new_purchases']),
                        array('name' => 'Закрытые планы', 'data' => $data['closed_purchases']),
                    ),
                    'htmlOptions' => array(
                        'style' => 'width: 70%; margin: 0 auto'
                    )
                ),

            ));
            ?>
            </div>
            <div style="width:40%; float:left;">
                <?
                $this->widget('bootstrap.widgets.TbHighCharts', array(
                    'options'=>array(
                        'chart'=>array('type'=>'column'),
                        'title' => array('text' => 'Статистика количества планов'),
                        'xAxis' => array(
                            'type'=>'category',
                            'categories' => array('Новые','Закрытые','Не конкурентные','Не минимальная цена','Редукционы'),
                        ),
                        'yAxis' => array(
                            'title' => array('text' => 'Закупки')
                        ),
                        'tooltip'=>array(
                            'headerFormat'=>''
                        ),
                        'plotOptions'=>array(
                            'series'=>array(
                                'borderWidth'=>0,
                                'dataLabels'=>array(
                                    'enabled'=>true,
                                )
                            ),
                        ),
                        'series' => array(
                            array('name' => 'Количество планов', 'colorByPoint'=> true, 'data' => array($data['new_purchases_total'],$data['closed_purchases_total'],$data['not_concurent'],$data['not_min_purchase'],$data['reductions'])),
                        ),
                        'htmlOptions' => array(
                            'style' => 'width: 20%; margin: 0 auto'
                        )
                    )
                ));
                ?>
            </div>
         </div>
        <div style="width:100%">
            <div style="width:70%; float:left;">
                <?
                //echo date('Y-m-d h:i:s',1388347200);

                $this->widget('bootstrap.widgets.TbHighCharts', array(
                    'options'=>array(
                        'lang'=>array(
                            'downloadJPEG'=>'Скачать JPEG',
                            'shortMonths'=>array("Jan" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"),
                            'weekdays'=>array('1','2','3','4','5','6','7')
                        ),
                        'title' => array('text' => 'Экономия и потери'),
                        'xAxis' => array(
                            /*'categories' => $createdArrKeys,*/
                            'type'=> 'datetime',
                        ),
                        'yAxis' => array(
                            'title' => array('text' => 'грн')
                        ),
                        /*'plotOptions'=>array (
                            'series'=>array(
                                'pointStart'=>1,
                                'pointInterval'=>9
                            )
                        ),*/
                        'series' => array(
                            array('name' => 'Экономия', 'color'=>'#89A54E', 'data' => $data['economy']),
                            array('name' => 'Потери', 'color'=>'#80699B', 'data' => $data['lose']),
                        ),
                        'htmlOptions' => array(
                            'style' => 'width: 70%; margin: 0 auto'
                        )
                    ),

                ));
                ?>
            </div>
            <div style="width:30%; float:left;">
                <?
                $this->widget('bootstrap.widgets.TbHighCharts', array(
                    'options'=>array(
                        'chart'=>array('type'=>'column'),
                        'title' => array('text' => 'Экономия и потерия всего'),
                        'xAxis' => array(
                            'type'=>'category',
                            'categories' => array('Экономия','Потери'),
                        ),
                        'yAxis' => array(
                            'title' => array('text' => 'грн.')
                        ),
                        /*'plotOptions'=>array (
                            'series'=>array(
                                'pointStart'=>1,
                                'pointInterval'=>9
                            )
                        ),*/
                        'tooltip'=>array(
                            'headerFormat'=>''
                        ),
                        'plotOptions'=>array(
                            'series'=>array(
                                'borderWidth'=>0,
                                'dataLabels'=>array(
                                    'enabled'=>true,
                                )
                            ),
                        ),
                        'series' => array(
                            array('name' => 'Показатели', 'colorByPoint'=> true, 'data' => array(array('color'=>'#89A54E','y'=>$data['economy_total']),array('color'=>'#80699B','y'=>$data['lose_total']))),
                            /*array('name' => 'John', 'data' => array(5, 7, 3,5, 7, 3,5, 7, 3))*/
                        ),
                        'htmlOptions' => array(
                            'style' => 'width: 20%; margin: 0 auto'
                        )
                    )
                ));
                ?>
            </div>
        </div>

        <div style="width:100%">
            <div style="width:70%; float:left;">
                <?
                //echo date('Y-m-d h:i:s',1388347200);

                $this->widget('bootstrap.widgets.TbHighCharts', array(
                    'options'=>array(
                        'lang'=>array(
                            'downloadJPEG'=>'Скачать JPEG',
                            'shortMonths'=>array("Jan" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"),
                            'weekdays'=>array('1','2','3','4','5','6','7')
                        ),
                        'title' => array('text' => 'Сумма заказа'),
                        'xAxis' => array(
                            /*'categories' => $createdArrKeys,*/
                            'type'=> 'datetime',
                        ),
                        'yAxis' => array(
                            'title' => array('text' => 'грн')
                        ),
                        /*'plotOptions'=>array (
                            'series'=>array(
                                'pointStart'=>1,
                                'pointInterval'=>9
                            )
                        ),*/
                        'series' => array(
                            array('name' => 'Сумма заказа', 'color'=>'#3D96AE', 'data' => $data['money']),
                        ),
                        'htmlOptions' => array(
                            'style' => 'width: 70%; margin: 0 auto'
                        )
                    ),

                ));
                ?>
            </div>
            <div style="width:30%; height:400px; float:left; color:#3D96AE; text-align: center; font-size:28px;">
                <br><br><br><br><br><br>
                <p>Всего за месяц</p>
                <p><?=number_format($data['money_total'],0, '.', ' ');?> грн.</p>
            </div>
        </div>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<?