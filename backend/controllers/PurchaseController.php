<?php
class PurchaseController extends BackController
{
    public function actionOffers()
    {

        $this->renderPartial('_offers');
        // partially rendering "_relational" view
        /*$this->renderPartial('_relational', array(
            'id' => Yii::app()->getRequest()->getParam('id'),
            'gridDataProvider' => $this->getGridDataProvider(),
            'gridColumns' => $this->getGridColumns()
        ));*/
    }
    public function behaviors() {
        return array(
            'exportableGrid' => array(
                'class' => 'backend.components.ExportableGridBehavior',
                'filename' => 'Закупки.csv',
                'csvDelimiter' => ';', //i.e. Excel friendly csv delimiter
            ));
    }
    public function actionAdmin() {
        $model = new Purchase('search');
        $model->unsetAttributes();
        if (isset($_GET['Purchase']))
            $model->attributes = $_GET['Purchase'];

        if ($this->isExportRequest()) {
            $whereSql='';
            $whereSql2='';
            if (isset($_GET['Purchase'])){
                $attributes = $_GET['Purchase'];
                foreach($attributes as $k=>$attr){
                    if($attr && !in_array($k,array('date_first','date_last','date_first2','date_last2','companygroup_id')))
                        $whereSql.=' AND z_purchase.'.$k.'='.$attr;
                    if($k=='companygroup_id' && $attr>0)
                        $whereSql.=' AND company.'.$k.'='.$attr;
                }
            }
            if((isset($_GET['Purchase']['date_first'])) && (isset($_GET['Purchase']['date_last'])))
                if(strlen($_GET['Purchase']['date_first'])>0 && strlen($_GET['Purchase']['date_last']))
                $whereSql.=' AND z_purchase.date_create BETWEEN "'.$_GET['Purchase']['date_first'].'" AND "'.$_GET['Purchase']['date_last'].'"';
            if((isset($_GET['Purchase']['date_first2'])) && (isset($_GET['Purchase']['date_last2'])))
                if(strlen($_GET['Purchase']['date_first2'])>0 && strlen($_GET['Purchase']['date_last2']))
                $whereSql.=' AND z_purchase.date_closed BETWEEN "'.$_GET['Purchase']['date_first2'].'" AND "'.$_GET['Purchase']['date_last2'].'"';


           /* $sortSql=' ORDER BY z_purchase.date_create DESC';
            if(isset($_GET['Purchase_sort'])){
                $sortSql=' ORDER BY z_purchase.'.str_replace('.',' ',$_GET['Purchase_sort']);
            }*/
            $sql ='
            SELECT
              t.*,
              z_markettype.title AS markettype,
              z_market.title AS market
            FROM
              (SELECT
                z_product.`purchase_id`,
                IF(offer.id,offer.price,z_offer.price) AS offer_price,
                IF(IF(offer.id,offer.winner,z_offer.winner) > 0,"Истина","Ложь") AS winner,
                z_product.`date_create`,
                company.title AS company,
                z_companygroup.title AS companygroup,
                tag.title AS product,
                /*z_markettype.title AS markettype, z_market.title AS market,*/
                z_purchase.`address`,
                z_unit.title AS unit,
                z_product.`amount`,
                z_purchasestate.`title` AS purchasestate,
                z_purchase.`date_deliver`,
                z_purchase.`date_closed`,
                z_purchase.`date_close`,
                z_purchase.`comment`,
                z_purchase.`close_text`,
                z_purchase.delay,
                z_purchase.`market_id`,
                z_company.title AS supplier,
                IF(z_company.companygroup_id = 1,"Внешний","Учасник") AS offertype,
                z_tag.title AS offer_product,
                IF(offer.id,offer.amount,z_offer.amount) AS offer_amount,
                IF(offer.id,offer.price * offer.amount,z_offer.price * z_offer.amount) AS offer_total,
                IF(IF(offer.id,offer.delivery,z_offer.delivery),"C доставкой","Без доставки") AS delivery,
                IF(offer.id,offer.delay,z_offer.delay) AS offer_delay,
                IF(offer.id,offer.comment,z_offer.comment) AS offer_comment,
                z_offer.id AS offer_id,
                IF(offer.id,offer.place,z_offer.place) AS offer_place,
                "__",
                IF(offer.id, offer.id, z_offer.id) AS id,
                IF(offer.id, offer.pid, z_offer.id) AS pid,
                z_offer.id AS `ofid`,
                z_offer.product_id AS `prid`,
                IF(
                  offer.id,
                  offer.product_id,
                  z_offer.product_id
                ) AS product_id,
                IF(
                  offer.price_reduce > 0,
                  offer.price_reduce,
                  0
                ) AS price_reduce,
                z_user.id AS user_id
              FROM
                z_purchase
                INNER JOIN z_product
                ON z_product.purchase_id = z_purchase.`id`
              LEFT JOIN z_offer
                ON z_offer.product_id = z_product.`id`
                AND z_offer.pid IS NULL
              LEFT JOIN z_offer offer
                ON offer.pid = z_offer.id
                AND offer.`id` =
                (SELECT
                  MAX(z_offer.id)
                FROM
                  z_offer
                WHERE z_offer.pid = `ofid` AND z_offer.product_id=`prid`)
                INNER JOIN z_tag tag
                  ON tag.id = z_product.`tag_id`
                INNER JOIN z_unit
                  ON z_unit.id = z_product.`unit_id`
                INNER JOIN z_purchasestate
                  ON z_purchasestate.id = z_purchase.purchasestate_id
                INNER JOIN z_company company
                  ON company.id = z_purchase.company_id
                INNER JOIN z_companygroup
                  ON z_companygroup.id = company.companygroup_id
                LEFT JOIN z_user
                  ON z_user.id = z_offer.user_id
                LEFT JOIN z_tag
                  ON z_tag.id = z_offer.tag_id
                LEFT JOIN z_company
                  ON z_company.id = z_offer.company_id
              WHERE z_purchase.id > 0
                '.$whereSql.'
              ) AS t
              INNER JOIN z_market
                ON z_market.id = t.market_id
              INNER JOIN z_markettype
                ON z_markettype.id = z_market.markettype_id
              ORDER BY t.purchase_id
            ';
            //echo $sql;
            //die();
            $data=Purchasereport::model()->findAllBySql($sql);

            $this->exportCSV($data,array(
              'purchase_id',
              'date_create',
              'companygroup',
              'company',
              'product',
              'markettype',
              'market',
              'address',
              'unit',
              'amount',
              'delay',
              'purchasestate',
              'date_deliver',
              'date_closed',
              'date_close',
              'comment',
              'close_text',
              'offer_id',
              'offer_place',
              'supplier',
              'offertype',
              'offer_product',
              'offer_amount',
              'offer_price',
              'offer_total',
              'delivery',
              'offer_delay',
              'winner',
              'offer_comment',

            ));
        }else
            $this->render('admin', array(
                'model' => $model,
            ));
    }
    public function actionRecalculate() {
        $command = Yii::app()->db->createCommand('CALL closePurchase('.$_POST['id'].')');
        $command->execute();
        $connection = Yii::app()->db;
        $sql ="
        SELECT
          z_offer.id AS `mpid`,
          z_offer.price,
          z_offer.amount,
          offer.id,
          offer.price,
          offer.amount,
          IF(z_offer.price>offer.price,((z_offer.price-offer.price)*offer.amount)/(offer.amount*offer.price)*100,0) AS price_reduce
        FROM
          z_offer
        INNER JOIN z_offer offer
        ON offer.pid=z_offer.id AND offer.id=(SELECT MAX(id) FROM z_offer WHERE z_offer.pid=`mpid`)
        INNER JOIN z_product
        ON z_product.id=z_offer.product_id
        WHERE z_offer.pid IS NULL AND z_product.purchase_id=:purchase_id
        ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":purchase_id", $_POST['id'], PDO::PARAM_INT);
        $result = $command->queryAll();
        if($result)
            foreach($result as $row){
                $offer=Offer::model()->findByPk($row['id']);
                $offer->price_reduce=$row['price_reduce'];
                $offer->save();
            }
        echo 'Аналитика успешно обновлена';
    }
}