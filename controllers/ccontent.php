<?php
require_once '../models/mcontent.php';

class Ccontent extends Mcontent {

    function mysql_validate($input) {
        foreach($input as $key => $value) {                                // Escape all SQL tags
            $row[$key] = mysqli_escape_string($value);
        }
        return $row;
    }

    function get_row($tbl1, $tbl2, $tbl3, $modelfunc,$key) {// Return SQL row from '$tbl' by '$key' Use model function name '$modelfunc'
        $rez = $this->$modelfunc($tbl1, $tbl2, $tbl3, $key);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }

    function get_rows($tbl1, $tbl2, $modelfunc, $filter, $counts, $popular) {// Return Assoc SQL array from '$tbl' Use model function '$modelfunc'
        $rez = $this->$modelfunc($tbl1, $tbl2, $filter, $counts, $popular);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row['id']] = $row;
        }
        return $out;
    }

    function get_count_rows($tbl1, $tbl2, $filter) {                                         // Return count rows
        $rez = $this->select_count_rows($tbl1, $tbl2, $filter);
        $out = mysqli_fetch_array($rez);
        return $out[0];
    }

    function get_unique($tbl, $col) {
        $rez = $this->select_unique($tbl, $col);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row[$col]] = $row[$col];
        }
        return $out;
    }

    function get_item_type($tbl, $id) {
        $rez = $this->select_item_type($tbl, $id);
        $out = mysqli_fetch_assoc($rez);
        return $out['type'];
    }

    function get_item($tbl, array $TypeList, array $TblCols, $ItemType, $id) {
        // Create SELECT string
        $select_data = [];
        foreach ($TypeList[$ItemType] as $tag_tbl) {
            foreach ($TblCols[$tag_tbl] as $col) {
                $select_data[] = "$tag_tbl.$col";
            }
        }
        $sql_select = implode(', ', $select_data);

        // Create JOIN
        $join_data = [];
        foreach ($TypeList[$ItemType] as $tag_tbl) {
            if(count($TblCols[$tag_tbl]) > 1) {
                $join_data[] = [
                    'type' => 'LEFT',
                    'base_table' => $tbl,
                    'join_table' => $tbl.'_'.$tag_tbl.'s',
                    'base_table_key' => 'id',
                    'join_table_key' => 'product_id',
                ];
                $join_data[] = [
                    'type' => 'INNER',
                    'base_table' => $tbl.'_'.$tag_tbl.'s',
                    'join_table' => $tag_tbl,
                    'base_table_key' => $tag_tbl.'_id',
                    'join_table_key' => 'id',
                ];
            }
            else {
                $join_data[] = [
                    'type' => 'LEFT',
                    'base_table' => $tbl,
                    'join_table' => $tag_tbl,
                    'base_table_key' => 'id',
                    'join_table_key' => 'product_id',
                ];
            }
        }

        foreach ($join_data as $join) {
            $joins[] = $join['type'].' JOIN '.$join['join_table']
                .' ON '.$join['base_table'].'.'.$join['base_table_key']
                .' = '.$join['join_table'].'.'.$join['join_table_key'];
        }
        $sql_join = implode(' ', $joins);

        $rez = $this->select_item($tbl, $sql_select, $sql_join, $id);
        $out = mysqli_fetch_assoc($rez);
        return $out;
    }

    public function getItemImg($tbl, $id) {
        $rez = $this->selectItemImg($tbl, $id);
        while ($row = mysqli_fetch_assoc($rez)) {
            $out[$row['id']] = $row;
        }
        return $out;
    }

    public function getOrders($tbl, $key, $value) {
        $rez = $this->selectOrders($tbl, $key, $value);
        $i = 0;
        while ($row = mysqli_fetch_assoc($rez)) {
            $i++;
            $out[$i] = $row;
        }
        return $out;
    }

    public function getProduct($tbl, $id) {
        $productSQL = $this->selectProduct($tbl, $id);
        $product = mysqli_fetch_assoc($productSQL);

        $images = $this->getItemImg('images', $id);
        if($images) {
            foreach ($images as $key => $value) {
                if($value['delta'] == 0) {
                    $product['image'] = $value['path'];
                    break;
                }
                $product['image'] = '../images/noimage.jpg';
            }
        }
        else {
            $product['image'] = '../images/noimage.jpg';
        }

        return $product;
    }

    public function addOrder($tbl, $post) {
        // check user orders before and after authorization
        if($_COOKIE['uorder'] && !$post['anonymus']) {
            $anonymusRows = $this->selectAnonymusOrders($tbl, $_COOKIE['uorder']);
            if($anonymusRows) {
                while ($row = mysqli_fetch_assoc($anonymusRows)) {
                    $anonymusOrders[$row['anonymus']] = $row;
                }

                if($anonymusOrders) {
                    foreach ($anonymusOrders as $key => $value) {
                        $this->updateAnonymusToUser($tbl, $post, $_COOKIE['uorder']);
                    }
                }

            }
        }

        // check  if this product was already order this user
        $existSQL = $this->selectUserOrder($tbl, $post);
        $existOrder = mysqli_fetch_assoc($existSQL);

        if(!$existOrder) {
            // If user is not autorize set kookie for orders
            if($post['anonymus']) {
                setcookie('uorder', $post['anonymus'], time()+12000, '/');
            }
            $this->insertOrder($tbl, $post);
        }
        else {
            $this->updateCountOrder($tbl, $post);
        }
    }

    public function processOrder($tbl1, $tbl2, $tbl3, $post) {
        // create post for 'uorders'
        $uorders = [
            'user_id' => $post['user_id'],
            'address' => $post['address'],
            'datetime' => $post['datetime'],
        ];
        $this->insertUOrder($tbl1, $uorders);
        $orderIDSQL = mysqli_fetch_assoc($this->selectLasdID($tbl1, 'id'));
        $orderID = $orderIDSQL['id'];

        // post['data'] is json string. must be processed first
        $jsObj = json_decode($post['data']);
        foreach ($jsObj as $key => $value) {
            $data[$key] = [
                'product_id' => $value->{'product_id'},
                'count' => $value->{'count'},
            ];

            // create post for 'orders'
            $orders[$key] = [
                'product_id' => $value->{'product_id'},
                'datetime' => $post['datetime'],
            ];
            $this->insertOrders($tbl2, $orders[$key]);

            // create post for 'orders_products'
            $orders_products[$key] = [
                'order_id' => $orderID,
                'product_id' => $value->{'product_id'},
                'count' => $value->{'count'},
            ];
            $this->insertOrdersProducts($tbl3, $orders_products[$key]);
        }

        // clear Cart
        $this->delCart('cart_products', $post['user_id']);
    }

}

$ccontent = new Ccontent();
