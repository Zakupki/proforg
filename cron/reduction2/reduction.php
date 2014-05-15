<?php
error_reporting(0);
echo date('Y-m-d h:i:s');
echo "\n";


$link = mysql_connect("localhost", "u_newzakup", "IqUT5Ex0");
mysql_select_db("newzakup", $link);
mysql_query("SET NAMES 'utf8'");
$query = mysql_query("
    SELECT
        z_purchase.id,
        z_purchase.date_reduction,
        z_product.check_date,
        z_product.id AS product_id,
        z_product.reductionstate,
        z_product.reductionplace,
        MAX(z_offer.reduction_place) AS max_place,
        MIN(z_offer.reduction_place) AS min_place
    FROM z_purchase
    INNER JOIN z_product
      ON z_product.purchase_id=z_purchase.id AND z_product.reductionstate<2
    INNER JOIN z_offer
      ON z_offer.product_id=z_product.id AND z_offer.pid IS NULL AND z_offer.reduction=1
    WHERE z_purchase.purchasestate_id=3
    GROUP BY z_product.id
    ");


while ($row = mysql_fetch_assoc($query)) {
    /*echo "<hr/>";
    $row['id'];
    echo "<br/>";*/
    $query2 = mysql_query("
    SELECT
        z_offer.id,
        z_offer.title,
        z_offer.price,
        z_offer.product_id,
        z_offer.reduction_place,
        z_offer.reduction_state,
        z_offer.reduction_pass
    FROM z_offer
    WHERE z_offer.product_id=" . $row['product_id'] . " AND z_offer.pid IS NULL AND z_offer.reduction=1
    ORDER BY z_offer.reduction_place ASC
    ");
    $cnt = 0;
    $tcnt = 1;
    $changedstate = 0;
    $reductionplace = 0;
    //echo "<pre>";
    //print_r($row);
    //echo "</pre>";

    while ($row2 = mysql_fetch_assoc($query2)) {

        if (!$row['reductionstate'] && !$cnt) {
            mysql_query("UPDATE z_product SET check_date=NOW(), reductionstate=1, reductionplace=" . $row2['reduction_place'] . " WHERE id=" . $row['product_id']);
        }
        $dataArr[$row2['product_id']][$row2['reduction_place']] = $row2;

        if (!$row2['reduction_pass'])
            $dataNPass[$row2['product_id']][$row2['reduction_place']] = $row2;

        //echo "<pre>";
        //print_r($row2);
        //echo "</pre>";
        $tcnt++;
        $cnt++;
    }
    /* echo "<pre>";
     print_r($dataNPass);
     echo "</pre>";*/

    if ($row['reductionstate'] == 1) {
        foreach ($dataArr as $product_id => $product) {
            if (count($dataNPass[$product_id]) < 1) {
                mysql_query("UPDATE z_product SET check_date=NOW(), reductionstate=2 WHERE id=" . $product_id);
            } else {
                $cnt = 0;
                $tcnt = 1;
                $change_state = 0;
                $changed_state = 0;
                $reduction_place = 0;
                foreach ($product as $k => $of) {
                    //echo "<hr>";
                    #Установка следующего актиныйм
                    if ($change_state == 1) {

                        mysql_query("UPDATE z_offer SET reduction_state=1 WHERE id=" . $of['id']);
                        $reduction_place = $of['reduction_place'];
                        //echo("UPDATE z_offer SET reduction_state=1 WHERE id=".$of['id']);
                        $changed_state = 1;
                        $change_state = 0;
                        //echo "2<br>";
                    }

                    #Обновление активного
                    if ($of['reduction_state']) {
                        $query_check = mysql_query("
                            SELECT
                                z_offer.id
                            FROM z_offer
                            WHERE z_offer.pid=" . $of['id'] . "
                                AND z_offer.date_create>'" . $row['check_date'] . "'
                            LIMIT 0,1
                            ");
                        if (!mysql_num_rows($query_check) && !$of['reduction_pass']) {
                            mysql_query("UPDATE z_offer SET reduction_state=0,reduction_pass=1 WHERE id=" . $of['id']);
                        } else
                            mysql_query("UPDATE z_offer SET reduction_state=0 WHERE id=" . $of['id']);
                        //echo ("UPDATE z_offer SET reduction_state=0 WHERE id=".$of['id']);
                        $change_state = 1;
                        //echo "1<br>";
                    }

                    #Установка первого активным
                    if ($tcnt == count($product) && !$changed_state) {
                        $keys = array_keys($product);
                        $first_key = $keys[0];
                        $first_id = $product[$first_key]['id'];
                        $reduction_place = $first_key;
                        mysql_query("UPDATE z_offer SET reduction_state=1 WHERE id=" . $first_id . " AND product_id=" . $product_id);
                        //echo ("UPDATE z_offer SET reduction_state=1 WHERE id=".$first_id." AND product_id=".$product_id);
                        //echo "3<br>";
                    }

                    /*echo "<pre>";
                    print_r($of);
                    echo "</pre>";*/
                    $tcnt++;
                    $cnt++;

                }
                #Обновление продукта
                //echo $reduction_place;

                //echo ("UPDATE z_product SET check_date=NOW(), reductionplace=" . $reduction_place . " WHERE id=" . $product_id);
                mysql_query("UPDATE z_product SET check_date=NOW(), reductionplace=" . $reduction_place . " WHERE id=" . $product_id);
            }
        }
    }


    #Обновление продукта
    //mysql_query("UPDATE z_product SET check_date=NOW(), reductionplace=" . $reductionplace . " WHERE id=" . $row['product_id']);


    /*echo "<pre>";
    print_r($row);
    echo "</pre>";*/
    echo "\r\n";
}
echo "\r\n";