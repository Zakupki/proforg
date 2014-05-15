<?php
class DispositionController extends BackController
{
    public function actionAdmin()
    {
        /** @var $model BaseActiveRecord */
        $model = $this->getNewModel('search');
        $model->unsetAttributes(); // clear any default values

        if(isset($_GET[$this->getModelName()]))
            $model->attributes = $_GET[$this->getModelName()];

        $model->restoreGridState();

        $this->render($this->view['admin'], array(
            'model' => $model,
        ));
        /* public function actionAdmin(){
        $sql='SELECT
        z_taggroup.`title` AS taggroup,
        z_tag.`title` as tag,
        MAX(z_offer.price) AS maxprice,
        MIN(z_offer.price) AS minprice,
        MIN(z_offer.price)/MAX(z_offer.price) AS perc,
        COUNT(z_offer.id) AS cnt,
        z_company.title AS company
        FROM
        z_taggroup
        INNER JOIN z_tag
        ON z_tag.taggroup_id = z_taggroup.id
        INNER JOIN z_offer
        ON z_offer.`tag_id`=z_tag.id AND z_offer.`winner`=1 AND z_offer.`exclude_lose`=0
        INNER JOIN z_product
        ON z_product.id=z_offer.`product_id`
        INNER JOIN z_purchase
        ON z_purchase.id=z_product.`purchase_id`
        INNER JOIN z_company
        ON z_company.id=z_purchase.`company_id`
        GROUP BY z_company.id,z_taggroup.id
        ORDER BY z_company.title,z_taggroup.`title`';
    }*/
    }


}