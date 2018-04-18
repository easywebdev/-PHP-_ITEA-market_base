<?php
require_once '../models/mcontent.php';

class Mcreateedit extends Mcontent {
    public function selectCol($tbl, $col, $nameid) {                        // Return one col from table
        $sql = "SELECT {$nameid}, {$col} FROM {$tbl}";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectImg($tbl, $id) {
        $sql = "SELECT id, product_id, delta, size, path FROM `{$tbl}` WHERE id = '{$id}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectChangedImgs($tbl, $id, $delta) {
        $sql = "SELECT id, delta, path FROM `{$tbl}` WHERE product_id = '{$id}' AND delta > '{$delta}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectExist($tbl, $post , $col) {
        $sql = "SELECT COUNT(1) FROM `{$tbl}` WHERE `{$col}` = '{$post[$col]}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectExsitEmail ($tbl, $post) {
        $sql = "SELECT COUNT(1) FROM `{$tbl}` WHERE `email` = '{$post['email']}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectUser($tbl, $id) {
        $sql = "SELECT `id`, `login`, `pass`, `email`, `status`, `fullname` FROM `{$tbl}` WHERE id = '{$id}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function selectFindUser($tbl, $post) {
        $sql = "SELECT `id`, `login`, `pass`, `email`, `status` FROM `{$tbl}` 
                WHERE `login` = '{$post['login']}' AND `pass` = '{$post['pass']}'";
        $rez = $this->sql($sql);
        return $rez;
    }

    public function insertItem($tbl, $post) {
        $sql = "INSERT INTO {$tbl} (`name`, `type`, `description`, `price`) 
                VALUES ('{$post['model']}', '{$post['type']}', '{$post['description']}', '{$post['price']}')";
        $this->sql($sql);
    }

    public function insertImg($tbl, $post) {
        $sql = "INSERT INTO {$tbl} (`product_id`, `delta`, `size`, `path`)
                VALUES ('{$post['product_id']}', '{$post['delta']}', '{$post['size']}', '{$post['path']}')";
        $this->sql($sql);
    }

    public function insertUser($tbl, $post) {
        $sql = "INSERT INTO `{$tbl}` (`login`, `pass`, `email`, `status`, `fullname`)
                VALUES ('{$post['login']}', '{$post['pass']}', '{$post['email']}', 0, '{$post['fullname']}')";
        $this->sql($sql);
    }

    public function updateUserStatus($tbl, $id) {
        $sql = "UPDATE `{$tbl}` SET `status` = '1' WHERE `id` = '$id'";
        $this->sql($sql);
    }

    public function updateImg($tbl, $post) {
        $sql = "UPDATE `{$tbl}` SET `delta` = '{$post['delta']}', `path` = '{$post['path']}' WHERE `id` = '{$post['id']}'";
        $this->sql($sql);
    }

    public function deleRow($tbl, $id) {                                              // Remove row from tbl
        $sql = "DELETE FROM {$tbl} WHERE id = '{$id}'";
        $this->sql($sql);
    }

    public function delKeyRow($tbl, $key, $value) {
        $sql = "DELETE FROM {$tbl} WHERE `{$key}` = '{$value}'";
        $this->sql($sql);
    }
}

$mcreateedit = new Mcreateedit();