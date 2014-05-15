<?php
class TaskController extends BackController
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