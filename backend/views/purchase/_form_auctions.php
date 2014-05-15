<?



//$gridDataProvider2= new CArrayDataProvider($products, array('pagination' => array('pageSize' => 9999)));

//$gridDataProvider= new CArrayDataProvider(array(0=>array('id'=>1)), array('pagination' => array('pageSize' => 9999)));

$gridColumns=array('id');





$products=Purchase::model()->getAdminPurchaseProducts($model->id);

foreach($products['products'] as $product){
echo '<table class="table table-bordered table-striped" style="color: #fff;">
            <tr>
                <td style="width:70px; background-color: #08c;">'.$product['id'].'</td>
                <td style="width:400px; background-color: #08c;">'.$product['title'].'</td>
                <td style="background-color: #08c;">'.$product['amount'].' '.$product['unit'].'</td>
            </tr>
      </table>';
    $offers=Offer::model()->findAllBySql('
        SELECT
            IF(offer.id, offer.id, z_offer.id) AS pid,
            IF(offer.id, offer.pid, z_offer.id) AS id,
            IF(offer.id,offer.amount,z_offer.amount) AS amount,
            IF(offer.id,offer.price,z_offer.price) AS price,
            IF(offer.id,offer.delivery,z_offer.delivery) AS delivery,
            IF(offer.id,offer.delay,z_offer.delay) AS delay,
            IF(offer.id,offer.winner,z_offer.winner) AS winner,
            DATE_FORMAT(IF(offer.id,offer.date_create,z_offer.date_create),"%d.%m.%Y %h:%i:%s") AS date_create,
            z_offer.id AS `ofid`,
            z_tag.title,
            z_company.title AS company_id,
            z_user.email AS user_id
        FROM z_offer
        LEFT JOIN z_offer offer
            ON offer.pid = z_offer.id AND offer.`id` = (SELECT MAX(id) FROM z_offer WHERE z_offer.pid = `ofid`)
        LEFT JOIN z_tag
            ON z_tag.id = z_offer.tag_id
        INNER JOIN z_company
            ON z_company.id = z_offer.company_id
        INNER JOIN z_user
            ON z_user.id = z_offer.user_id
        WHERE z_offer.pid IS NULL AND z_offer.product_id='.$product['id']
    );

        $this->widget(
            'bootstrap.widgets.TbExtendedGridView',
            array(
                'fixedHeader' => true,
                'headerOffset' => 40,
                // 40px is the height of the main navigation at bootstrap
                'type' => 'striped',
                'dataProvider' => new CArrayDataProvider($offers, array('pagination' => array('pageSize' => 9999))),
                'responsiveTable' => true,
                'template' => "{items}",
                'columns' => array(
                    array(
                        'name'=>'Номер',
                        'value'=>'$data->pid',
                        'htmlOptions' => array('style' => 'width: 70px')
                    ),
                    array(
                        'class'=>'bootstrap.widgets.TbRelationalColumn',
                        'name'=>'Товар',
                        'url' => $this->createUrl('purchase/offers'),
                        'value'=> '$data->title'
                    ),
                    array(
                        'name'=>'Компания',
                        'value'=>'$data->company_id',
                        'htmlOptions' => array('style' => 'width: 300px')
                    ),
                    array(
                        'name'=>'Пользователь',
                        'value'=>'$data->user_id',
                        'htmlOptions' => array('style' => 'width: 200px')
                    ),
                    array(
                        'name'=>'Цена',
                        'value'=>'$data->price',
                        'htmlOptions' => array('style' => 'width: 70px')
                    ),
                    array(
                        'name'=>'Количество',
                        'value'=>'$data->amount',
                        'htmlOptions' => array('style' => 'width: 70px')
                    ),
                    array(
                        'header' => 'Доставка',
                        'value' => '$data->delivery',
                        'htmlOptions' => array('style' => 'width: 70px')
                    ),
                    array(
                        'header' => 'Отсрочка',
                        'value' => '$data->delay',
                        'htmlOptions' => array('style' => 'width: 70px')
                    ),
                    array(
                        'name'=>'Дата',
                        'value'=>'$data->date_create',
                        'htmlOptions' => array('style' => 'width: 140px')
                    ),
                    array(
                        'name'=>'Победа',
                        'value'=>'$data->winner',
                        'htmlOptions' => array('style' => 'width: 70px')
                    ),
                ),
            )
        );


            /*foreach($products['offers'][$product['id']] as $offer){
                echo '<tr>
                        <td style="width:70px;"></td>
                        <td class="span1">'.$offer['id'].'</td>
                        <td style=""><span class="tbrelational-column" data-rowid="'.$offer['id'].'">'.$offer['title'].'</span></td>
                        <td>'.$offer['company'].'</td>
                        <td>'.$offer['email'].'</td>
                        <td>'.$offer['price'].' грн</td>
                        <td>'.$offer['amount'].' '.$offer['unit'].'</td>
                        <td>'.$offer['price'].' грн</td>
                      </tr>';
            }*/


}
