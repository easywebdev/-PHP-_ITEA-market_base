<?php
require_once '../config/db.php';

class Mcontent extends DB {

    function select_count_rows($tbl1, $tbl2, $filter) {                        // Select Count Rows
        $sql = "SELECT COUNT(1) FROM
                (SELECT {$tbl1}.name, {$tbl1}.type, COUNT({$tbl1}.id) AS orders FROM {$tbl1}
                LEFT JOIN {$tbl2} ON {$tbl1}.id = {$tbl2}.product_id
                GROUP BY {$tbl1}.id";

        if($filter) {
            $sql .= ' HAVING '.$filter;
        }

        $sql .= ') AS the_count_total';

        $rez = $this->sql($sql);
        return $rez;
    }

    function select_unique($tbl, $col) {                                        // Select types
        $sql = "SELECT DISTINCT $col FROM {$tbl}";
        $rez = $this->sql($sql);
        return $rez;
}

        function select_items_filter($tbl1, $tbl2, $filter, $start, $count) {  // Select Items by Filter options
        $sql = "SELECT {$tbl1}.id, {$tbl1}.name, {$tbl1}.price, {$tbl1}.type, COUNT($tbl2.id) AS orders
                FROM {$tbl1} LEFT JOIN {$tbl2} ON {$tbl1}.id = {$tbl2}.product_id GROUP BY {$tbl1}.id";

        if($filter) {
            $sql .= ' HAVING '.$filter;
        }

        $sql .= " LIMIT $start, $count";

        $rez = $this->sql($sql);
        return $rez;
    }

    function select_item_type($tbl, $id) {                                              // Select type of product
        $sql = "SELECT type FROM {$tbl} WHERE id = '{$id}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    function  select_item($tbl, $sql_select, $sql_join, $id) {
        $sql = "SELECT {$tbl}.name, {$tbl}.type, {$tbl}.description, {$tbl}.price, {$tbl}.datetime, $sql_select 
                FROM $tbl $sql_join WHERE {$tbl}.id = '{$id}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectItemImg ($tbl, $id) {
        $sql = "SELECT `id`, `product_id`, `delta`, `size`, `path`
                FROM {$tbl} WHERE `product_id` = {$id}";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectUserOrder($tbl, $post) {
        $sql = "SELECT `product_id`, `user_id`, `anonymus` FROM `{$tbl}` WHERE `product_id` = '{$post['product_id']}' AND 
                `user_id` = '{$post['user_id']}' AND `anonymus` = '{$post['anonymus']}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function  selectAnonymusOrders($tbl, $anonymus) {
        $sql = "SELECT `product_id`, `user_id`, `anonymus` FROM `{$tbl}` WHERE `anonymus` = '{$anonymus}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectOrders($tbl, $key, $value) {
        $sql = "SELECT `product_id`, `{$key}`, `count` FROM `{$tbl}` WHERE `{$key}` = '{$value}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectProduct($tbl, $id) {
        $sql = "SELECT `name`, price FROM `{$tbl}` WHERE `id` = '{$id}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectLasdID($tbl, $col) {
        $sql  = "SELECT {$col} FROM {$tbl} ORDER BY {$col} DESC LIMIT 1";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function insertOrder($tbl, $post) {
        $sql = "INSERT INTO `{$tbl}` (`product_id`, `user_id`, `anonymus`, `count`) 
                VALUES ('{$post['product_id']}', '{$post['user_id']}', '{$post['anonymus']}', '{$post['count']}')";
        $this->sql($sql);
    }

    public function insertOrders($tbl, $post) {
        $sql = "INSERT INTO `{$tbl}` (`product_id`, `datetime`) VALUES ('{$post['product_id']}', '{$post['datetime']}')";
        $this->sql($sql);
    }

    public function insertOrdersProducts($tbl, $post) {
        $sql = "INSERT INTO `{$tbl}` (`order_id`, `product_id`, `count`) VALUES ('{$post['order_id']}', '{$post['product_id']}', '{$post['count']}')";
        $this->sql($sql);
    }

    public function insertUOrder($tbl, $post) {
        $sql = "INSERT INTO `{$tbl}` (`user_id`, `address`, `datetime`) VALUES ('{$post['user_id']}', '{$post['address']}', '{$post['datetime']}')";
        $this->sql($sql);
    }

    public function updateCountOrder($tbl, $post) {
        $sql = "UPDATE `{$tbl}` SET `count` = count + '{$post['count']}' WHERE `product_id` = '{$post['product_id']}'";
        $this->sql($sql);
    }

    public function updateAnonymusToUser($tbl, $post, $anonymus) {
        $sql = "UPDATE `{$tbl}` SET `user_id` = '{$post['user_id']}', `anonymus` = '{$post['anonymus']}' WHERE `anonymus` = '{$anonymus}'";
        $this->sql($sql);
    }

    public function delCart($tbl, $user) {
        if(is_numeric($user)) {
            $sql = "DELETE FROM `{$tbl}` WHERE `user_id` = '{$user}'";
        }
        else {
            $sql = "DELETE FROM `$tbl` WHERE `anonymus` = '{$user}'";
        }
        $this->sql($sql);
    }
}

$mcontent = new Mcontent();